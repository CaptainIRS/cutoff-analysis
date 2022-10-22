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

class BranchDetails extends Component implements HasForms
{
    use InteractsWithForms;

    public $branch;

    public $types;

    public $courses;

    public $branches;

    public $duration;

    public $branch_options;

    public $show_courses;

    public function mount(Branch $branch): void
    {
        $this->branch = $branch;
        $this->max_year = Cache::rememberForever('max_year', fn () => max(Rank::select('year')->distinct()->orderBy('year')->pluck('year')->toArray()));
        $this->show_courses = 'latest';
        $this->filterPrograms();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1, 'sm' => 3])->schema([
                MultiSelect::make('types')
                    ->label('Institute Type')
                    ->options([
                        'iit' => 'IITs',
                        'nit' => 'NITs',
                        'iiit' => 'IIITs',
                        'gfti' => 'GFTIs',
                    ])
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
            ->whereIn('program_id', $this->branch->programs()->pluck('id'))
            ->when($this->types, function ($query, $types) {
                $query->whereIn('institute_id', Institute::whereIn('type', $types)->pluck('id'));
            })
            ->when($this->duration, fn ($query, $duration) => $query->whereIn('duration', $duration))
            ->when($this->show_courses === 'latest', fn ($query) => $query->where('years', 'like', '%'.$this->max_year.'%'))
            ->get();
    }

    public function render()
    {
        return view('livewire.branch-details');
    }
}
