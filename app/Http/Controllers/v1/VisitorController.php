<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function filter($year = '%')
    {
        try {
            $visitor = Cache::remember('chambers/v1/visitor:filter:'.$year, 120, function () use ($year) {
                return $visitor = DB::connection('magma')
                ->table('magma_visitor')
                ->selectRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(log, ' ', 1), ' ', -1) AS tanggal, COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(log, ' ', 1), ' ', -1)) AS jumlah")
                ->where('log','like','%'.$year.'%')
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();
            });

            if ($visitor->isEmpty())
                abort(404);
    
            $visitor = $this->setVisitor($visitor)
                ->setCategories()
                ->setSeries()
                ->getCharts();

            return view('v1.visitor.filter',compact('visitor','year'));
        }

        catch (Exception $e)
        {
            abort(404);
        }

    }

    public function index()
    {
        $visitor = Cache::remember('chambers/v1/visitor:index', 120, function () {
            return $visitor = DB::connection('magma')
            ->table('magma_visitor')
            ->selectRaw("SUBSTRING_INDEX(SUBSTRING_INDEX(log, ' ', 1), ' ', -1) AS tanggal, COUNT(SUBSTRING_INDEX(SUBSTRING_INDEX(log, ' ', 1), ' ', -1)) AS jumlah")
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();
        });

        $visitor = $this->setVisitor($visitor)
            ->setCategories()
            ->setSeries()
            ->getCharts();

        return view('v1.visitor.index',compact('visitor'));

    }

    protected function setVisitor($visitor)
    {
        $this->visitor = $visitor;
        return $this;
    }

    protected function setCategories()
    {
        $this->categories = $this->visitor->pluck('tanggal');
        return $this;
    }

    protected function getCategories()
    {
        return $this->categories;
    }

    protected function setSeries()
    {
        $this->series[] = [
            'name' => 'Pengunjung',
            'data' => $this->visitor->pluck('jumlah'),
            'color' => '#1b84e7'
        ];
        
        return $this;
    }

    protected function getSeries()
    {
        return $this->series;
    }

    protected function getCharts()
    {
        return collect([
            'categories' => $this->getCategories(),
            'series' => $this->getSeries()
        ]);
    }
}
