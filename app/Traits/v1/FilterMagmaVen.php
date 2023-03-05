<?php

namespace App\Traits\v1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait FilterMagmaVen
{
    /**
     * Query for nip
     *
     * @param Builder $query
     * @param string|null $nip
     * @return void
     */
    public function scopeNip(Builder $query, ?string $nip = null): Builder
    {
        return !is_null($nip) ? $query->where('erupt_usr', $nip) : $query;
    }


    /**
     * Query overtime
     *
     * @param Builder $query
     * @param array $dates
     * @return Builder
     */
    public function scopeOvertime(Builder $query, array $betweenDates): Builder
    {
        return $query->whereBetween('erupt_tgl', $betweenDates)
            ->where(function ($query) {
                $query->whereBetween(DB::raw("TIME(erupt_jam)"), ['16:00:00', '23:59:59'])
                    ->orWhereBetween(DB::raw("TIME(erupt_jam)"), ['00:00:00', '05:00:00']);
            });
    }

    /**
     * Query overtime in holiday
     *
     * @param Builder $query
     * @param array $holidayDates
     * @return Builder
     */
    public function scopeHoliday(Builder $query, array $holidayDates): Builder
    {
        return $query->whereIn('erupt_tgl', $holidayDates);
    }
}