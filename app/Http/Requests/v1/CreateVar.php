<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateVar extends FormRequest
{

    public function __construct(Request $request)
    {
        $this->setNoticenumber($request);
        $request->merge(['noticenumber' => $this->getNoticenumber()]);
    }

    protected $noticenumber;

    protected function setNoticenumber($request)
    {
        switch ($request->periode) {
            case '00:00-24:00':
                $periode = '2400';
                break;
            case '00:00-06:00':
                $periode = '0000';
                break;
            case '06:00-12:00':
                $periode = '0600';
                break;
            case '12:00-18:00':
                $periode = '1200';
                break;
            default:
                $periode = '1800';
                break;
        }

        $this->noticenumber = str_replace('-','',$request->date).$periode;

        return $this;
    }

    protected function getNoticenumber()
    {
        return $this->noticenumber;
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->administrasi->bidang_id == 2
            AND auth()->user()->administrasi->kantor_id == 'PVG')
        {
            return true;
        }

        return false;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Semua form harus diisi.',
            'size' => 'Pos Gunung Api tidak valid.',
            'status.in' => 'Status Gunung Api tidak valid.',
            'date.date_format' => 'Format tanggal tidak valid (Y-m-d).',
            'date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'periode.in' => 'Range periode waktu tidak valid. ', 
            'noticenumber.unique' => 'Laporan sudah dibuat.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $ga_code = substr($request->code,0,3);
        
        return [
            'code' => 'required|size:4',
            'status' => 'required|in:Level I (Normal),Level II (Waspada),Level III (Siaga),Level IV (Awas)',
            'date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'periode' => 'required|in:00:00-24:00,00:00-06:00,06:00-12:00,12:00-18:00,18:00-24:00',
            'noticenumber' => 'required|unique:magma.magma_var,var_noticenumber,'.$ga_code.',ga_code'
        ];
    }
}
