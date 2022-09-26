<?php

namespace App\Http\Livewire;

use App\Models\Gender;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Quota;
use App\Models\Rank;
use App\Models\SeatType;
use Cache;
use Closure;
use DB;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class FieldTrends extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $institute_type = [];

    public ?array $old_institute_type = [];

    public $course_id;

    public $old_course_id;

    public $program_id;

    public $old_program_id;

    public $institute_id;

    public $old_institute_id;

    public $quota_id;

    public $old_quota_id;

    public $seat_type_id;

    public $old_seat_type_id;

    public $gender_id;

    public $old_gender_id;

    public $round_display;

    public $old_round_display;

    protected $listeners = ['updateChartData'];

    public function mount(): void
    {
        $this->form->fill([
            'quota_id' => session()->exists('quota_id') ? session()->get('quota_id')[0] : null,
            'seat_type_id' => session('seat_type_id'),
            'gender_id' => session('gender_id'),
            'round_display' => session('round_display'),
        ]);
    }

    public function updateChartData(): void
    {
        $data = [];
        if ($this->program_id !== null && $this->quota_id !== null && $this->seat_type_id !== null && $this->gender_id !== null && $this->round_display !== null) {
            if ($this->institute_id === $this->old_institute_id
                && $this->institute_type === $this->old_institute_type
                && $this->quota_id === $this->old_quota_id
                && $this->seat_type_id === $this->old_seat_type_id
                && $this->course_id === $this->old_course_id
                && $this->program_id === $this->old_program_id
                && $this->gender_id === $this->old_gender_id
                && $this->round_display === $this->old_round_display) {
                return;
            }
            $programs = DB::table('program_tag')->whereIn('tag_id', $this->program_id)->pluck('program_id');
            $query = Rank::whereIn('program_id', $programs)
                ->where('quota_id', $this->quota_id)
                ->where('seat_type_id', $this->seat_type_id)
                ->where('gender_id', $this->gender_id);
            if ($this->course_id !== null && $this->course_id !== []) {
                $query->whereIn('course_id', $this->course_id);
            }
            if ($this->institute_id !== null && $this->institute_id !== []) {
                $query->whereIn('institute_id', $this->institute_id);
            } elseif ($this->institute_type !== null && $this->institute_type !== []) {
                $query->whereIn('institute_id', Institute::whereIn('type', $this->institute_type)->pluck('id'));
            }
            $program_data = $query->get();
            $year_round = Cache::rememberForever('year_round_distinct', fn () => Rank::select('year', 'round')->distinct()->orderBy('year')->orderBy('round')->get());
            switch($this->round_display) {
                case 'all':
                    break;
                case 'last':
                    $year_round = Cache::rememberForever('year_round_last', fn () => Rank::select('year', DB::raw('MAX(round) as round'))->groupBy('year')->orderBy('year')->get());
                    break;
                default:
                    $year_round = Cache::rememberForever('year_round_'.$this->round_display, fn () => Rank::select('year', 'round')->where('round', $this->round_display)->distinct()->orderBy('year')->get());
                    break;
            }
            $columns = $year_round->map(fn ($year_round) => $year_round->year.'_'.$year_round->round);
            $initial_institute_data = $columns->mapWithKeys(fn ($column) => [$column => null])->toArray();
            $institute_data = [];
            foreach ($program_data as $data) {
                if (! isset($institute_data[$data->institute_id])) {
                    $institute_data[$data->institute_id] = [];
                }
                if (! isset($institute_data[$data->institute_id][$data->course_id.', '.$data->program_id])) {
                    $institute_data[$data->institute_id][$data->course_id.', '.$data->program_id] = $initial_institute_data;
                }
                $institute_data[$data->institute_id][$data->course_id.', '.$data->program_id][$data->year.'_'.$data->round] = $data->closing_rank;
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
            $this->old_course_id = $this->course_id;
            $this->old_program_id = $this->program_id;
            $this->old_institute_id = $this->institute_id;
            $this->old_institute_type = $this->institute_type;
            $this->old_quota_id = $this->quota_id;
            $this->old_seat_type_id = $this->seat_type_id;
            $this->old_gender_id = $this->gender_id;
            $this->old_round_display = $this->round_display;
            $this->emit('chartDataUpdated', $data);
        } else {
            $this->old_course_id = $this->course_id;
            $this->old_program_id = $this->program_id;
            $this->old_institute_id = $this->institute_id;
            $this->old_institute_type = $this->institute_type;
            $this->old_quota_id = $this->quota_id;
            $this->old_seat_type_id = $this->seat_type_id;
            $this->old_gender_id = $this->gender_id;
            $this->old_round_display = $this->round_display;
            $this->emit('chartDataUpdated', []);
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(4)->schema([
                MultiSelect::make('program_id')
                    ->label('Branches')
                    ->placeholder('Select Branches')
                    ->options(Cache::rememberForever('allTags', fn () => DB::table('program_tag')->select('tag_id')->distinct()->orderBy('tag_id')->get()->pluck('tag_id', 'tag_id')))
                    ->optionsLimit(150)
                    ->afterStateUpdated(function (Closure $set) {
                        $set('course_id', null);
                        $set('institute_id', null);
                        $this->emit('updateChartData');
                    })
                    ->searchable()
                    ->required()
                    ->reactive(),
                MultiSelect::make('course_id')
                    ->options(function (Closure $get) {
                        $programs = DB::table('program_tag')->whereIn('tag_id', $get('program_id'))->pluck('program_id');

                        return Program::whereIn('id', $programs)->get()->pluck('courses')->flatten()->pluck('id', 'id');
                    })
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function (Closure $set) {
                        $set('institute_id', null);
                        $this->emit('updateChartData');
                    })
                    ->hidden(function (Closure $get) {
                        return ! $get('program_id');
                    })->reactive(),
                CheckboxList::make('institute_type')
                    ->label('Institute Types')
                    ->options([
                        'iit' => 'IITs',
                        'nit' => 'NITs',
                        'iiit' => 'IIITs',
                        'gfti' => 'GFTIs',
                    ])->columns([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->afterStateUpdated(function (Closure $set) {
                        $set('institute_id', null);
                        $this->emit('updateChartData');
                    })
                    ->hidden(function (Closure $get) {
                        return ! $get('program_id');
                    })->reactive(),
                MultiSelect::make('institute_id')
                    ->options(function (Closure $get) {
                        if ($get('program_id')) {
                            $programs = DB::table('program_tag')->whereIn('tag_id', $get('program_id'))->pluck('program_id');

                            $query = DB::table('institute_course_program')->whereIn('program_id', $programs);
                            if ($get('course_id')) {
                                $query->whereIn('course_id', $get('course_id'));
                            }
                            if ($get('institute_type')) {
                                $institutes = Institute::whereIn('type', $get('institute_type'))->pluck('id');
                                $query = $query->whereIn('institute_id', $institutes);
                            }

                            return $query->get()->pluck('institute_id', 'institute_id');
                        } else {
                            return Cache::rememberForever('allInstitutes', fn () => Institute::all()->pluck('id', 'id'));
                        }
                    })
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(function (Closure $get) {
                        return ! $get('program_id');
                    })
                    ->reactive(),
            ]),
            Grid::make(4)->schema([
                Select::make('quota_id')
                    ->options(Cache::rememberForever('allQuotas', fn () => Quota::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function (Closure $get) {
                        if ($get('quota_id') !== null) {
                            session()->put('quota_id', [$get('quota_id')]);
                        }
                        $this->emit('updateChartData');
                    })
                    ->label('Quota')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('seat_type_id')
                    ->options(Cache::rememberForever('allSeatTypes', fn () => SeatType::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function (Closure $get) {
                        if ($get('seat_type_id') !== null) {
                            session()->put('seat_type_id', $get('seat_type_id'));
                        }
                        $this->emit('updateChartData');
                    })
                    ->label('Seat Type')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('gender_id')
                    ->options(Cache::rememberForever('allGenders', fn () => Gender::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function (Closure $get) {
                        if ($get('gender_id') !== null && $get('gender_id') !== []) {
                            session()->put('gender_id', $get('gender_id'));
                        }
                        $this->emit('updateChartData');
                    })
                    ->label('Gender')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('round_display')
                    ->options([
                        'last' => 'Last Round Only',
                        'all' => 'All Rounds',
                        '1' => 'Round 1',
                        '2' => 'Round 2',
                        '3' => 'Round 3',
                        '4' => 'Round 4',
                        '5' => 'Round 5',
                        '6' => 'Round 6',
                        '7' => 'Round 7',
                    ])
                    ->afterStateUpdated(function (Closure $get) {
                        if ($get('round_display') !== null) {
                            session()->put('round_display', $get('round_display'));
                        }
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
