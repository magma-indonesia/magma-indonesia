<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateVarStep1 extends FormRequest
{

    public function __construct(Request $request)
    {
        $this->setNoticenumber($request);
        $request->merge(['noticenumber' => $this->getNoticenumber()]);
        $request->merge(['perwkt' => $this->getPerwkt()]);
    }

    protected $obscode, $periode, $perwkt, $data_date, $noticenumber;

    protected function setObscode($obscode)
    {
        $this->obscode = $obscode;
        return $this;
    }

    protected function setDataDate($date)
    {
        $this->data_date = str_replace('-','',$date);
        return $this;
    }

    protected function setPeriode($periode)
    {
        $this->periode = $periode != '00:00-24:00' ? substr($periode,0,2).'00' : '2400';
        $this->perwkt = $periode != '00:00-24:00' ? '6' : '24';
        return $this;
    }

    protected function setNoticenumber($request)
    {
        $this->setObscode($request->code)->setDataDate($request->date)->setPeriode($request->periode);
        $this->noticenumber = $this->obscode.$this->data_date.$this->periode;
        return $this;
    }

    protected function getNoticenumber()
    {
        return $this->noticenumber;
    }

    protected function getPerwkt()
    {
        return $this->perwkt;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
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
            'noticenumber.unique' => 'Laporan sudah pernah dibuat.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|size:4',
            'status' => 'required|in:1,2,3,4',
            'date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'periode' => 'required|in:00:00-24:00,00:00-06:00,06:00-12:00,12:00-18:00,18:00-24:00',
            'noticenumber' => 'required|unique:magma_vars,noticenumber'
        ];
    }
}
