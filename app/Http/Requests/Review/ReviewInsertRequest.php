<?php

namespace App\Http\Requests\Review;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ReviewInsertRequest extends FormRequest
{
    /* Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /* Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'trip_id' => 'required|integer',
			'rating' => 'nullable|integer|min:1|max:5',
			'comment' => 'required|string',
		];
    }
}