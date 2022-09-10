<?php

namespace App\Http\Livewire;

use App\Models\Gender;
use App\Models\Institute;
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

class InstituteTrends extends Component implements HasForms
{
    use InteractsWithForms;

    public $course_id;

    public $old_course_id;

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
            'quota_id' => session()->exists('quota_id') ? session()->get('quota_id')[0] : null,
            'seat_type_id' => session('seat_type_id'),
            'gender_id' => session('gender_id'),
        ]);
    }

    public function updateChartData(): void
    {
        if ($this->institute_id !== null && $this->quota_id !== null && $this->seat_type_id !== null && $this->gender_id !== null) {
            if ($this->institute_id === $this->old_institute_id
                && $this->quota_id === $this->old_quota_id
                && $this->seat_type_id === $this->old_seat_type_id
                && $this->course_id === $this->old_course_id
                && $this->gender_id === $this->old_gender_id) {
                return;
            }
            $data = [];
            $query = Rank::where('institute_id', $this->institute_id)
                ->where('quota_id', $this->quota_id)
                ->where('seat_type_id', $this->seat_type_id)
                ->where('gender_id', $this->gender_id);
            if ($this->course_id !== null && $this->course_id !== []) {
                $query->whereIn('course_id', $this->course_id);
            }
            $institute_data = $query->get();
            $initial_program_data = ['2012_1' => null, '2012_1' => null, '2013_1' => null, '2013_1' => null, '2013_2' => null, '2013_2' => null, '2013_3' => null, '2013_3' => null, '2014_1' => null, '2014_1' => null, '2014_2' => null, '2014_2' => null, '2014_3' => null, '2014_3' => null, '2014_4' => null, '2014_4' => null, '2015_1' => null, '2015_1' => null, '2015_2' => null, '2015_2' => null, '2015_3' => null, '2015_3' => null, '2015_4' => null, '2015_4' => null, '2016_1' => null, '2016_1' => null, '2016_2' => null, '2016_2' => null, '2016_3' => null, '2016_3' => null, '2016_4' => null, '2016_4' => null, '2016_5' => null, '2016_5' => null, '2017_1' => null, '2017_1' => null, '2017_2' => null, '2017_2' => null, '2017_3' => null, '2017_3' => null, '2017_4' => null, '2017_4' => null, '2017_5' => null, '2017_5' => null, '2017_6' => null, '2017_6' => null, '2018_1' => null, '2018_1' => null, '2018_2' => null, '2018_2' => null, '2018_3' => null, '2018_3' => null, '2018_4' => null, '2018_4' => null, '2018_5' => null, '2018_5' => null, '2018_6' => null, '2018_6' => null, '2018_7' => null, '2018_7' => null, '2019_1' => null, '2019_1' => null, '2019_2' => null, '2019_2' => null, '2019_3' => null, '2019_3' => null, '2019_4' => null, '2019_4' => null, '2019_5' => null, '2019_5' => null, '2019_6' => null, '2019_6' => null, '2020_1' => null, '2020_1' => null, '2020_2' => null, '2020_2' => null, '2020_3' => null, '2020_3' => null, '2020_4' => null, '2020_4' => null, '2020_5' => null, '2020_5' => null, '2020_6' => null, '2020_6' => null, '2021_1' => null, '2021_1' => null, '2021_2' => null, '2021_2' => null, '2021_3' => null, '2021_3' => null, '2021_4' => null, '2021_4' => null, '2021_5' => null, '2021_5' => null, '2021_6' => null, '2021_6' => null];
            $program_data = [];
            foreach ($institute_data as $data) {
                if (! isset($program_data[$data->course_id])) {
                    $program_data[$data->course_id] = [];
                }
                if (! isset($program_data[$data->course_id][$data->program_id])) {
                    $program_data[$data->course_id][$data->program_id] = $initial_program_data;
                }
                $program_data[$data->course_id][$data->program_id][$data->year.'_'.$data->round] = $data->closing_rank;
            }

            $datasets = [];
            foreach ($program_data as $institute => $course_data) {
                foreach ($course_data as $course => $data) {
                    $random_hue = crc32($institute.$course) % 360;
                    $datasets[] = [
                        'label' => $institute.', '.$course,
                        'data' => array_values($data),
                        'backgroundColor' => 'hsl('.$random_hue.', 100%, 80%)',
                        'borderColor' => 'hsl('.$random_hue.', 100%, 50%)',
                    ];
                }
            }
            $labels = array_keys($initial_program_data);
            foreach ($labels as $key => $label) {
                $labels[$key] = str_replace('_', ' - R', $label);
            }
            $data = [
                'labels' => $labels,
                'datasets' => $datasets,
            ];
            $this->old_course_id = $this->course_id;
            $this->old_institute_id = $this->institute_id;
            $this->old_quota_id = $this->quota_id;
            $this->old_seat_type_id = $this->seat_type_id;
            $this->old_gender_id = $this->gender_id;
            $this->emit('chartDataUpdated', $data);
        } else {
            $this->old_course_id = $this->course_id;
            $this->old_institute_id = $this->institute_id;
            $this->old_quota_id = $this->quota_id;
            $this->old_seat_type_id = $this->seat_type_id;
            $this->old_gender_id = $this->gender_id;
            $this->emit('chartDataUpdated', []);
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                Select::make('institute_id')
                    ->options(Cache::rememberForever('allInstitutes', fn () => Institute::all()->pluck('id', 'id')))
                    ->optionsLimit(150)
                    ->searchable()
                    ->label('Institute')
                    ->afterStateUpdated(function (Closure $set) {
                        $set('course_id', null);
                        $this->emit('updateChartData');
                    })
                    ->required()
                    ->reactive(),
                MultiSelect::make('course_id')
                    ->options(fn (Closure $get) => DB::table('institute_course_program')->where('institute_id', $get('institute_id'))->pluck('course_id', 'course_id'))
                    ->label('Course')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(function (Closure $get) {
                        return ! $get('institute_id');
                    })
                    ->searchable()
                    ->reactive(),
            ]),
            Grid::make(3)->schema([
                Select::make('quota_id')
                    ->options(Cache::rememberForever('allQuotas', fn () => Quota::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function (Closure $get) {
                        if ($get('quota_id') !== null) {
                            session()->put('quota_id', [$get('quota_id')]);
                        }
                        $this->emit('updateChartData');
                    })
                    ->searchable()
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
