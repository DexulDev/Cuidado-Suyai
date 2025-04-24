<template>
  <div class="exercise-list">
    <h5 class="mb-3">Lista de Ejercicios</h5>
    
    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Cargando...</span>
      </div>
    </div>
    
    <div v-else-if="exercises.length === 0" class="text-center py-4">
      <p class="text-muted">No hay ejercicios disponibles.</p>
    </div>
    
    <div v-else>
      <div class="card mb-3" v-for="exercise in exercises" :key="exercise.id">
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <h5 class="card-title">{{ exercise.name }}</h5>
              <p class="card-text">{{ exercise.description }}</p>
              <div class="d-flex flex-wrap gap-2 mb-2">
                <span class="badge bg-info me-1">{{ exercise.muscle_group }}</span>
                <span class="badge" :class="getDifficultyBadgeClass(exercise.difficulty)">
                  {{ exercise.difficulty }}
                </span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="d-flex flex-column h-100 justify-content-center">
                <div class="exercise-stats">
                  <div class="stat-item">
                    <i class="bi bi-stopwatch"></i>
                    <span>{{ exercise.duration }} min</span>
                  </div>
                  <div class="stat-item">
                    <i class="bi bi-lightning"></i>
                    <span>{{ exercise.intensity }}</span>
                  </div>
                  <div class="stat-item">
                    <i class="bi bi-fire"></i>
                    <span>{{ exercise.calories_burned }} cal</span>
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
export default {
  data() {
    return {
      exercises: [],
      loading: true
    };
  },
  mounted() {
    this.fetchExercises();
  },
  methods: {
    async fetchExercises() {
      try {
        const response = await fetch('/api/exercises');
        this.exercises = await response.json();
      } catch (error) {
        console.error('Error al cargar ejercicios:', error);
        this.exercises = [];
      } finally {
        this.loading = false;
      }
    },
    getDifficultyBadgeClass(difficulty) {
      switch (difficulty) {
        case 'principiante': return 'bg-success';
        case 'intermedio': return 'bg-warning text-dark';
        case 'avanzado': return 'bg-danger';
        default: return 'bg-secondary';
      }
    }
  }
};
</script>

<style scoped>
.card {
  background-color: rgba(255, 255, 255, 0.8);
}

.card-body {
  background-color: transparent;
}

.exercise-stats {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.stat-item i {
  font-size: 1.2rem;
  color: #3182ce;
}
</style>