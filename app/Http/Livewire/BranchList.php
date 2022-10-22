<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use Livewire\Component;

class BranchList extends Component
{
    public $branches;

    public function mount(): void
    {
        $this->branches = Branch::all();
    }

    public function render()
    {
        return view('livewire.branch-list');
    }
}
