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
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchByBranch extends Component implements HasTable
{
    use InteractsWithTable;

    public array $institute_type = [];

    public array $courses = [];

    public array $branches = [];

    public array $institutes = [];

    public ?string $seat_type = null;

    public ?string $gender = null;

    public ?string $round_display = null;

    public ?string $rank_type = null;

    public ?string $home_state = null;

    public ?string $minimum_rank = null;

    public ?string $maximum_rank = null;

    private $all_institutes;

    private $all_courses;

    private $all_branches;

    private $all_states;

    private $all_seat_types;

    private $all_genders;

    protected $queryString = [
        'tableSortColumn' => ['except' => 'closing_rank'],
        'tableSortDirection' => ['except' => 'asc'],
        'courses',
        'branches',
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
        $this->all_institutes = Cache::rememberForever('all_institutes', fn () => Institute::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_courses = Cache::rememberForever('all_courses', fn () => Course::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_branches = Cache::rememberForever('all_branches', fn () => Branch::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_states = Cache::rememberForever('all_states', fn () => State::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_seat_types = Cache::rememberForever('all_seat_types', fn () => SeatType::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_genders = Cache::rememberForever('all_genders', fn () => Gender::orderBy('id')->pluck('id', 'id')->toArray());
    }

    public function mount(): void
    {
        $courses = $this->ensureSubsetOf($this->courses, $this->all_courses);
        $branches = $this->ensureSubsetOf($this->branches, $this->all_branches);
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
            'branches' => $branches,
            'institutes' => $institutes,
            'seat_type' => $seat_type ?? session('seat_type', 'OPEN'),
            'gender' => $gender ?? session('gender', 'Gender-Neutral'),
            'round_display' => $round_display ?? session('round_display', Rank::ROUND_DISPLAY_LAST),
            'rank_type' => $rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED),
            'home_state' => ($rank_type ?? session('rank_type', Rank::RANK_TYPE_ADVANCED)) === Rank::RANK_TYPE_MAIN ? ($home_state ?? session('home_state')) : null,
            'minimum_rank' => $this->minimum_rank ?? session('minimum_rank'),
            'maximum_rank' => $this->maximum_rank ?? session('maximum_rank'),
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

    public function getRankQuery(): Builder
    {
        $institute_quotas = $this->getInstituteQuotas();
        $programs = Cache::rememberForever(
            'programs_'.implode('_', $this->branches),
            fn () => DB::table('branch_program')
                        ->whereIn('branch_id', $this->branches)
                        ->pluck('program_id')
        );
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

        $query = $query
            ->when($this->minimum_rank, fn ($query, $minimum_rank) => $query->where('closing_rank', '>=', $minimum_rank))
            ->when($this->maximum_rank, fn ($query, $maximum_rank) => $query->where('closing_rank', '<=', $maximum_rank));
        switch($this->round_display) {
            case Rank::ROUND_DISPLAY_ALL:
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
                $query = $query->where('round', $this->round_display);
                break;
        }

        return $query;
    }

    public function getTableQuery(): Builder
    {
        if ($this->branches
            && $this->seat_type
            && $this->gender
            && $this->round_display
            && ($this->rank_type === Rank::RANK_TYPE_ADVANCED || $this->home_state)
        ) {
            return $this->getRankQuery();
        } else {
            return Rank::query()->where('id', null);
        }
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
                        $this->branches = [];
                        if ($this->rank_type === Rank::RANK_TYPE_ADVANCED) {
                            $this->home_state = null;
                        } else {
                            $this->home_state = session('home_state');
                        }
                        session()->put('rank_type', $this->rank_type);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->required()
                    ->reactive(),
                Select::make('home_state')
                    ->label('Home state')
                    ->options(fn () => $this->all_states)
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->afterStateUpdated(function () {
                        session()->put('home_state', $this->home_state);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                CheckboxList::make('institute_type')
                    ->label('Institute types')
                    ->options(Institute::INSTITUTE_TYPE_OPTIONS)
                    ->columns(['default' => 3])
                    ->afterStateUpdated(function () {
                        $this->institutes = [];
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->hidden(fn () => $this->rank_type !== Rank::RANK_TYPE_MAIN)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'md' => 3])->schema([
                MultiSelect::make('branches')
                    ->label('Branches')
                    ->placeholder('Select Branches')
                    ->options(fn () => $this->all_branches)
                    ->optionsLimit(150)
                    ->afterStateUpdated(function () {
                        $this->courses = [];
                        $this->institutes = [];
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                MultiSelect::make('courses')
                    ->options(function () {
                        $programs = DB::table('branch_program')
                                        ->whereIn('branch_id', $this->branches)
                                        ->pluck('program_id');

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
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->hidden(! $this->branches)
                    ->reactive(),
                MultiSelect::make('institutes')
                    ->options(function () {
                        $programs = DB::table('branch_program')
                                        ->whereIn('branch_id', $this->branches)
                                        ->pluck('program_id');

                        return DB::table('institute_course_program')
                                    ->whereIn('program_id', $programs)
                                    ->whereIn('course_id', $this->courses)
                                    ->orderBy('institute_id')
                                    ->get()
                                    ->pluck('institute_id', 'institute_id');
                    })
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(function () {
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->hidden(! $this->branches || ! $this->courses)
                    ->reactive(),
            ]),
            Grid::make(['default' => 1, 'sm' => 3])->schema([
                Select::make('seat_type')
                    ->options($this->all_seat_types)
                    ->afterStateUpdated(function () {
                        session()->put('seat_type', $this->seat_type);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Seat type')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('gender')
                    ->options($this->all_genders)
                    ->afterStateUpdated(function () {
                        session()->put('gender', $this->gender);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Gender')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('round_display')
                    ->options(Rank::ROUND_DISPLAY_OPTIONS)
                    ->afterStateUpdated(function () {
                        session()->put('round_display', $this->round_display);
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
                    ->afterStateUpdated(function () {
                        session()->put('minimum_rank', $this->minimum_rank);
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
                    ->afterStateUpdated(function () {
                        session()->put('maximum_rank', $this->maximum_rank);
                        $this->gotoPage(1);
                        $this->form->getState();
                    })
                    ->label('Maximum Rank')
                    ->placeholder('Maximum Rank')
                    ->reactive(),
            ]),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('institute.id')
                ->label('Institute')
                ->sortable(),
            TextColumn::make('course.id')
                ->label('Course')
                ->sortable(),
            TextColumn::make('program.id')
                ->label('Program')
                ->sortable(),
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
        ];
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
        return view('livewire.search-by-branch');
    }
}