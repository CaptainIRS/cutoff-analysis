<?php

namespace App\Http\Livewire;

use App\Models\Course;
use App\Models\Gender;
use App\Models\Institute;
use App\Models\Rank;
use App\Models\SeatType;
use App\Models\State;
use Arr;
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

class InstituteTrends extends Component implements HasForms
{
    use InteractsWithForms;

    public array $institute_type = [];

    public array $courses = [];

    public array $institutes = [];

    public ?string $seat_type = null;

    public ?string $gender = null;

    public ?string $round_display = null;

    public ?string $rank_type = null;

    public ?string $home_state = null;

    public string $title = '';

    public array $initial_chart_data = [];

    private $all_institutes;

    private $all_courses;

    private $all_states;

    private $all_seat_types;

    private $all_genders;

    protected $listeners = ['updateChartData'];

    protected $queryString = [
        'courses',
        'institutes',
        'institute_type' => ['as' => 'institute-type'],
        'round_display' => ['as' => 'round-display', 'except' => 'last'],
        'seat_type' => ['as' => 'seat-type', 'except' => 'OPEN'],
        'gender' => ['except' => 'Gender-Neutral'],
        'rank_type' => ['as' => 'rank'],
        'home_state' => ['as' => 'home-state'],
    ];

    public function __construct()
    {
        $this->all_institutes = Cache::rememberForever('all_institutes', fn () => Institute::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_courses = Cache::rememberForever('all_courses', fn () => Course::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_states = Cache::rememberForever('all_states', fn () => State::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_seat_types = Cache::rememberForever('all_seat_types', fn () => SeatType::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_genders = Cache::rememberForever('all_genders', fn () => Gender::orderBy('id')->pluck('id', 'id')->toArray());
    }

    private function getTitle(?array $institutes, ?string $rank_type): string
    {
        $institute_names = [];
        foreach ($institutes as $institute) {
            if (str_starts_with($institute, 'Indian Institute of Technology')) {
                $institute_names[] = 'IIT '.substr($institute, 31);
            } elseif (str_starts_with($institute, 'National Institute of Technology')) {
                $institute_names[] = 'NIT '.substr($institute, 33);
            } else {
                $institute_names[] = $institute;
            }
        }

        return $institute_names ? Arr::join($institute_names ?? [], ', ', ' and ').' '.Rank::RANK_TYPE_OPTIONS[$rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)].' Cut-off Rank Trends' : '';
    }

    public function mount(): void
    {
        $courses = $this->ensureSubsetOf($this->courses, $this->all_courses);
        $institutes = $this->ensureSubsetOf($this->institutes, $this->all_institutes);
        $seat_type = $this->ensureBelongsTo($this->seat_type, $this->all_seat_types);
        $gender = $this->ensureBelongsTo($this->gender, $this->all_genders);
        $institute_type = $this->ensureSubsetOf($this->institute_type, array_keys(Institute::INSTITUTE_TYPE_OPTIONS));
        $round_display = $this->ensureBelongsTo($this->round_display, array_keys(Rank::ROUND_DISPLAY_OPTIONS));
        $rank_type = $this->ensureBelongsTo($this->rank_type, array_keys(Rank::RANK_TYPE_OPTIONS));
        $home_state = $this->ensureBelongsTo($this->home_state, $this->all_states);
        $this->form->fill([
            'institute_type' => $institute_type,
            'courses' => $courses,
            'institutes' => $institutes,
            'seat_type' => $seat_type ?? session('seat_type', 'OPEN'),
            'gender' => $gender ?? session('gender', 'Gender-Neutral'),
            'round_display' => $round_display ?? session('round_display', Rank::ROUND_DISPLAY_LAST),
            'rank_type' => $rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED),
            'home_state' => ($rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)) === Rank::RANK_TYPE_MAIN ? ($home_state ?? session('home_state')) : null,
            'title' => $this->getTitle($institutes, $rank_type),
            'initial_chart_data' => $this->getUpdatedChartData(),
        ]);
        $this->form->getState();
    }

    private function ensureSubsetOf(?array $values, array $array): array
    {
        return array_diff($values ?? [], $array) ? [] : ($values ?? []);
    }

    private function ensureBelongsTo(?string $value, array $array): ?string
    {
        return array_search($value, $array) !== false ? $value : null;
    }

    private function getInstituteType(): array
    {
        return $this->rank_type === Rank::RANK_TYPE_ADVANCED
            ? ['iit']
            : ($this->institute_type
                ? $this->institute_type
                : ['iiit', 'nit', 'gfti']
            );
    }

    private function getInstituteQuotas(): array
    {
        $institute_type = $this->getInstituteType();

        return Cache::rememberForever(
            'institute_quota_'.implode('_', $institute_type).($this->rank_type === Rank::RANK_TYPE_MAIN ? '_'.$this->home_state : ''),
            function () use ($institute_type) {
                return DB::table('institute_quota')
                    ->where(function ($query) use ($institute_type) {
                        $query->whereIn('institute_id', Institute::whereIn('type', $institute_type)->pluck('id'));
                        if ($this->rank_type === Rank::RANK_TYPE_MAIN) {
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

    public function getUpdatedChartData(): array
    {
        $data = [];
        if ($this->institutes
            && $this->seat_type
            && $this->gender
            && $this->round_display
            && ($this->rank_type === Rank::RANK_TYPE_ADVANCED || $this->home_state)
        ) {
            $institute_quotas = $this->getInstituteQuotas();
            $query = Rank::whereIn('institute_id', $this->institutes)
                        ->whereIn(DB::raw('institute_id || quota_id'), array_map(function ($institute_quota) {
                            return $institute_quota->institute_id.$institute_quota->quota_id;
                        }, $institute_quotas))
                        ->where('seat_type_id', $this->seat_type)
                        ->where('gender_id', $this->gender);
            if ($this->courses) {
                $query->whereIn('course_id', $this->courses);
            }

            switch($this->round_display) {
                case Rank::ROUND_DISPLAY_ALL:
                    $year_round = Cache::rememberForever(
                        'year_round_distinct',
                        fn () => Rank::select('year', 'round')
                                    ->distinct()
                                    ->orderBy('year')
                                    ->orderBy('round')
                                    ->get()
                    );
                    break;
                case Rank::ROUND_DISPLAY_LAST:
                    $year_round = Cache::rememberForever(
                        'year_round_last',
                        fn () => Rank::select('year', DB::raw('MAX(round) as round'))
                                    ->groupBy('year')
                                    ->orderBy('year')
                                    ->get()
                    );
                    if ($this->rank_type === Rank::RANK_TYPE_ADVANCED) {
                        // This fix is to handle the case of 2014 where there were
                        // 3 rounds for IITs and 4 rounds for NIT+
                        $year_round = $year_round->map(function ($item) {
                            if ($item->year === 2014) {
                                $item->round = 3;
                            }

                            return $item;
                        });
                    }
                    $query = $query->whereIn(DB::raw('year || round'), $year_round->map(function ($item) {
                        return $item->year.$item->round;
                    }));
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
                    $query = $query->where('round', $this->round_display);
                    break;
            }
            $program_data = $query->get();
            $columns = $year_round->map(fn ($year_round) => $year_round->year.'_'.$year_round->round);
            $initial_institute_data = $columns->mapWithKeys(fn ($column) => [$column => null])->toArray();
            $institute_data = [];
            foreach ($program_data as $data) {
                if (! isset($institute_data[$data->institute->alias])) {
                    $institute_data[$data->institute->alias] = [];
                }
                $program_label = $data->course->alias.', '.$data->program_id.' ('.$data->quota_id.')';
                if (! isset($institute_data[$data->institute->alias][$program_label])) {
                    $institute_data[$data->institute->alias][$program_label] = $initial_institute_data;
                }
                $institute_data[$data->institute->alias][$program_label][$data->year.'_'.$data->round] = $data->closing_rank;
            }

            $datasets = [];
            foreach ($institute_data as $institute => $program_data) {
                foreach ($program_data as $program => $data) {
                    $datasets[] = [
                        'label' => $institute.' ('.$program.')',
                        'data' => array_values($data),
                    ];
                }
            }
            $labels = array_keys($initial_institute_data);
            foreach ($labels as $key => $label) {
                $labels[$key] = str_replace('_', ' - R', $label);
            }
            $this->title = $this->getTitle($this->institutes, $this->rank_type);
            $data = [
                'labels' => $labels,
                'datasets' => $datasets,
                'title' => $this->title,
            ];
        } else {
            $this->title = '';
        }

        return $data;
    }

    public function updateChartData()
    {
        $data = $this->getUpdatedChartData();
        $this->emit('chartDataUpdated', $data);
        $this->form->getState();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1, 'md' => 3])->schema([
                Radio::make('rank_type')
                    ->label('Rank type')
                    ->columns(['default' => 2])
                    ->options(Rank::RANK_TYPE_OPTIONS)
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->institutes = [];
                        if ($this->rank_type === Rank::RANK_TYPE_ADVANCED) {
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
                    ->label('Home state')
                    ->options($this->all_states)
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->afterStateUpdated(function () {
                        session()->put('home_state', $this->home_state);
                        $this->emit('updateChartData');
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                CheckboxList::make('institute_type')
                    ->label('Institute types')
                    ->options(Institute::INSTITUTE_TYPE_OPTIONS)
                    ->columns(['default' => 3])
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->institutes = [];
                        $this->emit('updateChartData');
                    })
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'md' => 2])->schema([
                MultiSelect::make('institutes')
                    ->options(fn () => Institute::whereIn('type', $this->getInstituteType())->pluck('id', 'id'))
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->emit('updateChartData');
                    })
                    ->required()
                    ->reactive(),
                MultiSelect::make('courses')
                    ->options(fn () => Institute::whereIn('id', $this->institutes)->get()->pluck('courses')->flatten()->pluck('id', 'id'))
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function () {
                        $this->emit('updateChartData');
                    })
                    ->hidden(! $this->institutes)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'sm' => 3])->schema([
                Select::make('seat_type')
                    ->options($this->all_seat_types)
                    ->afterStateUpdated(function () {
                        session()->put('seat_type', $this->seat_type);
                        $this->emit('updateChartData');
                    })
                    ->label('Seat type')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('gender')
                    ->options($this->all_genders)
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
                    ->label('Display rounds')
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
