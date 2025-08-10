# Cuidado-Suyai

---

**Cuidado-Suyai** is a search engine built with Laravel 12, designed to query data about food and exercises stored in a structured database. It was created as a personal, functional, and heartfelt gift for someone very special in my life, blending technology, health, and genuine care. 💙

---

## 🧩 Features
- Search for food recipes by category (breakfast, lunch, dinner, snacks, desserts).
- Search for exercises by muscle group (legs, arms, chest, back, abs).
- Lightweight and fast interface.
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
3. Copy `.env.example` to `.env` and configure your environment variables.  
4. Run migrations with `php artisan migrate`.  
5. Seed example data using `php artisan db:seed --class=ExerciseSeeder` and `php artisan db:seed --class=FoodSeeder`.  
6. Start the server with `php artisan serve`.  
   To access it on your local network, first run `ipconfig` to get your IPv4 address, then run:  
   `php artisan serve --host=YourIPv4Address --port=8000`  
   (The port must always be 8000; replace `YourIPv4Address` with the actual address from `ipconfig`).  

7. Start the frontend development server with `npm run dev`.  
   To expose it on your local network, make sure to uncomment the necessary lines in `vite.config.js`.

---

### 💌 For you, with all my affection  
This project is more than code; it’s a piece of me I’m giving you. Programming for someone special is sharing my world, my passions, and also my care for you. I always wanted this tool to accompany you on your journey, with the same dedication I put into building it.

---
