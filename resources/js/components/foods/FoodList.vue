<template>
  <div class="food-list">
    <h5 class="mb-3">Lista de Recetas</h5>
    
    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
    </div>
    
    <div v-else-if="foods.length === 0" class="text-center py-4">
      <p class="text-muted">No hay recetas disponibles.</p>
    </div>
    
    <div v-else class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Calorías</th>
            <th>Proteínas</th>
            <th>Carbohidratos</th>
            <th>Grasas</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="food in foods" :key="food.id">
            <td>{{ food.name }}</td>
            <td><span class="badge bg-secondary">{{ food.category }}</span></td>
            <td>{{ food.calories }}</td>
            <td>{{ food.protein }}g</td>
            <td>{{ food.carbohydrates }}g</td>
            <td>{{ food.fats }}g</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      foods: [],
      loading: true
    };
  },
  mounted() {
    this.fetchFoods();
  },
  methods: {
    async fetchFoods() {
      try {
        const response = await fetch('/api/foods');
        this.foods = await response.json();
      } catch (error) {
        console.error('Error al cargar recetas:', error);
        this.foods = [];
      } finally {
        this.loading = false;
      }
    }
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

.badge {
  font-weight: 500;
}

.badge.bg-secondary {
  background-color: var(--cn-accent) !important;
  color: var(--cn-dark) !important;
}

.table {
  background-color: transparent;
}

.table tbody tr:nth-child(odd) {
  background-color: rgba(245, 222, 179, 0.1);
}

.table tbody tr:hover {
  background-color: rgba(139, 0, 0, 0.03);
}

.spinner-border {
  color: var(--cn-primary) !important;
}
</style>