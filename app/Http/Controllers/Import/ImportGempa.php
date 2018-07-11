<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaVar;
use App\VarGempa;
use App\VarVisual;
use App\VarLetusan;
use App\v1\MagmaVar as OldVar;
use App\Traits\ImportHelper;
use App\Traits\JenisGempa;

class ImportGempa extends Import
{
    use ImportHelper, JenisGempa;

    protected $obscode, $noticenumber, $gempa;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        // return $this->gempa;
        $this->gempa = collect($this->jenisgempa());
        $this->gempa->each(function ($item, $key) {
            $this->setItem($item)
                ->gempaSp()
                ->gempaNormal()
                ->gempaDominan()
                ->gempaLuncuran()
                ->gempaErupsi()
                ->gempaTerasa();
        });

        $data = [ 'success' => 1, 'text' => 'Data Kegempaan', 'message' => 'Data Kegempaan berhasil diperbarui', 'count' => VarGempa::jumlah() ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function gempaSp()
    {
        if ($this->item->jenis == 'sp') {

            $gempa = new \App\GempaSP;
            $table = 'e_'.$this->item->kode;
            $gempa->setTable($table);
            $kode = $this->item->kode;
    
            $old = Oldvar::select($this->item->select)
                ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
                ->where('var_'.$kode,'>',0)
                ->orderBy('no', 'asc')
                ->chunk(5000, function ($items) use ($kode,$gempa) {
                    foreach ($items as $key => $value) {
                        $no = $value->no;
                        $gacode = $value->ga_code;
                        $source = $value->var_source;

                        $this->obscode = $this->obscode($gacode,$source);
                        $this->noticenumber = $this->obscode.$value->var_noticenumber;

                        $jumlah = $value->{'var_'.$kode};
                        $amin = $value->{'var_'.$kode.'_amin'};
                        $amax = $value->{'var_'.$kode.'_amax'};
                        $spmin = $value->{'var_'.$kode.'_spmin'};
                        $spmax = $value->{'var_'.$kode.'_spmax'};
                        $dmin = $value->{'var_'.$kode.'_dmin'};
                        $dmax = $value->{'var_'.$kode.'_dmax'};

                        $createGempa = VarGempa::firstOrCreate(['noticenumber_id' => $this->noticenumber]);

                        $create = $gempa->firstOrCreate(
                            [
                                'var_gempa_id' => $createGempa->id
                            ],
                            [
                                'noticenumber_id' => $this->noticenumber,
                                'jumlah' => $jumlah,
                                'amin' => $amin,
                                'amax' => $amax,
                                'spmin' => $spmin,
                                'spmax' => $spmax,
                                'dmin' => $dmin,
                                'dmax' => $dmax
                            ]
                        );
                        
                        if ($create) {
                            $this->data = $this->tempTable($kode,$no);
                        }
                    }
                });
        }

        return $this;
    }

    protected function gempaNormal()
    {
        if ($this->item->jenis == 'normal') {

            $gempa = new \App\GempaNormal;
            $table = 'e_'.$this->item->kode;
            $gempa->setTable($table);
            $kode = $this->item->kode;
    
            $old = Oldvar::select($this->item->select)
                ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
                ->where('var_'.$kode,'>',0)
                ->orderBy('no', 'asc')
                ->chunk(5000, function ($items) use ($kode,$gempa) {
                    foreach ($items as $key => $value) {
                        $no = $value->no;
                        $gacode = $value->ga_code;
                        $source = $value->var_source;

                        $this->obscode = $this->obscode($gacode,$source);
                        $this->noticenumber = $this->obscode.$value->var_noticenumber;

                        $jumlah = $value->{'var_'.$kode};
                        $amin = $value->{'var_'.$kode.'_amin'};
                        $amax = $value->{'var_'.$kode.'_amax'};
                        $dmin = $value->{'var_'.$kode.'_dmin'};
                        $dmax = $value->{'var_'.$kode.'_dmax'};

                        $createGempa = VarGempa::firstOrCreate(['noticenumber_id' => $this->noticenumber]);

                        $create = $gempa->firstOrCreate(
                            [
                                'var_gempa_id' => $createGempa->id
                            ],
                            [
                                'noticenumber_id' => $this->noticenumber,
                                'jumlah' => $jumlah,
                                'amin' => $amin,
                                'amax' => $amax,
                                'dmin' => $dmin,
                                'dmax' => $dmax
                            ]
                        );
                        
                        if ($create) {
                            $this->data = $this->tempTable($kode,$no);
                        }
                    }
                });
        }

        return $this;
    }

    protected function gempaDominan()
    {
        if ($this->item->jenis == 'dominan') {

            $gempa = new \App\GempaDominan;
            $table = 'e_'.$this->item->kode;
            $gempa->setTable($table);
            $kode = $this->item->kode;
    
            $old = Oldvar::select($this->item->select)
                ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
                ->where('var_'.$kode,'>',0)
                ->orderBy('no', 'asc')
                ->chunk(5000, function ($items) use ($kode,$gempa) {
                    foreach ($items as $key => $value) {
                        $no = $value->no;
                        $gacode = $value->ga_code;
                        $source = $value->var_source;

                        $this->obscode = $this->obscode($gacode,$source);
                        $this->noticenumber = $this->obscode.$value->var_noticenumber;

                        $jumlah = $value->{'var_'.$kode};
                        $amin = $value->{'var_'.$kode.'_amin'};
                        $amax = $value->{'var_'.$kode.'_amax'};
                        $adom = $value->{'var_'.$kode.'_adom'};

                        $createGempa = VarGempa::firstOrCreate(['noticenumber_id' => $this->noticenumber]);

                        $create = $gempa->firstOrCreate(
                            [
                                'var_gempa_id' => $createGempa->id
                            ],
                            [
                                'noticenumber_id' => $this->noticenumber,
                                'jumlah' => $jumlah,
                                'amin' => $amin,
                                'amax' => $amax,
                                'adom' => $adom
                            ]
                        );
                        
                        if ($create) {
                            $this->data = $this->tempTable($kode,$no);
                        }
                    }
                });
        }

        return $this;
    }

    protected function gempaLuncuran()
    {
        if ($this->item->jenis == 'luncuran') {

            $gempa = new \App\GempaLuncuran;
            $table = 'e_'.$this->item->kode;
            $gempa->setTable($table);
            $kode = $this->item->kode;
    
            $old = Oldvar::select($this->item->select)
                ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
                ->where('var_'.$kode,'>',0)
                ->orderBy('no', 'asc')
                ->chunk(5000, function ($items) use ($kode,$gempa) {
                    foreach ($items as $key => $value) {
                        $no = $value->no;
                        $gacode = $value->ga_code;
                        $source = $value->var_source;

                        $this->obscode = $this->obscode($gacode,$source);
                        $this->noticenumber = $this->obscode.$value->var_noticenumber;

                        $jumlah = $value->{'var_'.$kode};
                        $amin = $value->{'var_'.$kode.'_amin'};
                        $amax = $value->{'var_'.$kode.'_amax'};
                        $dmin = $value->{'var_'.$kode.'_dmin'};
                        $dmax = $value->{'var_'.$kode.'_dmax'};
                        $rmin = $value->{'var_'.$kode.'_rmin'};
                        $rmax = $value->{'var_'.$kode.'_rmax'};
                        $arah = $value->{'var_'.$kode.'_alun'};

                        $createGempa = VarGempa::firstOrCreate(['noticenumber_id' => $this->noticenumber]);

                        $create = $gempa->firstOrCreate(
                            [
                                'var_gempa_id' => $createGempa->id
                            ],
                            [
                                'noticenumber_id' => $this->noticenumber,
                                'jumlah' => $jumlah,
                                'amin' => $amin,
                                'amax' => $amax,
                                'dmin' => $dmin,
                                'dmax' => $dmax,
                                'rmin' => $rmin,
                                'rmax' => $rmax,
                                'arah' => $arah
                            ]
                        );
                        
                        if ($create) {
                            $this->data = $this->tempTable($kode,$no);
                        }
                    }
                });
        }

        return $this;
    }

    protected function gempaErupsi()
    {
        if ($this->item->jenis == 'erupsi') {

            $gempa = new \App\GempaErupsi;
            $table = 'e_'.$this->item->kode;
            $gempa->setTable($table);
            $kode = $this->item->kode;
    
            $old = Oldvar::select($this->item->select)
                ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
                ->where('var_'.$kode,'>',0)
                ->orderBy('no', 'asc')
                ->chunk(5000, function ($items) use ($kode,$gempa) {
                    foreach ($items as $key => $value) {
                        $no = $value->no;
                        $gacode = $value->ga_code;
                        $source = $value->var_source;

                        $this->obscode = $this->obscode($gacode,$source);
                        $this->noticenumber = $this->obscode.$value->var_noticenumber;

                        $jumlah = $value->{'var_'.$kode};
                        $amin = $value->{'var_'.$kode.'_amin'};
                        $amax = $value->{'var_'.$kode.'_amax'};
                        $dmin = $value->{'var_'.$kode.'_dmin'};
                        $dmax = $value->{'var_'.$kode.'_dmax'};
                        $tmin = $value->{'var_'.$kode.'_tmin'};
                        $tmax = $value->{'var_'.$kode.'_tmax'};
                        $wasap = $value->{'var_'.$kode.'_wasap'};

                        $createGempa = VarGempa::firstOrCreate(['noticenumber_id' => $this->noticenumber]);

                        $create = $gempa->firstOrCreate(
                            [
                                'var_gempa_id' => $createGempa->id
                            ],
                            [
                                'noticenumber_id' => $this->noticenumber,
                                'jumlah' => $jumlah,
                                'amin' => $amin,
                                'amax' => $amax,
                                'dmin' => $dmin,
                                'dmax' => $dmax,
                            ]
                        );

                        $visual_id  = VarVisual::select('id')->where('noticenumber_id',$this->noticenumber)->first()->id;

                        $createLetusan = VarLetusan::firstOrCreate(
                            [   
                                'var_visual_id'     => $visual_id
                            ],
                            [   
                                'tmin'         => $tmin,
                                'tmax'         => $tmax,
                                'wasap'        => $wasap,
                            ]
                        );
                        
                        if ($create AND $createLetusan) {
                            $this->data = $this->tempTable($kode,$no);
                        }
                    }
                });
        }

        return $this;
    }

    protected function gempaTerasa()
    {
        if ($this->item->jenis == 'terasa') {

            $gempa = new \App\GempaTerasa;
            $table = 'e_'.$this->item->kode;
            $gempa->setTable($table);
            $kode = $this->item->kode;
    
            $old = Oldvar::select($this->item->select)
                ->whereBetween('no',[$this->startNo($kode),$this->endNo('var')])
                ->where('var_'.$kode,'>',0)
                ->orderBy('no', 'asc')
                ->chunk(5000, function ($items) use ($kode,$gempa) {
                    foreach ($items as $key => $value) {
                        $no = $value->no;
                        $gacode = $value->ga_code;
                        $source = $value->var_source;

                        $this->obscode = $this->obscode($gacode,$source);
                        $this->noticenumber = $this->obscode.$value->var_noticenumber;

                        $jumlah = $value->{'var_'.$kode};
                        $amin = $value->{'var_'.$kode.'_amin'};
                        $amax = $value->{'var_'.$kode.'_amax'};
                        $spmin = $value->{'var_'.$kode.'_spmin'};
                        $spmax = $value->{'var_'.$kode.'_spmax'};
                        $dmin = $value->{'var_'.$kode.'_dmin'};
                        $dmax = $value->{'var_'.$kode.'_dmax'};
                        $skala = $value->{'var_'.$kode.'_skalamin'};

                        $createGempa = VarGempa::firstOrCreate(['noticenumber_id' => $this->noticenumber]);

                        $create = $gempa->firstOrCreate(
                            [
                                'var_gempa_id' => $createGempa->id
                            ],
                            [
                                'noticenumber_id' => $this->noticenumber,
                                'jumlah' => $jumlah,
                                'amin' => $amin,
                                'amax' => $amax,
                                'spmin' => $spmin,
                                'spmax' => $spmax,
                                'dmin' => $dmin,
                                'dmax' => $dmax,
                                'skala' => $skala
                            ]
                        );
                        
                        if ($create) {
                            $this->data = $this->tempTable($kode,$no);
                        }
                    }
                });
        }

        return $this;
    }
}