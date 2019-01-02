<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CreateRoqRequest extends FormRequest
{

    const NIP_PEMERIKSA = [
        '196708121994032002',
        '196808152002121001',
        '198006212005021014'
    ];

    protected $datetime_utc;

    protected $noticenumber;

    protected $latlon_text;

    protected $pemeriksa;

    public function __construct(Request $request)
    {
        $utc = $this->setDateTimeUtc($request)->getDateTimeUtc();
        $noticenumber = $this->setNoticenumber($request)->getNoticenumber();
        $latlon_text = $this->setLatLonText($request)->getLatLonText();
        $pemeriksa = $this->setPemeriksa($request)->getPemeriksa();
        $roq_source = $this->getRoqSource($request);

        $request->merge([
            'datetime_wib_str' => $request->datetime_wib,
            'datetime_utc' => $utc,
            'id_lap' => $noticenumber,
            'magtype' => 'SR',
            'dep_unit' => 'Km',
            'latlon_text' => $latlon_text,
            'nearest_volcano' => null,
            'roq_nama_pemeriksa' => $pemeriksa['nama'],
            'roq_nip_pemeriksa' => $pemeriksa['nip'],
            'roq_source' => $roq_source
        ]);
    }

    protected function setDateTimeUtc($request)
    {
        $wib = Carbon::createFromFormat('Y-m-d H:i:s',$request->datetime_wib);
        $this->datetime_utc = $wib->subHours(7)->toDateTimeString();
        return $this;
    }

    protected function getDateTimeUtc()
    {
        return $this->datetime_utc;
    }

    protected function setNoticenumber($request)
    {
        $wib = Carbon::createFromFormat('Y-m-d H:i:s',$request->datetime_wib)->format('YmdHis');
        $this->noticenumber = 'ROQ'.$wib;
        return $this;
    }

    protected function getNoticenumber()
    {
        return $this->noticenumber;
    }

    protected function setLatLonText($request)
    {
        $lat = $request->lat_lima < 0 ? abs($request->lat_lima).' LS' : $request->lat_lima.' LU';
        $lon = $request->lon_lima.' BT';
        $this->latlon_text = $lat.' '.$lon;
        return $this;
    }

    protected function getLatLonText()
    {
        return $this->latlon_text;
    }

    protected function setPemeriksa($request)
    {
        $nip = auth()->user()->nip;

        $this->pemeriksa = [
            'nama' => null,
            'nip' => null
        ];

        if (in_array($nip,self::NIP_PEMERIKSA))
        {
            $this->pemeriksa = [
                'nama' => auth()->user()->name,
                'nip' => auth()->user()->nip
            ];
        }

        return $this;
    }

    protected function getPemeriksa()
    {
        return $this->pemeriksa;
    }

    protected function getRoqSource($request)
    {
        $roq_source = $request->roq_source_code;
        $roq_source = str_replace('BMKG','Badan Meteorologi, Klimatologi dan Geofisika (BMKG)',$roq_source);
        $roq_source = str_replace('USGS','United States Geological Survey (USGS)',$roq_source);
        $roq_source = str_replace('GFZ','Deutsche GeoForschungsZentrum (GFZ)',$roq_source);

        return implode(';',$roq_source);
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
            'datetime_wib.required' => '<b>Waktu Gempa (WIB)</b> harus diisi.',
            'datetime_wib.date_format' => 'Format <b>Waktu Gempa (WIB)</b> Y-m-d H:i:s (ex: 2018-12-01 23:59:59).',
            'datetime_win.tomorrow' => 'Tanggal tidak boleh lebih dari tanggal hari ini.',
            'lat_lima.required' => '<b>Lintang</b> harus diisi.',
            'lat_lima.numeric' => '<b>Lintang</b> harus dalam tipe numeric.',
            'lat_lima.between' => 'Nilai <b>Lintang</b> antara -11 hingga 6.',
            'lon_lima.required' => '<b>Bujur</b> harus diisi.',
            'lon_lima.numeric' => '<b>Bujur</b> harus dalam tipe numeric.',
            'lon_lima.between' => 'Nilai <b>Bujur</b> antara 94 hingga 141.',
            'magnitude.required' => '<b>Magnitudo</b> harus diisi.',
            'magnitude.numeric' => '<b>Magnitudo</b> harus dalam tipe numeric.',
            'magnitude.between' => 'Nilai <b>Magnitudo</b> antara 1 hingga 10.',
            'depth.required' => '<b>Kedalaman</b> harus diisi.',
            'depth.numeric' => '<b>Kedalaman</b> harus dalam tipe numeric.',
            'depth.between' => 'Nilai <b>Kedalaman</b> antara 1-500 Km.',
            'area.required' => '<b>Area</b> harus diisi.',
            'koter.required' => '<b>Kota Terdekat</b> harus diisi.',
            'mmi' => 'present|nullable',
            'roq_tanggapan.required' => '<b>Tanggapan</b> harus dipilih.',
            'roq_tanggapan.boolean' => '<b>Tanggapan</b> hanya tersedia Ya atau Tidak.',
            'roq_title.required_if' => '<b>Nama Daerah</b> harus diisi jika <b>Tanggapan</b> dibuat.',
            'roq_intro.required_if' => '<b>Pendahuluan</b> harus diisi jika <b>Tanggapan</b> dibuat.',
            'roq_konwil.required_if' => '<b>Kondisi Wilayah</b> harus diisi jika <b>Tanggapan</b> dibuat.',
            'roq_mekanisme.required_if' => '<b>Mekanisme</b> harus diisi jika <b>Tanggapan</b> dibuat.',
            'roq_efek.required_if' => '<b>Efek</b> harus diisi jika <b>Tanggapan</b> dibuat.',
            'roq_rekom.required_if' => '<b>Rekomendasi</b> harus diisi jika <b>Tanggapan</b> dibuat.',
            'roq_source_code.required_if' => '<b>Sumber Data</b> harus dipilih jika <b>Tanggapan</b> dibuat.',
            'roq_source_code.array' => 'Parameter nilai Sumber Data harus dalam bentuk Array (hubungi Admin untuk penjelasan).',
            'roq.roq_source_code.*.in' => 'Sumber Data tidak masuk dalam data.',
            'roq_tsu.required_if' => '<b>Potensi Tsunami</b> harus dipilih jika <b>Tanggapan</b> dibuat.',
            'roq_tsu.boolean' => 'Tsunami hanya tersedia pilihan Berpotensi atau Tidak Berpotensi.'
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
            'datetime_wib' => 'required|date_format:Y-m-d H:i:s|before:tomorrow',
            'lat_lima' => 'required|numeric|between:-11,6',
            'lon_lima' => 'required|numeric|between:94,141',
            'magnitude' => 'required|numeric|between:1,10',
            'depth' => 'required|numeric|between:1,500',
            'area' => 'required',
            'koter' => 'required',
            'roq_tanggapan' => 'required|boolean',
            'roq_title' => 'required_if:roq_tanggapan,1',
            'roq_intro' => 'required_if:roq_tanggapan,1',
            'roq_konwil' => 'required_if:roq_tanggapan,1',
            'roq_mekanisme' => 'required_if:roq_tanggapan,1',
            'roq_efek' => 'required_if:roq_tanggapan,1',
            'roq_rekom' => 'required_if:roq_tanggapan,1',
            'roq_source_code' => 'required_if:roq_tanggapan,1|array',
            'roq_source_code.*' => 'required_if:roq_tanggapan,1|in:BMKG,GFZ,USGS',
            'roq_tsu' => 'required_if:roq_tanggapan,1|boolean'
        ];
    }
}
