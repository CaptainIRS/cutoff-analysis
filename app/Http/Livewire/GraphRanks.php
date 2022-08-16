<?php

namespace App\Http\Livewire;

use App\Models\Rank;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\MultiSelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class GraphRanks extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        return Rank::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('institute')
                ->label('Institute')
                ->searchable()
                ->sortable(),
            TextColumn::make('course')
                ->label('Course')
                ->searchable()
                ->sortable(),
            TextColumn::make('program')
                ->label('Program')
                ->searchable()
                ->sortable(),
            TextColumn::make('quota')
                ->label('Quota')
                ->searchable()
                ->sortable(),
            TextColumn::make('seat_type')
                ->label('Seat Type')
                ->searchable()
                ->sortable(),
            TextColumn::make('gender')
                ->label('Gender'),
            TextColumn::make('year')
                ->label('Year')
                ->searchable()
                ->sortable(),
            TextColumn::make('round')
                ->label('Round')
                ->searchable()
                ->sortable(),
            TextColumn::make('opening_rank')
                ->label('Opening Rank')
                ->searchable()
                ->sortable(),
            TextColumn::make('closing_rank')
                ->label('Closing Rank')
                ->searchable()
                ->sortable(),
        ];
    }

    // protected function applySearchToTableQuery(Builder $query): Builder
    // {
    //     if (filled($searchQuery = $this->getTableSearchQuery())) {
    //         $query->whereIn('rank', Rank::search($searchQuery)->keys());
    //     }

    //     return $query;
    // }

    // public function isTableSearchable(): bool
    // {
    //     return true;
    // }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    // protected function getTableFilters(): array
    // {
    //     return [
    //         MultiSelectFilter::make('rank')
    //             ->label('Rank')
    //             ->options(Rank::all()),
    //     ];
    // }

    public function render()
    {
        return view('livewire.graph-ranks');
    }
}
