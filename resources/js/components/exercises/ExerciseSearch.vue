<template>
  <div class="exercise-search card shadow-sm">
    <div class="row g-3">
      <div class="col-md-8">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" class="form-control border-start-0" 
            v-model="searchQuery" 
            placeholder="Buscar ejercicios..."
            @keyup.enter="searchExercises"
            >
        </div>
      </div>
      <div class="col-md-4">
        <select class="form-select" v-model="selectedMuscleGroup" @change="searchExercises">
          <option value="">Todos los grupos musculares</option>
          <option value="piernas">Piernas</option>
          <option value="brazos">Brazos</option>
          <option value="pecho">Pecho</option>
          <option value="espalda">Espalda</option>
          <option value="abdomen">Abdomen</option>
          <option value="todo el cuerpo">Todo el cuerpo</option>
        </select>
      </div>
      <div class="col-12">
        <button @click="searchExercises" class="btn btn-primary">
          <i class="bi bi-search me-2"></i>Buscar
        </button>
      </div>
    </div>
    <div class="results-container mt-4">
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>
      
      <div v-else-if="exercises.length === 0 && !loading && !hasSearched" class="text-center py-5">
        <!-- Estado inicial con recomendaciones -->
        <div class="welcome-section">
          <div class="welcome-icon mb-4">
            <i class="bi bi-activity"></i>
          </div>
          <h3 class="welcome-title">Descubre ejercicios saludables</h3>
          <p class="welcome-description">Explora nuestra colección de ejercicios para tu bienestar diario</p>
          
          <div class="recommendations-grid mt-4">
            <div class="recommendation-card" @click="searchByMuscleGroup('piernas')">
              <div class="recommendation-icon">
                <i class="bi bi-person-walking"></i>
              </div>
              <span class="recommendation-label">Piernas</span>
            </div>
            <div class="recommendation-card" @click="searchByMuscleGroup('brazos')">
              <div class="recommendation-icon">
                <i class="bi bi-person-arms-up"></i>
              </div>
              <span class="recommendation-label">Brazos</span>
            </div>
            <div class="recommendation-card" @click="searchByMuscleGroup('abdomen')">
              <div class="recommendation-icon">
                <i class="bi bi-person-standing"></i>
              </div>
              <span class="recommendation-label">Abdomen</span>
            </div>
            <div class="recommendation-card" @click="searchByMuscleGroup('todo el cuerpo')">
              <div class="recommendation-icon">
                <i class="bi bi-person-check"></i>
              </div>
              <span class="recommendation-label">Todo el cuerpo</span>
            </div>
            <div class="recommendation-card" @click="searchByMuscleGroup('pecho')">
              <div class="recommendation-icon">
                <i class="bi bi-heart-pulse"></i>
              </div>
              <span class="recommendation-label">Pecho</span>
            </div>
            <div class="recommendation-card" @click="searchByMuscleGroup('espalda')">
              <div class="recommendation-icon">
                <i class="bi bi-person-standing-dress"></i>
              </div>
              <span class="recommendation-label">Espalda</span>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else-if="exercises.length === 0 && hasSearched" class="text-center py-5">
        <i class="bi bi-emoji-frown fs-1 text-muted"></i>
        <p class="mt-3 mb-4">No se encontraron ejercicios. Intenta con otra búsqueda.</p>
        
        <!-- Sugerencias de categorías -->
        <p class="text-muted mb-3">¿O prueba con estas opciones?</p>
        <div class="recommendations-grid mt-4" style="max-width: 400px;">
          <div class="recommendation-card" @click="searchByMuscleGroup('piernas')">
            <div class="recommendation-icon">
              <i class="bi bi-person-walking"></i>
            </div>
            <span class="recommendation-label">Piernas</span>
          </div>
          <div class="recommendation-card" @click="searchByMuscleGroup('brazos')">
            <div class="recommendation-icon">
              <i class="bi bi-person-arms-up"></i>
            </div>
            <span class="recommendation-label">Brazos</span>
          </div>
          <div class="recommendation-card" @click="searchByMuscleGroup('abdomen')">
            <div class="recommendation-icon">
              <i class="bi bi-person-standing"></i>
            </div>
            <span class="recommendation-label">Abdomen</span>
          </div>
          <div class="recommendation-card" @click="searchByMuscleGroup('todo el cuerpo')">
            <div class="recommendation-icon">
              <i class="bi bi-person-check"></i>
            </div>
            <span class="recommendation-label">Todo el cuerpo</span>
          </div>
        </div>
      </div>
      
      <div v-else class="row row-cols-1 row-cols-md-2 g-4">
        <div v-for="exercise in exercises" :key="exercise.id" class="col">
          <div class="card exercise-card h-100" @click="openExercise(exercise.id)" style="cursor: pointer;">
            <div class="row g-0 h-100">
              <div class="col-md-4" v-if="exercise.image_path || exercise.image_url">
                <div class="exercise-image-container">
                  <img :src="exercise.image_path || exercise.image_url" 
                       :alt="exercise.name"
                       class="img-fluid rounded-start">
                </div>
              </div>
              <div :class="(exercise.image_path || exercise.image_url) ? 'col-md-8' : 'col-12'">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title">{{ exercise.name }}</h5>
                    <div>
                      <span class="badge bg-category me-1" v-if="exercise.muscle_group">{{ exercise.muscle_group }}</span>
                      <span class="badge" :class="getDifficultyBadgeClass(exercise.difficulty)">
                        {{ exercise.difficulty }}
                      </span>
                    </div>
                  </div>
                  <p class="card-text">{{ truncateText(exercise.description, 120) }}</p>
                  
                  <div class="exercise-details mt-auto">
                    <div class="exercise-detail" v-if="exercise.duration">
                      <i class="bi bi-stopwatch"></i>
                      <span>{{ exercise.duration }} min</span>
                    </div>
                    <div class="exercise-detail" v-if="exercise.intensity">
                      <i class="bi bi-lightning-charge"></i>
                      <span>{{ exercise.intensity }}</span>
                    </div>
                    <div class="exercise-detail" v-if="exercise.calories_burned">
                      <i class="bi bi-fire"></i>
                      <span>~{{ exercise.calories_burned }} cal</span>
                    </div>
                    <div class="exercise-detail" v-if="exercise.equipment">
                      <i class="bi bi-tools"></i>
                      <span>{{ exercise.equipment }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Detalle usando ModalPortal -->
      <ModalPortal :show="showExerciseModal" @hide="closeExerciseModal" size="xl">
        <template #header>
          <h4 class="modal-title text-white">{{ selectedExercise?.name }}</h4>
        </template>
        
        <template #body>
          <div v-if="selectedExercise" class="exercise-modal-content">
            <!-- Imagen del ejercicio -->
            <div v-if="selectedExercise.image_path || selectedExercise.image_url" class="text-center mb-4">
              <img :src="selectedExercise.image_path || selectedExercise.image_url" 
                   :alt="selectedExercise.name"
                   class="img-fluid rounded"
                   style="max-height: 300px; object-fit: cover;">
            </div>

            <!-- Información básica -->
            <div class="row mb-4">
              <div class="col-md-6">
                <h5 class="text-primary">Información General</h5>
                <div class="exercise-info">
                  <div class="info-item" v-if="selectedExercise.muscle_group">
                    <strong>Grupo Muscular:</strong>
                    <span class="badge bg-category ms-2">{{ selectedExercise.muscle_group }}</span>
                  </div>
                  <div class="info-item" v-if="selectedExercise.difficulty">
                    <strong>Dificultad:</strong>
                    <span class="badge ms-2" :class="getDifficultyBadgeClass(selectedExercise.difficulty)">
                      {{ selectedExercise.difficulty }}
                    </span>
                  </div>
                  <div class="info-item" v-if="selectedExercise.duration">
                    <strong>Duración:</strong> {{ selectedExercise.duration }} minutos
                  </div>
                  <div class="info-item" v-if="selectedExercise.intensity">
                    <strong>Intensidad:</strong> {{ selectedExercise.intensity }}
                  </div>
                  <div class="info-item" v-if="selectedExercise.calories_burned">
                    <strong>Calorías quemadas:</strong> ~{{ selectedExercise.calories_burned }} cal
                  </div>
                  <div class="info-item" v-if="selectedExercise.equipment">
                    <strong>Equipamiento:</strong> {{ selectedExercise.equipment }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Descripción completa -->
            <div class="mb-4">
              <h5 class="text-primary">Descripción</h5>
              <p class="exercise-description">{{ selectedExercise.description }}</p>
            </div>

            <!-- Instrucciones -->
            <div v-if="selectedExercise.instructions" class="mb-4">
              <h5 class="text-primary">Instrucciones</h5>
              <div class="exercise-instructions">{{ selectedExercise.instructions }}</div>
            </div>

            <!-- Beneficios -->
            <div v-if="selectedExercise.benefits" class="mb-4">
              <h5 class="text-primary">Beneficios</h5>
              <p class="exercise-benefits">{{ selectedExercise.benefits }}</p>
            </div>

            <!-- Precauciones -->
            <div v-if="selectedExercise.precautions" class="mb-4">
              <h5 class="text-warning">Precauciones</h5>
              <p class="exercise-precautions">{{ selectedExercise.precautions }}</p>
            </div>
          </div>
        </template>
      </ModalPortal>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import ModalPortal from '../ui/ModalPortal.vue';

export default {
  name: 'ExerciseSearch',
  components: {
    ModalPortal
  },
  data() {
    return {
      searchQuery: '',
      selectedMuscleGroup: '',
      exercises: [],
      loading: false,
      selectedExercise: null,
      showExerciseModal: false,
      hasSearched: false
    };
  },
  methods: {
    searchExercises() {
      this.loading = true;
      this.hasSearched = true;
      
      axios.get('/api/exercises/search', {
        params: {
          query: this.searchQuery,
          muscle_group: this.selectedMuscleGroup
        }
      })
      .then(response => {
        this.exercises = response.data;
        this.loading = false;
      })
      .catch(error => {
        console.error('Error buscando ejercicios:', error);
        this.loading = false;
      });
    },
    truncateText(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    },
    getDifficultyBadgeClass(difficulty) {
      switch (difficulty) {
        case 'principiante': return 'bg-success';
        case 'intermedio': return 'bg-warning text-dark';
        case 'avanzado': return 'bg-danger';
        default: return 'bg-secondary';
      }
    },
    openExercise(exerciseId) {
      // Buscar el ejercicio en los resultados actuales
      this.selectedExercise = this.exercises.find(ex => ex.id === exerciseId);
      if (this.selectedExercise) {
        this.showExerciseModal = true;
      }
    },
    closeExerciseModal() {
      this.showExerciseModal = false;
    },
    searchByMuscleGroup(muscleGroup) {
      this.selectedMuscleGroup = muscleGroup;
      this.searchQuery = '';
      this.searchExercises();
    }
  },
  mounted() {
    // No hacer búsqueda automática, mostrar recomendaciones
  }
}
</script>

<style scoped>
:root {
  --cn-primary: #8B0000;
  --cn-secondary: #A52A2A;
  --cn-accent: #FFC107;
  --cn-lightaccent: #000000;
  --cn-light: #F5DEB3;
  --cn-dark: #000000;
  --cn-darklight: #333;
}

.exercise-search.card {
  background-color: #f8f9fa !important;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.card {
  background-color: #f8f9fa !important;
}

.exercise-card {
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 1rem;
}

.exercise-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.exercise-details {
  display: flex;
  gap: 15px;
  margin-top: 1rem;
}

.exercise-detail {
  display: flex;
  align-items: center;
  gap: 5px;
  color: var(--cn-dark);
  font-size: 0.9rem;
}

.exercise-detail i {
  font-size: 1rem;
  color: var(--cn-secondary);
}

.badge.bg-category {
  background-color: var(--cn-accent);
  color: var(--cn-dark);
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

.spinner-border {
  color: var(--cn-primary) !important;
}

h5.card-title {
  color: var(--cn-primary);
}

.exercise-image-container {
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--cn-light);
  overflow: hidden;
}

.exercise-image-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Estilos del modal */
.exercise-modal-content {
  font-family: 'Inter', sans-serif;
}

.exercise-info .info-item {
  margin-bottom: 0.75rem;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f0f0f0;
}

.exercise-info .info-item:last-child {
  border-bottom: none;
}

.exercise-description {
  line-height: 1.6;
  color: #555;
}

.exercise-instructions {
  background: #f8f9fa;
  padding: 1rem;
  border-radius: 8px;
  border-left: 4px solid var(--cn-primary);
  white-space: pre-line;
}

.exercise-benefits {
  background: #e8f5e8;
  padding: 1rem;
  border-radius: 8px;
  border-left: 4px solid #28a745;
}

.exercise-precautions {
  background: #fff3cd;
  padding: 1rem;
  border-radius: 8px;
  border-left: 4px solid #ffc107;
  color: #856404;
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