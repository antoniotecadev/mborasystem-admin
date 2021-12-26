<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PagamentoUpdateRequest extends FormRequest
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
            'contact_id' => ['required', Rule::exists('contacts', 'id')->where(function ($query) {
                $query->where('account_id', Auth::user()->account_id);
            })],
            'pacote' => ['required', 'integer'],
            'inicio' => ['required', 'date'],
            'fim' => ['required', 'date'],
        ];
    }
}
