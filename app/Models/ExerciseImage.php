<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExerciseImage extends Model
{
    use HasFactory;

    protected $fillable = ['exercise_id', 'path', 'position'];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
