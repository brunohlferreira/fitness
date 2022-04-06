<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkoutPresetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3'],
            'description' => ['string', 'nullable'],
            'level' => ['integer', 'min:0', 'max:255'],
            'time_cap' => ['integer', 'min:0', 'max:255', 'nullable'],
            'workout_type_id' => ['integer'],
        ];
    }
}
