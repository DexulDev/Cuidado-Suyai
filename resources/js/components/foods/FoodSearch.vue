<template>
  <div class="food-search card shadow-sm">
      <div class="row g-3">
        <div class="col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
              <i class="bi bi-search"></i>
            </span>
            <input
              type="text"
              class="form-control border-start-0"
              v-model="searchQuery"
              @keyup.enter="searchFoods"
              placeholder="Buscar por nombre de alimento/receta..."
            />
          </div>
        </div>
        <div class="col-md-4">
          <select class="form-select" v-model="selectedCategory" @change="searchFoods">
            <option value="">Todas las categorías</option>
            <option value="desayuno">Desayuno</option>
            <option value="almuerzo">Almuerzo</option>
            <option value="cena">Cena</option>
            <option value="snack">Snack</option>
            <option value="postre">Postre</option>
          </select>
        </div>
        <div class="col-12">
          <button @click="searchFoods" class="btn btn-primary">
            <i class="bi bi-search me-2"></i>Buscar
          </button>
        </div>
      </div>

      <div class="mt-4" v-if="loading">
        <div class="d-flex justify-content-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
        </div>
      </div>

      <div class="mt-4" v-else-if="foods.length">
        <div class="row row-cols-1 row-cols-md-2 g-4">
          <div v-for="food in foods" :key="food.id" class="col">
            <div class="card h-100 recipe-card">
              <div class="row g-0 h-100">
                <div class="col-md-4">
                  <div class="recipe-image-container">
                    <img v-if="food.image_path || food.image_url" 
                         :src="food.image_path || food.image_url" 
                         :alt="food.name"
                         class="img-fluid rounded-start">
                    <div v-else class="recipe-placeholder">
                      <i class="bi bi-image"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="card-body d-flex flex-column h-100">
                    <div>
                      <h5 class="card-title">{{ food.name }}</h5>
                      <div class="recipe-meta mb-2">
                        <span class="badge bg-category me-1">{{ food.category }}</span>
                        <span class="badge bg-warning">{{ food.difficulty }}</span>
                      </div>
                      <p class="card-text small">{{ truncateText(food.description, 100) }}</p>
                    </div>
                    
                    <div class="mt-auto">
                      <div class="nutrition-info">
                        <div class="nutrition-item" v-if="food.calories_per_serving">
                          <span class="nutrition-value">{{ food.calories_per_serving }}</span>
                          <span class="nutrition-label">kcal</span>
                        </div>
                        <div class="nutrition-item" v-if="food.protein">
                          <span class="nutrition-value">{{ food.protein }}g</span>
                          <span class="nutrition-label">Prot.</span>
                        </div>
                        <div class="nutrition-item" v-if="food.carbohydrates">
                          <span class="nutrition-value">{{ food.carbohydrates }}g</span>
                          <span class="nutrition-label">Carb.</span>
                        </div>
                        <div class="nutrition-item" v-if="food.fats">
                          <span class="nutrition-value">{{ food.fats }}g</span>
                          <span class="nutrition-label">Grasas</span>
                        </div>
                      </div>
                      
                      <div class="recipe-meta mt-2" v-if="food.preparation_time">
                        <div class="recipe-meta-item">
                          <i class="bi bi-clock"></i>
                          <span>{{ food.preparation_time }} min</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-center mt-4 py-5">
        <i class="bi bi-emoji-frown fs-1 text-muted"></i>
        <p class="mt-3">No se encontraron recetas. Intenta con otra búsqueda.</p>
      </div>
    </div>  
</template>

<script>
export default {
  data() {
    return {
      searchQuery: '',
      selectedCategory: '',
      foods: [],
      loading: false
    };
  },
  methods: {
    async searchFoods() {
      this.loading = true;
        
      axios.get('/api/foods/search', {
        params: {
          query: this.searchQuery,
          category: this.selectedCategory
        }
      })
      .then(response => {
        this.foods = response.data;
        this.loading = false;
      })
      .catch(error => {
        console.error('Error buscando recetas:', error);
        this.loading = false;
      });
    },
    truncateText(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    }
  },
  mounted() {
    this.searchFoods();
  }
};
</script>

<style scoped>
:root {
  --cn-primary: #8B0000;
  --cn-secondary: #A52A2A;
  --cn-accent: #FFC107;
  --cn-light: #F5DEB3;
  --cn-dark: #000000;
  --cn-white: #FFFFFF;
}

.food-search.card {
  background-color: #000000 !important;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.recipe-card {
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 1.5rem;
  overflow: hidden;
}

.recipe-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.badge.bg-primary {
  background-color: var(--cn-primary) !important;
  color: var(--cn-white) !important;
}

.badge.bg-success {
  background-color: var(--cn-accent) !important;
  color: var(--cn-dark) !important;
}

.badge.bg-warning {
  background-color: var(--cn-secondary) !important;
  color: var(--cn-white) !important;
}

.recipe-image-container {
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--cn-light);
  overflow: hidden;
}

.recipe-image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.recipe-placeholder {
  font-size: 3rem;
  color: #d3c7a7;
}

.nutrition-info {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 1rem;
}

.nutrition-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: var(--cn-light);
  padding: 8px 12px;
  border-radius: 8px;
  min-width: 70px;
}

.nutrition-value {
  font-weight: 600;
  color: var(--cn-secondary);
}

.nutrition-label {
  font-size: 0.75rem;
  color: var(--cn-dark);
}

.badge.bg-category {
  background-color: var(--cn-accent);
  color: var(--cn-dark);
}

.recipe-meta {
  display: flex;
  gap: 15px;
}

.recipe-meta-item {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 0.85rem;
  color: var(--cn-dark);
}

.recipe-meta-item i {
  font-size: 0.9rem;
  color: var(--cn-secondary);
}

.recipe-details {
  border-top: 1px solid var(--cn-light);
  padding-top: 15px;
}

.ingredient-list, .preparation-list {
  padding-left: 1.5rem;
}

.ingredient-list li, .preparation-list li {
  margin-bottom: 0.5rem;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.form-control:focus, .form-select:focus {
  border-color: var(--cn-darklight) !important;
  box-shadow: none !important;
  outline: none !important;
}

.input-group {
  position: relative;
}

.input-group:focus-within::after {
  content: '';
  position: absolute;
  top: -4px;
  left: -4px;
  right: -4px;
  bottom: -4px;
  border-radius: 6px;
  box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.164);
  pointer-events: none;
  z-index: -1;
}

.input-group:focus-within .input-group-text,
.input-group:focus-within .form-control {
  border-color: var(--cn-darklight) !important;
}

.input-group-text {
  color: var(--cn-primary);
}

.btn-outline-primary {
  color: var(--cn-primary) !important;
  border-color: var(--cn-primary) !important;
}

.btn-outline-primary:hover {
  background-color: var(--cn-primary) !important;
  color: var(--cn-white) !important;
}

.spinner-border {
  color: var(--cn-primary) !important;
}

.food-search.card {
  background-color: #d3bf9a !important;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  border: none;
}

.card, .card-body {
  border-radius: 0 !important;
}
</style>