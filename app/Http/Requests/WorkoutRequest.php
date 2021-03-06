<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkoutRequest extends FormRequest
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
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'level' => ['required', 'integer', 'min:0', 'max:255'],
            'time_cap' => ['required', 'integer', 'min:0', 'max:255'],
            'score' => ['nullable', 'string'],
            'workout_type_id' => ['required', 'integer', 'min:0'],
            'workout_preset_id' => ['nullable', 'integer', 'min:0'],
            'exercises' => ['nullable', 'array'],
            'exercises.*.id' => ['required', 'numeric', 'exists:exercises'],
            'exercises.*.note' => ['nullable', 'string'],
            'exercises.*.sets' => ['nullable', 'array'],
            'exercises.*.sets.*.repetitions' => ['required', 'numeric'],
            'exercises.*.sets.*.weight' => ['required', 'numeric'],
            'exercises.*.sets.*.distance' => ['required', 'numeric'],
            'exercises.*.sets.*.calories' => ['required', 'numeric'],
            'exercises.*.sets.*.minutes' => ['required', 'numeric'],
        ];
    }
}
