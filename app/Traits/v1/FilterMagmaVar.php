<?php

namespace App\Traits\v1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait FilterMagmaVar
{
    /**
     * Change var_issued from string to datetime
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeVarIssuedDateTime(Builder $query): Builder
    {
        return $query->addSelect(DB::raw(
            "STR_TO_DATE(`var_issued`, '%d/%m/%Y %T') AS var_issued_datetime"));
    }

    /**
     * Query for nip
     *
     * @param Builder $query
     * @param string|null $nip
     * @return void
     */
    public function scopeNip(Builder $query, ?string $nip = null): Builder
    {
        return !is_null($nip) ? $query->where('var_nip_pelapor', $nip) : $query;
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
        return $query->whereBetween('var_log', $betweenDates)
            ->where(function ($query) {
                $query->whereBetween(DB::raw("TIME(var_log)"), ['16:00:00', '23:59:59'])
                    ->orWhereBetween(DB::raw("TIME(var_log)"), ['00:00:00', '05:00:00']);
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
        return $query->whereIn(DB::raw("DATE(var_log)"), $holidayDates);
    }
}