<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodImage extends Model
{
    use HasFactory;

    protected $fillable = ['food_id', 'path', 'position'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
