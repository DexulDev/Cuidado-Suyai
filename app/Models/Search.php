<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    protected $fillable = [
        'search_type',
        'query',
        'category',
        'difficulty',
        'results_count',
        'ip_address',
        'user_agent',
        'filters'
    ];

    protected $casts = [
        'filters' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for food searches
    public function scopeFoodSearches($query)
    {
        return $query->where('search_type', 'food');
    }

    // Scope for exercise searches
    public function scopeExerciseSearches($query)
    {
        return $query->where('search_type', 'exercise');
    }

    // Get popular search terms
    public static function getPopularTerms($type = null, $limit = 10)
    {
        $query = static::whereNotNull('query')
            ->where('query', '!=', '');
            
        if ($type) {
            $query->where('search_type', $type);
        }
        
        return $query->selectRaw('query, COUNT(*) as count')
            ->groupBy('query')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    // Get search statistics
    public static function getStats($type = null)
    {
        $query = static::query();
        
        if ($type) {
            $query->where('search_type', $type);
        }
        
        return [
            'total_searches' => $query->count(),
            'unique_terms' => $query->whereNotNull('query')->distinct('query')->count(),
            'avg_results' => $query->avg('results_count'),
            'searches_today' => $query->whereDate('created_at', today())->count(),
        ];
    }
}
