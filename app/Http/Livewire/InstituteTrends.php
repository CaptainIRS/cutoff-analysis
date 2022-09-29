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

    public $round_display;

    public $old_round_display;

    protected $listeners = ['updateChartData'];

    public function mount(): void
    {
        $this->form->fill([
            'institute_id' => null,
            'course_id' => [],
            'quota_id' => session()->exists('quota_id') ? session()->get('quota_id')[0] : null,
            'seat_type_id' => session('seat_type_id'),
            'gender_id' => session('gender_id'),
            'round_display' => session('round_display'),
        ]);
    }

    private function haveFieldsChanged(): bool
    {
        return $this->institute_id === $this->old_institute_id
            && $this->quota_id === $this->old_quota_id
            && $this->seat_type_id === $this->old_seat_type_id
            && $this->course_id === $this->old_course_id
            && $this->gender_id === $this->old_gender_id
            && $this->round_display === $this->old_round_display;
    }

    public function updateChartData(): void
    {
        if ($this->institute_id
            && $this->quota_id
            && $this->seat_type_id
            && $this->gender_id
            && $this->round_display
        ) {
            if ($this->haveFieldsChanged()) {
                return;
            }
            $data = [];
            $query = Rank::where('institute_id', $this->institute_id)
                ->where('quota_id', $this->quota_id)
                ->where('seat_type_id', $this->seat_type_id)
                ->where('gender_id', $this->gender_id);
            if ($this->course_id) {
                $query->whereIn('course_id', $this->course_id);
            }
            $institute_data = $query->get();
            $year_round = Cache::rememberForever(
                'year_round_distinct',
                fn () => Rank::select('year', 'round')
                            ->distinct()
                            ->orderBy('year')
                            ->orderBy('round')
                            ->get()
            );
            switch($this->round_display) {
                case 'all':
                    break;
                case 'last':
                    $year_round = Cache::rememberForever(
                        'year_round_last',
                        fn () => Rank::select('year', DB::raw('MAX(round) as round'))
                                    ->groupBy('year')
                                    ->orderBy('year')
                                    ->get()
                    );
                    break;
                default:
                    $year_round = Cache::rememberForever(
                        'year_round_'.$this->round_display,
                        fn () => Rank::select('year', 'round')
                                    ->where('round', $this->round_display)
                                    ->distinct()
                                    ->orderBy('year')
                                    ->get()
                    );
                    break;
            }
            $columns = $year_round->map(fn ($year_round) => $year_round->year.'_'.$year_round->round);
            $initial_program_data = $columns->mapWithKeys(fn ($column) => [$column => null])->toArray();
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
        }
        $this->old_course_id = $this->course_id;
        $this->old_institute_id = $this->institute_id;
        $this->old_quota_id = $this->quota_id;
        $this->old_seat_type_id = $this->seat_type_id;
        $this->old_gender_id = $this->gender_id;
        $this->old_round_display = $this->round_display;
        $this->emit('chartDataUpdated', $data);
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
                    ->afterStateUpdated(function () {
                        $this->course_id = [];
                        $this->emit('updateChartData');
                    })
                    ->required()
                    ->reactive(),
                MultiSelect::make('course_id')
                    ->options($this->institute_id ? Institute::find($this->institute_id)->courses->pluck('id', 'id') : [])
                    ->label('Course')
                    ->afterStateUpdated(fn () => $this->emit('updateChartData'))
                    ->hidden(! $this->institute_id)
                    ->searchable()
                    ->reactive(),
            ]),
            Grid::make(4)->schema([
                Select::make('quota_id')
                    ->options(Cache::rememberForever('allQuotas', fn () => Quota::all()->pluck('id', 'id')))
                    ->afterStateUpdated(function () {
                        session()->put('quota_id', [$this->quota_id]);
                        $this->emit('updateChartData');
                    })
                    ->searchable()
                    ->label('Quota')
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
                    ->afterStateUpdated(function () {
                        session()->put('round_display', $this->round_display);
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
