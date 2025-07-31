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
        'calories_per_serving',
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

    protected $casts = [
        'is_active' => 'boolean',
        'calories_per_serving' => 'integer',
        'protein' => 'integer',
        'carbohydrates' => 'integer',
        'fats' => 'integer',
        'preparation_time' => 'integer',
        'servings' => 'integer',
    ];

    // Scope para comidas activas
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors para compatibilidad con el frontend
    public function getCaloriesAttribute()
    {
        return $this->calories_per_serving;
    }

    public function getProteinsAttribute()
    {
        return $this->protein;
    }

    public function getCarbsAttribute()
    {
        return $this->carbohydrates;
    }
    
    // Método para obtener la URL de imagen usando el storage de Laravel
    public function getImagePath()
    {
        if (!$this->attributes['image_url']) {
            return null;
        }
        
        if (str_starts_with($this->attributes['image_url'], 'http')) {
            return $this->attributes['image_url'];
        }
        
        return asset('storage/foods/' . $this->attributes['image_url']);
    }

    // Accessor usando el sistema de Laravel Storage
    protected function imageUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return null;
                }
                
                if (str_starts_with($value, 'http')) {
                    return $value;
                }
                
                return asset('storage/foods/' . $value);
            }
        );
    }
}