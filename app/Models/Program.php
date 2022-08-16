<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Program extends Model
{
    use Searchable;

    protected $keyType = 'string';

    public $incrementing = false;
}
