<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DegustacijaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // dozvoli samo menadžeru događaja ili administratoru
        return $this->user()?->can('managerOrAdmin') ?? false;
    }

    public function rules(): array
    {
        return [
            'Naziv'     => ['required','string','max:255'],
            // <input type="datetime-local"> vraća ISO format koji prolazi kao 'date'
            'Datum'     => ['required','date','after:now'],
            'Lokacija'  => ['required','string','max:255'],
            'Kapacitet' => ['required','integer','min:1'],

            // NEMOJ tražiti ovo iz forme – postavlja se u kontroleru
            // 'user_id'               => ...
            // 'status_degustacija_id' => ...
        ];
    }

    public function messages(): array
    {
        return [
            'Naziv.required'     => 'Unesite naziv degustacije.',
            'Datum.required'     => 'Unesite datum i vreme.',
            'Datum.after'        => 'Datum mora biti u budućnosti.',
            'Lokacija.required'  => 'Unesite lokaciju.',
            'Kapacitet.required' => 'Unesite kapacitet.',
            'Kapacitet.min'      => 'Kapacitet mora biti najmanje 1.',
        ];
    }
}
