<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Program extends Model
{
    use Searchable;

    protected $keyType = 'string';

    public $incrementing = false;

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
