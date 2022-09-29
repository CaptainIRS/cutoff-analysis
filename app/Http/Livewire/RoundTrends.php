<?php

namespace App\Http\Livewire;

use App\Models\Gender;
use App\Models\Institute;
use App\Models\Quota;
use App\Models\Rank;
use App\Models\SeatType;
use Cache;
use DB;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class RoundTrends extends Component implements HasForms
{
    use InteractsWithForms;

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
            'institute_id' => null,
            'program_id' => null,
            'course_id' => null,
            'quota_id' => session()->exists('quota_id') ? session()->get('quota_id')[0] : null,
            'seat_type_id' => session('seat_type_id'),
            'gender_id' => session('gender_id'),
        ]);
    }

    private function haveFieldsChanged(): bool
    {
        return $this->institute_id === $this->old_institute_id
            && $this->quota_id === $this->old_quota_id
            && $this->seat_type_id === $this->old_seat_type_id
            && $this->course_id === $this->old_course_id
            && $this->program_id === $this->old_program_id
            && $this->gender_id === $this->old_gender_id;
    }

    public function updateChartData(): void
    {
        $data = [];
        if ($this->institute_id
            && $this->course_id
            && $this->program_id
            && $this->quota_id
            && $this->seat_type_id
            && $this->gender_id
        ) {
            if ($this->haveFieldsChanged()) {
                return;
            }
            $query = Rank::where('institute_id', $this->institute_id)
                ->where('course_id', $this->course_id)
                ->where('program_id', $this->program_id)
                ->where('quota_id', $this->quota_id)
                ->where('seat_type_id', $this->seat_type_id)
                ->where('gender_id', $this->gender_id);
            $institute_data = $query->get();
            $initial_round_data = ['1' => null, '2' => null, '3' => null, '4' => null, '5' => null, '6' => null, '7' => null];
            $round_data = [];
            foreach ($institute_data as $data) {
                if (! isset($round_data[$data->year])) {
                    $round_data[$data->year] = $initial_round_data;
                }
                $round_data[$data->year][$data->round] = $data->closing_rank;
            }

            $datasets = [];
            foreach ($round_data as $year => $year_data) {
                $random_hue = crc32($year) % 360;
                $datasets[] = [
                    'label' => $year,
                    'data' => array_values($year_data),
                    'backgroundColor' => 'hsl('.$random_hue.', 100%, 80%)',
                    'borderColor' => 'hsl('.$random_hue.', 100%, 50%)',
                ];
            }
            $labels = array_keys($initial_round_data);
            foreach ($labels as $key => $label) {
                $labels[$key] = str_replace('_', ' - R', $label);
            }
            $data = [
                'labels' => $labels,
                'datasets' => $datasets,
            ];
        }
        $this->old_course_id = $this->course_id;
        $this->old_program_id = $this->program_id;
        $this->old_institute_id = $this->institute_id;
        $this->old_quota_id = $this->quota_id;
        $this->old_seat_type_id = $this->seat_type_id;
        $this->old_gender_id = $this->gender_id;
        $this->emit('chartDataUpdated', $data);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(3)->schema([
                Select::make('institute_id')
                    ->options(Cache::rememberForever('allInstitutes', fn () => Institute::all()->pluck('id', 'id')))
                    ->optionsLimit(150)
                    ->searchable()
                    ->label('Institute')
                    ->afterStateUpdated(function () {
                        $this->course_id = null;
                        $this->program_id = null;
                        $this->emit('updateChartData');
                    })
                    ->required()
                    ->reactive(),
                Select::make('course_id')
                    ->options(function () {
                        return DB::table('institute_course_program')
                            ->where('institute_id', $this->institute_id)
                            ->pluck('course_id', 'course_id');
                    })
                    ->optionsLimit(150)
                    ->label('Course')
                    ->afterStateUpdated(function () {
                        $this->program_id = null;
                        $this->emit('updateChartData');
                    })
                    ->hidden(! $this->institute_id)
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('program_id')
                    ->options(
                        DB::table('institute_course_program')
                            ->where('institute_id', $this->institute_id)
                            ->where('course_id', $this->course_id)
                            ->pluck('program_id', 'program_id')
                    )
                    ->optionsLimit(150)
                    ->label('Program')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(! $this->course_id)
                    ->searchable()
                    ->required()
                    ->reactive(),
            ]),
            Grid::make(3)->schema([
                Select::make('quota_id')
                    ->options(Cache::rememberForever('allQuotas', fn () => Quota::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function () {
                        session()->put('quota_id', [$this->quota_id]);
                        $this->emit('updateChartData');
                    })
                    ->label('Quota')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('seat_type_id')
                    ->options(Cache::rememberForever('allSeatTypes', fn () => SeatType::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function () {
                        session()->put('seat_type_id', $this->seat_type_id);
                        $this->emit('updateChartData');
                    })
                    ->label('Seat Type')
                    ->searchable()
                    ->required()
                    ->reactive(),
                Select::make('gender_id')
                    ->options(Cache::rememberForever('allGenders', fn () => Gender::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function () {
                        session()->put('gender_id', $this->gender_id);
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
