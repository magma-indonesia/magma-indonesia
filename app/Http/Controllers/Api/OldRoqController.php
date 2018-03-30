<?php

namespace App\Http\Controllers\Api;

use App\OldRoq;
use App\User;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OldRoqResource;
use App\Http\Resources\OldRoqCollection;

class OldRoqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roqs = OldRoq::paginate(30);

        return new OldRoqCollection($roqs);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OldRoq  $oldRoq
     * @return \Illuminate\Http\Response
     */
    public function show($no)
    {
        $roq = OldRoq::findOrFail($no);

        return new OldRoqResource($roq);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OldRoq  $oldRoq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $no)
    {
        $roq = OldRoq::findOrFail($no);

        $validator = Validator::make($request->all(), [
            'tanggapan' => 'required',
            'title' => 'required',
            'intro' => 'required',
            'konwil' => 'required',
            'mekanisme' => 'required',
            'efek' => 'required',
            'rekomendasi' => 'required',
            'nip_pelapor' => 'required|digits:18',
        ]);

        $verikator = ['196708121994032002','198006212005021014','196808152002121001'];

        if ($validator->fails()) {
            return $validator->errors();
        }

        $nip_pelapor = $request->nip_pelapor;
        $nama_pelapor = User::where('nip',$request->nip_pelapor)->first()->name;
        
        if (in_array($nip_pelapor,$verikator))
        {

            if (empty($roq->roq_nip_pemeriksa)){
                $roq->fill([
                    'roq_nama_pemeriksa' => $nama_pelapor,
                    'roq_nip_pemeriksa' => $nip_pelapor,
                ]);
            }

            if (empty($roq->roq_nip_pelapor)){
                $nama_pelapor = $nama_pelapor;
                $nip_pelapor = $nip_pelapor;

                $roq->fill([
                    'roq_nama_pelapor' => $nama_pelapor,
                    'roq_nip_pelapor' => $nip_pelapor,
                ]);
            }

        } else {
            $roq->fill([
                'roq_nama_pelapor' => $nama_pelapor,
                'roq_nip_pelapor' => $nip_pelapor,
            ]);
        }
        
        $roq->fill([
            'roq_tanggapan' => $request->tanggapan,
            'roq_title' => $request->title,
            'roq_tsu' => empty($request->tsunami) ? 'TIDAK' : $request->tsunami,
            'roq_intro' => $request->intro,
            'roq_konwil' => $request->konwil,
            'roq_mekanisme' => $request->mekanisme,
            'roq_efek' => $request->efek,
            'roq_rekom' => $request->rekomendasi,
            'roq_source' => $request->sumber
        ]);

        $data = [
            'data' => [ 'success' => true, 'message' => 'Data berhasil diupdate'],
        ];

        if ($roq->save())
        {
            return response()->json($data);
        } else {
            return response()->json([
                'data' => [ 'success' => false, 'message' => 'Data gagal diupdate'],
            ]);
        }
        
    }
}
