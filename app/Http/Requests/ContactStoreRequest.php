<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'max:25', 'min:4'],
            'last_name' => ['required', 'max:25', 'min:4'],
            'nif_bi' => ['required', 'max:15', 'min:14', 'alpha_num'],
            'email' => ['max:50', 'email'],
            'phone' => ['required', 'min:9', 'regex:/(9)[0-9]{8}/', 'max:9'],
            'alternative_phone' => ['required', 'regex:/(9)[0-9]{8}/', 'max:9'],
            'cantina' => ['required', 'max:25', 'min:5'],
            'municipality' => ['required', 'max:20'],
            'district' => ['required', 'max:20'],
            'street' => ['required', 'max:20'],
            'estado' => ['required', 'boolean'],
            'imei' => ['required', 'max:20']
        ];
    }
}
