<template>
  <div class="search-container surface-blur p-4">
    <!-- Pestañas de navegación -->
    <div class="search-tabs mb-4">
      <div class="tab-button" :class="{'active': activeTab === 'food'}" @click="switchTab('food')">
        <i class="bi bi-journal-richtext"></i>
        <span>Alimentos y Recetas</span>
      </div>
      <div class="tab-button" :class="{'active': activeTab === 'exercise'}" @click="switchTab('exercise')">
        <i class="bi bi-activity"></i>
        <span>Ejercicios</span>
      </div>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="tab-content">
      <!-- Tab de Alimentos -->
      <div v-if="activeTab === 'food'" class="tab-pane fade show active">
        <food-search></food-search>
      </div>
      
      <!-- Tab de Ejercicios -->
      <div v-if="activeTab === 'exercise'" class="tab-pane fade show active">
        <exercise-search></exercise-search>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SearchContainer',
  data() {
    return {
      activeTab: 'food' // Inicialmente mostrar alimentos
    };
  },
  methods: {
    switchTab(tabId) {
      this.activeTab = tabId;
    }
  },
  mounted() {
    // Detectar si hay un parámetro de tab en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'exercise') {
      this.activeTab = 'exercise';
    }
  }
}
</script>

<style scoped>
.search-container {
  border-radius: var(--cn-radius);
  margin-bottom: 2rem;
  box-shadow: var(--cn-shadow);
  position: relative;
  z-index: 10;
  transition: var(--cn-transition);
  border: 1px solid rgba(255,255,255,.6);
}

.search-tabs {
  display: flex;
  gap: 1rem;
  border-bottom: 1px solid rgba(var(--cn-primary-rgb), 0.1);
  padding-bottom: 1rem;
  margin-bottom: 1.5rem;
}

.tab-button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 0.75rem 1.5rem;
  border-radius: var(--cn-radius-sm);
  font-weight: 500;
  cursor: pointer;
  transition: var(--cn-transition);
  background: rgba(255,255,255,0.5);
  color: var(--cn-dark);
  border: 1px solid transparent;
}

.tab-button i {
  font-size: 1.1rem;
}

.tab-button:hover {
  background: rgba(var(--cn-primary-rgb), 0.08);
  transform: translateY(-2px);
}

.tab-button.active {
  background: var(--cn-primary);
  color: white;
  box-shadow: 0 4px 12px -2px rgba(var(--cn-primary-rgb), 0.3);
  transform: translateY(-2px);
}

.tab-pane {
  animation: fadeIn 0.3s ease-out;
}

/* Animaciones */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Media queries para adaptarse a distintos dispositivos */
@media (max-width: 768px) {
  .search-tabs {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .tab-button {
    padding: 0.65rem 1rem;
    justify-content: center;
  }
}
</style>
