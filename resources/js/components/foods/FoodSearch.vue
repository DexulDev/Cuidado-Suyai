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
            <option value="desayunos">Desayunos</option>
            <option value="almuerzos">Almuerzos</option>
            <option value="cenas">Cenas</option>
            <option value="snacks">Snacks</option>
            <option value="postres">Postres</option>
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
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">{{ food.name }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ food.calories }} calorías</h6>
                <p class="card-text">
                  <span class="badge bg-primary me-2">Proteínas: {{ food.protein }}g</span>
                  <span class="badge bg-success me-2">Carbohidratos: {{ food.carbohydrates }}g</span>
                  <span class="badge bg-warning">Grasas: {{ food.fats }}g</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <p v-else class="text-center mt-4">No se encontraron recetas.</p>
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
  height: 100%;
  min-height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--cn-light);
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