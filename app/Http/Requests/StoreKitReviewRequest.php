<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKitReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow all, or add logic if needed
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|integer',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string|max:1000',
        ];
    }
}
