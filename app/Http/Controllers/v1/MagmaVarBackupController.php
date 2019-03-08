<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MagmaVar as NewVar;
use App\v1\MagmaVar as OldVar;
use App\TempTable;
use Log;

class MagmaVarBackupController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    protected $new;

    protected function updateVisualKawah($var)
    {
        try {
            $this->old = OldVar::updateOrCreate(
                            [
                                'ga_code' => $var->code_id,
                                'var_data_date' => $var->var_data_date->format('Y-m-d'),
                                'periode' => $var->periode
                            ],
                            [
                                'var_viskawah' => $var->visual->visual_kawah
                            ]
                        );
            
            return $this;
        }

        catch (Exception $e) {
            session(['last_error' => $e]);
            return $this;
        }

    }

    protected function setLastRestore($var)
    {
        session(['last_restore' => $var->var_data_date->format('Y-m-d')]);
        return $this;
    }

    protected function getLastRestore()
    {
        return session('last_restore') ?
            session('last_restore') :
            session(['last_restore' => '2016-04-01']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->new = NewVar::with('visual')
                        ->whereBetween('var_data_date',[session('last_restore'),'2019-01-07'])
                        ->orderBy('created_at');

        $this->new->chunk(1000, function ($items) {
            foreach ($items as $key => $var) {
                $this->updateVisualKawah($var)
                    ->setLastRestore($var); 
            }
        });

        return session()->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\v1\MagmaVarBackup  $magmaVarBackup
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Backup::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\v1\MagmaVarBackup  $magmaVarBackup
     * @return \Illuminate\Http\Response
     */
    public function edit(MagmaVarBackup $magmaVarBackup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\v1\MagmaVarBackup  $magmaVarBackup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MagmaVarBackup $magmaVarBackup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\v1\MagmaVarBackup  $magmaVarBackup
     * @return \Illuminate\Http\Response
     */
    public function destroy(MagmaVarBackup $magmaVarBackup)
    {
        //
    }
}
