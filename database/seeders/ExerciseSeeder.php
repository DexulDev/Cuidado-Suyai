<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run()
    {
        $exercises = [
            // EJERCICIOS PRINCIPIANTES
            [
                'name' => 'Caminata ligera',
                'description' => 'Ejercicio cardiovascular de bajo impacto, perfecto para empezar una rutina de ejercicios. Mejora la resistencia cardiovascular y fortalece las piernas gradualmente.',
                'category' => 'cardio',
                'muscle_group' => 'todo el cuerpo',
                'difficulty' => 'principiante',
                'duration' => 30,
                'intensity' => 'baja',
                'calories_burned' => 120,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Flexiones de rodillas',
                'description' => 'Variación modificada de las flexiones tradicionales, ideal para desarrollar fuerza en la parte superior del cuerpo. Se apoyan las rodillas en el suelo para reducir la carga.',
                'category' => 'fuerza',
                'muscle_group' => 'pecho',
                'difficulty' => 'principiante',
                'duration' => 10,
                'intensity' => 'moderada',
                'calories_burned' => 40,
                'equipment' => 'colchoneta',
                'is_active' => true
            ],
            [
                'name' => 'Sentadillas básicas',
                'description' => 'Ejercicio fundamental para fortalecer las piernas y glúteos. Movimiento natural que imita el acto de sentarse, excelente para principiantes.',
                'category' => 'fuerza',
                'muscle_group' => 'piernas',
                'difficulty' => 'principiante',
                'duration' => 15,
                'intensity' => 'moderada',
                'calories_burned' => 60,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Plancha estática',
                'description' => 'Ejercicio isométrico que fortalece el core, mejora la postura y desarrolla estabilidad. Manteniendo la posición se trabaja todo el núcleo corporal.',
                'category' => 'fuerza',
                'muscle_group' => 'abdomen',
                'difficulty' => 'principiante',
                'duration' => 5,
                'intensity' => 'moderada',
                'calories_burned' => 25,
                'equipment' => 'colchoneta',
                'is_active' => true
            ],
            [
                'name' => 'Estiramiento básico',
                'description' => 'Rutina de estiramientos suaves para mejorar la flexibilidad y reducir la tensión muscular. Ideal para comenzar o terminar una sesión de ejercicios.',
                'category' => 'flexibilidad',
                'muscle_group' => 'todo el cuerpo',
                'difficulty' => 'principiante',
                'duration' => 20,
                'intensity' => 'baja',
                'calories_burned' => 30,
                'equipment' => 'colchoneta',
                'is_active' => true
            ],

            // EJERCICIOS INTERMEDIOS
            [
                'name' => 'Flexiones tradicionales',
                'description' => 'Ejercicio clásico para desarrollar fuerza en pecho, hombros y tríceps. Versión completa apoyando solo manos y pies, trabajando múltiples grupos musculares.',
                'category' => 'fuerza',
                'muscle_group' => 'pecho',
                'difficulty' => 'intermedio',
                'duration' => 15,
                'intensity' => 'moderada',
                'calories_burned' => 80,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Sentadillas con salto',
                'description' => 'Variación explosiva de la sentadilla que añade un componente pliométrico. Desarrolla potencia, fuerza y resistencia en las piernas.',
                'category' => 'cardio',
                'muscle_group' => 'piernas',
                'difficulty' => 'intermedio',
                'duration' => 12,
                'intensity' => 'alta',
                'calories_burned' => 100,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Estocadas alternas',
                'description' => 'Ejercicio unilateral que fortalece piernas y glúteos mientras mejora el equilibrio y la coordinación. Alterna entre pierna derecha e izquierda.',
                'category' => 'fuerza',
                'muscle_group' => 'piernas',
                'difficulty' => 'intermedio',
                'duration' => 15,
                'intensity' => 'moderada',
                'calories_burned' => 90,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Plancha lateral',
                'description' => 'Ejercicio para fortalecer los músculos laterales del core (oblicuos). Mantener la posición lateral desarrolla estabilidad y fuerza funcional.',
                'category' => 'fuerza',
                'muscle_group' => 'abdomen',
                'difficulty' => 'intermedio',
                'duration' => 8,
                'intensity' => 'moderada',
                'calories_burned' => 35,
                'equipment' => 'colchoneta',
                'is_active' => true
            ],
            [
                'name' => 'Escaladores (Mountain Climbers)',
                'description' => 'Ejercicio cardiovascular de alta intensidad que combina fuerza y cardio. Simula el movimiento de escalar, trabajando todo el cuerpo.',
                'category' => 'cardio',
                'muscle_group' => 'todo el cuerpo',
                'difficulty' => 'intermedio',
                'duration' => 10,
                'intensity' => 'alta',
                'calories_burned' => 120,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Peso muerto con mancuernas',
                'description' => 'Ejercicio compuesto que fortalece la cadena posterior: glúteos, isquiotibiales y espalda baja. Fundamental para el desarrollo de fuerza funcional.',
                'category' => 'fuerza',
                'muscle_group' => 'espalda',
                'difficulty' => 'intermedio',
                'duration' => 20,
                'intensity' => 'moderada',
                'calories_burned' => 110,
                'equipment' => 'mancuernas',
                'is_active' => true
            ],

            // EJERCICIOS AVANZADOS
            [
                'name' => 'Burpees completos',
                'description' => 'Ejercicio de cuerpo completo que combina sentadilla, flexión, salto y plancha. Uno de los ejercicios más completos y desafiantes para quemar calorías.',
                'category' => 'cardio',
                'muscle_group' => 'todo el cuerpo',
                'difficulty' => 'avanzado',
                'duration' => 10,
                'intensity' => 'muy alta',
                'calories_burned' => 180,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Flexiones con palmada',
                'description' => 'Versión pliométrica de las flexiones que requiere explosividad y coordinación. Desarrolla potencia en la parte superior del cuerpo.',
                'category' => 'fuerza',
                'muscle_group' => 'pecho',
                'difficulty' => 'avanzado',
                'duration' => 8,
                'intensity' => 'muy alta',
                'calories_burned' => 70,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Sentadilla pistol (una pierna)',
                'description' => 'Sentadilla unilateral avanzada que requiere gran fuerza, equilibrio y flexibilidad. Se ejecuta en una sola pierna manteniendo la otra extendida.',
                'category' => 'fuerza',
                'muscle_group' => 'piernas',
                'difficulty' => 'avanzado',
                'duration' => 12,
                'intensity' => 'muy alta',
                'calories_burned' => 85,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Plancha con elevación de brazos',
                'description' => 'Variación avanzada de la plancha que añade inestabilidad al levantar alternativamente los brazos. Desafía el equilibrio y la fuerza del core.',
                'category' => 'fuerza',
                'muscle_group' => 'abdomen',
                'difficulty' => 'avanzado',
                'duration' => 10,
                'intensity' => 'alta',
                'calories_burned' => 50,
                'equipment' => 'colchoneta',
                'is_active' => true
            ],
            [
                'name' => 'Dominadas',
                'description' => 'Ejercicio de tracción vertical que desarrolla fuerza en espalda, bíceps y antebrazos. Requiere levantar todo el peso corporal.',
                'category' => 'fuerza',
                'muscle_group' => 'espalda',
                'difficulty' => 'avanzado',
                'duration' => 15,
                'intensity' => 'alta',
                'calories_burned' => 90,
                'equipment' => 'barra de dominadas',
                'is_active' => true
            ],

            // EJERCICIOS DE FLEXIBILIDAD Y EQUILIBRIO
            [
                'name' => 'Yoga fluido (Vinyasa)',
                'description' => 'Secuencia de posturas de yoga conectadas con la respiración. Mejora flexibilidad, fuerza y equilibrio mientras reduce el estrés.',
                'category' => 'flexibilidad',
                'muscle_group' => 'todo el cuerpo',
                'difficulty' => 'intermedio',
                'duration' => 45,
                'intensity' => 'moderada',
                'calories_burned' => 150,
                'equipment' => 'colchoneta de yoga',
                'is_active' => true
            ],
            [
                'name' => 'Equilibrio en una pierna',
                'description' => 'Ejercicio básico de equilibrio que fortalece músculos estabilizadores y mejora la propiocepción. Base para movimientos más complejos.',
                'category' => 'equilibrio',
                'muscle_group' => 'piernas',
                'difficulty' => 'principiante',
                'duration' => 10,
                'intensity' => 'baja',
                'calories_burned' => 20,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Estiramiento de cadena posterior',
                'description' => 'Secuencia de estiramientos para relajar y elongar músculos de la parte posterior del cuerpo: espalda, glúteos e isquiotibiales.',
                'category' => 'flexibilidad',
                'muscle_group' => 'espalda',
                'difficulty' => 'principiante',
                'duration' => 15,
                'intensity' => 'baja',
                'calories_burned' => 25,
                'equipment' => 'colchoneta',
                'is_active' => true
            ],

            // EJERCICIOS DE RESISTENCIA
            [
                'name' => 'Circuito HIIT básico',
                'description' => 'Entrenamiento de intervalos de alta intensidad que alterna períodos de esfuerzo máximo con descanso. Muy efectivo para quemar grasa.',
                'category' => 'cardio',
                'muscle_group' => 'todo el cuerpo',
                'difficulty' => 'intermedio',
                'duration' => 20,
                'intensity' => 'muy alta',
                'calories_burned' => 200,
                'equipment' => 'ninguno',
                'is_active' => true
            ],
            [
                'name' => 'Saltos en tijera (Jumping Jacks)',
                'description' => 'Ejercicio cardiovascular clásico que activa todo el cuerpo. Coordinación de brazos y piernas en un movimiento rítmico y energético.',
                'category' => 'cardio',
                'muscle_group' => 'todo el cuerpo',
                'difficulty' => 'principiante',
                'duration' => 10,
                'intensity' => 'moderada',
                'calories_burned' => 70,
                'equipment' => 'ninguno',
                'is_active' => true
            ]
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
    }
}