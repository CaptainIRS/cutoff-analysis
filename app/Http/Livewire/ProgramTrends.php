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
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ProgramTrends extends Component implements HasForms
{
    use InteractsWithForms;

    public $program_id;

    public $course_id;

    public $institute_id;

    public $quota_id;

    public $seat_type_id;

    public $gender_id;

    protected $listeners = ['updateChartData'];

    public function updateChartData(): void
    {
        $data = [];
        if ($this->course_id !== null && $this->program_id !== null && $this->quota_id !== null && $this->seat_type_id !== null && $this->gender_id !== null) {
            $query = Rank::where('program_id', $this->program_id)
                ->where('course_id', $this->course_id)
                ->where('quota_id', $this->quota_id)
                ->where('seat_type_id', $this->seat_type_id)
                ->where('gender_id', $this->gender_id);
            if ($this->institute_id !== null) {
                $query->whereIn('institute_id', $this->institute_id);
            }
            $program_data = $query->get();
            $initial_institute_data = ['2012_1' => null, '2012_1' => null, '2013_1' => null, '2013_1' => null, '2013_2' => null, '2013_2' => null, '2013_3' => null, '2013_3' => null, '2014_1' => null, '2014_1' => null, '2014_2' => null, '2014_2' => null, '2014_3' => null, '2014_3' => null, '2014_4' => null, '2014_4' => null, '2015_1' => null, '2015_1' => null, '2015_2' => null, '2015_2' => null, '2015_3' => null, '2015_3' => null, '2015_4' => null, '2015_4' => null, '2016_1' => null, '2016_1' => null, '2016_2' => null, '2016_2' => null, '2016_3' => null, '2016_3' => null, '2016_4' => null, '2016_4' => null, '2016_5' => null, '2016_5' => null, '2017_1' => null, '2017_1' => null, '2017_2' => null, '2017_2' => null, '2017_3' => null, '2017_3' => null, '2017_4' => null, '2017_4' => null, '2017_5' => null, '2017_5' => null, '2017_6' => null, '2017_6' => null, '2018_1' => null, '2018_1' => null, '2018_2' => null, '2018_2' => null, '2018_3' => null, '2018_3' => null, '2018_4' => null, '2018_4' => null, '2018_5' => null, '2018_5' => null, '2018_6' => null, '2018_6' => null, '2018_7' => null, '2018_7' => null, '2019_1' => null, '2019_1' => null, '2019_2' => null, '2019_2' => null, '2019_3' => null, '2019_3' => null, '2019_4' => null, '2019_4' => null, '2019_5' => null, '2019_5' => null, '2019_6' => null, '2019_6' => null, '2020_1' => null, '2020_1' => null, '2020_2' => null, '2020_2' => null, '2020_3' => null, '2020_3' => null, '2020_4' => null, '2020_4' => null, '2020_5' => null, '2020_5' => null, '2020_6' => null, '2020_6' => null, '2021_1' => null, '2021_1' => null, '2021_2' => null, '2021_2' => null, '2021_3' => null, '2021_3' => null, '2021_4' => null, '2021_4' => null, '2021_5' => null, '2021_5' => null, '2021_6' => null, '2021_6' => null];
            $institute_data = [];
            foreach ($program_data as $data) {
                if (! isset($institute_data[$data->institute_id])) {
                    $institute_data[$data->institute_id] = $initial_institute_data;
                }
                $institute_data[$data->institute_id][$data->year.'_'.$data->round] = $data->closing_rank;
            }

            $datasets = [];
            foreach ($institute_data as $institute => $data) {
                $random_hue = rand(0, 360);
                $datasets[] = [
                    'label' => $institute,
                    'data' => array_values($data),
                    'backgroundColor' => 'hsl('.$random_hue.', 100%, 80%)',
                    'borderColor' => 'hsl('.$random_hue.', 100%, 50%)',
                    'borderWidth' => 1,
                ];
            }
            $data = [
                'labels' => array_keys($initial_institute_data),
                'datasets' => $datasets,
            ];
        }
        $this->emit('chartDataUpdated', $data);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(3)->schema([
                Select::make('course_id')
                    ->options(Cache::rememberForever('allCourses', fn () => Course::all()->pluck('id', 'id')))
                    ->label('Course')
                    ->afterStateUpdated(function (Closure $set) {
                        $set('program_id', null);
                        $set('institute_id', null);
                        $this->emit('updateChartData');
                    })->required()
                    ->reactive(),
                Select::make('program_id')
                    ->options(function (Closure $get) {
                        if ($get('course_id')) {
                            return DB::table('institute_course_program')->where('course_id', $get('course_id'))->pluck('program_id', 'program_id');
                        } else {
                            return Cache::rememberForever('allPrograms', fn () => Program::all()->pluck('id', 'id'));
                        }
                    })
                    ->label('Program')
                    ->afterStateUpdated(function (Closure $set) {
                        $set('institute_id', null);
                        $this->emit('updateChartData');
                    })
                    ->hidden(function (Closure $get) {
                        return ! $get('course_id');
                    })->required()
                    ->reactive(),
                MultiSelect::make('institute_id')
                    ->options(function (Closure $get) {
                        if ($get('program_id') && $get('course_id')) {
                            return DB::table('institute_course_program')->where('program_id', $get('program_id'))->where('course_id', $get('course_id'))->get()->pluck('institute_id');
                        } else {
                            return Cache::rememberForever('allInstitutes', fn () => Institute::all()->pluck('id', 'id'));
                        }
                    })->label('Institute')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(function (Closure $get) {
                        return ! $get('program_id') || ! $get('course_id');
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
        return view('livewire.program-trends');
    }
}
