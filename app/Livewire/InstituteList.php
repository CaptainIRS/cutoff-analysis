<?php

namespace App\Livewire;

use App\Models\Institute;
use App\Models\State;
use Cache;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class InstituteList extends Component implements HasForms
{
    use InteractsWithForms;

    public $institutes;

    public array $states = [];

    public array $types = [];

    public function mount(): void
    {
        $this->institutes = Institute::all();
        $this->form->fill([
            'states' => [],
            'types' => [],
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make(['default' => 1, 'sm' => 2])->schema([
                MultiSelect::make('types')
                    ->label('Institute Type')
                    ->options([
                        'iit' => 'IITs',
                        'nit' => 'NITs',
                        'iiit' => 'IIITs',
                        'gfti' => 'GFTIs',
                    ])
                    ->afterStateUpdated(fn () => $this->filterInstitutes())
                    ->reactive(),
                MultiSelect::make('states')
                    ->label('States')
                    ->options(Cache::rememberForever('all_states', fn () => State::orderBy('id')->pluck('id', 'id')->toArray()))
                    ->afterStateUpdated(fn () => $this->filterInstitutes())
                    ->reactive(),
            ]),
        ];
    }

    protected function filterInstitutes(): void
    {
        $this->institutes = Institute::query()
            ->when($this->states, fn ($query, $states) => $query->whereIn('state', $states))
            ->when($this->types, fn ($query, $types) => $query->whereIn('type', $types))
            ->get();
    }

    public function render()
    {
        return view('livewire.institute-list');
    }
}
