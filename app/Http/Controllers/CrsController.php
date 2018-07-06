<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SigertanCrs;
use App\IndonesiaProvince as Provinsi;
use DB;
use Indonesia;

class CrsController extends Controller
{

    /**
     * Meload seluruh data Kota berdasarkan Provinsi
     *
     * @return App\IndonesiaCity $cities;
     */
    public function getCities(Request $request)
    {
        $cities = Provinsi::findOrFail($request->id)->cities;
        return $cities;
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
                $status = explode(';',$request->input('status','BARU;DRAFT;TERBIT'));
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

        $provinsi = $request->provinsi == 'all' ? '%' : $request->input('provinsi','%');
        $kota = $request->kota == 'all' ? '%' : $request->input('kota','%');

        $crs = SigertanCrs::orderBy('waktu_kejadian','desc')
                ->whereIn('status',$status)
                ->whereIn('type',$tipe)
                ->whereBetween('waktu_kejadian',[$request->start,$request->end])
                ->where('province_id','like',$provinsi)
                ->where('city_id','like',$kota);
                
        $valid = $request->input('valid','all');

        switch ($valid) {
            case 'not':
                return $crs->doesntHave('validator');
            case 'all':
                return $crs;
            default:
                $valid == 'valid' ? $valid = 1 : $valid;
                $valid == 'invalid' ? $valid = 0 : $valid;
                return $crs->whereHas('validator', function($query) use ($valid){
                            $query->where('valid','like',$valid);
                        });
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $provinsi = Provinsi::orderBy('name')->get();
        
        $crs = $this->applyFilter($request);
  
        $crs = $crs->paginate(30,['*'],'crs_page');

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

        $request->flash();
        
        return view('crs.index',compact(
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'create';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return 'store';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
