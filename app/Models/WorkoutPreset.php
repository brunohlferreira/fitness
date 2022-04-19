<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WorkoutPreset extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'level', 'time_cap', 'workout_type_id', 'created_by', 'updated_by'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (is_null(Auth::user())) {
                return;
            }

            $model->created_by = Auth::user()->id;
        });
        static::updating(function ($model) {
            if (is_null(Auth::user())) {
                return;
            }

            $model->updated_by = Auth::user()->id;
        });
    }

    public function type()
    {
        return $this->belongsTo(WorkoutType::class, 'workout_type_id', 'id');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withPivot('id', 'note')->orderBy('position');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
