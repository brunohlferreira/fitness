<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'bilateral', 'created_by', 'updated_by'];

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

        static::deleting(function ($model) {
            $model->bodyParts()->detach();
            $model->equipments()->detach();
        });
    }

    public function bodyParts()
    {
        return $this->belongsToMany(BodyPart::class);
    }

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
