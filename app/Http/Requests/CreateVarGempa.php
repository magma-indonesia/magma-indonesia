<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Traits\JenisGempaVar;

class CreateVarGempa extends FormRequest
{
    use JenisGempaVar;

    protected $customMessages;

    protected $customRules;

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
        return $this->customMessages; 
    }

    protected function setCustomMessages($key,$code)
    {
        $messages = $this->customMessages;

        switch ($key) {
            case 'jumlah':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Jumlah Gempa '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Jumlah Gempa '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.min'] = 'Jumlah Gempa '.$this->namaGempa($code).' minimal 1';
                break;
            case 'amin':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Amplitudo Minimum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Amplitudo Minimum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Amplitudo Minimum '.$this->namaGempa($code).' antara 0.5 - 24mm';
                $messages['data.'.$code.'.'.$key.'.lte'] = 'Amplitudo Minimum '.$this->namaGempa($code).' harus kurang dari Amplitudo Maksimum';
                break;
            case 'amax':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Amplitudo Maximum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Amplitudo Maximum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Amplitudo Maximum '.$this->namaGempa($code).' antara 0.5 - 24mm';
                $messages['data.'.$code.'.'.$key.'.gte'] = 'Amplitudo Maximum '.$this->namaGempa($code).' harus lebih besari dar Amplitudo Minimum';
                break;
            case 'dmin':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Durasi Minimum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Durasi Minimum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Durasi Minimum '.$this->namaGempa($code).' minimal antara 1-1200 detik';
                $messages['data.'.$code.'.'.$key.'.lte'] = 'Durasi Minimum '.$this->namaGempa($code).' harus kurang dari Durasi Maksimum';
                break;
            case 'dmax':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Durasi Maximum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Durasi Maximum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Durasi Maximum '.$this->namaGempa($code).' minimal antara 1-1200 detik';
                $messages['data.'.$code.'.'.$key.'.gte'] = 'Durasi Maximum '.$this->namaGempa($code).' harus lebih besar dari Durasi Minimum';
                break;
            case 'spmin':
                $messages['data.'.$code.'.'.$key.'.required'] = 'SP Minimum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'SP Minimum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'SP Minimum '.$this->namaGempa($code).' antara 0.5-24 detik';
                $messages['data.'.$code.'.'.$key.'.lte'] = 'SP Minimum '.$this->namaGempa($code).' harus kurang dari SP Maksimum';
                break;
            case 'spmax':
                $messages['data.'.$code.'.'.$key.'.required'] = 'SP Maximum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'SP Maximum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'SP Maximum '.$this->namaGempa($code).' antara 0.5-24 detik';
                $messages['data.'.$code.'.'.$key.'.gte'] = 'SP Maximum '.$this->namaGempa($code).' harus lebih besar dari SP Minimum';
                break;
            case 'adom':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Amplitudo Dominan '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Amplitudo Dominan'.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Amplitudo Dominaan '.$this->namaGempa($code).' antara 0.5 - 24mm';
                break;
            case 'rmin':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Jarak Luncuran Minimum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Jarak Luncuran Minimum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Jarak Luncuran Minimum '.$this->namaGempa($code).' antara 0-10.000 meter';
                $messages['data.'.$code.'.'.$key.'.lte'] = 'Jarak Luncuran Minimum '.$this->namaGempa($code).' harus kurang dari Jarak Luncuran Maksimum';
                break;
            case 'rmax':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Jarak Luncuran Maximum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Jarak Luncuran Maximum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Jarak Luncuran Maximum '.$this->namaGempa($code).' antara 0-10.000 meter';
                break;      
            case 'alun':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Arah Luncuran '.$this->namaGempa($code).' harus dipilih minimal 1';
                $messages['data.'.$code.'.'.$key.'.array'] = 'Arah Luncuran '.$this->namaGempa($code).' tidak ada dalam list';
                $messages['data.'.$code.'.'.$key.'.*.in'] = 'Arah Luncuran '.$this->namaGempa($code).' tidak ada dalam list';
                break;
            case 'tmin':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Tinggi Letusan Minimum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Tinggi Letusan Minimum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Tinggi Letusan Minimum '.$this->namaGempa($code).' antara 0-10.000 meter';
                $messages['data.'.$code.'.'.$key.'.lte'] = 'Tinggi Letusan Minimum '.$this->namaGempa($code).' harus kurang dari Tinggi Letusan Maksimum';
                break;
            case 'tmax':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Jarak Luncuran Maximum '.$this->namaGempa($code).' harus diisi';
                $messages['data.'.$code.'.'.$key.'.numeric'] = 'Jarak Luncuran Maximum '.$this->namaGempa($code).' bertipe numeric';
                $messages['data.'.$code.'.'.$key.'.between'] = 'Jarak Luncuran Maximum '.$this->namaGempa($code).' antara 0-10.000 meter';
                break;
            case 'wasap':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Warna Asap '.$this->namaGempa($code).' harus dipilih minimal 1';
                $messages['data.'.$code.'.'.$key.'.array'] = 'Warna Asap '.$this->namaGempa($code).' tidak ada dalam list';
                $messages['data.'.$code.'.'.$key.'.*.in'] = 'Warna Asap '.$this->namaGempa($code).' tidak ada dalam list';
                break;
            case 'skala':
                $messages['data.'.$code.'.'.$key.'.required'] = 'Skala Gempa '.$this->namaGempa($code).' belum diisi';
                $messages['data.'.$code.'.'.$key.'.array'] = 'Skala Gempa '.$this->namaGempa($code).' tidak ada dalam list';
                $messages['data.'.$code.'.'.$key.'.*.in'] = 'Skala Gempa :input '.$this->namaGempa($code).' tidak ada dalam list';
                break;
            default:
                break;
        }

        $this->customMessages = $messages;

        return $this;
    }    

    protected function setCustomRules($request)
    {
        $rules = [
            'has_gempa' => 'required|boolean',
        ];

        $this->customMessages = [
            'has_gempa.required' => 'Pilihan Gempa harus dipilih',
        ];

        if ($request->has_gempa)
        {
            $rules['tipe_gempa'] = 'required|array';
            $rules['tipe_gempa.*'] = 'required|in:tej,tel,vlp,dpt,dev,vta,vtb,hyb,lof,tor,hrm,tre,mtr,hbs,gug,apg,apl,lts,gtb,trs';

            foreach ($request->tipe_gempa as $key => $codeGempa) {
                foreach ($request->data[$codeGempa] as $key => $value) {
                    
                    switch ($key) {
                        case 'jumlah':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|integer|min:1';
                            break;
                        case 'amin':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0.5,24|lte:data.'.$codeGempa.'.amax';
                            break;
                        case 'amax':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0.5,24|gte:data.'.$codeGempa.'.amin';
                            break;
                        case 'dmin':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:1,1200|lte:data.'.$codeGempa.'.dmax';
                            break;
                        case 'dmax':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:1,1200|gte:data.'.$codeGempa.'.dmin';
                            break;
                        case 'spmin':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0.5,20|lte:data.'.$codeGempa.'.spmax';
                            break;
                        case 'spmax':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0.5,20|gte:data.'.$codeGempa.'.spmin';
                            break;
                        case 'adom':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|lte:data.'.$codeGempa.'.amax|gte:data.'.$codeGempa.'.amin';
                            break;
                        case 'rmin':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0,10000|lte:data.'.$codeGempa.'.rmax';
                            break;
                        case 'rmax':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0,10000';
                            break;
                        case 'alun':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|array';
                            $rules['data.'.$codeGempa.'.'.$key.'.*'] = 'in:Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut,Utara,Timur Laut';
                            break;
                        case 'tmin':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0,10000|lte:data.'.$codeGempa.'.tmax';
                            break;
                        case 'tmax':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|between:0,10000';
                            break;
                        case 'wasap':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|array';
                            $rules['data.'.$codeGempa.'.'.$key.'.*'] = 'in:Putih,Kelabu,Cokelat,Hitam';
                            break;
                        case 'skala':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|array';
                            $rules['data.'.$codeGempa.'.'.$key.'.*'] = 'in:I,II,III,IV,V,VI,VII';
                            break;
                        default:
                            # code...
                            break;
                    }

                    $this->setCustomMessages($key,$codeGempa);

                }
            }
        }

        return $this->customRules = $rules;
    }

    protected function namaGempa($code)
    {
        foreach ($this->jenisgempa() as $key => $value) {
            if ($value['kode'] == $code)
            {
                return $value['nama'];
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $this->setCustomRules($request);

        return $this->customRules;
    }

}
