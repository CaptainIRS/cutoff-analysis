<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Rank;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InstituteTrends extends Component implements HasForms
{
    use CommonFields;
    use InteractsWithForms;

    public array $courses = [];

    public array $institutes = [];

    public array $programs = [];

    public array $initial_chart_data = [];

    protected $listeners = ['updateChartData'];

    protected $queryString = [
        'courses',
        'institutes',
        'programs',
        'institute_type' => ['as' => 'institute-type'],
        'round_display' => ['as' => 'round-display', 'except' => 'last'],
        'seat_type' => ['as' => 'seat-type', 'except' => 'OPEN'],
        'gender' => ['except' => 'Gender-Neutral'],
        'rank_type' => ['as' => 'rank'],
        'home_state' => ['as' => 'home-state'],
    ];

    public function __construct()
    {
        $this->initialiseCache();
    }

    private function getTitle(?array $institutes, ?array $programs, ?array $courses, ?string $rank_type): string
    {
        if (count($institutes) === 1 && count($programs) === 1 && count($courses) === 1) {
            $institute = Institute::find($institutes[0]);
            $program = Program::find($programs[0]);
            $course = Course::find($courses[0]);

            return str_replace('&nbsp;', ' ', $institute->alias).' '.$course->alias.' '.$program->name.' Cut-off Trends';
        }

        $institute_names = array_map(fn ($id) => str_replace('&nbsp;', ' ', $this->all_institutes[$id]), $institutes ?? []);

        return $institute_names
            ? Arr::join($institute_names, ', ', ' and ')
                .' '.Rank::RANK_TYPE_OPTIONS[$rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)]
                .' Cut-off Trends'
            : 'Analyze Institute Cut-off Trends in JoSAA Counselling';
    }

    public function mount(): void
    {
        $courses = $this->ensureSubsetOf($this->courses, $this->all_courses);
        $institutes = $this->ensureSubsetOf($this->institutes, $this->all_institutes);
        $programs = $this->ensureSubsetOf($this->programs, $this->all_programs);
        $seat_type = $this->ensureBelongsTo($this->seat_type, $this->all_seat_types);
        $gender = $this->ensureBelongsTo($this->gender, $this->all_genders);
        $institute_type = $this->ensureSubsetOf($this->institute_type, Institute::INSTITUTE_TYPE_OPTIONS);
        $round_display = $this->ensureBelongsTo($this->round_display, Rank::ROUND_DISPLAY_OPTIONS);
        $rank_type = $this->ensureBelongsTo($this->rank_type, Rank::RANK_TYPE_OPTIONS);
        $home_state = $this->ensureBelongsTo($this->home_state, $this->all_states);
        if (! $this->is_canonical && $institutes && count($institutes) === 1) {
            $this->canonical_url = route('institute-trends-proxy', [
                'institute' => $institutes[0],
            ]);
        }
        $this->form->fill([
            'institute_type' => $institute_type,
            'courses' => $courses,
            'programs' => $programs,
            'institutes' => $institutes,
            'seat_type' => $seat_type ?? session('seat_type', 'OPEN'),
            'gender' => $gender ?? session('gender', 'Gender-Neutral'),
            'round_display' => $round_display ?? session('round_display', Rank::ROUND_DISPLAY_LAST),
            'rank_type' => $rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED),
            'home_state' => ($rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)) === Rank::RANK_TYPE_MAIN ? ($home_state ?? session('home_state')) : null,
            'title' => $this->getTitle($institutes, $programs, $courses, $rank_type),
            'initial_chart_data' => $this->getUpdatedChartData(),
            'alternative_url' => route('institute-trends', ['rank' => $rank_type, 'institutes' => $institutes]),
        ]);
        $this->form->getState();
    }

    public function getUpdatedChartData(): array
    {
        $data = [];
        if ($this->institutes
            && $this->seat_type
            && $this->gender
            && $this->round_display
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
            if ($this->programs) {
                $query->whereIn('program_id', $this->programs);
            }

            $year_round = $this->filterYearRound($query);
            if ($this->rank_type === Rank::RANK_TYPE_MAIN) {
                $year_round = $year_round->filter(fn ($item) => $item->year >= 2014);
            }
            $program_data = $query->get();
            $columns = $year_round->map(fn ($year_round) => $year_round->year.'_'.$year_round->round);
            $initial_institute_data = $columns->mapWithKeys(fn ($column) => [$column => null])->toArray();
            $institute_data = [];
            foreach ($program_data as $data) {
                if (! isset($institute_data[$data->institute->alias])) {
                    $institute_data[$data->institute->alias] = [];
                }
                $program_label = $data->course->alias.' '.$data->program->name.' ('.$data->quota_id.')';
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
                $labels[$key] = str_replace('_', "\nRound ", $label);
            }
            $institute_ids = Institute::whereIn('alias', array_keys($institute_data))->pluck('id')->toArray();
            $this->title = $this->getTitle($institute_ids, $this->programs, $this->courses, $this->rank_type);
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
        $this->dispatch('chartDataUpdated', chartData: $data);
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
                        $this->programs = [];
                        $this->courses = [];
                        $this->institutes = [];
                        if ($this->rank_type === Rank::RANK_TYPE_ADVANCED) {
                            $this->home_state = null;
                        } else {
                            $this->home_state = session('home_state');
                        }
                        session()->put('rank_type', $this->rank_type);
                        $this->dispatch('updateChartData');
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
                        $this->dispatch('updateChartData');
                    })
                    ->searchable()
                    ->reactive(),
                CheckboxList::make('institute_type')
                    ->label('Institute types')
                    ->options(Institute::INSTITUTE_TYPE_OPTIONS)
                    ->columns(['default' => 3])
                    ->afterStateUpdated(function () {
                        $this->programs = [];
                        $this->courses = [];
                        $this->institutes = [];
                        $this->dispatch('updateChartData');
                    })
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'md' => 3])->schema([
                Select::make('institutes')
                    ->multiple()
                    ->allowHtml()
                    ->options(fn () => Institute::whereIn('type', $this->getInstituteType())->pluck('alias', 'id'))
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->programs = [];
                        $this->dispatch('updateChartData');
                    })
                    ->required()
                    ->reactive(),
                Select::make('courses')
                    ->multiple()
                    ->allowHtml()
                    ->options(fn () => Institute::whereIn('id', $this->institutes)->get()->pluck('courses')->flatten()->pluck('alias', 'id'))
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function () {
                        $this->programs = [];
                        $this->dispatch('updateChartData');
                    })
                    ->hidden(! $this->institutes)
                    ->reactive(),
                Select::make('programs')
                    ->multiple()
                    ->allowHtml()
                    ->options(fn () => DB::table('institute_course_program')->whereIn('institute_id', $this->institutes)->whereIn('course_id', $this->courses)->get()->pluck('program_name', 'program_id'))
                    ->label('Program')
                    ->searchable()
                    ->afterStateUpdated(function () {
                        $this->dispatch('updateChartData');
                    })
                    ->hidden(! $this->institutes || ! $this->courses)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'sm' => 3])->schema([
                Select::make('seat_type')
                    ->options($this->all_seat_types)
                    ->afterStateUpdated(function () {
                        session()->put('seat_type', $this->seat_type);
                        $this->dispatch('updateChartData');
                    })
                    ->label('Seat type')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('gender')
                    ->options($this->all_genders)
                    ->afterStateUpdated(function () {
                        session()->put('gender', $this->gender);
                        $this->dispatch('updateChartData');
                    })
                    ->label('Gender')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('round_display')
                    ->options(Rank::ROUND_DISPLAY_OPTIONS)
                    ->afterStateUpdated(function () {
                        session()->put('round_display', $this->round_display);
                        $this->dispatch('updateChartData');
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
