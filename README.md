# Cuidado-Newen

----

**Cuidado-Newen** es un motor de búsqueda desarrollado en Laravel 12, enfocado en consultar datos ya existentes sobre alimentos y ejercicios almacenados en una base de datos estructurada. Fue creado como un regalo personal y funcional para mi novio, combinando tecnología, salud y amor 💙

----

## 🧩 Funcionalidades
- Búsqueda de recetas por nombre o categoría (macros, calorías, etc.).
- Búsqueda de ejercicios según tipo, grupo muscular o dificultad.
- UI ligera y rápida.
- Datos pre-cargados para evitar dependencia externa.
----
## 🛠️ Tecnologías
- Laravel 12
- Vue 3
- MySQL
- Blade
- Bootstrap 5
----
## 🚀 Instalación
1. Clona este repositorio.
2. Ejecuta `composer install`.
3. Copia el archivo `.env.example` a `.env` y configura tu entorno.
4. Corre las migraciones con `php artisan migrate`.
5. Carga la base de datos con los datos de ejemplo con `php artisan db:seed --class=ExerciseSeeder` y `php artisan db:seed --class=FoodSeeder`.
----
6. Inicia el servidor con `php artisan serve`. Si vas a usarlo de manera local con tu red para que los demás puedan entrar usa primero `ipconfig` y luego en el `php artisan serve` agrega `--host=TuIPv4 --port=8000` (el puerto siempre es 8000) de manera que quede algo como `php artisan serve --host=XXX.XXX.X.XXX --port=8000` donde las 'X' son numeros.
----
7. Inicia el servidor de estilos usando `npm run dev`. Si vas a usarlo igual de manera local con la red par aque los demás entren, descomenta las lineas del archivo vite.config.js

---

### 💌 Dedicado a
Un regalo de código y cariño, hecho para mi novio. Programar para ti también es compartir lo que amo hacer 💙
