<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DegustacioniPaketUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('admin') ?? false;
    }

    public function rules(): array
    {
        $paket = $this->route('degustacioni_paket'); // route model param

        return [
            'NazivPaketa' => [
                'required','string','max:255',
                Rule::unique('degustacioni_pakets','NazivPaketa')->ignore($paket->id ?? null),
            ],
            'Cena' => ['required','numeric','min:0'],
            'Opis' => ['nullable','string','max:2000'],
        ];
    }
}
