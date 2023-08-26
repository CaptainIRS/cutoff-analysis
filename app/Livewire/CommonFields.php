<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Course;
use App\Models\Gender;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Rank;
use App\Models\SeatType;
use App\Models\State;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait CommonFields
{
    private $all_courses;

    private $all_branches;

    private $all_institutes;

    private $all_programs;

    private $all_states;

    private $all_seat_types;

    private $all_genders;

    public array $institute_type = [];

    public ?string $seat_type = null;

    public ?string $gender = null;

    public ?string $round_display = null;

    public ?string $rank_type = null;

    public ?string $home_state = null;

    public string $title = '';

    public bool $is_canonical = false;

    public bool $hide_controls = false;

    public string $alternative_url = '';

    public string $canonical_url = '';

    private function initialiseCache()
    {
        $this->all_courses = Cache::rememberForever('all_courses', fn () => Course::orderBy('id')->pluck('alias', 'id')->toArray());
        $this->all_branches = Cache::rememberForever('all_branches', fn () => Branch::orderBy('id')->pluck('name', 'id')->toArray());
        $this->all_institutes = Cache::rememberForever('all_institutes', fn () => Institute::orderBy('id')->pluck('alias', 'id')->toArray());
        $this->all_programs = Cache::rememberForever('all_programs', fn () => Program::orderBy('id')->pluck('name', 'id')->toArray());
        $this->all_states = Cache::rememberForever('all_states', fn () => State::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_seat_types = Cache::rememberForever('all_seat_types', fn () => SeatType::orderBy('id')->pluck('id', 'id')->toArray());
        $this->all_genders = Cache::rememberForever('all_genders', fn () => Gender::orderBy('id')->pluck('id', 'id')->toArray());
    }

    private function ensureSubsetOf(?array $values, array $array): array
    {
        if ($values && ! array_diff($values, array_keys($array))) {
            return $values ?? [];
        } elseif ($values && ! array_diff($values, array_values($array))) {
            return array_keys(array_intersect($array, $values));
        } elseif ($values) {
            $this->is_canonical = true;
        }

        return [];
    }

    private function ensureBelongsTo(?string $value, array $array): ?string
    {
        if ($value && in_array($value, array_keys($array), true)) {
            return $value;
        } elseif ($value && in_array($value, array_values($array), true)) {
            return array_search($value, $array, true);
        } elseif ($value) {
            $this->is_canonical = true;
        }

        return null;
    }

    private function getInstituteType(): array
    {
        return $this->rank_type === Rank::RANK_TYPE_ADVANCED
            ? ['iit']
            : ($this->institute_type
                ? $this->institute_type
                : ['iiit', 'nit', 'gfti']
            );
    }

    private function getInstituteQuotas(): array
    {
        $institute_type = $this->getInstituteType();

        return Cache::rememberForever(
            'institute_quota_'.implode('_', $institute_type).($this->rank_type === Rank::RANK_TYPE_MAIN ? '_'.$this->home_state : ''),
            function () use ($institute_type) {
                return DB::table('institute_quota')
                    ->where(function ($query) use ($institute_type) {
                        $query->whereIn('institute_id', Institute::whereIn('type', $institute_type)->pluck('id'));
                        if ($this->rank_type === Rank::RANK_TYPE_MAIN) {
                            if ($this->home_state) {
                                $query->where(function ($sub_query) {
                                    $sub_query->where('quota_id', 'OS')->whereNotIn('state_id', [$this->home_state])
                                        ->orWhere('quota_id', 'HS')->whereIn('state_id', [$this->home_state])
                                        ->orWhereNotIn('quota_id', ['OS', 'HS'])->whereIn('state_id', [$this->home_state])
                                        ->orWhere('quota_id', 'AI');
                                });
                            } else {
                                $query->whereIn('quota_id', ['OS', 'AI']);
                            }
                        }
                    })
                    ->distinct()
                    ->get()
                    ->toArray();
            }
        );
    }

    private function filterYearRound(&$query): mixed
    {
        switch ($this->round_display) {
            case Rank::ROUND_DISPLAY_ALL:
                $year_round = Cache::rememberForever(
                    'year_round_distinct',
                    fn () => Rank::select('year', 'round')
                        ->distinct()
                        ->orderBy('year')
                        ->orderBy('round')
                        ->get()
                );
                break;
            case Rank::ROUND_DISPLAY_LAST:
                $year_round = Cache::rememberForever(
                    'year_round_last',
                    fn () => Rank::select('year', DB::raw('MAX(round) as round'))
                        ->groupBy('year')
                        ->orderBy('year')
                        ->get()
                );
                if ($this->rank_type === Rank::RANK_TYPE_ADVANCED) {
                    // This fix is to handle the case of 2014 where there were
                    // 3 rounds for IITs and 4 rounds for NIT+
                    $year_round = $year_round->map(function ($item) {
                        if ($item->year === 2014) {
                            $item->round = 3;
                        }

                        return $item;
                    });
                } else {
                    // Remove 2014 from the list for NIT+ if category is not OPEN
                    // as ranks for other categories are shown as general ranks in 2014
                    $year_round = $year_round->filter(fn ($item) => $item->year !== 2014 || $this->seat_type === 'OPEN');
                }
                $query = $query->whereIn(DB::raw('year || round'), $year_round->map(function ($item) {
                    return $item->year.$item->round;
                }));
                break;
            default:
                $year_round = Cache::rememberForever(
                    'year_round_'.$this->round_display,
                    fn () => Rank::select('year', 'round')
                        ->where('round', $this->round_display)
                        ->distinct()
                        ->orderBy('year')
                        ->get()
                );
                $query = $query->where('round', $this->round_display);
                break;
        }

        return $year_round;
    }
}
