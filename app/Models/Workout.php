<?php

namespace App\Models;

use App\Models\WorkoutType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'date', 'level', 'time_cap', 'score', 'workout_type_id', 'workout_id', 'created_by'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (is_null(Auth::user())) {
                return;
            }

            $model->created_by = Auth::user()->id;
        });
    }

    public function type()
    {
        return $this->belongsTo(WorkoutType::class, 'workout_type_id', 'id');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withPivot('id', 'note')->withTimestamps()->orderBy('position');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
