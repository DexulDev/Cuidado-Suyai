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
        
        <!-- Sugerencias de categorías (todas excepto la actual) -->
        <p class="text-muted mb-3">¿O prueba con estas opciones?</p>
        <div class="recommendations-grid mt-4" style="max-width: 600px;">
          <div v-if="selectedMuscleGroup !== 'piernas'" class="recommendation-card" @click="searchByMuscleGroup('piernas')">
            <div class="recommendation-icon">
              <i class="bi bi-person-walking"></i>
            </div>
            <span class="recommendation-label">Piernas</span>
          </div>
          <div v-if="selectedMuscleGroup !== 'brazos'" class="recommendation-card" @click="searchByMuscleGroup('brazos')">
            <div class="recommendation-icon">
              <i class="bi bi-person-arms-up"></i>
            </div>
            <span class="recommendation-label">Brazos</span>
          </div>
          <div v-if="selectedMuscleGroup !== 'abdomen'" class="recommendation-card" @click="searchByMuscleGroup('abdomen')">
            <div class="recommendation-icon">
              <i class="bi bi-person-standing"></i>
            </div>
            <span class="recommendation-label">Abdomen</span>
          </div>
          <div v-if="selectedMuscleGroup !== 'todo el cuerpo'" class="recommendation-card" @click="searchByMuscleGroup('todo el cuerpo')">
            <div class="recommendation-icon">
              <i class="bi bi-person-check"></i>
            </div>
            <span class="recommendation-label">Todo el cuerpo</span>
          </div>
          <div v-if="selectedMuscleGroup !== 'pecho'" class="recommendation-card" @click="searchByMuscleGroup('pecho')">
            <div class="recommendation-icon">
              <i class="bi bi-heart-pulse"></i>
            </div>
            <span class="recommendation-label">Pecho</span>
          </div>
          <div v-if="selectedMuscleGroup !== 'espalda'" class="recommendation-card" @click="searchByMuscleGroup('espalda')">
            <div class="recommendation-icon">
              <i class="bi bi-person-standing-dress"></i>
            </div>
            <span class="recommendation-label">Espalda</span>
          </div>
        </div>
      </div>
      
      <div v-else class="row row-cols-1 row-cols-md-2 g-4">
        <div v-for="exercise in exercises" :key="exercise.id" class="col">
          <div class="card h-100 recipe-card" @click="openExercise(exercise.id)" style="cursor: pointer;">
            <div class="row g-0 h-100">
              <div class="col-md-4">
                <div class="recipe-image-container">
                  <img v-if="exercise.image_path || exercise.image_url"
                       :src="exercise.image_path || exercise.image_url"
                       :alt="exercise.name"
                       class="img-fluid rounded-start">
                  <div v-else class="recipe-placeholder">
                    <i class="bi bi-image"></i>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card-body d-flex flex-column h-100">
                  <div>
                    <h5 class="card-title">{{ exercise.name }}</h5>
                    <div class="recipe-meta mb-2">
                      <span class="badge bg-category me-1" v-if="exercise.muscle_group">{{ exercise.muscle_group }}</span>
                      <span class="badge" :class="mapExerciseDifficulty(exercise.difficulty)">{{ exercise.difficulty }}</span>
                    </div>
                    <p class="card-text small">{{ truncateText(exercise.description, 100) }}</p>
                  </div>
                  <div class="mt-auto">
                    <div class="nutrition-info">
                      <div class="nutrition-item" v-if="exercise.duration">
                        <span class="nutrition-value">{{ exercise.duration }}</span>
                        <span class="nutrition-label">min</span>
                      </div>
                      <div class="nutrition-item" v-if="exercise.intensity">
                        <span class="nutrition-value">{{ exercise.intensity }}</span>
                        <span class="nutrition-label">Intens.</span>
                      </div>
                      <div class="nutrition-item" v-if="exercise.calories_burned">
                        <span class="nutrition-value">~{{ exercise.calories_burned }}</span>
                        <span class="nutrition-label">cal</span>
                      </div>
                      <div class="nutrition-item" v-if="exercise.equipment">
                        <span class="nutrition-value">{{ truncateText(exercise.equipment, 14) }}</span>
                        <span class="nutrition-label">Equipo</span>
                      </div>
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
          <h5 class="modal-title">{{ selectedExercise?.name }}</h5>
        </template>
        <template #body>
          <div v-if="selectedExercise">
            <!-- Carrusel de imágenes (igual a foods) -->
            <div v-if="selectedExercise.images && selectedExercise.images.length" id="exerciseImagesCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item" :class="{active: idx===0}" v-for="(img, idx) in selectedExercise.images" :key="img.id">
                  <img :src="img.full_url || img.path || img.url" class="d-block w-100" :alt="selectedExercise.name" style="max-height:380px;object-fit:cover;border-radius:8px;">
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#exerciseImagesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#exerciseImagesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
              </button>
            </div>

            <div class="row mb-4">
              <div class="col-md-12">
                <!-- Badges -->
                <div class="modal-badges d-flex flex-wrap gap-2 mb-3">
                  <span class="badge-pill" :class="getDifficultyBadgeClass(selectedExercise.difficulty)">
                    {{ selectedExercise.difficulty }}
                  </span>
                  <span class="badge-pill badge-primary" v-if="selectedExercise.duration">
                    <i class="bi bi-clock me-1"></i>{{ selectedExercise.duration }} min
                  </span>
                  <span class="badge-pill badge-info" v-if="selectedExercise.intensity">
                    <i class="bi bi-lightning-charge me-1"></i>{{ selectedExercise.intensity }}
                  </span>
                  <span class="badge-pill badge-secondary" v-if="selectedExercise.muscle_group">
                    <i class="bi bi-person-bounding-box me-1"></i>{{ selectedExercise.muscle_group }}
                  </span>
                </div>

                <!-- Descripción -->
                <div class="recipe-description mb-4 surface-blur p-3 rounded" v-if="selectedExercise.description">
                  <div v-for="(line, idx) in splitLines(selectedExercise.description)" :key="idx" class="mb-2">
                    {{ line }}
                  </div>
                </div>

                <!-- Grid de métricas (análoga a nutrición) -->
                <div class="nutrition-card surface-blur p-4 mb-4 rounded" v-if="anyMetric">
                  <h6 class="fw-bold mb-3">Detalles del Ejercicio</h6>
                  <div class="nutrition-grid">
                    <div class="nutrition-item-large" v-if="selectedExercise.calories_burned">
                      <div class="nutrition-icon"><i class="bi bi-fire"></i></div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large">~{{ selectedExercise.calories_burned }}</div>
                        <div class="nutrition-label-large">Calorías</div>
                      </div>
                    </div>
                    <div class="nutrition-item-large" v-if="selectedExercise.equipment">
                      <div class="nutrition-icon"><i class="bi bi-tools"></i></div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large">{{ selectedExercise.equipment }}</div>
                        <div class="nutrition-label-large">Equipo</div>
                      </div>
                    </div>
                    <div class="nutrition-item-large" v-if="selectedExercise.muscle_group">
                      <div class="nutrition-icon"><i class="bi bi-person"></i></div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large text-capitalize">{{ selectedExercise.muscle_group }}</div>
                        <div class="nutrition-label-large">Músculo</div>
                      </div>
                    </div>
                    <div class="nutrition-item-large" v-if="selectedExercise.intensity">
                      <div class="nutrition-icon"><i class="bi bi-lightning"></i></div>
                      <div class="nutrition-details">
                        <div class="nutrition-value-large">{{ selectedExercise.intensity }}</div>
                        <div class="nutrition-label-large">Intensidad</div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Instrucciones -->
                <div v-if="selectedExercise.instructions" class="preparation-section surface-blur p-4 rounded mb-4">
                  <h6 class="recipe-section-title"><i class="bi bi-journal-text me-2"></i>Instrucciones</h6>
                  <div class="section-divider mb-3" style="width:50px;margin-top:.5rem;"></div>
                  <ol class="preparation-list">
                    <li v-for="(line, idx) in splitLines(selectedExercise.instructions)" :key="idx" class="preparation-step">{{ line }}</li>
                  </ol>
                </div>

                <!-- Beneficios -->
                <div v-if="selectedExercise.benefits" class="ingredients-section surface-blur p-4 rounded mb-4">
                  <h6 class="recipe-section-title"><i class="bi bi-heart-pulse me-2"></i>Beneficios</h6>
                  <div class="section-divider mb-3" style="width:50px;margin-top:.5rem;"></div>
                  <ul class="ingredients-list">
                    <li v-for="(line, idx) in splitLines(selectedExercise.benefits)" :key="idx" class="ingredient-item">{{ line }}</li>
                  </ul>
                </div>

                <!-- Precauciones -->
                <div v-if="selectedExercise.precautions" class="preparation-section surface-blur p-4 rounded">
                  <h6 class="recipe-section-title"><i class="bi bi-exclamation-triangle me-2"></i>Precauciones</h6>
                  <div class="section-divider mb-3" style="width:50px;margin-top:.5rem;"></div>
                  <ul class="ingredients-list">
                    <li v-for="(line, idx) in splitLines(selectedExercise.precautions)" :key="idx" class="ingredient-item">{{ line }}</li>
                  </ul>
                </div>

              </div>
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
  name:'ExerciseSearch', components:{ ModalPortal },
  data(){ return { searchQuery:'', selectedMuscleGroup:'', exercises:[], loading:false, selectedExercise:null, showExerciseModal:false, hasSearched:false }; },
  computed:{
    anyMetric(){
      const e = this.selectedExercise || {}; 
      return e.calories_burned || e.equipment || e.muscle_group || e.intensity;
    }
  },
  methods:{
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
    closeExerciseModal() {
      this.showExerciseModal = false;
    },
    searchByMuscleGroup(muscleGroup) {
      this.selectedMuscleGroup = muscleGroup;
      this.searchQuery = '';
      this.searchExercises();
    },
    async openExercise(id){
      try {
        this.loadingDetail = true;
        const { data } = await axios.get(`/api/exercises/${id}`);
        // Normalizar imágenes (por si backend aún no incluye full_url)
        if(Array.isArray(data.images)){
          data.images = data.images.map(img => ({
            ...img,
            full_url: img.full_url || (img.path ? `/storage/exercises/${img.path}` : img.url || '')
          })).sort((a,b)=> (a.position??0)-(b.position??0));
        }
        this.selectedExercise = data;
        this.showExerciseModal = true;
      } catch(e){ console.error('Error cargando ejercicio', e); }
      finally { this.loadingDetail = false; }
    },
    splitLines(text){ if(!text) return []; return text.split('\n').map(l=>l.trim()).filter(Boolean); },
    mapExerciseDifficulty(diff){
      if(!diff) return 'badge-secondary';
      const d = diff.toLowerCase();
      if(d==='principiante') return 'badge-success';
      if(d==='intermedio') return 'badge-warning';
      if(d==='avanzado') return 'badge-danger';
      return 'badge-secondary';
    },
  }
};
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
  border: none;
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

/* Añadir estilos de badges coherentes con FoodSearch */
.badge-success{ background-color:#28a745; color:#fff; }
.badge-warning{ background-color:#ffc107; color:#212529; }
.badge-danger{ background-color:#dc3545; color:#fff; }
.badge-secondary{ background-color:#6c757d; color:#fff; }
.badge-primary{ background-color:var(--cn-primary); color:#fff; }

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

/***** Reuso de estilos de FoodSearch para consistencia *****/
.modal-badges .badge-pill{ padding:.4rem .8rem; border-radius:20px; font-size:.75rem; font-weight:500; }
.badge-pill.badge-primary{ background: var(--cn-primary); color:#fff; }
.badge-pill.badge-info{ background: #17a2b8; color:#fff; }
.badge-pill.badge-secondary{ background:#6c757d; color:#fff; }
.recipe-description p{ margin:0; line-height:1.55; }
.nutrition-card{ background:rgba(255,255,255,.95); }
.nutrition-grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:1rem; }
.nutrition-item-large{ display:flex; align-items:center; gap:1rem; padding:1rem; background:#f8f9fa; border-radius:8px; }
.nutrition-icon{ font-size:1.5rem; color:var(--cn-primary); }
.nutrition-value-large{ font-size:1.25rem; font-weight:600; color:var(--cn-primary); }
.nutrition-label-large{ font-size:.875rem; color:#6c757d; }
.recipe-section-title{ font-size:1.1rem; font-weight:600; color:var(--cn-primary); margin-bottom:.5rem; }
.section-divider{ height:3px; background:linear-gradient(90deg,var(--cn-primary) 0%, var(--cn-accent) 100%); border-radius:2px; }
.ingredients-list, .preparation-list{ margin:0; padding-left:1.5rem; }
.ingredient-item, .preparation-step{ margin-bottom:.5rem; line-height:1.6; }
.surface-blur{ background:rgba(255,255,255,.95); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,.3); }

/* Copia de estilos clave de FoodSearch para unificación visual */
.recipe-card{ transition:all .3s ease; border:none; box-shadow:0 2px 10px rgba(0,0,0,.05); margin-bottom:1.5rem; overflow:hidden; }
.recipe-card:hover{ transform:translateY(-5px); box-shadow:0 8px 15px rgba(0,0,0,.1); }
.recipe-image-container{ height:200px; display:flex; align-items:center; justify-content:center; background:var(--cn-light); overflow:hidden; }
.recipe-image-container img{ width:100%; height:100%; object-fit:cover; }
.recipe-placeholder{ font-size:3rem; color:#d3c7a7; }
.recipe-meta{ display:flex; gap:15px; flex-wrap:wrap; }
.recipe-meta-item{ display:flex; align-items:center; gap:4px; font-size:.85rem; color:var(--cn-dark); }
.recipe-meta-item i{ font-size:.9rem; color:var(--cn-secondary); }
.nutrition-info{ display:flex; flex-wrap:wrap; gap:10px; margin-top:1rem; }
.nutrition-item{ display:flex; flex-direction:column; align-items:center; background:var(--cn-light); padding:8px 12px; border-radius:8px; min-width:70px; }
.nutrition-value{ font-weight:600; color:var(--cn-secondary); }
.nutrition-label{ font-size:.75rem; color:var(--cn-dark); }
/* Recomendaciones / bienvenida (mismos estilos que FoodSearch) */
.welcome-section{ padding:2rem; }
.welcome-icon{ display:inline-flex; align-items:center; justify-content:center; width:80px; height:80px; background:linear-gradient(135deg,var(--cn-primary),var(--cn-secondary)); border-radius:50%; color:#fff; font-size:2rem; margin:0 auto; }
.welcome-title{ color:var(--cn-dark); font-weight:600; margin-bottom:.5rem; }
.welcome-description{ color:#6c757d; margin-bottom:2rem; }
.recommendations-grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:1rem; max-width:600px; margin:0 auto; }
.recommendation-card{ display:flex; flex-direction:column; align-items:center; padding:1.5rem 1rem; background:rgba(255,255,255,.9); border:2px solid rgba(139,0,0,.1); border-radius:12px; cursor:pointer; transition:all .3s ease; }
.recommendation-card:hover{ transform:translateY(-5px); border-color:var(--cn-primary); box-shadow:0 8px 25px rgba(139,0,0,.15); }
.recommendation-icon{ display:flex; align-items:center; justify-content:center; width:50px; height:50px; background:linear-gradient(135deg,var(--cn-primary),var(--cn-secondary)); border-radius:50%; color:#fff; font-size:1.5rem; margin-bottom:.75rem; }
.recommendation-label{ color:var(--cn-dark); font-weight:500; font-size:.95rem; }
/* Badge pill mapping reuse already present from existing code */
</style>