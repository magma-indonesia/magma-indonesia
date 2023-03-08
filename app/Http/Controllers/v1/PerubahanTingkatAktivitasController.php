<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVarOptimize;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PerubahanTingkatAktivitasController extends Controller
{
    public $year;

    /**
     * Membuat range tahun
     *
     * @return Collection
     */
    public function years(): Collection
    {
        return collect(CarbonPeriod::create(
            Carbon::createFromDate('2015')->startOfYear(),
            '1 year',
            now()->endOfYear(),
        ));
    }

    public function vars(?string $year = null): Collection
    {
        $year = !is_null($year) ? $year : $this->year;

        return MagmaVarOptimize::select([
            'no',
            'ga_nama_gapi',
            'ga_code',
            'cu_status',
            'pre_status',
            'var_data_date'
        ])->whereRaw('cu_status <> pre_status AND YEAR(var_data_date) = ?', [$year])
            ->orderBy('var_data_date')
            ->get()
            ->each->append([
                'level', 'previous_level', 'activity_change'
            ]);
    }

    /**
     * Cache hasil rekapan index gunung api
     *
     * @param boolean $forever
     * @return void
     */
    public function cacheIndex()
    {
        if ($this->year === now()->format('Y')) {
            return Cache::tags(["perubahan-tingkat-aktivitas"])->rememberForever("perubahan-tingkat-aktivitas-{$this->year}", function () {
                return $this->vars();
            });
        }

        return Cache::tags(["perubahan-tingkat-aktivitas"])->remember("perubahan-tingkat-aktivitas-{$this->year}", 60, function () {
            return $this->vars();
        });
    }

    public function index(?string $year = null, bool $flush = false)
    {
        if ($flush) {
            Cache::tags(['perubahan-tingkat-aktivitas'])->flush();
        }

        try {
            $this->year = !is_null($year) ? $year : now()->format('Y');
            Carbon::createFromFormat('Y', $this->year)->format('Y');
        } catch (\Throwable $th) {
            abort(404);
        }

        if ((int) $this->year < 2015 OR $this->year > (int) now()->format('Y'))
            abort(404);

        // return $this->cacheIndex();

        return view('gunungapi.perubahan-tingkat-aktivitas.index', [
            'vars' => $this->cacheIndex(),
            'selected_year' => $year,
            'years' => $this->years(),
        ]);
    }
}
