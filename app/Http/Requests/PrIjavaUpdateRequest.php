<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrIjavaUpdateRequest extends FormRequest
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
            'Datum' => ['required'],
            'status_prijava_id' => ['required', 'integer'],
            'degustacija_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'degustacioni_paket_id' => ['required', 'integer'],
        ];
    }
}
