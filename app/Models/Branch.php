<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Branch extends Model
{
    use Searchable;

    protected $keyType = 'string';

    public $incrementing = false;

    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }
}
