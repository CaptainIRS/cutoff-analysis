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

    protected $listeners = ['updateChartData'];

    public function updateChartData(): void
    {
        $data = [];
        if ($this->program_id !== null && $this->quota_id !== null && $this->seat_type_id !== null && $this->gender_id !== null) {
            if ($this->institute_id === $this->old_institute_id
                && $this->institute_type === $this->old_institute_type
                && $this->quota_id === $this->old_quota_id
                && $this->seat_type_id === $this->old_seat_type_id
                && $this->course_id === $this->old_course_id
                && $this->program_id === $this->old_program_id
                && $this->gender_id === $this->old_gender_id) {
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
            $initial_institute_data = ['2012_1' => null, '2012_1' => null, '2013_1' => null, '2013_1' => null, '2013_2' => null, '2013_2' => null, '2013_3' => null, '2013_3' => null, '2014_1' => null, '2014_1' => null, '2014_2' => null, '2014_2' => null, '2014_3' => null, '2014_3' => null, '2014_4' => null, '2014_4' => null, '2015_1' => null, '2015_1' => null, '2015_2' => null, '2015_2' => null, '2015_3' => null, '2015_3' => null, '2015_4' => null, '2015_4' => null, '2016_1' => null, '2016_1' => null, '2016_2' => null, '2016_2' => null, '2016_3' => null, '2016_3' => null, '2016_4' => null, '2016_4' => null, '2016_5' => null, '2016_5' => null, '2017_1' => null, '2017_1' => null, '2017_2' => null, '2017_2' => null, '2017_3' => null, '2017_3' => null, '2017_4' => null, '2017_4' => null, '2017_5' => null, '2017_5' => null, '2017_6' => null, '2017_6' => null, '2018_1' => null, '2018_1' => null, '2018_2' => null, '2018_2' => null, '2018_3' => null, '2018_3' => null, '2018_4' => null, '2018_4' => null, '2018_5' => null, '2018_5' => null, '2018_6' => null, '2018_6' => null, '2018_7' => null, '2018_7' => null, '2019_1' => null, '2019_1' => null, '2019_2' => null, '2019_2' => null, '2019_3' => null, '2019_3' => null, '2019_4' => null, '2019_4' => null, '2019_5' => null, '2019_5' => null, '2019_6' => null, '2019_6' => null, '2020_1' => null, '2020_1' => null, '2020_2' => null, '2020_2' => null, '2020_3' => null, '2020_3' => null, '2020_4' => null, '2020_4' => null, '2020_5' => null, '2020_5' => null, '2020_6' => null, '2020_6' => null, '2021_1' => null, '2021_1' => null, '2021_2' => null, '2021_2' => null, '2021_3' => null, '2021_3' => null, '2021_4' => null, '2021_4' => null, '2021_5' => null, '2021_5' => null, '2021_6' => null, '2021_6' => null];
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
            $data = [
                'labels' => array_keys($initial_institute_data),
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
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(4)->schema([
                MultiSelect::make('program_id')
                    ->label('Fields')
                    ->placeholder('Select Fields')
                    ->options(Cache::rememberForever('allTags', fn () => DB::table('program_tag')->select('tag_id')->distinct()->orderBy('tag_id')->get()->pluck('tag_id', 'tag_id')))
                    ->afterStateUpdated(function (Closure $set) {
                        $set('course_id', null);
                        $set('institute_id', null);
                    })
                    ->searchable()
                    ->afterStateUpdated(function (Closure $set) {
                        $set('institute_id', null);
                        $this->emit('updateChartData');
                    })->reactive(),
                MultiSelect::make('course_id')
                    ->options(function (Closure $get) {
                        if ($get('program_id')) {
                            $programs = DB::table('program_tag')->whereIn('tag_id', $get('program_id'))->pluck('program_id');

                            return Program::whereIn('id', $programs)->get()->pluck('courses')->flatten()->pluck('id', 'id');
                        } else {
                            return Cache::rememberForever('allCourses', fn () => Course::all()->pluck('id', 'id'));
                        }
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
                        return ! $get('program_id') && ! $get('course_id');
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
                    ->label('Institute')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(function (Closure $get) {
                        return ! $get('program_id') && ! $get('course_id');
                    })
                    ->reactive(),
            ]),
            Grid::make(3)->schema([
                Select::make('quota_id')
                    ->options(Cache::rememberForever('allQuotas', fn () => Quota::all()->pluck('id', 'id')))
                    ->label('Quota')
                    ->required()
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->reactive(),
                Select::make('seat_type_id')
                    ->options(Cache::rememberForever('allSeatTypes', fn () => SeatType::all()->pluck('id', 'id')))
                    ->label('Seat Type')
                    ->required()
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->reactive(),
                Select::make('gender_id')
                    ->options(Cache::rememberForever('allGenders', fn () => Gender::all()->pluck('id', 'id')))
                    ->label('Gender')
                    ->required()
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->reactive(),
            ]),
        ];
    }

    public function render()
    {
        return view('livewire.chart');
    }
}
