<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait FilterVona
{
    /**
     * Filter by nip
     *
     * @param Builder $query
     * @param string|null $nip
     * @return Builder
     */
    public function scopeNip(Builder $query, ?string $nip = null): Builder
    {
        return !is_null($nip) ? $query->where('nip_pelapor', $nip) : $query;
    }

    /**
     * VONA Query
     *
     * @param Builder $query
     * @param array $dates
     * @return Builder
     */
    public function scopeOvertime(Builder $query, array $dates): Builder
    {
        $query->where('type', 'REAL')
            ->where('is_sent', 1)
            ->whereBetween('created_at', $dates)
            ->where(function ($query) {
                $query->whereBetween(DB::raw("TIME(created_at)"), ['16:00:00', '23:59:59'])
                    ->orWhereBetween(DB::raw("TIME(created_at)"), ['00:00:00', '05:00:00']);
            });

        return $query;
    }

    /**
     * Holiday report
     *
     * @param Builder $query
     * @param array $holidayDates
     * @return Builder
     */
    public function scopeHoliday(Builder $query, array $holidayDates): Builder
    {
        return $query->whereIn(DB::raw("DATE(created_at)"), $holidayDates);
    }
}