<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\Institute;
use App\Models\Rank;
use Cache;
use DB;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class InstituteDetails extends Component implements HasForms
{
    use InteractsWithForms;

    public $institute;

    public $courses;

    public $branches;

    public $duration;

    public $branch_options;

    public $show_courses;

    public function mount(Institute $institute): void
    {
        $this->institute = $institute;
        $this->max_year = Cache::rememberForever('max_year', fn () => max(Rank::select('year')->distinct()->orderBy('year')->pluck('year')->toArray()));
        $this->show_courses = 'latest';
        $this->filterPrograms();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1, 'sm' => 3])->schema([
                MultiSelect::make('branches')
                    ->label('Branches')
                    ->options($this->branch_options)
                    ->afterStateUpdated(fn () => $this->filterPrograms())
                    ->reactive(),
                MultiSelect::make('duration')
                    ->label('Duration')
                    ->options([
                        '4' => '4 Years',
                        '5' => '5 Years',
                    ])
                    ->afterStateUpdated(fn () => $this->filterPrograms())
                    ->reactive(),
                Radio::make('show_courses')
                    ->columns(['default' => 2])
                    ->label('Show Courses')
                    ->options([
                        'latest' => 'Show Latest Courses',
                        'all' => 'Show All Courses',
                    ])
                    ->afterStateUpdated(fn () => $this->filterPrograms())
                    ->reactive(),
            ]),
        ];
    }

    protected function filterPrograms(): void
    {
        $this->courses = DB::table('institute_course_program')
            ->where('institute_id', $this->institute->id)
            ->when($this->branches, function ($query, $branches) {
                $programs = Branch::with('programs')
                    ->when($branches, fn ($query, $branches) => $query->whereIn('id', $branches))
                    ->get()
                    ->pluck('programs')
                    ->flatten()
                    ->pluck('id')
                    ->unique();
                $query->whereIn('program_id', $programs);
            })
            ->when($this->duration, fn ($query, $duration) => $query->whereIn('duration', $duration))
            ->when($this->show_courses === 'latest', fn ($query) => $query->where('years', 'like', '%'.$this->max_year.'%'))
            ->get();
        $this->branch_options = DB::table('branch_program')->whereIn('program_id', collect($this->courses)->pluck('program_id')->unique())->pluck('branch_id', 'branch_id')->toArray();
    }

    public function render()
    {
        return view('livewire.institute-details');
    }
}
