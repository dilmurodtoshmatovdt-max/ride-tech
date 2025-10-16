<?php

namespace App\Http\Requests\Trip;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class TripInsertRequest extends FormRequest
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
			'from_address' => 'required|string',
			'to_address' => 'required|string',
			'preferences' => 'nullable|string'
		];
    }
}