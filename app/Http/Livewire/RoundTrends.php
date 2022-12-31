<?php

namespace App\Http\Livewire;

use App\Models\Institute;
use App\Models\Rank;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RoundTrends extends Component implements HasForms
{
    use InteractsWithForms;
    use CommonFields;

    public ?string $course = null;

    public ?string $program = null;

    public ?string $institute = null;

    public array $initial_chart_data = [];

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
        $this->initialiseCache();
    }

    private function getTitle(?string $institute, ?string $course, ?string $program, ?string $rank_type): string
    {
        $institute_alias = $institute ? str_replace('&nbsp;', ' ', $this->all_institutes[$institute]) : null;
        $course_alias = $course ? str_replace('&nbsp;', ' ', $this->all_courses[$course]) : null;
        $program_name = $program ? $this->all_programs[$program] : null;

        return ($institute_alias && $course_alias && $program_name) ? $institute_alias.' '.$course_alias.' '.$program_name.' '.Rank::RANK_TYPE_OPTIONS[$rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)].' Cut-off Trends' : 'Analyse Round-wise Cut-off Trends in JoSAA Counselling';
    }

    public function mount(): void
    {
        $course = $this->ensureBelongsTo($this->course, $this->all_courses);
        $program = $this->ensureBelongsTo($this->program, $this->all_programs);
        $institute = $this->ensureBelongsTo($this->institute, $this->all_institutes);
        $seat_type = $this->ensureBelongsTo($this->seat_type, $this->all_seat_types);
        $gender = $this->ensureBelongsTo($this->gender, $this->all_genders);
        $institute_type = $this->ensureSubsetOf($this->institute_type, Institute::INSTITUTE_TYPE_OPTIONS);
        $round_display = $this->ensureBelongsTo($this->round_display, Rank::ROUND_DISPLAY_OPTIONS);
        $rank_type = $this->ensureBelongsTo($this->rank_type, Rank::RANK_TYPE_OPTIONS);
        $home_state = $this->ensureBelongsTo($this->home_state, $this->all_states);
        if (! $this->is_canonical && $institute && $course && $program) {
            $this->canonical_url = route('round-trends-proxy', [
                'institute' => $institute,
                'course' => $course,
                'program' => $program,
            ]);
        }
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
            'alternative_url' => route('round-trends', ['rank' => $rank_type, 'institute' => $institute, 'course' => $course, 'program' => $program]),
        ]);
        $this->form->getState();
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
                    ->options(fn () => DB::table('institute_course_program')->where('institute_id', $this->institute)->where('course_id', $this->course)->pluck('program_name', 'program_id'))
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
