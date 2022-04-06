<?php

namespace App\Models;

use App\Models\ExerciseWorkoutPresetSet;
use App\Models\WorkoutPreset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseWorkoutPreset extends Pivot
{
    use HasFactory;

    protected $fillable = ['exercise_id', 'workout_preset_id', 'position', 'note'];

    public function workout()
    {
        return $this->belongsTo(WorkoutPreset::class, 'workout_preset_id');
    }

    public function sets()
    {
        return $this->hasMany(ExerciseWorkoutPresetSet::class, 'exercise_workout_preset_id', 'id')->select(['id', 'repetitions', 'weight', 'distance', 'calories', 'minutes'])->orderBy('position');
    }
}
