<template>
  <div class="newen-container">
    <div class="container">
      <div class="search-container">
        <div class="tab-content p-4 rounded-3 shadow-sm bg-white" id="searchTabsContent">
          <div v-if="activeTab === 'food'" role="tabpanel">
            <food-search />
          </div>
          <div v-else role="tabpanel">
            <exercise-search />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FoodSearch from './foods/FoodSearch.vue';
import ExerciseSearch from './exercises/ExerciseSearch.vue';

export default {
  name: 'App',
  components: {
    FoodSearch,
    ExerciseSearch
  },
  data() {
    return {
      activeTab: window.location.pathname.includes('exercises') ? 'exercise' : 'food'
    }
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css");

:root {
  --cn-primary: #8B0000;
  --cn-secondary: #A52A2A;
  --cn-accent: #FFC107;
  --cn-light: #F5DEB3;
  --cn-darklight: #d3bf9a;
  --cn-dark: #000000;
  --cn-white: #FFFFFF;
}

body {
  font-family: 'Poppins', sans-serif !important;
  background-color: var(--cn-light) !important;
  color: var(--cn-dark) !important;
}

.newen-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.search-container {
  max-width: 900px;
  margin: 0 auto;
}

/* Sobrescribir los estilos de Bootstrap para usar nuestra paleta */
.btn-primary {
  background-color: var(--cn-accent) !important;
  border-color: var(--cn-accent) !important;
  color: var(--cn-dark) !important;
}

.btn-primary:hover {
  background-color: #e6ac00 !important;
  border-color: #e6ac00 !important;
}

.btn-outline-primary {
  color: var(--cn-primary) !important;
  border-color: var(--cn-primary) !important;
}

.btn-outline-primary:hover {
  background-color: var(--cn-primary) !important;
  color: var(--cn-white) !important;
}

/* Cambiar el fondo de los componentes de búsqueda */
.tab-content {
  background-color: var(--cn-darklight) !important;
}

/* Asegurar que las badges de nutrición tengan colores correctos */
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

/* Asegurar escala de grises y contraste */
.card {
  background-color: #f5f2e2 !important;
}

.table {
  background-color: var(--cn-white) !important;
}

.list-group-item {
  background-color: #fafafa !important;
}

/* Spinner consistente */
.spinner-border {
  color: var(--cn-primary) !important;
} 
</style>