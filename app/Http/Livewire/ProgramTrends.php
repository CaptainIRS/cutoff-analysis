<?php

namespace App\Http\Livewire;

use App\Models\Course;
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

class ProgramTrends extends Component implements HasForms
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

    protected $listeners = ['updateChartData'];

    public function mount(): void
    {
        $this->form->fill([
            'quota_id' => session('quota_id'),
            'seat_type_id' => session('seat_type_id'),
            'gender_id' => session('gender_id'),
        ]);
    }

    public function updateChartData(): void
    {
        $data = [];
        if ($this->course_id !== null && $this->program_id !== null && $this->quota_id !== null && $this->seat_type_id !== null && $this->gender_id !== null) {
            if ($this->institute_id === $this->old_institute_id
                && $this->institute_type === $this->old_institute_type
                && $this->quota_id === $this->old_quota_id
                && $this->seat_type_id === $this->old_seat_type_id
                && $this->course_id === $this->old_course_id
                && $this->program_id === $this->old_program_id
                && $this->gender_id === $this->old_gender_id) {
                return;
            }
            $query = Rank::where('program_id', $this->program_id)
                ->where('course_id', $this->course_id)
                ->whereIn('quota_id', $this->quota_id)
                ->where('seat_type_id', $this->seat_type_id)
                ->where('gender_id', $this->gender_id);
            if ($this->institute_id !== null && $this->institute_id !== []) {
                $query->whereIn('institute_id', $this->institute_id);
            } elseif ($this->institute_type !== null && $this->institute_type !== []) {
                $query->whereIn('institute_id', Institute::whereIn('type', $this->institute_type)->pluck('id'));
            }
            $program_data = $query->get();
            $initial_institute_data = ['2012_1' => null, '2012_1' => null, '2013_1' => null, '2013_1' => null, '2013_2' => null, '2013_2' => null, '2013_3' => null, '2013_3' => null, '2014_1' => null, '2014_1' => null, '2014_2' => null, '2014_2' => null, '2014_3' => null, '2014_3' => null, '2014_4' => null, '2014_4' => null, '2015_1' => null, '2015_1' => null, '2015_2' => null, '2015_2' => null, '2015_3' => null, '2015_3' => null, '2015_4' => null, '2015_4' => null, '2016_1' => null, '2016_1' => null, '2016_2' => null, '2016_2' => null, '2016_3' => null, '2016_3' => null, '2016_4' => null, '2016_4' => null, '2016_5' => null, '2016_5' => null, '2017_1' => null, '2017_1' => null, '2017_2' => null, '2017_2' => null, '2017_3' => null, '2017_3' => null, '2017_4' => null, '2017_4' => null, '2017_5' => null, '2017_5' => null, '2017_6' => null, '2017_6' => null, '2018_1' => null, '2018_1' => null, '2018_2' => null, '2018_2' => null, '2018_3' => null, '2018_3' => null, '2018_4' => null, '2018_4' => null, '2018_5' => null, '2018_5' => null, '2018_6' => null, '2018_6' => null, '2018_7' => null, '2018_7' => null, '2019_1' => null, '2019_1' => null, '2019_2' => null, '2019_2' => null, '2019_3' => null, '2019_3' => null, '2019_4' => null, '2019_4' => null, '2019_5' => null, '2019_5' => null, '2019_6' => null, '2019_6' => null, '2020_1' => null, '2020_1' => null, '2020_2' => null, '2020_2' => null, '2020_3' => null, '2020_3' => null, '2020_4' => null, '2020_4' => null, '2020_5' => null, '2020_5' => null, '2020_6' => null, '2020_6' => null, '2021_1' => null, '2021_1' => null, '2021_2' => null, '2021_2' => null, '2021_3' => null, '2021_3' => null, '2021_4' => null, '2021_4' => null, '2021_5' => null, '2021_5' => null, '2021_6' => null, '2021_6' => null];
            $institute_data = [];
            foreach ($program_data as $data) {
                if (! isset($institute_data[$data->institute_id])) {
                    $institute_data[$data->institute_id] = [];
                }
                if (! isset($institute_data[$data->institute_id][$data->quota_id])) {
                    $institute_data[$data->institute_id][$data->quota_id] = $initial_institute_data;
                }
                $institute_data[$data->institute_id][$data->quota_id][$data->year.'_'.$data->round] = $data->closing_rank;
            }

            $datasets = [];
            foreach ($institute_data as $institute => $quota_data) {
                foreach ($quota_data as $quota => $data) {
                    $random_hue = crc32($institute.$quota) % 360;
                    $datasets[] = [
                        'label' => $institute.' ('.$quota.')',
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
            $this->emit('chartDataUpdated', $data);
        } else {
            $this->old_course_id = $this->course_id;
            $this->old_program_id = $this->program_id;
            $this->old_institute_id = $this->institute_id;
            $this->old_institute_type = $this->institute_type;
            $this->old_quota_id = $this->quota_id;
            $this->old_seat_type_id = $this->seat_type_id;
            $this->old_gender_id = $this->gender_id;
            $this->emit('chartDataUpdated', []);
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(4)->schema([
                Select::make('course_id')
                    ->options(Cache::rememberForever('allCourses', fn () => Course::all()->pluck('id', 'id')))
                    ->label('Course')
                    ->searchable()
                    ->afterStateUpdated(function (Closure $set) {
                        $set('program_id', null);
                        $set('institute_id', null);
                        $this->emit('updateChartData');
                    })->required()
                    ->reactive(),
                Select::make('program_id')
                    ->options(fn (Closure $get) => DB::table('institute_course_program')->where('course_id', $get('course_id'))->pluck('program_id', 'program_id'))
                    ->optionsLimit(150)
                    ->label('Program')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search, Closure $get) => DB::table('institute_course_program')->where('course_id', $get('course_id'))->whereIn('program_id', Program::search($search)->get()->pluck('id'))->pluck('program_id', 'program_id'))
                    ->afterStateUpdated(function (Closure $set) {
                        $set('institute_type', null);
                        $this->emit('updateChartData');
                    })
                    ->hidden(function (Closure $get) {
                        return ! $get('course_id');
                    })->required()
                    ->reactive(),
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
                        return ! $get('program_id') || ! $get('course_id');
                    })->reactive(),
                MultiSelect::make('institute_id')
                    ->options(function (Closure $get) {
                        $institutes = DB::table('institute_course_program')->where('course_id', $get('course_id'))->where('program_id', $get('program_id'));
                        if ($get('institute_type')) {
                            $institute_ids = $institutes->pluck('institute_id');

                            return Institute::whereIn('id', $institute_ids)->whereIn('type', $get('institute_type'))->pluck('id', 'id');
                        } else {
                            return $institutes->pluck('institute_id', 'institute_id');
                        }
                    })
                    ->optionsLimit(150)
                    ->label('Institute')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(function (Closure $get) {
                        return ! $get('program_id') || ! $get('course_id');
                    })
                    ->reactive(),
            ]),
            Grid::make(3)->schema([
                MultiSelect::make('quota_id')
                    ->options(Cache::rememberForever('allQuotas', fn () => Quota::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function (Closure $get) {
                        if ($get('quota_id') !== null && $get('quota_id') != []) {
                            session()->put('quota_id', $get('quota_id'));
                        }
                        $this->emit('updateChartData');
                    })
                    ->label('Quota')
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
            ]),
        ];
    }

    public function render()
    {
        return view('livewire.chart');
    }
}
