<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    public function run()
    {
        DB::table('exercises')->insert([
            [
                'name' => 'Flexiones',
                'description' => 'Ejercicio básico para fortalecer la parte superior del cuerpo.',
                'muscle_group' => 'pecho',
                'difficulty' => 'intermedio',
                'duration' => 15,
                'intensity' => 'Media',
                'calories_burned' => 100,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sentadillas',
                'description' => 'Ejercicio para tren inferior que trabaja muslos y glúteos.',
                'muscle_group' => 'piernas',
                'difficulty' => 'principiante',
                'duration' => 20,
                'intensity' => 'Media',
                'calories_burned' => 150,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Plancha',
                'description' => 'Ejercicio isométrico para fortalecer el núcleo.',
                'muscle_group' => 'abdomen',
                'difficulty' => 'principiante',
                'duration' => 5,
                'intensity' => 'Alta',
                'calories_burned' => 50,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Burpees',
                'description' => 'Ejercicio de cuerpo completo que combina sentadilla, flexión y salto.',
                'muscle_group' => 'cuerpo completo',
                'difficulty' => 'avanzado',
                'duration' => 10,
                'intensity' => 'Muy alta',
                'calories_burned' => 160,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Estocadas',
                'description' => 'Ejercicio de tren inferior que trabaja piernas y glúteos.',
                'muscle_group' => 'piernas',
                'difficulty' => 'intermedio',
                'duration' => 15,
                'intensity' => 'Media',
                'calories_burned' => 120,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}