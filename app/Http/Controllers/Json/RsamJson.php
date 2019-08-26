<?php

namespace App\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tightenco\Collect\Support\Collection;
use StreamParser;

class RsamJson extends Controller
{
    protected $data = array();

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    protected function getWinstonUrl()
    {
        return sprintf('%s:%s/rsam%s%s%s%s%s',
                env('WINSTON_URL','http://172.16.2.253'),
                env('WINSTON_PORT','16030'),
                $this->getChannel(),
                $this->getStartDate(),
                $this->getEndDate(),
                $this->getRsamPeriod(),
                $this->getCsv());
    }

    protected function getChannel($channel = 'JRMZ_EHZ_VG_00')
    {
        return '?code='.$channel;
    }

    protected function getStartDate()
    {
        return '&t1='.now()->subDays(90)->format('YmdHi');
    }

    protected function getEndDate()
    {
        return '&t2='.now()->format('YmdHi');
    }

    protected function getRsamPeriod($period = 600)
    {
        return '&rsamp='.$period;
    }

    protected function getCsv()
    {
        return '&csv=1';
    }

    public function index(Request $request)
    {
        StreamParser::csv($this->getWinstonUrl())->each(function(Collection $data){
            $flatten = $data->flatten();
            $this->data[] = [
                \Carbon\Carbon::parse($flatten[0])->timestamp*1000,
                floatval($flatten[1])
            ];
        });

        return $this->data;
    }
}
