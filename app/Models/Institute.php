<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Institute extends Model
{
    use Searchable;

    protected $keyType = 'string';

    public $incrementing = false;

    public $asYouType = true;

    protected $fillable = ['institute'];

    public const INSTITUTE_TYPE_OPTIONS = [
        'nit' => 'NITs',
        'iiit' => 'IIITs',
        'gfti' => 'GFTIs',
    ];

    public function searchableAs()
    {
        return 'institute';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();
        $array['institute'] = $this->id;

        return $array;
    }

    public function ranks()
    {
        return $this->hasMany(Rank::class);
    }

    public function programs()
    {
        return $this->hasManyThrough(Program::class, Course::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function quotas()
    {
        return $this->belongsToMany(Quota::class)->as('state');
    }
}
