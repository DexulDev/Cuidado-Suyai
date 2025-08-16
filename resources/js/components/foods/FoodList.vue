<template>
  <div class="food-list">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h5 class="mb-0 fw-bold">Recetas Saludables</h5>
      <div class="d-flex align-items-center gap-2">
        <button 
          class="btn-view-toggle" 
          :class="{ active: viewMode === 'grid' }" 
          @click="viewMode = 'grid'" 
          aria-label="Vista de cuadrícula"
        >
          <i class="bi bi-grid-3x3-gap-fill"></i>
        </button>
        <button 
          class="btn-view-toggle" 
          :class="{ active: viewMode === 'list' }" 
          @click="viewMode = 'list'" 
          aria-label="Vista de lista"
        >
          <i class="bi bi-list-ul"></i>
        </button>
      </div>
    </div>
    
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <p class="mt-3 text-muted">Cargando recetas...</p>
    </div>
    
    <div v-else-if="foods.length === 0" class="empty-state py-5">
      <div class="text-center">
        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
        <h6 class="fw-bold">No hay recetas disponibles</h6>
        <p class="text-muted">Intenta con otra búsqueda o vuelve más tarde.</p>
      </div>
    </div>
    
    <!-- Grid View -->
    <div v-else-if="viewMode === 'grid'" class="row g-4">
      <div v-for="food in foods" :key="food.id" class="col-12 col-md-6 col-lg-4">
        <s-card :hover="true" class="h-100 food-card">
          <template v-if="food.image_url" #image>
            <div class="food-card-image">
              <img v-lazy :data-src="food.image_url" :alt="food.name" />
            </div>
          </template>
          <template #title>
            <div class="d-flex justify-content-between align-items-start">
              <span>{{ food.name }}</span>
            </div>
          </template>
          <template #subtitle>
            <div class="d-flex align-items-center gap-2 mb-2">
              <s-badge variant="accent" pill>{{ food.category }}</s-badge>
              <span class="calories-badge">
                <i class="bi bi-fire me-1"></i> {{ food.calories }} cal
              </span>
            </div>
          </template>
          
          <div class="food-stats">
            <div class="food-stat-item">
              <div class="food-stat-label">Proteínas</div>
              <div class="food-stat-value">{{ food.protein }}g</div>
              <div class="food-stat-bar">
                <div class="food-stat-progress" :style="{ width: `${Math.min(food.protein * 2, 100)}%`, backgroundColor: 'var(--cn-info)' }"></div>
              </div>
            </div>
            <div class="food-stat-item">
              <div class="food-stat-label">Carbohidratos</div>
              <div class="food-stat-value">{{ food.carbohydrates }}g</div>
              <div class="food-stat-bar">
                <div class="food-stat-progress" :style="{ width: `${Math.min(food.carbohydrates / 2, 100)}%`, backgroundColor: 'var(--cn-accent)' }"></div>
              </div>
            </div>
            <div class="food-stat-item">
              <div class="food-stat-label">Grasas</div>
              <div class="food-stat-value">{{ food.fats }}g</div>
              <div class="food-stat-bar">
                <div class="food-stat-progress" :style="{ width: `${Math.min(food.fats * 3, 100)}%`, backgroundColor: 'var(--cn-secondary)' }"></div>
              </div>
            </div>
          </div>
          
          <template #footer>
            <div class="d-flex justify-content-end">
              <s-button variant="outline-primary" size="sm">
                <template #icon><i class="bi bi-eye"></i></template>
                Ver detalles
              </s-button>
            </div>
          </template>
        </s-card>
      </div>
    </div>
    
    <!-- List View -->
    <div v-else class="list-view">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Calorías</th>
              <th>Proteínas</th>
              <th>Carbohidratos</th>
              <th>Grasas</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="food in foods" :key="food.id">
              <td class="fw-medium">{{ food.name }}</td>
              <td><s-badge variant="accent" pill>{{ food.category }}</s-badge></td>
              <td>{{ food.calories }} cal</td>
              <td>{{ food.protein }}g</td>
              <td>{{ food.carbohydrates }}g</td>
              <td>{{ food.fats }}g</td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      foods: [],
      loading: true,
      viewMode: 'grid' // 'grid' or 'list'
    };
  },
  mounted() {
    this.fetchFoods();
  },
  methods: {
    async fetchFoods() {
      try {
        const response = await fetch('/api/foods');
        const data = await response.json();
        
        // Add placeholder image URLs if none exist
        this.foods = data.map(food => ({
          ...food,
          image_url: food.image_url || `https://source.unsplash.com/300x200/?food,${food.category.toLowerCase()},healthy`
        }));
      } catch (error) {
        console.error('Error al cargar recetas:', error);
        this.foods = [];
        window.emitToast('Error al cargar las recetas', 'error');
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.food-list {
  position: relative;
}

.btn-view-toggle {
  background: transparent;
  border: none;
  border-radius: var(--cn-radius-sm);
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--cn-dark-soft);
  transition: var(--cn-transition);
  cursor: pointer;
}

.btn-view-toggle.active {
  background-color: var(--cn-primary);
  color: white;
}

.btn-view-toggle:hover:not(.active) {
  background-color: rgba(var(--cn-primary-rgb), 0.1);
}

.food-card-image {
  height: 180px;
  width: 100%;
  overflow: hidden;
}

.food-card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.food-card:hover .food-card-image img {
  transform: scale(1.05);
}

.calories-badge {
  display: inline-flex;
  align-items: center;
  font-size: 0.75rem;
  color: var(--cn-dark-soft);
  background-color: var(--cn-gray-100);
  padding: 0.35em 0.65em;
  border-radius: 50rem;
}

.food-stats {
  margin-top: 1rem;
}

.food-stat-item {
  margin-bottom: 0.85rem;
}

.food-stat-label {
  font-size: 0.8rem;
  color: var(--cn-dark-soft);
  margin-bottom: 0.2rem;
  display: flex;
  justify-content: space-between;
}

.food-stat-value {
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--cn-dark);
  position: absolute;
  right: 0;
  margin-top: -20px;
}

.food-stat-bar {
  height: 6px;
  background-color: var(--cn-gray-200);
  border-radius: 3px;
  overflow: hidden;
}

.food-stat-progress {
  height: 100%;
  border-radius: 3px;
}

.empty-state {
  display: flex;
  align-items: center;
  justify-content: center;
}

.empty-state i {
  opacity: 0.6;
}

/* List view styling */
.list-view .table {
  background-color: transparent;
  margin-bottom: 0;
}

.list-view .table thead th {
  border-bottom: 2px solid rgba(var(--cn-primary-rgb), 0.1);
  background-color: rgba(var(--cn-primary-rgb), 0.03);
  color: var(--cn-dark-soft);
  font-weight: 600;
  padding: 0.85rem 1rem;
}

.list-view .table tbody tr {
  transition: var(--cn-transition);
  border-bottom: 1px solid var(--cn-gray-200);
}

.list-view .table tbody tr:hover {
  background-color: rgba(var(--cn-primary-rgb), 0.03);
}

.list-view .table td {
  padding: 0.85rem 1rem;
  vertical-align: middle;
}

.spinner-border {
  color: var(--cn-primary);
  width: 3rem;
  height: 3rem;
}

@media (max-width: 768px) {
  .food-card-image {
    height: 160px;
  }
  
  .table th, .table td {
    padding: 0.5rem;
  }
}

@media (max-width: 576px) {
  .viewMode-buttons {
    display: none;
  }
}
</style>