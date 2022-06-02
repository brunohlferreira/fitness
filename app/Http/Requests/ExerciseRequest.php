<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExerciseRequest extends FormRequest
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
            'description' => ['nullable'],
            'bilateral' => ['required', 'integer'],
            'bodyParts' => ['nullable', 'array'],
            'bodyParts.*.id' => ['required', 'numeric', 'distinct'],
            'bodyParts.*.impact' => ['required', 'numeric'],
            'equipments' => ['nullable', 'array'],
            'equipments.*.id' => ['required', 'numeric', 'distinct'],
            'equipments.*.name' => ['required', 'string'],
            'equipments.*.selected' => ['required', 'numeric'],
        ];
    }
}
