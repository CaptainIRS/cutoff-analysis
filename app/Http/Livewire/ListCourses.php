<?php

namespace App\Http\Livewire;

use App\Models\Course;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListCourses extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Course::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('course')
                ->label('Course')
                ->searchable()
                ->sortable(),
        ];
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('course', Course::search($searchQuery)->keys());
        }

        return $query;
    }

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableFilters(): array
    {
        return [
            MultiSelectFilter::make('course')
                ->label('Course')
                ->options(Course::all()),
        ];
    }

    public function render()
    {
        return view('livewire.list-courses');
    }
}
