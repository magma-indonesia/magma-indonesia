<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DataDasarSejarahLetusan extends Model
{
    protected $with = [
        'user:nip,name',
    ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    protected $appends = [
        'start_text',
        'end_text',
        'date_text',
    ];

    public function gunungapi()
    {
        return $this->belongsTo(Gadd::class, 'code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }

    public function getStartTextAttribute()
    {
        $year = $this->attributes['start_year'];

        if (is_null($this->attributes['start_month'])) {
            return $year;
        }

        $month = strlen($this->attributes['start_month']) === 1 ?
            "0{$this->attributes['start_month']}" : $this->attributes['start_month'];

        if (is_null($this->attributes['start_date'])) {
            return Carbon::createFromFormat('Y-m', "$year-$month")->format('Y, F');
        }

        $date = strlen($this->attributes['start_date']) === 1 ?
            "0{$this->attributes['start_date']}" : $this->attributes['start_date'];

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$date")->format('j F Y');
    }

    public function getEndTextAttribute()
    {
        $year = $this->attributes['end_year'];

        if (is_null($this->attributes['end_month'])) {
            return $year;
        }

        $month = strlen($this->attributes['end_month']) === 1 ?
            "0{$this->attributes['end_month']}" : $this->attributes['end_month'];

        if (is_null($this->attributes['end_date'])) {
            return Carbon::createFromFormat('Y-m', "$year-$month")->format('Y, F');
        }

        $date = strlen($this->attributes['end_date']) === 1 ?
            "0{$this->attributes['end_date']}" : $this->attributes['end_date'];

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$date")->format('j F Y');
    }

    public function getDateTextAttribute()
    {
        $start_year = $this->attributes['start_year'];
        $start_month = $this->attributes['start_month'];
        $start_date = $this->attributes['start_date'];
        $end_year = $this->attributes['end_year'];
        $end_month = $this->attributes['end_month'];
        $end_date = $this->attributes['end_date'];

        if ($start_date AND $end_date) {
            $start = Carbon::createFromFormat('Y-m-d', "$start_year-$start_month-$start_date");
            $end = Carbon::createFromFormat('Y-m-d', "$end_year-$end_month-$end_date");

            return $start->eq($end) ? $start->format('j F Y') : $start->format('j F Y')." - ".$end->format('j F Y');
        }

        if ($start_month AND $end_month) {

            $start_format = is_null($start_date) ? 'F Y' : 'j F Y';
            $end_format = is_null($end_date) ? 'F Y' : 'j F Y';

            $start_date = $start_date ?? '01';
            $end_date = $end_date ?? '01';

            $start = Carbon::createFromFormat('Y-m-d', "$start_year-$start_month-$start_date");
            $end = Carbon::createFromFormat('Y-m-d', "$end_year-$end_month-$end_date");

            return $start->eq($end) ? $start->format($start_format) : $start->format($start_format) . " - " . $end->format($end_format);
        }

        return $this->getStartTextAttribute();
    }
}
