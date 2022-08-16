<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function quota()
    {
        return $this->belongsTo(Quota::class);
    }

    public function seat_type()
    {
        return $this->belongsTo(SeatType::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}
