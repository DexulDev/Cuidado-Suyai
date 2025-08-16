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
            <div class="card h-100 recipe-card" @click="openFood(food.id)" style="cursor: pointer;">
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
      <div v-else-if="!hasSearched" class="text-center mt-4 py-5">
        <!-- Estado inicial con recomendaciones -->
        <div class="welcome-section">
          <div class="welcome-icon mb-4">
            <i class="bi bi-journal-richtext"></i>
          </div>
          <h3 class="welcome-title">Descubre recetas saludables</h3>
          <p class="welcome-description">Explora nuestra colección de recetas para tu bienestar diario</p>
          
          <div class="recommendations-grid mt-4">
            <div class="recommendation-card" @click="searchByCategory('desayuno')">
              <div class="recommendation-icon">
                <i class="bi bi-cup-hot"></i>
              </div>
              <span class="recommendation-label">Desayuno</span>
            </div>
            <div class="recommendation-card" @click="searchByCategory('almuerzo')">
              <div class="recommendation-icon">
                <i class="bi bi-egg-fried"></i>
              </div>
              <span class="recommendation-label">Almuerzo</span>
            </div>
            <div class="recommendation-card" @click="searchByCategory('postre')">
              <div class="recommendation-icon">
                <i class="bi bi-cake"></i>
              </div>
              <span class="recommendation-label">Postre</span>
            </div>
            <div class="recommendation-card" @click="searchByCategory('cena')">
              <div class="recommendation-icon">
                <i class="bi bi-moon"></i>
              </div>
              <span class="recommendation-label">Cena</span>
            </div>
            <div class="recommendation-card" @click="searchByCategory('snack')">
              <div class="recommendation-icon">
                <i class="bi bi-cup-straw"></i>
              </div>
              <span class="recommendation-label">Snack</span>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else-if="foods.length === 0 && hasSearched" class="text-center mt-4 py-5">
        <i class="bi bi-emoji-frown fs-1 text-muted"></i>
        <p class="mt-3 mb-4">No se encontraron recetas. Intenta con otra búsqueda.</p>
        
        <!-- Sugerencias de categorías -->
        <p class="text-muted mb-3">¿O prueba con estas opciones?</p>
        <div class="recommendations-grid mt-4" style="max-width: 500px;">
          <div class="recommendation-card" @click="searchByCategory('desayuno')">
            <div class="recommendation-icon">
              <i class="bi bi-cup-hot"></i>
            </div>
            <span class="recommendation-label">Desayuno</span>
          </div>
          <div class="recommendation-card" @click="searchByCategory('almuerzo')">
            <div class="recommendation-icon">
              <i class="bi bi-egg-fried"></i>
            </div>
            <span class="recommendation-label">Almuerzo</span>
          </div>
          <div class="recommendation-card" @click="searchByCategory('postre')">
            <div class="recommendation-icon">
              <i class="bi bi-cake"></i>
            </div>
            <span class="recommendation-label">Postre</span>
          </div>
          <div class="recommendation-card" @click="searchByCategory('cena')">
            <div class="recommendation-icon">
              <i class="bi bi-moon"></i>
            </div>
            <span class="recommendation-label">Cena</span>
          </div>
        </div>
      </div>

      <!-- Modal Detalle usando ModalPortal -->
      <ModalPortal :show="showFoodModal" @hide="closeFoodModal" size="xl">
        <template #header>
          <h5 class="modal-title">{{ activeFood?.name }}</h5>
        </template>
        
        <template #body>
          <div v-if="activeFood">
            <div v-if="activeFood.images && activeFood.images.length" id="foodImagesCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item" :class="{active: idx===0}" v-for="(img, idx) in activeFood.images" :key="img.id">
                  <img v-lazy :data-src="img.full_url" class="d-block w-100" :alt="activeFood.name" style="max-height:380px;object-fit:cover;border-radius:8px;">
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#foodImagesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#foodImagesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
              </button>
            </div>
            
            <div class="row mb-4">
              <div class="col-md-12">
                <div class="modal-badges d-flex flex-wrap gap-2 mb-3">
                  <span class="badge-pill" :class="getDifficultyBadgeClass(activeFood.difficulty)">
                    {{ activeFood.difficulty }}
                  </span>
                  <span class="badge-pill badge-primary" v-if="activeFood.preparation_time">
                    <i class="bi bi-clock me-1"></i>{{ activeFood.preparation_time }} min
                  </span>
                </div>
                
                <div class="recipe-description mb-4 surface-blur p-3 rounded">
                  <p>{{ activeFood.description }}</p>
                </div>
                
                <div class="nutrition-card surface-blur p-4 mb-4 rounded">
                  <h6 class="fw-bold mb-3">Información Nutricional</h6>
                  <div class="nutrition-grid">
                    <div class="nutrition-item-large" v-if="activeFood.calories_per_serving">
                      <div class="nutrition-icon">
                        <i class="bi bi-fire"></i>
                      </div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large">{{ activeFood.calories_per_serving }}</div>
                        <div class="nutrition-label-large">Calorías</div>
                      </div>
                    </div>
                    
                    <div class="nutrition-item-large" v-if="activeFood.protein">
                      <div class="nutrition-icon">
                        <i class="bi bi-egg-fried"></i>
                      </div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large">{{ activeFood.protein }}g</div>
                        <div class="nutrition-label-large">Proteína</div>
                      </div>
                    </div>
                    
                    <div class="nutrition-item-large" v-if="activeFood.carbohydrates">
                      <div class="nutrition-icon">
                        <i class="bi bi-reception-4"></i>
                      </div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large">{{ activeFood.carbohydrates }}g</div>
                        <div class="nutrition-label-large">Carbohidratos</div>
                      </div>
                    </div>
                    
                    <div class="nutrition-item-large" v-if="activeFood.fats">
                      <div class="nutrition-icon">
                        <i class="bi bi-droplet-half"></i>
                      </div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large">{{ activeFood.fats }}g</div>
                        <div class="nutrition-label-large">Grasas</div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div v-if="activeFood.ingredients" class="ingredients-section surface-blur p-4 rounded mb-4">
                  <h6 class="recipe-section-title">
                    <i class="bi bi-basket2 me-2"></i>Ingredientes
                  </h6>
                  <div class="section-divider mb-3" style="width: 50px; margin-top: 0.5rem;"></div>
                  <ul class="ingredients-list">
                    <li v-for="(line, idx) in splitLines(activeFood.ingredients)" :key="idx" class="ingredient-item">
                      {{ line }}
                    </li>
                  </ul>
                </div>
                
                <div v-if="activeFood.preparation" class="preparation-section surface-blur p-4 rounded">
                  <h6 class="recipe-section-title">
                    <i class="bi bi-journal-text me-2"></i>Preparación
                  </h6>
                  <div class="section-divider mb-3" style="width: 50px; margin-top: 0.5rem;"></div>
                  <ol class="preparation-list">
                    <li v-for="(line, idx) in splitLines(activeFood.preparation)" :key="idx" class="preparation-step">
                      {{ line }}
                    </li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </template>
      </ModalPortal>
    </div>  
</template>

<script>
export default {
  data() {
    return {
      searchQuery: '',
      selectedCategory: '',
      foods: [],
      loading: false,
      activeFood: null,
      showFoodModal: false,
      hasSearched: false
    };
  },
  methods: {
    async searchFoods() {
      this.loading = true;
      this.hasSearched = true;
        
      try {
        const response = await axios.get('/api/foods/search', {
          params: {
            query: this.searchQuery,
            category: this.selectedCategory
          }
        });
        
        this.foods = response.data;
        this.loading = false;
        
        // Registrar búsqueda para telemetría SOLO cuando se hace una búsqueda real
        if (this.searchQuery && this.searchQuery.trim().length > 0) {
          this.logSearch();
        }
      } catch (error) {
        console.error('Error buscando recetas:', error);
        this.loading = false;
      }
    },
    truncateText(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    },

    async openFood(id) {
      try {
        const { data } = await axios.get(`/api/foods/${id}`);
        this.activeFood = data;
        this.showFoodModal = true;
      } catch (e) { 
        console.error('Error cargando comida', e);
      }
    },

    closeFoodModal() {
      this.showFoodModal = false;
      this.activeFood = null;
    },

    searchByCategory(category) {
      this.selectedCategory = category;
      this.searchQuery = '';
      this.searchFoods();
    },

    getDifficultyBadgeClass(difficulty) {
      switch(difficulty?.toLowerCase()) {
        case 'fácil': return 'badge-success';
        case 'medio': return 'badge-warning';
        case 'difícil': return 'badge-danger';
        default: return 'badge-secondary';
      }
    },

    splitLines(text) {
      if (!text) return [];
      return text.split('\n').filter(line => line.trim() !== '');
    },

    async logSearch() {
      if (!this.searchQuery || this.searchQuery.trim().length === 0) return;
      
      try {
        await axios.post('/api/searches', {
          query: this.searchQuery.trim(),
          search_type: 'food',
          category: this.selectedCategory || null,
          results_count: this.foods.length
        });
      } catch (error) {
        console.error('Error al registrar búsqueda:', error);
      }
    }
  },
  mounted() {
    // No hacer búsqueda automática, mostrar recomendaciones
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
  background-color: #f8f9fa !important;
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
  background-color: #f8f9fa !important;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  border: none;
}

.card, .card-body {
  border-radius: 0 !important;
}

/* Modal Styles */
.modal-modern {
  z-index: 99999;
}

.modal-modern .modal-content {
  border: none;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.modal-modern .modal-header {
  border-bottom: 1px solid #dee2e6;
  padding: 1.5rem;
}

.modal-modern .modal-body {
  padding: 1.5rem;
}

.modal-modern .modal-footer {
  border-top: 1px solid #dee2e6;
  padding: 1rem 1.5rem;
}

.badge-pill {
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
}

.badge-success {
  background-color: #28a745;
  color: white;
}

.badge-warning {
  background-color: #ffc107;
  color: #212529;
}

.badge-danger {
  background-color: #dc3545;
  color: white;
}

.badge-secondary {
  background-color: #6c757d;
  color: white;
}

.badge-primary {
  background-color: var(--cn-primary);
  color: white;
}

.nutrition-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
}

.nutrition-item-large {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background-color: #f8f9fa;
  border-radius: 8px;
}

.nutrition-icon {
  font-size: 1.5rem;
  color: var(--cn-primary);
}

.nutrition-value-large {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--cn-primary);
}

.nutrition-label-large {
  font-size: 0.875rem;
  color: #6c757d;
}

.recipe-section-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--cn-primary);
  margin-bottom: 0.5rem;
}

.section-divider {
  height: 3px;
  background: linear-gradient(90deg, var(--cn-primary) 0%, var(--cn-accent) 100%);
  border-radius: 2px;
}

.ingredients-list, .preparation-list {
  margin: 0;
  padding-left: 1.5rem;
}

.ingredient-item, .preparation-step {
  margin-bottom: 0.5rem;
  line-height: 1.6;
}

.surface-blur {
  background-color: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Estilos para las recomendaciones */
.welcome-section {
  padding: 2rem;
}

.welcome-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--cn-primary), var(--cn-secondary));
  border-radius: 50%;
  color: white;
  font-size: 2rem;
  margin: 0 auto;
}

.welcome-title {
  color: var(--cn-dark);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.welcome-description {
  color: #6c757d;
  margin-bottom: 2rem;
}

.recommendations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
  max-width: 600px;
  margin: 0 auto;
}

.recommendation-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1.5rem 1rem;
  background: rgba(255, 255, 255, 0.9);
  border: 2px solid rgba(139, 0, 0, 0.1);
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.recommendation-card:hover {
  transform: translateY(-5px);
  border-color: var(--cn-primary);
  box-shadow: 0 8px 25px rgba(139, 0, 0, 0.15);
}

.recommendation-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, var(--cn-primary), var(--cn-secondary));
  border-radius: 50%;
  color: white;
  font-size: 1.5rem;
  margin-bottom: 0.75rem;
}

.recommendation-label {
  color: var(--cn-dark);
  font-weight: 500;
  font-size: 0.95rem;
}
</style>