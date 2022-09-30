<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\Course;
use App\Models\Gender;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Rank;
use App\Models\SeatType;
use App\Models\State;
use Cache;
use DB;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class BranchTrends extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $institute_type = [];

    private ?array $old_institute_type = [];

    public $courses;

    private $old_courses;

    public $branches;

    private $old_branches;

    public $institutes;

    private $old_institutes;

    public $seat_type;

    private $old_seat_type;

    public $gender;

    private $old_gender;

    public $round_display;

    private $old_round_display;

    public $rank_type;

    private $old_rank_type;

    public $home_state;

    private $old_home_state;

    protected $listeners = ['updateChartData'];

    protected $queryString = ['courses', 'branches', 'institutes', 'institute_type', 'seat_type', 'gender', 'round_display', 'rank_type', 'home_state'];

    public function mount(): void
    {
        $courses = array_diff(
            $this->courses ?? [],
            Cache::rememberForever('allCourseIds', fn () => Course::all()->pluck('id')->toArray())
        ) ? [] : $this->courses;
        $branches = array_diff(
            $this->branches ?? [],
            Cache::rememberForever('allBrancheIds', fn () => Branch::all()->pluck('id')->toArray())
        ) ? [] : $this->branches;
        $institutes = array_diff(
            $this->institutes ?? [],
            Cache::rememberForever('allInstituteIds', fn () => Institute::all()->pluck('id')->toArray())
        ) ? [] : $this->institutes;
        $seat_type = array_search(
            $this->seat_type,
            Cache::rememberForever('allSeatTypeIds', fn () => SeatType::all()->pluck('id')->toArray())
        ) ? $this->seat_type : null;
        $gender = array_search(
            $this->gender,
            Cache::rememberForever('allGenderIds', fn () => Gender::all()->pluck('id')->toArray())
        ) ? $this->gender : null;
        $institute_type = array_diff(
            $this->institute_type ?? [],
            Cache::rememberForever('allInstituteTypeIds', fn () => Institute::all()->pluck('type')->toArray())
        ) ? $this->institute_type : null;
        $round_display = array_search($this->round_display, array_keys(Rank::ROUND_DISPLAY_OPTIONS)) !== false ? $this->round_display : null;
        $rank_type = array_search($this->rank_type, array_keys(Rank::RANK_TYPE_OPTIONS)) !== false ? $this->rank_type : null;
        $home_state = array_search(
            $this->home_state,
            Cache::rememberForever('allStateIds', fn () => State::all()->pluck('id')->toArray())
        ) ? $this->home_state : null;
        $this->form->fill([
            'institute_type' => $institute_type,
            'courses' => $courses,
            'branches' => $branches,
            'institutes' => $institutes,
            'seat_type' => $seat_type ?? session('seat_type', 'OPEN'),
            'gender' => $gender ?? session('gender', 'Gender-Neutral'),
            'round_display' => $round_display ?? session('round_display', 'last'),
            'rank_type' => $rank_type ?? session('rank_type', 'advanced'),
            'home_state' => $rank_type === 'main' ? ($home_state ?? session('home_state')) : null,
        ]);
    }

    private function haveFieldsChanged(): bool
    {
        return $this->institutes === $this->old_institutes
            && $this->institute_type === $this->old_institute_type
            && $this->seat_type === $this->old_seat_type
            && $this->courses === $this->old_courses
            && $this->branches === $this->old_branches
            && $this->gender === $this->old_gender
            && $this->round_display === $this->old_round_display
            && $this->rank_type === $this->old_rank_type
            && $this->home_state === $this->old_home_state;
    }

    private function getInstituteQuotas(): array
    {
        $institute_type = $this->rank_type === 'advanced'
                ? ['iit']
                : ($this->institute_type
                    ? $this->institute_type
                    : ['iiit', 'nit', 'gfti']
                );

        return Cache::rememberForever(
            'institute_quota_'.implode('_', $institute_type).($this->rank_type === 'main' ? '_'.$this->home_state : ''),
            function () use ($institute_type) {
                return DB::table('institute_quota')
                    ->where(function ($query) use ($institute_type) {
                        $query->whereIn('institute_id', Institute::whereIn('type', $institute_type)->pluck('id'));
                        if ($this->rank_type === 'main') {
                            $query->where(function ($sub_query) {
                                $sub_query->where('quota_id', 'OS')->whereNotIn('state_id', [$this->home_state])
                                    ->orWhere('quota_id', 'HS')->whereIn('state_id', [$this->home_state])
                                    ->orWhereNotIn('quota_id', ['OS', 'HS'])->whereIn('state_id', [$this->home_state])
                                    ->orWhere('quota_id', 'AI');
                            });
                        }
                    })
                    ->distinct()
                    ->get()
                    ->toArray();
            }
        );
    }

    public function updateChartData(): void
    {
        $data = [];
        if ($this->branches
            && $this->seat_type
            && $this->gender
            && $this->round_display
            && ($this->rank_type === 'advanced' || $this->home_state)
        ) {
            if ($this->haveFieldsChanged()) {
                return;
            }

            $institute_quotas = $this->getInstituteQuotas();
            $programs = DB::table('branch_program')
                        ->whereIn('branch_id', $this->branches)
                        ->pluck('program_id');
            $query = Rank::whereIn('program_id', $programs)
                        ->whereIn(DB::raw('institute_id || quota_id'), array_map(function ($institute_quota) {
                            return $institute_quota->institute_id.$institute_quota->quota_id;
                        }, $institute_quotas))
                        ->where('seat_type_id', $this->seat_type)
                        ->where('gender_id', $this->gender);
            if ($this->courses) {
                $query->whereIn('course_id', $this->courses);
            }
            if ($this->institutes) {
                $query->whereIn('institute_id', $this->institutes);
            } elseif ($this->institute_type) {
                $query->whereIn('institute_id', Institute::whereIn('type', $this->institute_type)->pluck('id'));
            }
            $program_data = $query->get();
            $year_round = Cache::rememberForever(
                'year_round_distinct',
                fn () => Rank::select('year', 'round')
                            ->distinct()
                            ->orderBy('year')
                            ->orderBy('round')
                            ->get()
            );
            switch($this->round_display) {
                case 'all':
                    break;
                case 'last':
                    $year_round = Cache::rememberForever(
                        'year_round_last',
                        fn () => Rank::select('year', DB::raw('MAX(round) as round'))
                                    ->groupBy('year')
                                    ->orderBy('year')
                                    ->get()
                    );
                    if ($this->rank_type === 'advanced') {
                        // This fix is to handle the case of 2014 where there were
                        // 3 rounds for IITs and 4 rounds for NIT+
                        $year_round = $year_round->map(function ($item) {
                            if ($item->year === 2014) {
                                $item->round = 3;
                            }

                            return $item;
                        });
                    }
                    break;
                default:
                    $year_round = Cache::rememberForever(
                        'year_round_'.$this->round_display,
                        fn () => Rank::select('year', 'round')
                                    ->where('round', $this->round_display)
                                    ->distinct()
                                    ->orderBy('year')
                                    ->get()
                    );
                    break;
            }
            $columns = $year_round->map(fn ($year_round) => $year_round->year.'_'.$year_round->round);
            $initial_institute_data = $columns->mapWithKeys(fn ($column) => [$column => null])->toArray();
            $institute_data = [];
            foreach ($program_data as $data) {
                if (! isset($institute_data[$data->institute_id])) {
                    $institute_data[$data->institute_id] = [];
                }
                $program_label = $data->course_id.', '.$data->program_id.' ('.$data->quota_id.')';
                if (! isset($institute_data[$data->institute_id][$program_label])) {
                    $institute_data[$data->institute_id][$program_label] = $initial_institute_data;
                }
                $institute_data[$data->institute_id][$program_label][$data->year.'_'.$data->round] = $data->closing_rank;
            }

            $datasets = [];
            foreach ($institute_data as $institute => $program_data) {
                foreach ($program_data as $program => $data) {
                    $random_hue = crc32($institute.$program) % 360;
                    $datasets[] = [
                        'label' => $institute.' ('.$program.')',
                        'data' => array_values($data),
                        'backgroundColor' => 'hsl('.$random_hue.', 100%, 80%)',
                        'borderColor' => 'hsl('.$random_hue.', 100%, 50%)',
                    ];
                }
            }
            $labels = array_keys($initial_institute_data);
            foreach ($labels as $key => $label) {
                $labels[$key] = str_replace('_', ' - R', $label);
            }
            $data = [
                'labels' => $labels,
                'datasets' => $datasets,
            ];
        }
        $this->old_courses = $this->courses;
        $this->old_branches = $this->branches;
        $this->old_institutes = $this->institutes;
        $this->old_institute_type = $this->institute_type;
        $this->old_seat_type = $this->seat_type;
        $this->old_gender = $this->gender;
        $this->old_round_display = $this->round_display;
        $this->emit('chartDataUpdated', $data);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1, 'md' => 3])->schema([
                Radio::make('rank_type')
                    ->label('Rank Type')
                    ->columns(['default' => 2])
                    ->options(Rank::RANK_TYPE_OPTIONS)
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->institutes = [];
                        if ($this->rank_type === 'advanced') {
                            $this->home_state = null;
                        } else {
                            $this->home_state = session('home_state');
                        }
                        session()->put('rank_type', $this->rank_type);
                        $this->emit('updateChartData');
                    })
                    ->required()
                    ->reactive(),
                Select::make('home_state')
                    ->label('Home State')
                    ->options(
                        Cache::rememberForever(
                            'allHomeStates',
                            fn () => State::orderBy('id')->pluck('id', 'id')
                        )
                    )
                    ->hidden(fn () => $this->rank_type !== 'main')
                    ->afterStateUpdated(function () {
                        session()->put('home_state', $this->home_state);
                        $this->emit('updateChartData');
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                CheckboxList::make('institute_type')
                    ->label('Institute Types')
                    ->options([
                        'nit' => 'NITs',
                        'iiit' => 'IIITs',
                        'gfti' => 'GFTIs',
                    ])
                    ->columns(['default' => 3])
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->institutes = [];
                        $this->emit('updateChartData');
                    })
                    ->hidden(fn () => $this->rank_type !== 'main')
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'md' => 3])->schema([
                MultiSelect::make('branches')
                    ->label('Branches')
                    ->placeholder('Select Branches')
                    ->options(
                        Cache::rememberForever(
                            'allBranches',
                            fn () => Branch::orderBy('id')->pluck('id', 'id')
                        )
                    )
                    ->optionsLimit(150)
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->institutes = [];
                        $this->emit('updateChartData');
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                MultiSelect::make('courses')
                    ->options(function () {
                        $programs = Cache::rememberForever(
                            'programs_'.implode('_', $this->branches),
                            fn () => Branch::find($this->branches)
                                        ->first()
                                        ->programs
                                        ->pluck('id')
                                        ->toArray()
                        );

                        return Program::whereIn('id', $programs)
                                    ->get()
                                    ->pluck('courses')
                                    ->flatten()
                                    ->pluck('id', 'id');
                    })
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function () {
                        $this->institutes = [];
                        $this->emit('updateChartData');
                    })
                    ->hidden(! $this->branches)->reactive(),
                MultiSelect::make('institutes')
                    ->options(function () {
                        if ($this->branches) {
                            $programs = Cache::rememberForever(
                                'programs_'.implode('_', $this->branches),
                                fn () => Branch::find($this->branches)
                                            ->first()
                                            ->programs
                                            ->pluck('id')
                                            ->toArray()
                            );
                            $query = DB::table('institute_course_program')->whereIn('program_id', $programs);
                            if ($this->courses) {
                                $query->whereIn('course_id', $this->courses);
                            }
                            $institute_type = $this->rank_type === 'advanced'
                                ? ['iit']
                                : ($this->institute_type
                                    ? $this->institute_type
                                    : ['iiit', 'nit', 'gfti']
                                );
                            $institutes = Institute::whereIn('type', $institute_type)->pluck('id');
                            $query = $query->whereIn('institute_id', $institutes);

                            return $query->orderBy('institute_id')
                                        ->get()
                                        ->pluck('institute_id', 'institute_id');
                        } else {
                            return Cache::rememberForever(
                                'allInstitutes',
                                fn () => Institute::all()->pluck('id', 'id')
                            );
                        }
                    })
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(! $this->branches)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'sm' => 3])->schema([
                Select::make('seat_type')
                    ->options(Cache::rememberForever('allSeatTypes', fn () => SeatType::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function () {
                        session()->put('seat_type', $this->seat_type);
                        $this->emit('updateChartData');
                    })
                    ->label('Seat Type')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('gender')
                    ->options(Cache::rememberForever('allGenders', fn () => Gender::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function () {
                        session()->put('gender', $this->gender);
                        $this->emit('updateChartData');
                    })
                    ->label('Gender')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('round_display')
                    ->options(Rank::ROUND_DISPLAY_OPTIONS)
                    ->afterStateUpdated(function () {
                        session()->put('round_display', $this->round_display);
                        $this->emit('updateChartData');
                    })
                    ->label('Display Rounds')
                    ->searchable()
                    ->required()
                    ->reactive(),
            ]),
        ];
    }

    public function render()
    {
        return view('livewire.chart');
    }
}
