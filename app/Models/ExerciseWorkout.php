<?php

namespace App\Models;

use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseWorkout extends Pivot
{
    use HasFactory;

    protected $fillable = ['exercise_id', 'workout_id', 'position', 'note'];

    public function workout()
    {
        return $this->belongsTo(Workout::class, 'workout_id');
    }

    public function sets()
    {
        return $this->hasMany(ExerciseWorkoutSet::class, 'exercise_workout_id', 'id')->select(['id', 'repetitions', 'weight', 'distance', 'calories', 'minutes'])->orderBy('position');
    }
}
