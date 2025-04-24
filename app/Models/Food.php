<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    
    protected $table = 'foods';

    protected $fillable = [
        'name',
        'description',
        'category',
        'calories',
        'protein',
        'carbohydrates',
        'fats',
        'ingredients',
        'preparation',
        'preparation_time',
        'servings',
        'difficulty',
        'image_url',
        'is_active'
    ];

    // Alias para usar en el frontend
    protected $appends = ['proteins', 'carbs'];

    // Getter para el alias 'proteins'
    public function getProteinsAttribute()
    {
        return $this->protein;
    }

    // Getter para el alias 'carbs'
    public function getCarbsAttribute()
    {
        return $this->carbohydrates;
    }
}