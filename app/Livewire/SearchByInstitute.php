<?php

namespace App\Livewire;

use App\Models\Institute;
use App\Models\Rank;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchByInstitute extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use CommonFields;

    public array $courses = [];

    public array $programs = [];

    public array $institutes = [];

    public ?string $minimum_rank = null;

    public ?string $maximum_rank = null;

    protected $queryString = [
        'tableSortColumn' => ['except' => 'closing_rank'],
        'tableSortDirection' => ['except' => 'asc'],
        'courses',
        'programs',
        'institutes',
        'institute_type' => ['as' => 'institute-type'],
        'round_display' => ['as' => 'round-display', 'except' => 'last'],
        'seat_type' => ['as' => 'seat-type', 'except' => 'OPEN'],
        'gender' => ['except' => 'Gender-Neutral'],
        'rank_type' => ['as' => 'rank'],
        'home_state' => ['as' => 'home-state'],
        'minimum_rank' => ['as' => 'min-rank'],
        'maximum_rank' => ['as' => 'max-rank'],
    ];

    public function __construct()
    {
        $this->initialiseCache();
    }

    private function getTitle(?array $institutes, ?string $rank_type): string
    {
        $institutes = $institutes ? Institute::whereIn('id', $institutes)->pluck('alias')->toArray() : [];
        $institute_names = array_map(fn ($institute_alias) => str_replace('&nbsp;', ' ', $institute_alias), $institutes);

        return $institute_names ? Arr::join($institute_names ?? [], ', ', ' and ').' '.Rank::RANK_TYPE_OPTIONS[$rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)].' Cut-off Ranks' : 'View Institute-wise Cut-off Ranks in JoSAA Counselling';
    }

    public function mount(): void
    {
        $courses = $this->ensureSubsetOf($this->courses, $this->all_courses);
        $programs = $this->ensureSubsetOf($this->programs, $this->all_programs);
        $institutes = $this->ensureSubsetOf($this->institutes, $this->all_institutes);
        $seat_type = $this->ensureBelongsTo($this->seat_type, $this->all_seat_types);
        $gender = $this->ensureBelongsTo($this->gender, $this->all_genders);
        $institute_type = $this->ensureSubsetOf($this->institute_type, Institute::INSTITUTE_TYPE_OPTIONS);
        $round_display = $this->ensureBelongsTo($this->round_display, Rank::ROUND_DISPLAY_OPTIONS);
        $rank_type = $this->ensureBelongsTo($this->rank_type, Rank::RANK_TYPE_OPTIONS);
        $home_state = $this->ensureBelongsTo($this->home_state, $this->all_states);
        if (! $this->is_canonical && $institutes && count($institutes) === 1) {
            $this->canonical_url = route('search-by-institute-proxy', [
                'institute' => $institutes[0],
            ]);
        } else {
            $this->canonical_url = request()->fullUrl();
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
            'home_state' => ($rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)) ? ($home_state ?? session('home_state')) : null,
            'minimum_rank' => $this->minimum_rank ?? session('minimum_rank'),
            'maximum_rank' => $this->maximum_rank ?? session('maximum_rank'),
            'title' => $this->getTitle($institutes, $rank_type),
            'alternative_url' => route('search-by-institute', ['rank' => $rank_type, 'institutes' => $institutes]),
        ]);
        $this->form->getState();
    }

    public function getRankQuery(): Builder
    {
        $institute_quotas = $this->getInstituteQuotas();
        $query = Rank::whereIn(DB::raw('institute_id || quota_id'), array_map(function ($institute_quota) {
            return $institute_quota->institute_id.$institute_quota->quota_id;
        }, $institute_quotas))
            ->where('seat_type_id', $this->seat_type)
            ->where('gender_id', $this->gender);
        if ($this->institutes) {
            $query->whereIn('institute_id', $this->institutes);
        }
        if ($this->courses) {
            $query->whereIn('course_id', $this->courses);
        }
        if ($this->programs) {
            $query->whereIn('program_id', $this->programs);
        }
        $query = $query
            ->when($this->minimum_rank, fn ($query, $minimum_rank) => $query->where('closing_rank', '>=', $minimum_rank))
            ->when($this->maximum_rank, fn ($query, $maximum_rank) => $query->where('closing_rank', '<=', $maximum_rank));
        $institute_type = $this->getInstituteType();
        $query->whereIn('institute_id', Institute::whereIn('type', $institute_type)->pluck('id'));

        $this->filterYearRound($query);

        if ($this->institutes) {
            $this->title = $this->getTitle($this->institutes, $this->rank_type);
        } else {
            $this->title = '';
        }

        $this->dispatch('titleUpdated', title: $this->title);

        return $query;
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1, 'md' => 3])->schema([
                Radio::make('rank_type')
                    ->label('Rank type')
                    ->columns(['default' => 2])
                    ->options(Rank::RANK_TYPE_OPTIONS)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $set('courses', []);
                        $set('institutes', []);
                        if ($get('rank_type') === Rank::RANK_TYPE_ADVANCED) {
                            $set('home_state', null);
                        } else {
                            $set('home_state', session('home_state'));
                        }
                        session()->put('rank_type', $get('rank_type'));
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->reactive(),
                Select::make('home_state')
                    ->label('Home state')
                    ->hint('To show home state quota ranks')
                    ->hintIcon('heroicon-o-information-circle')
                    ->options($this->all_states)
                    ->hidden(fn (Get $get) => $get('rank_type') !== Rank::RANK_TYPE_MAIN)
                    ->afterStateUpdated(function (Get $get) {
                        session()->put('home_state', $get('home_state'));
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->searchable()
                    ->reactive(),
                CheckboxList::make('institute_type')
                    ->label('Institute types')
                    ->options(Institute::INSTITUTE_TYPE_OPTIONS)
                    ->columns(['default' => 3])
                    ->afterStateUpdated(function (Set $set) {
                        $set('courses', []);
                        $set('institutes', []);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->hidden(fn (Get $get) => $get('rank_type') !== Rank::RANK_TYPE_MAIN)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'md' => 3])->schema([
                Select::make('institutes')
                    ->multiple()
                    ->allowHtml()
                    ->options(fn () => Institute::whereIn('type', $this->getInstituteType())->pluck('alias', 'id'))
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(function (Set $set) {
                        $set('courses', []);
                        $set('programs', []);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->reactive(),
                Select::make('courses')
                    ->multiple()
                    ->options(fn (Get $get) => Institute::whereIn('id', $get('institutes'))->get()->pluck('courses')->flatten()->pluck('alias', 'id'))
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function (Set $set) {
                        $set('programs', []);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->hidden(fn (Get $get) => ! $get('institutes'))
                    ->reactive(),
                Select::make('programs')
                    ->multiple()
                    ->label('Programs')
                    ->placeholder('Select Programs')
                    ->options(fn (Get $get) => DB::table('institute_course_program')->whereIn('institute_id', $get('institutes'))->whereIn('course_id', $get('courses'))->get()->pluck('program_name', 'program_id'))
                    ->optionsLimit(150)
                    ->afterStateUpdated(function () {
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->searchable()
                    ->hidden(fn (Get $get) => ! $get('courses') || ! $get('institutes'))
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'sm' => 3])->schema([
                Select::make('seat_type')
                    ->options($this->all_seat_types)
                    ->afterStateUpdated(function (Get $get) {
                        session()->put('seat_type', $get('seat_type'));
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Seat type')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('gender')
                    ->options($this->all_genders)
                    ->afterStateUpdated(function (Get $get) {
                        session()->put('gender', $get('gender'));
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Gender')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('round_display')
                    ->options(Rank::ROUND_DISPLAY_OPTIONS)
                    ->afterStateUpdated(function (Get $get) {
                        session()->put('round_display', $get('round_display'));
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Display rounds')
                    ->searchable()
                    ->required()
                    ->reactive(),
            ]),
            Grid::make(['default' => 2])->schema([
                TextInput::make('minimum_rank')
                    ->numeric()
                    ->nullable()
                    ->step(500)
                    ->afterStateUpdated(function (Get $get) {
                        session()->put('minimum_rank', $get('minimum_rank'));
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Minimum Rank')
                    ->placeholder('Minimum Rank')
                    ->reactive(),
                TextInput::make('maximum_rank')
                    ->numeric()
                    ->nullable()
                    ->step(500)
                    ->afterStateUpdated(function (Get $get) {
                        session()->put('maximum_rank', $get('maximum_rank'));
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Maximum Rank')
                    ->placeholder('Maximum Rank')
                    ->reactive(),
            ]),
        ];
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('institute.alias')
                ->html()
                ->sortable()
                ->url(fn (Rank $record) => route('institute-trends-proxy', ['institute' => $record->institute_id])),
            TextColumn::make('course.alias')
                ->label('Course')
                ->sortable(),
            TextColumn::make('program.name')
                ->label('Program')
                ->sortable()
                ->url(fn (Rank $record) => route('round-trends-proxy', [
                    'institute' => $record->institute_id,
                    'course' => $record->course_id,
                    'program' => $record->program_id,
                ])),
            TextColumn::make('year')
                ->label('Year')
                ->sortable(),
            TextColumn::make('round')
                ->label('Round')
                ->sortable(),
            TextColumn::make('opening_rank')
                ->label('Opening Rank')
                ->sortable(),
            TextColumn::make('closing_rank')
                ->label('Closing Rank')
                ->sortable(),
        ])
            ->query(function () {
                if ($this->seat_type
                && $this->gender
                && $this->round_display
                ) {
                    return $this->getRankQuery();
                } else {
                    $this->title = '';
                    $this->dispatch('titleUpdated', title: $this->title);

                    return Rank::query()->where('id', null);
                }
            });
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'closing_rank';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }

    protected function paginateTableQuery(Builder $query)
    {
        return $query->fastPaginate($this->getTableRecordsPerPage());
    }

    public function render()
    {
        return view('livewire.search-by-institute');
    }
}
