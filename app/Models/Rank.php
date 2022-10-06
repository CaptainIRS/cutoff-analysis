<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    public const ROUND_DISPLAY_LAST = 'last';

    public const ROUND_DISPLAY_ALL = 'all';

    public const ROUND_DISPLAY_OPTIONS = [
        self::ROUND_DISPLAY_LAST => 'Last Round Only',
        self::ROUND_DISPLAY_ALL => 'All Rounds',
        '1' => 'Round 1',
        '2' => 'Round 2',
        '3' => 'Round 3',
        '4' => 'Round 4',
        '5' => 'Round 5',
        '6' => 'Round 6',
        '7' => 'Round 7',
    ];

    public const RANK_TYPE_MAIN = 'jee-main';

    public const RANK_TYPE_ADVANCED = 'jee-advanced';

    public const RANK_TYPE_OPTIONS = [
        self::RANK_TYPE_MAIN => 'JEE (Main)',
        self::RANK_TYPE_ADVANCED => 'JEE (Advanced)',
    ];

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
