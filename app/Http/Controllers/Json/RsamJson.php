<?php

namespace App\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tightenco\Collect\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use StreamParser;

class RsamJson extends Controller
{
    protected $data = [];

    protected $useCache = true;

    protected $channel;

    protected $startDate;

    protected $endDate;

    protected $rsamPeriod;

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

    protected function setCache($cache)
    {
        $this->useCache = $cache;
        return $this;
    }

    protected function getCache()
    {
        return $this->useCache;
    }

    protected function setChannel($channel)
    {
        $this->channel = '?code='.$channel;
        return $this;
    }

    protected function getChannel()
    {
        return $this->channel;
    }

    protected function setStartDate($start)
    {
        $this->startDate = '&t1='.Carbon::createFromFormat('Y-m-d H:i:s',$start.' 00:00:00')->format('YmdHi');
        return $this;
    }

    protected function getStartDate()
    {
        return $this->startDate;
    }

    protected function setEndDate($end)
    {
        $this->endDate = '&t2='.Carbon::createFromFormat('Y-m-d H:i:s',$end.' 23:59:00')->format('YmdHi');
        return $this;
    }

    protected function getEndDate()
    {
        return $this->endDate;
    }

    protected function setRsamPeriod($period)
    {
        $this->rsamPeriod = '&rsamP='.$period;
        return $this;
    }

    protected function getRsamPeriod()
    {
        return $this->rsamPeriod;
    }

    protected function getCsv()
    {
        return '&csv=1';
    }

    protected function cacheData($url, $request)
    {
        $cache = Cache::remember('json:rsam:'.$request->channel.'-'.$request->start.':'.$request->end.':'.$request->periode, 120, function() use ($url) {
            StreamParser::csv($url)->each(function(Collection $data){
                $flatten = $data->flatten();
                $this->data[] = [
                    \Carbon\Carbon::parse($flatten[0])->timestamp*1000,
                    floatval($flatten[1])
                ];
            });
    
            return $this->data;
        });

        return $cache;
    }

    protected function freshData($url)
    {
        StreamParser::csv($url)->each(function(Collection $data){
            $flatten = $data->flatten();
            $this->data[] = [
                \Carbon\Carbon::parse($flatten[0])->timestamp*1000,
                floatval($flatten[1])
            ];
        });

        return $this->data;
    }

    protected function getData($url, $request)
    {
        $data = $this->getCache() ? $this->cacheData($url, $request) : $this->freshData($url);

        return $data;
    }

    public function index(Request $request)
    {
        $winstonUrl = $this->setChannel($request->channel)
                            ->setStartDate($request->start)
                            ->setEndDate($request->end)
                            ->setRsamPeriod($request->periode)
                            ->getWinstonUrl();

        return $this->getData($winstonUrl, $request);
    }
}
