<template>
  <div>
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
      
      <div v-else-if="exercises.length === 0" class="text-center py-5">
        <i class="bi bi-emoji-frown fs-1 text-muted"></i>
        <p class="mt-3">No se encontraron ejercicios. Intenta con otra búsqueda.</p>
      </div>
      
      <div v-else class="row row-cols-1 row-cols-md-2 g-4">
        <div v-for="exercise in exercises" :key="exercise.id" class="col">
          <div class="card exercise-card h-100">
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
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ExerciseSearch',
  data() {
    return {
      searchQuery: '',
      selectedMuscleGroup: '',
      exercises: [],
      loading: false
    };
  },
  methods: {
    searchExercises() {
      this.loading = true;
      
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
    }
  },
  mounted() {
    this.searchExercises();
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
  --cn-darklight: #d3bf9a;
  --cn-dark: #000000;
  --cn-white: #FFFFFF;
}

.card {
  background-color: #f5f2e2 !important;
}

.exercise-card {
  transition: all 0.3s ease;
  border: none;
  border-left: 4px solid var(--cn-primary);
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
</style>