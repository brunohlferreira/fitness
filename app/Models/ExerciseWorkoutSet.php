<?php

namespace App\Models;

use App\Models\ExerciseWorkout;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseWorkoutSet extends Model
{
    use HasFactory;

    protected $fillable = ['exercise_workout_id', 'position', 'repetitions', 'weight', 'distance', 'calories', 'minutes'];

    public $timestamps = false;

    public function exercise()
    {
        return $this->belongsTo(ExerciseWorkout::class, 'exercise_workout_id');
    }
}
