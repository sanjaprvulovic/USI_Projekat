<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DegustacijaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'Naziv' => ['required', 'string'],
            'Datum' => ['required'],
            'Lokacija' => ['required', 'string'],
            'Kapacitet' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'status_degustacija_id' => ['required', 'integer'],
        ];
    }
}
