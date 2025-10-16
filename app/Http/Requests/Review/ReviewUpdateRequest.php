<?php

namespace App\Http\Requests\Review;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ReviewUpdateRequest extends FormRequest
{
    /* Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
			'passenger_id' => 'nullable|integer',
			'driver_id' => 'nullable|integer',
			'rating' => 'nullable|integer',
			'comment' => 'nullable|string',
			'trip_id' => 'nullable|integer',
		];
    }
}