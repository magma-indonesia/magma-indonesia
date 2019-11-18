<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\ResumeHarian;
use App\BencanaGeologi;
use Illuminate\Support\Carbon;
use App\Http\Controllers\ResumeHarianController as Resume; 

class ResumeHarianController extends Resume
{
    public function index()
    {
        return ResumeHarian::orderByDesc('updated_at')->paginate(7);
    }

    public function latest()
    {
        $date = now()->format('Y-m-d');
        $resume = ResumeHarian::whereTanggal($date)->first();

        return $resume ?: $this->create(new Request(['date' => $date]));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'date' => 'nullable|date_format:Y-m-d'
        ],[
            'date.date_format' => 'Format tanggal harus Y-m-d'
        ]);

        $this->date = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d'): 
                now()->format('Y-m-d');

        $this->date_before = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->subDay()->format('Y-m-d'): 
                now()->subDay()->format('Y-m-d');

        $this->noticenumber = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->subDay()->format('Ymd').'2400': 
                now()->subDay()->format('Ymd').'2400';

        $this->date_localized = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->formatLocalized('%d %B %Y'): 
                now()->formatLocalized('%d %B %Y');

        $this->date_localized_before = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->subDay()->formatLocalized('%d %B %Y'): 
                now()->subDay()->formatLocalized('%d %B %Y');

        $this->bencanas = BencanaGeologi::orderBy('urutan')
                            ->with([
                                'gunungapi',
                                'pendahuluan',
                                'magma_var' => function ($query) {
                                    $query->whereVarDataDate($this->date_before)
                                        ->whereVarPerwkt('24 Jam');
                                },
                                'vona:ga_code,volcanic_act_summ,vc_height,vc_height_text,summit_elevation,issued,issued_time,cu_avcode,notice_number'
                            ])
                            ->get();

        $contents = $this->checkVar()->generateContents()->getContents();

        $resume = str_replace('&deg;C','°C',implode("\n\n", $contents->toArray()));

        return ResumeHarian::updateOrCreate([
            'tanggal' => $this->date
        ],[
            'resume' => $resume,
            'truncated' => (strlen($resume) > 20) ? substr($resume, 0, 200) . '...' : $resume,
        ]);

    }

}
