<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'bilateral', 'created_by', 'updated_by'];

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
