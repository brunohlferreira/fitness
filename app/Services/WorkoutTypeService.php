<?php

namespace App\Services;

use App\Models\WorkoutType;
use Illuminate\Database\Eloquent\Collection;

class WorkoutTypeService
{
    public function getAll(): Collection
    {
        return WorkoutType::query()->select('id', 'name', 'description')->get();
    }
}
