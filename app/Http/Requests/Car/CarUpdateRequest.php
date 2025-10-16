<?php

namespace App\Http\Requests\Car;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CarUpdateRequest extends FormRequest
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
			'brand' => 'nullable|string',
			'model' => 'nullable|string',
			'number' => 'nullable|string',
			'color' => 'nullable|string',
		];
    }
}