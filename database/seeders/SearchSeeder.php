<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Search;
use Carbon\Carbon;

class SearchSeeder extends Seeder
{
    public function run()
    {
        $searches = [
            // Búsquedas de comidas
            [
                'search_type' => 'food',
                'query' => 'avena',
                'category' => 'desayuno',
                'difficulty' => null,
                'results_count' => 3,
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'filters' => null,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'search_type' => 'food',
                'query' => 'salmón',
                'category' => 'cena',
                'difficulty' => 'intermedio',
                'results_count' => 1,
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'filters' => null,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'search_type' => 'food',
                'query' => 'vegano',
                'category' => 'almuerzo',
                'difficulty' => null,
                'results_count' => 2,
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
                'filters' => null,
                'created_at' => Carbon::now()->subHours(12),
                'updated_at' => Carbon::now()->subHours(12)
            ],

            // Búsquedas de ejercicios
            [
                'search_type' => 'exercise',
                'query' => 'flexiones',
                'category' => 'pecho',
                'difficulty' => 'principiante',
                'results_count' => 2,
                'ip_address' => '192.168.1.105',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'filters' => null,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'search_type' => 'exercise',
                'query' => 'cardio',
                'category' => null,
                'difficulty' => 'intermedio',
                'results_count' => 5,
                'ip_address' => '192.168.1.106',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15',
                'filters' => null,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'search_type' => 'exercise',
                'query' => 'burpees',
                'category' => 'todo el cuerpo',
                'difficulty' => 'avanzado',
                'results_count' => 1,
                'ip_address' => '192.168.1.107',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:92.0) Gecko/20100101',
                'filters' => null,
                'created_at' => Carbon::now()->subHours(18),
                'updated_at' => Carbon::now()->subHours(18)
            ],
            [
                'search_type' => 'exercise',
                'query' => 'abdomen',
                'category' => 'abdomen',
                'difficulty' => null,
                'results_count' => 3,
                'ip_address' => '192.168.1.108',
                'user_agent' => 'Mozilla/5.0 (iPad; CPU OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
                'filters' => null,
                'created_at' => Carbon::now()->subHours(8),
                'updated_at' => Carbon::now()->subHours(8)
            ],
            [
                'search_type' => 'exercise',
                'query' => 'yoga',
                'category' => 'flexibilidad',
                'difficulty' => 'principiante',
                'results_count' => 2,
                'ip_address' => '192.168.1.109',
                'user_agent' => 'Mozilla/5.0 (Android 12; Mobile; rv:95.0) Gecko/95.0',
                'filters' => null,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2)
            ],

            // Búsquedas sin resultados (para testing)
            [
                'search_type' => 'food',
                'query' => 'comida inexistente',
                'category' => null,
                'difficulty' => null,
                'results_count' => 0,
                'ip_address' => '192.168.1.110',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'filters' => null,
                'created_at' => Carbon::now()->subHour(),
                'updated_at' => Carbon::now()->subHour()
            ],
            [
                'search_type' => 'exercise',
                'query' => 'ejercicio inventado',
                'category' => null,
                'difficulty' => null,
                'results_count' => 0,
                'ip_address' => '192.168.1.111',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                'filters' => null,
                'created_at' => Carbon::now()->subMinutes(30),
                'updated_at' => Carbon::now()->subMinutes(30)
            ]
        ];

        foreach ($searches as $search) {
            Search::create($search);
        }
    }
}