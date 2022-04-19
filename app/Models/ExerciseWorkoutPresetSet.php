<?php

namespace App\Models;

use App\Models\ExerciseWorkoutPreset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseWorkoutPresetSet extends Model
{
    use HasFactory;

    protected $fillable = ['exercise_workout_preset_id', 'position', 'repetitions', 'weight', 'distance', 'calories', 'minutes'];

    public $timestamps = false;

    public function exercise()
    {
        return $this->belongsTo(ExerciseWorkoutPreset::class, 'exercise_workout_preset_id');
    }
}
