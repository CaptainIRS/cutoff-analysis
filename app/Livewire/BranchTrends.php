<?php

namespace App\Livewire;

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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BranchTrends extends Component implements HasForms
{
    use InteractsWithForms;
    use CommonFields;

    public array $courses = [];

    public array $branches = [];

    public array $institutes = [];

    public array $initial_chart_data = [];

    protected $listeners = ['updateChartData'];

    protected $queryString = [
        'courses',
        'branches',
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
        $this->initialiseCache();
    }

    private function getTitle(?array $branches, ?string $rank_type): string
    {
        $branches = array_map(fn ($branch) => $this->all_branches[$branch], $branches ?? []);

        return $branches ? Arr::join($branches ?? [], ', ', ' and ').' Branch '.Rank::RANK_TYPE_OPTIONS[$rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)].' Cut-off Trends' : 'Analyze Branch-wise Cut-off Trends of IITs, NITs, IIITs and GFTIs';
    }

    public function mount(): void
    {
        $courses = $this->ensureSubsetOf($this->courses, $this->all_courses);
        $branches = $this->ensureSubsetOf($this->branches, $this->all_branches);
        $institutes = $this->ensureSubsetOf($this->institutes, $this->all_institutes);
        $seat_type = $this->ensureBelongsTo($this->seat_type, $this->all_seat_types);
        $gender = $this->ensureBelongsTo($this->gender, $this->all_genders);
        $institute_type = $this->ensureSubsetOf($this->institute_type, Institute::INSTITUTE_TYPE_OPTIONS);
        $round_display = $this->ensureBelongsTo($this->round_display, Rank::ROUND_DISPLAY_OPTIONS);
        $rank_type = $this->ensureBelongsTo($this->rank_type, Rank::RANK_TYPE_OPTIONS);
        $home_state = $this->ensureBelongsTo($this->home_state, $this->all_states);
        if (! $this->is_canonical && $branches && $rank_type && count($branches) === 1) {
            $this->canonical_url = route('branch-trends-proxy', [
                'branch' => $branches[0],
                'rank' => $rank_type,
            ]);
        }
        $this->form->fill([
            'institute_type' => $institute_type,
            'courses' => $courses,
            'branches' => $branches,
            'institutes' => $institutes,
            'seat_type' => $seat_type ?? session('seat_type', 'OPEN'),
            'gender' => $gender ?? session('gender', 'Gender-Neutral'),
            'round_display' => $round_display ?? session('round_display', Rank::ROUND_DISPLAY_LAST),
            'rank_type' => $rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED),
            'home_state' => ($rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)) === Rank::RANK_TYPE_MAIN ? ($home_state ?? session('home_state')) : null,
            'title' => $this->getTitle($branches, $rank_type),
            'initial_chart_data' => $this->getUpdatedChartData(),
            'alternative_url' => route('branch-trends', ['rank' => $rank_type, 'branches' => $branches]),
        ]);
        $this->form->getState();
    }

    public function getUpdatedChartData(): array
    {
        $data = [];
        if ($this->branches
            && $this->seat_type
            && $this->gender
            && $this->round_display
        ) {
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
            }
            $institute_type = $this->getInstituteType();
            $query->whereIn('institute_id', Institute::whereIn('type', $institute_type)->pluck('id'));

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
            $this->title = $this->getTitle($this->branches, $this->rank_type);
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
                        $this->courses = [];
                        $this->institutes = [];
                        $this->dispatch('updateChartData');
                    })
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'md' => 3])->schema([
                Select::make('branches')
                    ->multiple()
                    ->label('Branches')
                    ->placeholder('Select Branches')
                    ->options($this->all_branches)
                    ->optionsLimit(150)
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->institutes = [];
                        $this->dispatch('updateChartData');
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('courses')
                    ->multiple()
                    ->allowHtml()
                    ->options(function () {
                        if ($this->branches) {
                            $programs = Cache::rememberForever(
                                'programs_'.implode('_', $this->branches),
                                fn () => DB::table('branch_program')
                                    ->whereIn('branch_id', $this->branches)
                                    ->pluck('program_id')
                            );

                            return Program::whereIn('id', $programs)
                                ->get()
                                ->pluck('courses')
                                ->flatten()
                                ->pluck('alias', 'id');
                        } else {
                            return [];
                        }
                    })
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function () {
                        $this->institutes = [];
                        $this->dispatch('updateChartData');
                    })
                    ->hidden(! $this->branches)->reactive(),
                Select::make('institutes')
                    ->multiple()
                    ->allowHtml()
                    ->options(function () {
                        if ($this->branches) {
                            $programs = Cache::rememberForever(
                                'programs_'.implode('_', $this->branches),
                                fn () => DB::table('branch_program')
                                    ->whereIn('branch_id', $this->branches)
                                    ->pluck('program_id')
                            );
                            $query = DB::table('institute_course_program')->whereIn('program_id', $programs);
                            if ($this->courses) {
                                $query->whereIn('course_id', $this->courses);
                            }
                            $institute_type = $this->getInstituteType();
                            $institutes = Institute::whereIn('type', $institute_type)->pluck('id');
                            $query = $query->whereIn('institute_id', $institutes);

                            return $query->orderBy('institute_id')
                                ->get()
                                ->pluck('institute_alias', 'institute_id');
                        } else {
                            return [];
                        }
                    })
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(fn () => $this->dispatch('updateChartData'))
                    ->hidden(! $this->branches)
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
