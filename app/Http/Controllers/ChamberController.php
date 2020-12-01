<?php

namespace App\Http\Controllers;

use App\MagmaVar;
use App\EqLts;
use App\StatistikHome;
use Illuminate\Http\Request;

class ChamberController extends Controller
{
    protected $categories;
    protected $visitor;
    protected $series;

    public function index()
    {
        $statistics = StatistikHome::limit(60)->orderBy('date')->get();
        $statistics_chart = $this->setCategories($statistics)->setSeries()->getCharts();
        $statistics_sum = StatistikHome::sum('hit');
        $vars_count = MagmaVar::count();
        $latest = Magmavar::latest()->first();
        $lts_sum = EqLts::sum('jumlah');
        $latest_lts = EqLts::latest()->first();
        return view('chambers.index', compact(
            'vars_count','latest','lts_sum',
            'latest_lts', 'statistics', 
            'statistics_chart', 'statistics_sum'));
    }

    protected function setCategories($categories)
    {
        $this->visitor = $categories;
        $this->categories = $categories->pluck('date');
        return $this;
    }

    protected function setSeries()
    {
        $this->series[] = [
            'name' => 'Statistik MAGMA v2',
            'data' => $this->visitor->pluck('hit'),
            'color' => '#1b84e7'
        ];

        return $this;
    }

    protected function getCharts()
    {
        return collect([
            'categories' => $this->categories,
            'series' => $this->series
        ]);
    }
}
