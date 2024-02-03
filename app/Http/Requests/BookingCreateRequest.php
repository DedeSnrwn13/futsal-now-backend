<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingCreateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ground_id' => 'required|exists:grounds,id',
            'user_id' => 'required|exists:users,id',
            'order_date' => 'required',
            'started_at' => 'required',
            'ended_at' => 'required|after_or_equal:started_at',
            'total_price' => 'required|numeric',
            'order_number' => 'required|unique:bookings,order_number',
            'order_status' => 'required|string',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'paid_at' => 'nullable',
            'promo_id' => 'nullable|exists:promos,id',
            'ref_id' => 'nullable|string',
        ];
    }
}
