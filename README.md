# Cuidado-Suyai

---

**Cuidado-Suyai** is a search engine developed with Laravel 12, focused on querying existing data about food and exercises stored in a structured database. It was created as a personal and functional gift for my partner's school project, blending technology, health, and love 💙

---

## 🧩 Features
- Search for food recipes by category (breakfast, lunch, dinner, snacks, desserts).
- Search for exercises by muscle group (legs, arms, chest, back, abs).
- Lightweight and fast UI.
- Preloaded data to avoid external dependencies.

---

## 🛠️ Technologies
- Laravel 12
- Vue 3
- MySQL
- Blade
- Bootstrap 5

---

## 🚀 Installation
1. Clone this repository.
2. Run `composer install`.
3. Copy the `.env.example` file to `.env` and configure your environment variables.
4. Run the migrations with `php artisan migrate`.
5. Seed the database with example data using `php artisan db:seed --class=ExerciseSeeder` and `php artisan db:seed --class=FoodSeeder`.
6. Start the server with `php artisan serve`.  
   If you want to access it locally on your network, first run `ipconfig` to get your IPv4 address, then run the server with:  
   `php artisan serve --host=YourIPv4Address --port=8000`  
   (The port must always be 8000. Replace `YourIPv4Address` with the actual numbers shown in `ipconfig`.)

7. Start the frontend development server with `npm run dev`.  
   If you want to expose it to others on your local network, make sure to uncomment the corresponding lines in the `vite.config.js` file.

---

### 💌 Dedicated to
A gift of code and affection, made for my boyfriend.  
Programming for you is also sharing what I love to do 💙
