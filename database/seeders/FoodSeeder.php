<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Food;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foods = [
            // DESAYUNOS
            [
                'name' => 'Bowl de avena con frutas',
                'description' => 'Un desayuno nutritivo y energético para comenzar el día con avena integral, frutas frescas y semillas.',
                'category' => 'desayuno',
                'calories_per_serving' => 320,
                'protein' => 10,
                'carbohydrates' => 45,
                'fats' => 9,
                'ingredients' => "• 1/2 taza de avena integral\n• 1 taza de leche descremada o vegetal\n• 1 plátano maduro\n• 1/4 taza de frutos rojos\n• 1 cucharada de miel\n• Canela en polvo\n• 1 cucharada de semillas de chía",
                'preparation' => "1. En una olla pequeña, cocina la avena con la leche a fuego medio por 5 minutos, revolviendo ocasionalmente.\n2. Agrega una pizca de canela y las semillas de chía.\n3. Retira del fuego y sirve en un bowl.\n4. Decora con el plátano en rodajas, frutos rojos y un poco de miel.",
                'preparation_time' => 15,
                'servings' => 1,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Tostadas de aguacate con huevo',
                'description' => 'Desayuno completo rico en proteínas y grasas saludables, perfecto para empezar el día con energía.',
                'category' => 'desayuno',
                'calories_per_serving' => 385,
                'protein' => 15,
                'carbohydrates' => 28,
                'fats' => 25,
                'ingredients' => "• 2 rebanadas de pan integral\n• 1 aguacate maduro\n• 2 huevos\n• Jugo de limón\n• Sal y pimienta al gusto\n• Hojuelas de chile (opcional)",
                'preparation' => "1. Tuesta el pan integral hasta que esté dorado.\n2. Machaca el aguacate y añade unas gotas de limón, sal y pimienta.\n3. Unta el aguacate sobre las tostadas.\n4. Prepara los huevos al gusto y colócalos encima.\n5. Espolvorea con hojuelas de chile si deseas.",
                'preparation_time' => 15,
                'servings' => 1,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Smoothie verde energizante',
                'description' => 'Bebida repleta de vitaminas y minerales para empezar con energía, rica en antioxidantes.',
                'category' => 'desayuno',
                'calories_per_serving' => 240,
                'protein' => 6,
                'carbohydrates' => 33,
                'fats' => 9,
                'ingredients' => "• 1 plátano maduro\n• 1 puñado de espinacas frescas\n• 1/2 taza de piña en trozos\n• 1 cucharada de semillas de chía\n• 1 taza de leche de almendras\n• Hielo al gusto",
                'preparation' => "1. Coloca todos los ingredientes en la licuadora.\n2. Procesa hasta obtener una consistencia suave.\n3. Sirve inmediatamente decorado con unas hojas de menta.",
                'preparation_time' => 10,
                'servings' => 1,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            
            // ALMUERZOS
            [
                'name' => 'Ensalada mediterránea con garbanzos',
                'description' => 'Una ensalada completa inspirada en la dieta mediterránea, rica en fibra y proteínas vegetales.',
                'category' => 'almuerzo',
                'calories_per_serving' => 380,
                'protein' => 15,
                'carbohydrates' => 42,
                'fats' => 18,
                'ingredients' => "• 1 lata de garbanzos escurridos\n• 2 tazas de lechuga romana\n• 1 taza de tomates cherry\n• 1 pepino en cubos\n• 1/2 cebolla roja\n• 1/4 taza de aceitunas negras\n• 50g de queso feta\n• 2 cucharadas de aceite de oliva\n• Jugo de limón\n• Orégano seco",
                'preparation' => "1. En un bowl grande, mezcla la lechuga, tomates, pepino y cebolla.\n2. Agrega los garbanzos, aceitunas y queso feta.\n3. Prepara el aderezo mezclando aceite, limón y orégano.\n4. Vierte el aderezo sobre la ensalada y mezcla bien.",
                'preparation_time' => 20,
                'servings' => 2,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Bowl de quinoa con pollo y verduras',
                'description' => 'Un plato equilibrado rico en proteínas completas y fibra, ideal para el almuerzo.',
                'category' => 'almuerzo',
                'calories_per_serving' => 450,
                'protein' => 29,
                'carbohydrates' => 45,
                'fats' => 16,
                'ingredients' => "• 1 taza de quinoa\n• 200g de pechuga de pollo\n• 1 calabacín mediano\n• 1 pimiento rojo\n• 1 zanahoria\n• 2 cucharadas de aceite de oliva\n• 1 diente de ajo\n• Comino, sal y pimienta\n• Cilantro fresco",
                'preparation' => "1. Cocina la quinoa según las instrucciones del paquete.\n2. Corta el pollo en cubos y sazona con especias.\n3. Cocina el pollo hasta que esté dorado.\n4. Saltea las verduras con ajo y aceite.\n5. Mezcla todo y decora con cilantro.",
                'preparation_time' => 30,
                'servings' => 2,
                'difficulty' => 'intermedio',
                'is_active' => true
            ],
            [
                'name' => 'Pasta integral con pesto y tomates',
                'description' => 'Pasta saludable con pesto casero y tomates frescos, rica en carbohidratos complejos.',
                'category' => 'almuerzo',
                'calories_per_serving' => 420,
                'protein' => 12,
                'carbohydrates' => 55,
                'fats' => 18,
                'ingredients' => "• 200g de pasta integral\n• 2 tazas de albahaca fresca\n• 1/4 taza de piñones\n• 2 dientes de ajo\n• 1/4 taza de aceite de oliva\n• 50g de parmesano\n• 1 taza de tomates cherry\n• Sal y pimienta",
                'preparation' => "1. Cocina la pasta según las instrucciones.\n2. Para el pesto: licúa albahaca, piñones, ajo y aceite.\n3. Agrega el parmesano rallado al pesto.\n4. Mezcla la pasta con el pesto y tomates.\n5. Sirve inmediatamente.",
                'preparation_time' => 25,
                'servings' => 3,
                'difficulty' => 'intermedio',
                'is_active' => true
            ],
            
            // CENAS
            [
                'name' => 'Salmón al horno con espárragos',
                'description' => 'Una cena ligera y nutritiva rica en ácidos grasos omega-3 y proteínas de alta calidad.',
                'category' => 'cena',
                'calories_per_serving' => 410,
                'protein' => 34,
                'carbohydrates' => 9,
                'fats' => 27,
                'ingredients' => "• 2 filetes de salmón (150g c/u)\n• 1 manojo de espárragos\n• 2 cucharadas de aceite de oliva\n• 1 limón\n• 2 dientes de ajo\n• Eneldo fresco\n• Sal y pimienta",
                'preparation' => "1. Precalienta el horno a 200°C.\n2. Coloca el salmón en una bandeja forrada.\n3. Acomoda los espárragos alrededor.\n4. Mezcla aceite, limón, ajo y especias.\n5. Hornea 15-18 minutos hasta que esté cocido.",
                'preparation_time' => 25,
                'servings' => 2,
                'difficulty' => 'intermedio',
                'is_active' => true
            ],
            [
                'name' => 'Curry de verduras con leche de coco',
                'description' => 'Un plato vegano lleno de sabor y nutrientes, rico en verduras y especias aromáticas.',
                'category' => 'cena',
                'calories_per_serving' => 320,
                'protein' => 8,
                'carbohydrates' => 35,
                'fats' => 18,
                'ingredients' => "• 1 cebolla picada\n• 2 dientes de ajo\n• Jengibre fresco rallado\n• 1 calabacín\n• 1 zanahoria\n• 1 pimiento rojo\n• 1 taza de brócoli\n• 400ml leche de coco\n• 2 cucharadas pasta de curry\n• Cilantro fresco",
                'preparation' => "1. Sofríe la cebolla, ajo y jengibre.\n2. Agrega la pasta de curry y mezcla.\n3. Incorpora las verduras y cocina.\n4. Vierte la leche de coco y cocina 15 minutos.\n5. Decora con cilantro fresco.",
                'preparation_time' => 35,
                'servings' => 3,
                'difficulty' => 'intermedio',
                'is_active' => true
            ],
            [
                'name' => 'Pechuga de pollo a la plancha con verduras',
                'description' => 'Cena ligera y proteica con verduras asadas, perfecta para la noche.',
                'category' => 'cena',
                'calories_per_serving' => 350,
                'protein' => 32,
                'carbohydrates' => 15,
                'fats' => 18,
                'ingredients' => "• 2 pechugas de pollo\n• 1 calabacín\n• 1 berenjena\n• 1 pimiento amarillo\n• 2 cucharadas aceite de oliva\n• Hierbas provenzales\n• Limón\n• Sal y pimienta",
                'preparation' => "1. Sazona el pollo con hierbas y especias.\n2. Corta las verduras en trozos grandes.\n3. Cocina el pollo a la plancha 6-8 minutos por lado.\n4. Asa las verduras hasta que estén tiernas.\n5. Sirve con un toque de limón.",
                'preparation_time' => 30,
                'servings' => 2,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            
            // SNACKS
            [
                'name' => 'Hummus casero con crudités',
                'description' => 'Un snack saludable perfecto para satisfacer el hambre entre comidas, rico en proteínas vegetales.',
                'category' => 'snack',
                'calories_per_serving' => 250,
                'protein' => 7,
                'carbohydrates' => 23,
                'fats' => 15,
                'ingredients' => "• 1 lata de garbanzos\n• 2 cucharadas de tahini\n• 1 diente de ajo\n• Jugo de 1 limón\n• 2 cucharadas aceite de oliva\n• Comino\n• Pimentón\n• Verduras: zanahoria, apio, pepino",
                'preparation' => "1. Procesa garbanzos, tahini, ajo y limón.\n2. Agrega aceite de oliva gradualmente.\n3. Sazona con comino y sal.\n4. Decora con pimentón y aceite.\n5. Sirve con verduras cortadas.",
                'preparation_time' => 15,
                'servings' => 4,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Mix de frutos secos y semillas',
                'description' => 'Snack energético perfecto para llevar, rico en grasas saludables y proteínas.',
                'category' => 'snack',
                'calories_per_serving' => 180,
                'protein' => 6,
                'carbohydrates' => 8,
                'fats' => 15,
                'ingredients' => "• 1/4 taza de almendras\n• 1/4 taza de nueces\n• 2 cucharadas de semillas de girasol\n• 2 cucharadas de semillas de calabaza\n• 1 cucharada de pasas\n• Pizca de sal marina",
                'preparation' => "1. Mezcla todos los frutos secos y semillas.\n2. Agrega una pizca de sal marina.\n3. Guarda en un recipiente hermético.\n4. Consume en porciones de 30g.",
                'preparation_time' => 5,
                'servings' => 6,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            
            // POSTRES
            [
                'name' => 'Pudín de chía con frutas del bosque',
                'description' => 'Un postre saludable, rico en fibra y antioxidantes, perfecto para una opción dulce nutritiva.',
                'category' => 'postre',
                'calories_per_serving' => 220,
                'protein' => 5,
                'carbohydrates' => 25,
                'fats' => 12,
                'ingredients' => "• 3 cucharadas de semillas de chía\n• 1 taza de leche de almendras\n• 1 cucharadita de vainilla\n• 1 cucharada de miel\n• 1 taza de frutas del bosque\n• Yogur griego (opcional)",
                'preparation' => "1. Mezcla chía, leche, vainilla y miel en un frasco.\n2. Agita bien y refrigera 4 horas mínimo.\n3. Agita nuevamente antes de servir.\n4. Decora con frutas del bosque y yogur.",
                'preparation_time' => 10,
                'servings' => 2,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Manzanas horneadas con canela',
                'description' => 'Un postre clásico bajo en calorías y sin azúcares añadidos, lleno de sabor natural.',
                'category' => 'postre',
                'calories_per_serving' => 120,
                'protein' => 1,
                'carbohydrates' => 30,
                'fats' => 1,
                'ingredients' => "• 2 manzanas grandes\n• 1 cucharadita de canela\n• 2 cucharadas de nueces picadas\n• 1 cucharada de pasas\n• 1 cucharada de miel (opcional)\n• Jugo de limón",
                'preparation' => "1. Precalienta el horno a 180°C.\n2. Corta las manzanas por la mitad y retira el centro.\n3. Mezcla nueces, pasas y canela para el relleno.\n4. Rellena las manzanas y hornea 25-30 minutos.\n5. Sirve caliente con yogur natural.",
                'preparation_time' => 35,
                'servings' => 4,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Yogur griego con granola casera',
                'description' => 'Postre cremoso y crujiente, rico en probióticos y fibra.',
                'category' => 'postre',
                'calories_per_serving' => 280,
                'protein' => 15,
                'carbohydrates' => 35,
                'fats' => 10,
                'ingredients' => "• 1 taza de yogur griego natural\n• 1/4 taza de avena\n• 2 cucharadas de miel\n• 2 cucharadas de almendras laminadas\n• 1 cucharada de semillas de girasol\n• Frutas frescas variadas",
                'preparation' => "1. Mezcla avena, miel, almendras y semillas.\n2. Extiende en una bandeja y hornea 10 minutos.\n3. Deja enfriar para que quede crujiente.\n4. Sirve el yogur con granola y frutas encima.",
                'preparation_time' => 20,
                'servings' => 2,
                'difficulty' => 'fácil',
                'is_active' => true
            ]
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }
    }
}