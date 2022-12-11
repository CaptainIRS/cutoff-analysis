<?php

namespace App\Http\Livewire;

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
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class RoundTrends extends Component implements HasForms
{
    use InteractsWithForms;

    public array $institute_type = [];

    public ?string $course = null;

    public ?string $program = null;

    public ?string $institute = null;

    public ?string $seat_type = null;

    public ?string $gender = null;

    public ?string $round_display = null;

    public ?string $rank_type = null;

    public ?string $home_state = null;

    public string $title = '';

    public array $initial_chart_data = [];

    private $all_institutes;

    private $all_programs;

    private $all_courses;

    private $all_states;

    private $all_seat_types;

    private $all_genders;

    protected $listeners = ['updateChartData'];

    protected $queryString = [
        'course',
        'program',
        'institute',
        'institute_type' => ['as' => 'institute-type'],
        'round_display' => ['as' => 'round-display', 'except' => 'last'],
        'seat_type' => ['as' => 'seat-type', 'except' => 'OPEN'],
        'gender' => ['except' => 'Gender-Neutral'],
        'rank_type' => ['as' => 'rank'],
        'home_state' => ['as' => 'home-state'],
    ];

    public function __construct()
    {
        $this->all_institutes = Cache::rememberForever('all_institutes', fn () => Institute::orderBy('id')->pluck('name', 'id')->toArray());
        $this->all_courses = Cache::rememberForever('all_courses', fn () => Course::orderBy('id')->pluck('name', 'id')->toArray());
        $this->all_programs = Cache::rememberForever('all_programs', fn () => Program::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_states = Cache::rememberForever('all_states', fn () => State::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_seat_types = Cache::rememberForever('all_seat_types', fn () => SeatType::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_genders = Cache::rememberForever('all_genders', fn () => Gender::orderBy('id')->pluck('id', 'id')->toArray());
    }

    private function getTitle(?string $institute, ?string $course, ?string $program, ?string $rank_type): string
    {
        $institute_alias = $institute ? str_replace('&nbsp;', ' ', Institute::find($institute)->alias) : null;
        $course_alias = $course ? str_replace('&nbsp;', ' ', Course::find($course)->alias) : null;

        return ($institute_alias && $course_alias && $program) ? $institute_alias.' '.$course_alias.' '.$program.' '.Rank::RANK_TYPE_OPTIONS[$rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)].' Cut-off Trends' : 'Analyse Round-wise Cut-off Trends in JoSAA Counselling';
    }

    public function mount(): void
    {
        $course = $this->ensureBelongsTo($this->course, array_keys($this->all_courses));
        $program = $this->ensureBelongsTo($this->program, $this->all_programs);
        $institute = $this->ensureBelongsTo($this->institute, array_keys($this->all_institutes));
        $seat_type = $this->ensureBelongsTo($this->seat_type, $this->all_seat_types);
        $gender = $this->ensureBelongsTo($this->gender, $this->all_genders);
        $institute_type = $this->ensureSubsetOf($this->institute_type, array_keys(Institute::INSTITUTE_TYPE_OPTIONS));
        $round_display = $this->ensureBelongsTo($this->round_display, array_keys(Rank::ROUND_DISPLAY_OPTIONS));
        $rank_type = $this->ensureBelongsTo($this->rank_type, array_keys(Rank::RANK_TYPE_OPTIONS));
        $home_state = $this->ensureBelongsTo($this->home_state, $this->all_states);
        $this->form->fill([
            'institute_type' => $institute_type,
            'course' => $course,
            'program' => $program,
            'institute' => $institute,
            'seat_type' => $seat_type ?? session('seat_type', 'OPEN'),
            'gender' => $gender ?? session('gender', 'Gender-Neutral'),
            'round_display' => $round_display ?? session('round_display', Rank::ROUND_DISPLAY_LAST),
            'rank_type' => $rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED),
            'home_state' => ($rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)) === Rank::RANK_TYPE_MAIN ? ($home_state ?? session('home_state')) : null,
            'title' => $this->getTitle($institute, $course, $program, $rank_type),
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
                            if ($this->home_state) {
                                $query->where(function ($sub_query) {
                                    $sub_query->where('quota_id', 'OS')->whereNotIn('state_id', [$this->home_state])
                                        ->orWhere('quota_id', 'HS')->whereIn('state_id', [$this->home_state])
                                        ->orWhereNotIn('quota_id', ['OS', 'HS'])->whereIn('state_id', [$this->home_state])
                                        ->orWhere('quota_id', 'AI');
                                });
                            } else {
                                $query->whereIn('quota_id', ['OS', 'AI']);
                            }
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
        if ($this->institute
            && $this->course
            && $this->program
            && $this->seat_type
            && $this->gender
            && $this->round_display
        ) {
            $institute_quotas = $this->getInstituteQuotas();
            $query = Rank::where('institute_id', $this->institute)
                        ->where('course_id', $this->course)
                        ->where('program_id', $this->program)
                        ->whereIn(DB::raw('institute_id || quota_id'), array_map(function ($institute_quota) {
                            return $institute_quota->institute_id.$institute_quota->quota_id;
                        }, $institute_quotas))
                        ->where('seat_type_id', $this->seat_type)
                        ->where('gender_id', $this->gender);
            $institute_data = $query->get();
            $initial_round_data = ['Round 1' => null, 'Round 2' => null, 'Round 3' => null, 'Round 4' => null, 'Round 5' => null, 'Round 6' => null, 'Round 7' => null];
            $round_data = [];
            foreach ($institute_data as $data) {
                if ($this->rank_type === Rank::RANK_TYPE_MAIN && $data->year === 2014 && $this->seat_type !== 'OPEN') {
                    // Fix for 2014 data, as general rank is used for all categories
                    continue;
                }
                if (! isset($round_data[$data->year])) {
                    $round_data[$data->year] = $initial_round_data;
                }
                $round_data[$data->year]['Round '.$data->round] = $data->closing_rank;
            }

            $datasets = [];
            foreach ($round_data as $year => $year_data) {
                $datasets[] = [
                    'label' => $year,
                    'data' => array_values($year_data),
                ];
            }
            $labels = array_keys($initial_round_data);
            $this->title = $this->getTitle($this->institute, $this->course, $this->program, $this->rank_type);
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
                        $this->course = null;
                        $this->institute = null;
                        $this->program = null;
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
                    ->hint('To show home state quota ranks')
                    ->hintIcon('heroicon-o-information-circle')
                    ->options($this->all_states)
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->afterStateUpdated(function () {
                        session()->put('home_state', $this->home_state);
                        $this->emit('updateChartData');
                    })
                    ->searchable()
                    ->reactive(),
                CheckboxList::make('institute_type')
                    ->label('Institute types')
                    ->options(Institute::INSTITUTE_TYPE_OPTIONS)
                    ->columns(['default' => 3])
                    ->afterStateUpdated(function () {
                        $this->course = null;
                        $this->institute = null;
                        $this->program = null;
                        $this->emit('updateChartData');
                    })
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'md' => 3])->schema([
                Select::make('institute')
                    ->allowHtml()
                    ->options(fn () => Institute::whereIn('type', $this->getInstituteType())->pluck('alias', 'id'))
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(function () {
                        $this->course = null;
                        $this->program = null;
                        $this->emit('updateChartData');
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('course')
                    ->allowHtml()
                    ->options(fn () => Institute::where('id', $this->institute)->get()->pluck('courses')->flatten()->pluck('alias', 'id'))
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function () {
                        $this->program = null;
                        $this->emit('updateChartData');
                    })
                    ->hidden(! $this->institute)
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('program')
                    ->options(fn () => DB::table('institute_course_program')->where('institute_id', $this->institute)->where('course_id', $this->course)->pluck('program_id', 'program_id'))
                    ->label('Program')
                    ->searchable()
                    ->afterStateUpdated(function () {
                        $this->emit('updateChartData');
                    })
                    ->hidden(! $this->institute || ! $this->course)
                    ->searchable()
                    ->required()
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'sm' => 2])->schema([
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
            ]),
        ];
    }

    public function render()
    {
        return view('livewire.chart');
    }
}
