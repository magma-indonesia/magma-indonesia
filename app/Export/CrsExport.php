<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use App\SigertanCrs;
use App\IndonesiaProvince as Provinsi;
use DB;

class CrsExport implements FromView
{
    use Exportable;

    // protected $type;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * View untuk display CRS dengan filter
     *
     * @return \Illuminate\Http\Response
     */
    public function applyFilter($request)
    {

        if (count($request->all()) == 0 || (count($request->all()) == 1 AND $request->has('crs_page'))){
            return $crs = SigertanCrs::orderBy('waktu_kejadian','desc');
        }

        switch($request->status){
            case 'all':
                $status = ['BARU','DRAFT','TERBIT'];
                break;
            default:
                $status = explode(';',$request->status);
        }

        switch ($request->tipe) {
            case '1':
                $tipe = ['GERAKAN TANAH'];
                break;
            case '2':
                $tipe = ['GEMPA BUMI'];
                break;
            case '3':
                $tipe = ['TSUNAMI'];
                break;
            case '4':
                $tipe = ['GUNUNG API'];
                break;
            case '5':
                $tipe = ['SEMBURAN LUMPUR, GAS DAN AIR'];
                break;
            default:
                $tipe = ['GUNUNG API','GEMPA BUMI','TSUNAMI','GERAKAN TANAH','SEMBURAN LUMPUR, GAS, DAN AIR'];
                break;
        }

        $provinsi = $request->provinsi == 'all' ? '%' : $request->provinsi;
        $kota = $request->kota == 'all' ? '%' : $request->kota;

        $crs = SigertanCrs::orderBy('waktu_kejadian','asc')
                ->whereIn('status',$status)
                ->whereIn('type',$tipe)
                ->whereBetween('waktu_kejadian',[$request->start,$request->end])
                ->where('province_id','like',$provinsi)
                ->where('city_id','like',$kota);
                
        $valid = $request->valid;

        switch ($valid) {
            case 'not':
                return $crs->doesntHave('validator');
                break;
            case 'all':
                return $crs;
                break;
            default:
                $valid == 'valid' ? $valid = 1 : $valid;
                $valid == 'invalid' ? $valid = 0 : $valid;
                return $crs->whereHas('validator', function($query) use ($valid){
                            $query->where('valid','like',$valid);
                        });
                break;
        }

    }

    public function view(): View
    {
        $provinsi = Provinsi::orderBy('name')->get();
        
        $crs = $this->applyFilter($this->request);
  
        $crs = $crs->get();

        $jumlahBaru = SigertanCrs::whereIn('status',['BARU'])->count();
        $jumlahDraft = SigertanCrs::whereIn('status',['DRAFT'])->count();
        $jumlahTerbit = SigertanCrs::whereIn('status',['TERBIT'])->count();
        $total = SigertanCrs::count();

        $jumlahMga = SigertanCrs::whereIn('type',['GUNUNG API'])->count();
        $jumlahMgt = SigertanCrs::whereIn('type',['GERAKAN TANAH'])->count();
        $jumlahMgb = SigertanCrs::whereIn('type',['GEMPA BUMI','TSUNAMI'])->count();
        $jumlahEtc = SigertanCrs::whereIn('type',['SEMBURAN LUMPUR, GAS, DAN AIR'])->count();

        $topProv = DB::table('sigertan_crs')
                    ->join('indonesia_provinces','sigertan_crs.province_id','=','indonesia_provinces.id')
                    ->select('indonesia_provinces.name','sigertan_crs.province_id', DB::raw('count(*) as total'))
                    ->groupBy('sigertan_crs.province_id')
                    ->orderBy('total','desc')
                    ->take(8)
                    ->get();
        
        return view('export.crs',compact(
                    'crs',
                    'provinsi',
                    'jumlahBaru',
                    'jumlahDraft',
                    'jumlahTerbit',
                    'total',
                    'jumlahMga',
                    'jumlahMgt',
                    'jumlahMgb',
                    'jumlahEtc',
                    'topProv'
                    )
                );
    }

}
