<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Traits\JenisGempaVar;

class CreateVarStep3 extends FormRequest
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
            default:
                # code...
                break;
        }

        $this->customMessages = $messages;

        return $this;
    }    

    protected function setCustomRules($request)
    {
        $rules = [
            'hasgempa' => 'required|boolean',
        ];

        $this->customMessages = [
            'hasgempa.required' => 'Pilihan Gempa harus dipilih',
        ];

        if ($request->hasgempa)
        {
            $rules['tipe_gempa'] = 'required|array';
            $rules['tipe_gempa.*'] = 'required|in:tej,tel,vlp,dpt,dev,vta,vtb,hyb,lof,tor,hrm,tre,mtr,hbs,gug,apg,apl,lts,gtb,trs';

            foreach ($request->tipe_gempa as $key => $codeGempa) {
                foreach ($request->data[$codeGempa] as $key => $value) {
                    switch ($key) {
                        case 'jumlah':
                            $rules['data.'.$codeGempa.'.'.$key] = 'required|numeric|min:1';
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
