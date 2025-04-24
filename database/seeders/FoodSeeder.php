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
                'description' => 'Un desayuno nutritivo y energético para comenzar el día.',
                'category' => 'desayunos',
                'calories' => 320,
                'protein' => 10.5,
                'carbohydrates' => 45.2,
                'fats' => 9.3,
                'ingredients' => "1/2 taza de avena\n1 taza de leche descremada o vegetal\n1 plátano\n1/4 taza de frutos rojos\n1 cucharada de miel\nCanela en polvo\n1 cucharada de semillas de chía",
                'preparation' => "En una olla pequeña, cocina la avena con la leche a fuego medio por 5 minutos, revolviendo ocasionalmente.\nAgrega una pizca de canela y las semillas de chía.\nRetira del fuego y sirve en un bowl.\nDecora con el plátano en rodajas, frutos rojos y un poco de miel.",
                'preparation_time' => 15,
                'servings' => 1,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Tostadas de aguacate con huevo',
                'description' => 'Desayuno completo rico en proteínas y grasas saludables.',
                'category' => 'desayunos',
                'calories' => 385,
                'protein' => 15.3,
                'carbohydrates' => 28.0,
                'fats' => 24.5,
                'ingredients' => "2 rebanadas de pan integral\n1 aguacate maduro\n2 huevos\nJugo de limón\nSal y pimienta al gusto\nHojuelas de chile (opcional)",
                'preparation' => "Tuesta el pan integral.\nMachaca el aguacate y añade unas gotas de limón, sal y pimienta.\nUntalo sobre las tostadas.\nPrepara los huevos al gusto (fritos o revueltos) y colócalos encima.\nEspolvorea con hojuelas de chile si deseas algo picante.",
                'preparation_time' => 15,
                'servings' => 1,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Smoothie verde energizante',
                'description' => 'Bebida repleta de vitaminas y minerales para empezar con energía.',
                'category' => 'desayunos',
                'calories' => 240,
                'protein' => 6.2,
                'carbohydrates' => 32.5,
                'fats' => 8.7,
                'ingredients' => "1 plátano maduro\n1 puñado de espinacas frescas\n1/2 taza de piña en trozos\n1 cucharada de semillas de chía\n1 taza de leche de almendras\nHielo al gusto",
                'preparation' => "Coloca todos los ingredientes en la licuadora.\nProcesa hasta obtener una consistencia suave.\nSirve inmediatamente decorado con unas hojas de menta.",
                'preparation_time' => 10,
                'servings' => 1,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            
            // ALMUERZOS
            [
                'name' => 'Ensalada mediterránea con garbanzos',
                'description' => 'Una ensalada completa inspirada en la dieta mediterránea.',
                'category' => 'almuerzos',
                'calories' => 380,
                'protein' => 15.0,
                'carbohydrates' => 42.0,
                'fats' => 18.0,
                'ingredients' => "1 lata de garbanzos, escurridos y enjuagados\n2 tazas de lechuga romana\n1 taza de tomates cherry, cortados por la mitad\n1 pepino, en cubos\n1/2 cebolla roja, en rodajas finas\n1/4 taza de aceitunas negras\n50g de queso feta desmenuzado\n2 cucharadas de aceite de oliva\nJugo de 1/2 limón\n1 cucharadita de orégano seco\nSal y pimienta al gusto",
                'preparation' => "En un bowl grande, mezcla la lechuga, tomates, pepino y cebolla.\nAgrega los garbanzos, aceitunas y queso feta.\nEn un recipiente aparte, mezcla el aceite de oliva, jugo de limón, orégano, sal y pimienta.\nVierte el aderezo sobre la ensalada y mezcla bien.\nSirve inmediatamente.",
                'preparation_time' => 20,
                'servings' => 2,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Bowl de quinoa con pollo y verduras',
                'description' => 'Un plato equilibrado rico en proteínas y fibra.',
                'category' => 'almuerzos',
                'calories' => 450,
                'protein' => 28.5,
                'carbohydrates' => 45.0,
                'fats' => 15.7,
                'ingredients' => "1 taza de quinoa\n200g de pechuga de pollo\n1 calabacín mediano\n1 pimiento rojo\n1 zanahoria\n2 cucharadas de aceite de oliva\n1 diente de ajo picado\n1 cucharadita de comino\nSal y pimienta\nJugo de limón\nCilantro fresco picado",
                'preparation' => "Cocina la quinoa según las instrucciones del paquete.\nCorta el pollo en cubos y sazona con comino, sal y pimienta.\nCalienta una cucharada de aceite en una sartén y cocina el pollo hasta que esté dorado.\nEn otra sartén, saltea las verduras cortadas en juliana con el ajo y el aceite restante.\nMezcla la quinoa con las verduras y el pollo.\nAliña con jugo de limón y decora con cilantro fresco.",
                'preparation_time' => 30,
                'servings' => 2,
                'difficulty' => 'intermedio',
                'is_active' => true
            ],
            
            // CENAS
            [
                'name' => 'Salmón al horno con espárragos',
                'description' => 'Una cena ligera y nutritiva rica en ácidos grasos omega-3.',
                'category' => 'cenas',
                'calories' => 410,
                'protein' => 34.0,
                'carbohydrates' => 8.5,
                'fats' => 27.0,
                'ingredients' => "2 filetes de salmón (150g cada uno)\n1 manojo de espárragos\n2 cucharadas de aceite de oliva\n1 limón\n2 dientes de ajo picados\nEneldo fresco\nSal y pimienta negra",
                'preparation' => "Precalienta el horno a 200°C.\nColoca los filetes de salmón en una bandeja para hornear forrada con papel aluminio.\nCorta las partes duras de los espárragos y colócalos alrededor del salmón.\nMezcla el aceite de oliva, jugo de limón, ajo picado, sal y pimienta.\nVierte esta mezcla sobre el salmón y los espárragos.\nHornea por 15-18 minutos o hasta que el salmón esté cocido.\nDecora con eneldo fresco y rodajas de limón antes de servir.",
                'preparation_time' => 25,
                'servings' => 2,
                'difficulty' => 'intermedio',
                'is_active' => true
            ],
            [
                'name' => 'Curry de verduras con leche de coco',
                'description' => 'Un plato vegano lleno de sabor y nutrientes.',
                'category' => 'cenas',
                'calories' => 320,
                'protein' => 8.0,
                'carbohydrates' => 35.0,
                'fats' => 18.0,
                'ingredients' => "1 cebolla mediana picada\n2 dientes de ajo picados\n1 trozo de jengibre fresco rallado\n1 calabacín en cubos\n1 zanahoria en rodajas\n1 pimiento rojo en trozos\n1 taza de brócoli en floretes\n400ml de leche de coco\n2 cucharadas de pasta de curry rojo\n1 cucharada de aceite de coco\nSal al gusto\nCilantro fresco picado\nJugo de lima",
                'preparation' => "En una olla grande, calienta el aceite de coco y sofríe la cebolla hasta que esté transparente.\nAgrega el ajo y el jengibre, cocinando por un minuto más.\nAñade la pasta de curry y mezcla bien.\nIncorpora las verduras y cocina por unos minutos.\nVierte la leche de coco, reduce el fuego y deja cocinar por 15 minutos.\nSazona con sal y jugo de lima.\nSirve con arroz y decora con cilantro fresco.",
                'preparation_time' => 35,
                'servings' => 3,
                'difficulty' => 'intermedio',
                'is_active' => true
            ],
            
            // SNACKS
            [
                'name' => 'Hummus casero con crudités',
                'description' => 'Un snack saludable perfecto para satisfacer el hambre entre comidas.',
                'category' => 'snacks',
                'calories' => 250,
                'protein' => 7.0,
                'carbohydrates' => 23.0,
                'fats' => 15.0,
                'ingredients' => "1 lata de garbanzos escurridos\n2 cucharadas de tahini\n1 diente de ajo\nJugo de 1 limón\n2 cucharadas de aceite de oliva\n1/2 cucharadita de comino\nSal al gusto\nPimentón dulce para decorar\nVerduras variadas: zanahoria, apio, pepino",
                'preparation' => "Coloca los garbanzos, tahini, ajo, jugo de limón y comino en un procesador de alimentos.\nProcesa mientras agregas el aceite de oliva en un hilo fino.\nSi es necesario, añade un poco de agua para lograr una consistencia suave.\nSazona con sal al gusto.\nTransfiere a un bowl, espolvorea con pimentón y un chorrito de aceite de oliva.\nSirve con las verduras cortadas en bastones.",
                'preparation_time' => 15,
                'servings' => 4,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            
            // POSTRES
            [
                'name' => 'Pudín de chía con frutas del bosque',
                'description' => 'Un postre saludable, rico en fibra y antioxidantes.',
                'category' => 'postres',
                'calories' => 220,
                'protein' => 5.0,
                'carbohydrates' => 25.0,
                'fats' => 12.0,
                'ingredients' => "3 cucharadas de semillas de chía\n1 taza de leche de almendras\n1 cucharadita de extracto de vainilla\n1 cucharada de miel o sirope de arce\n1 taza de frutas del bosque mixtas (frescas o congeladas)\n1 cucharada de yogur griego (opcional)",
                'preparation' => "En un frasco con tapa, mezcla las semillas de chía, leche de almendras, vainilla y miel.\nCierra el frasco y agita bien para combinar todos los ingredientes.\nRefrigera por al menos 4 horas o toda la noche.\nAntes de servir, agita nuevamente y verifica la consistencia. Si está muy espeso, añade un poco más de leche.\nSirve en un vaso o bowl y decora con las frutas del bosque y una cucharada de yogur griego.",
                'preparation_time' => 10,
                'servings' => 2,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
            [
                'name' => 'Manzanas horneadas con canela',
                'description' => 'Un postre clásico bajo en calorías y sin azúcares añadidos.',
                'category' => 'postres',
                'calories' => 120,
                'protein' => 0.5,
                'carbohydrates' => 30.0,
                'fats' => 0.5,
                'ingredients' => "2 manzanas grandes\n1 cucharadita de canela\n2 cucharadas de nueces picadas\n1 cucharada de pasas\n1 cucharada de miel (opcional)\nJugo de limón",
                'preparation' => "Precalienta el horno a 180°C.\nCorta las manzanas por la mitad y retira el centro con una cucharilla.\nRocía con jugo de limón para evitar que se oscurezcan.\nMezcla las nueces, pasas y canela.\nRellena cada mitad de manzana con esta mezcla.\nSi deseas, añade un poco de miel por encima.\nColoca las manzanas en una bandeja con un poco de agua en el fondo.\nHornea por 25-30 minutos o hasta que estén tiernas.\nSirve caliente, opcionalmente con un poco de yogur natural.",
                'preparation_time' => 35,
                'servings' => 4,
                'difficulty' => 'fácil',
                'is_active' => true
            ],
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }
    }
}