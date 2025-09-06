<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DegustacijaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('managerOrAdmin') ?? false;
    }

    public function rules(): array
    {
        return [
            'Naziv'     => ['required','string','max:255'],
            'Datum'     => ['required','date'],
            'Lokacija'  => ['required','string','max:255'],
            'Kapacitet' => ['required','integer','min:1'],
        ];
    }
}
