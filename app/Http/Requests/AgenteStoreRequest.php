<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AgenteStoreRequest extends FormRequest
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
            'nome_completo' => ['required', 'max:50', 'min:7'],
            'email' => ['max:50', 'email'],
            'telefone' => ['required', 'regex:/(9)[0-9]{8}/', 'max:9'],
            'telefone_alternativo' => ['required', 'regex:/(9)[0-9]{8}/', 'max:9'],
            'municipio' => ['required', 'max:20'],
            'bairro' => ['required', 'max:20'],
            'rua' => ['required', 'max:20'],
            'banco' => ['required', 'max:50'],
            'estado' => ['required', 'boolean'],
            'senha' => ['required', 'min:8'],
            'equipa_id' => ['required', Rule::exists('equipas', 'id')->where(function ($query) {
                $query->where('account_id', Auth::user()->account_id);
            })],
        ];
    }
}
