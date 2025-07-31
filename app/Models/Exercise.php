<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $table = 'exercises';

    protected $fillable = [
        'name',
        'description',
        'category',
        'difficulty',
        'duration',
        'calories_burned',
        'equipment',
        'muscle_group',
        'intensity',
        'image_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration' => 'integer',
        'calories_burned' => 'integer',
    ];

    // Scope para ejercicios activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
        
        return asset('storage/exercises/' . $this->attributes['image_url']);
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
                
                return asset('storage/exercises/' . $value);
            }
        );
    }
}