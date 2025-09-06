<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DegustacioniPaketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'NazivPaketa' => ['required','string','max:255','unique:degustacioni_pakets,NazivPaketa'],
            'Cena'        => ['required','numeric','min:0'],
            'Opis'        => ['nullable','string','max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'NazivPaketa.required' => 'Unesite naziv paketa.',
            'NazivPaketa.unique'   => 'Paket sa tim nazivom već postoji.',
            'Cena.required'        => 'Unesite cenu.',
            'Cena.numeric'         => 'Cena mora biti broj.',
            'Cena.min'             => 'Cena ne može biti negativna.',
        ];
    }
}
