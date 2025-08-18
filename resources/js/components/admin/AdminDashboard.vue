<template>
  <div class="admin-dashboard">
    <div class="row g-4 mb-4">
      <div class="col-md-3" v-for="card in statCards" :key="card.label">
        <div class="card-modern card-modern--interactive p-3 h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold text-muted small">{{ card.label }}</span>
            <i :class="card.icon" class="fs-5" style="color:var(--cn-primary)"></i>
          </div>
          <div class="display-6 fw-bold" style="line-height:1; color:var(--cn-primary)">{{ card.value }}</div>
        </div>
      </div>
    </div>
    
    <!-- Admin Actions Bar -->
    <div class="admin-actions mb-4">
      <div class="d-flex justify-content-end">
        <button class="btn btn-cn-primary" @click="openSearchTelemetry">
          <i class="bi bi-search-heart me-1"></i> Ver Búsquedas
        </button>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card-modern p-3 mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Comidas</h5>
            <a :href="routes.createFood" class="btn btn-sm btn-cn-accent">Nueva</a>
          </div>
          <div class="table-responsive">
            <table class="table align-middle small mb-0">
              <thead>
                <tr>
                  <th>Nombre</th><th>Cat</th><th>Dif</th><th>Imgs</th><th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="f in foods" :key="f.id" class="hover-row">
                  <td class="fw-medium">{{ f.name }}</td>
                  <td>{{ f.category }}</td>
                  <td>{{ f.difficulty }}</td>
                  <td>{{ f.images.length }}</td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary me-1" @click="openFoodEdit(f)"><i class="bi bi-pencil"></i></button>
                    <form :action="foodDestroyUrl(f.id)" method="POST" class="d-inline" @submit.prevent="confirmDelete($event)">
                      <input type="hidden" name="_token" :value="csrf">
                      <input type="hidden" name="_method" value="DELETE">
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="card-modern p-3 mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Ejercicios</h5>
            <a :href="routes.createExercise" class="btn btn-sm btn-cn-accent">Nuevo</a>
          </div>
          <div class="list-group small border-0">
            <div v-for="e in exercises" :key="e.id" class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom">
              <span class="text-truncate">{{ e.name }}</span>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-uppercase">{{ e.difficulty }}</span>
                <button class="btn btn-sm btn-outline-secondary" @click="openExerciseEdit(e)"><i class="bi bi-pencil"></i></button>
                <form :action="exerciseDestroyUrl(e.id)" method="POST" class="d-inline" @submit.prevent="confirmDelete($event)">
                  <input type="hidden" name="_token" :value="csrf">
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Food Edit Modal -->
    <div class="modal fade modal-modern admin-modal" id="foodAdminModal" tabindex="-1" ref="foodModal" data-bs-backdrop="true" data-bs-keyboard="true">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title">Editar Comida</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
          <div class="modal-body" v-if="foodForm">
            <form @submit.prevent="submitFood">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small">Nombre</label>
                  <input v-model="foodForm.name" class="form-control">
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Categoría</label>
                  <input v-model="foodForm.category" class="form-control">
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Dificultad</label>
                  <select v-model="foodForm.difficulty" class="form-select">
                    <option value="fácil">Fácil</option>
                    <option value="intermedio">Intermedio</option>
                    <option value="difícil">Difícil</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label small">Descripción</label>
                  <textarea v-model="foodForm.description" rows="2" class="form-control"></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label small">Ingredientes</label>
                  <textarea v-model="foodForm.ingredients" rows="4" class="form-control"></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label small">Preparación</label>
                  <textarea v-model="foodForm.preparation" rows="4" class="form-control"></textarea>
                </div>
              </div>
              <div class="mt-4">
                <label class="form-label small">Imágenes</label>
                <div class="d-flex flex-wrap gap-3" @dragover.prevent>
                  <!-- Imágenes existentes -->
                  <div v-for="img in sortedImages" :key="img.id" class="position-relative" draggable="true" @dragstart="dragStart(img)" @drop.prevent="dropImage(img)">
                    <img :src="storageFood(img.path)" loading="lazy" class="rounded shadow-sm" style="width:110px; height:110px; object-fit:cover;">
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" style="--bs-btn-padding-y:.1rem;--bs-btn-padding-x:.35rem;" @click="toggleDelete(img)"><i class="bi" :class="img._delete ? 'bi-arrow-counterclockwise':'bi-x'"></i></button>
                  </div>
                  <!-- Previews nuevas (no guardadas aún) -->
                  <div v-for="p in newImagePreviews" :key="'newf-'+p.id" class="position-relative">
                    <img :src="p.url" class="rounded shadow-sm border border-2 border-primary" style="width:110px;height:110px;object-fit:cover;opacity:.9;">
                    <span class="badge bg-primary position-absolute bottom-0 start-0 m-1">Nuevo</span>
                    <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0" style="--bs-btn-padding-y:.1rem;--bs-btn-padding-x:.35rem;" @click="removeNewImage(p.id)"><i class="bi bi-x"></i></button>
                  </div>
                  <!-- Botón añadir -->
                  <label class="d-flex flex-column justify-content-center align-items-center border rounded border-dashed shadow-sm" style="width:110px;height:110px; cursor:pointer; gap:.25rem; background:var(--cn-light);">
                    <i class="bi bi-plus-lg"></i>
                    <span class="small">Añadir</span>
                    <input type="file" accept="image/*" multiple hidden @change="queueNewImages">
                  </label>
                </div>
              </div>
              <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-cn-primary" :disabled="saving">
                  <span v-if="!saving">Guardar Cambios</span>
                  <span v-else><span class="spinner-border spinner-border-sm me-1"></span>Guardando...</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Exercise Edit Modal -->
    <div class="modal fade modal-modern admin-modal" id="exerciseAdminModal" tabindex="-1" ref="exerciseModal" data-bs-backdrop="true" data-bs-keyboard="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header"><h5 class="modal-title">Editar Ejercicio</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body" v-if="exerciseForm">
            <form @submit.prevent="submitExercise">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small">Nombre</label>
                  <input v-model="exerciseForm.name" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Categoría</label>
                  <input v-model="exerciseForm.category" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Dificultad</label>
                  <select v-model="exerciseForm.difficulty" class="form-select">
                    <option value="principiante">Principiante</option>
                    <option value="intermedio">Intermedio</option>
                    <option value="avanzado">Avanzado</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Duración (min)</label>
                  <input type="number" v-model="exerciseForm.duration" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Calorías</label>
                  <input type="number" v-model="exerciseForm.calories_burned" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Intensidad</label>
                  <input v-model="exerciseForm.intensity" class="form-control" />
                </div>
                <div class="col-md-3">
                  <label class="form-label small">Músculo</label>
                  <input v-model="exerciseForm.muscle_group" class="form-control" />
                </div>
                <div class="col-12">
                  <label class="form-label small">Descripción</label>
                  <textarea v-model="exerciseForm.description" rows="3" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label class="form-label small">Equipo</label>
                  <input v-model="exerciseForm.equipment" class="form-control" />
                </div>
                <div class="col-12">
                  <label class="form-label small">Imágenes</label>
                  <div class="d-flex flex-wrap gap-3" @dragover.prevent>
                    <!-- Imágenes existentes ejercicio -->
                    <div v-for="img in sortedExerciseImages" :key="img.id" class="position-relative" draggable="true" @dragstart="dragStart(img)" @drop.prevent="dropExerciseImage(img)">
                      <img :src="`/storage/exercises/${img.path}`" loading="lazy" class="rounded shadow-sm" style="width:110px; height:110px; object-fit:cover;">
                      <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" style="--bs-btn-padding-y:.1rem;--bs-btn-padding-x:.35rem;" @click="toggleExerciseDelete(img)"><i class="bi" :class="img._delete ? 'bi-arrow-counterclockwise':'bi-x'"></i></button>
                    </div>
                    <!-- Previews nuevas ejercicios -->
                    <div v-for="p in newExerciseImagePreviews" :key="'newx-'+p.id" class="position-relative">
                      <img :src="p.url" class="rounded shadow-sm border border-2 border-primary" style="width:110px;height:110px;object-fit:cover;opacity:.9;">
                      <span class="badge bg-primary position-absolute bottom-0 start-0 m-1">Nuevo</span>
                      <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0" style="--bs-btn-padding-y:.1rem;--bs-btn-padding-x:.35rem;" @click="removeNewExerciseImage(p.id)"><i class="bi bi-x"></i></button>
                    </div>
                    <!-- Botón añadir -->
                    <label class="d-flex flex-column justify-content-center align-items-center border rounded border-dashed shadow-sm" style="width:110px;height:110px; cursor:pointer; gap:.25rem; background:var(--cn-light);">
                      <i class="bi bi-plus-lg"></i>
                      <span class="small">Añadir</span>
                      <input type="file" accept="image/*" multiple hidden @change="queueNewExerciseImages">
                    </label>
                  </div>
                </div>
              </div>
              <div class="mt-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-cn-primary" :disabled="saving">
                  <span v-if="!saving">Guardar Cambios</span>
                  <span v-else><span class="spinner-border spinner-border-sm me-1"></span>Guardando...</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Search Analytics Modal -->
    <SearchAnalyticsModal 
      :show="showSearchAnalytics" 
      @hide="showSearchAnalytics = false"
      :api-route="routes.searchAnalyticsJson"
    />
  </div>
</template>

<script>
import SearchAnalyticsModal from './SearchAnalyticsModal.vue';
export default {
  components: {
    SearchAnalyticsModal
  },
  props: { initialFoods: Array, initialExercises: Array, routes: Object, csrf: String },
  data() {
    return { 
      foods: this.initialFoods || [], 
      exercises: this.initialExercises || [], 
      foodForm: null, 
      exerciseForm: null, 
      newImages: [], 
      newImagePreviews: [],
      deletedImages: new Set(), 
      dragSource: null, 
      saving: false, 
      newExerciseImages: [], 
      newExerciseImagePreviews: [],
      deletedExerciseImages: new Set(),
      showSearchAnalytics: false,
      foodModalInstance: null,
      exerciseModalInstance: null
    };
  },
  computed: {
    statCards() { return [ { label:'Comidas', value:this.foods.length, icon:'bi bi-egg-fried' }, { label:'Ejercicios', value:this.exercises.length, icon:'bi bi-activity' } ]; },
    sortedImages() { return this.foodForm ? [...this.foodForm.images].sort((a,b)=>a.position-b.position) : []; },
    sortedExerciseImages() { return this.exerciseForm ? [...(this.exerciseForm.images || [])].sort((a,b)=>a.position-b.position) : []; }
  },
  methods: {
    foodDestroyUrl(id){ return this.routes.destroyFood.replace(':id', id); },
    exerciseDestroyUrl(id){ return this.routes.destroyExercise ? this.routes.destroyExercise.replace(':id', id) : '#'; },
    storageFood(path){ return `/storage/foods/${path}`; },
    openFoodEdit(food){ this.foodForm = JSON.parse(JSON.stringify(food)); this.deletedImages.clear(); this.newImages=[]; this.showModal('foodModal'); },
    openExerciseEdit(ex){ this.exerciseForm = JSON.parse(JSON.stringify(ex)); this.deletedExerciseImages.clear(); this.newExerciseImages=[]; this.showModal('exerciseModal'); },
    showModal(ref){ 
      const el = this.$refs[ref]; 
      if (!el) return;
      const bootstrap = window.bootstrap;
      if (bootstrap && bootstrap.Modal) {
        try {
          if (ref==='foodModal') {
            this.foodModalInstance = this.foodModalInstance || new bootstrap.Modal(el,{backdrop:true,keyboard:true,focus:true});
            this.foodModalInstance.show();
          } else if (ref==='exerciseModal') {
            this.exerciseModalInstance = this.exerciseModalInstance || new bootstrap.Modal(el,{backdrop:true,keyboard:true,focus:true});
            this.exerciseModalInstance.show();
          }
        } catch (err) { /* fallback minimal */ el.style.display='block'; el.classList.add('show'); document.body.classList.add('modal-open'); }
      }
    },
    
    cleanupModals() {
      try {
        // Eliminar todos los backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
          backdrop.remove();
        });
        
        // Limpiar clases y estilos del body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
        
        // Cerrar modales abiertos
        document.querySelectorAll('.modal.show').forEach(modal => {
          if (window.bootstrap && window.bootstrap.Modal && window.bootstrap.Modal.getInstance) {
            try {
              const modalInstance = window.bootstrap.Modal.getInstance(modal);
              if (modalInstance) {
                modalInstance.hide();
              } else {
                // Fallback manual
                modal.classList.remove('show');
                modal.style.display = 'none';
                // NO establecer aria-hidden en modales que pueden tener foco
              }
            } catch (e) {
              // Fallback si falla getInstance
              modal.classList.remove('show');
              modal.style.display = 'none';
              // NO establecer aria-hidden en modales que pueden tener foco
            }
          } else {
            // Limpieza manual si Bootstrap no está disponible
            modal.classList.remove('show');
            modal.style.display = 'none';
            // NO establecer aria-hidden en modales que pueden tener foco
          }
        });
      } catch (err) {
        // Si todo falla, al menos remover las clases básicas
        document.querySelectorAll('.modal.show').forEach(modal => {
          modal.classList.remove('show');
          modal.style.display = 'none';
        });
      }
    },
    toggleDelete(img){ img._delete = !img._delete; if(img._delete) this.deletedImages.add(img.id); else this.deletedImages.delete(img.id); },
    toggleExerciseDelete(img){ img._delete = !img._delete; if(img._delete) this.deletedExerciseImages.add(img.id); else this.deletedExerciseImages.delete(img.id); },
    dragStart(img){ this.dragSource = img; },
    dropImage(target){ if(!this.dragSource || this.dragSource.id===target.id) return; const order = this.sortedImages; const from=order.findIndex(i=>i.id===this.dragSource.id); const to=order.findIndex(i=>i.id===target.id); order.splice(to,0, order.splice(from,1)[0]); order.forEach((i,idx)=> i.position=idx); this.foodForm.images = order; },
    dropExerciseImage(target){ if(!this.dragSource || this.dragSource.id===target.id) return; const order = this.sortedExerciseImages; const from=order.findIndex(i=>i.id===this.dragSource.id); const to=order.findIndex(i=>i.id===target.id); order.splice(to,0, order.splice(from,1)[0]); order.forEach((i,idx)=> i.position=idx); this.exerciseForm.images = order; },
    queueNewImages(e){ 
      const files = Array.from(e.target.files || []);
      files.forEach(f=>{ this.newImages.push(f); this.newImagePreviews.push({ id: Math.random().toString(36).slice(2), url: URL.createObjectURL(f), _file: f }); });
      e.target.value='';
    },
    queueNewExerciseImages(e){ 
      const files = Array.from(e.target.files || []);
      files.forEach(f=>{ this.newExerciseImages.push(f); this.newExerciseImagePreviews.push({ id: Math.random().toString(36).slice(2), url: URL.createObjectURL(f), _file: f }); });
      e.target.value='';
    },
    showModal(ref){ 
      const el = this.$refs[ref]; 
      if (!el) return;
      const bootstrap = window.bootstrap;
      if (bootstrap && bootstrap.Modal) {
        try {
          if (ref==='foodModal') {
            this.foodModalInstance = this.foodModalInstance || new bootstrap.Modal(el,{backdrop:true,keyboard:true,focus:true});
            this.foodModalInstance.show();
          } else if (ref==='exerciseModal') {
            this.exerciseModalInstance = this.exerciseModalInstance || new bootstrap.Modal(el,{backdrop:true,keyboard:true,focus:true});
            this.exerciseModalInstance.show();
          }
        } catch (err) { /* fallback minimal */ el.style.display='block'; el.classList.add('show'); document.body.classList.add('modal-open'); }
      }
    },
    
    cleanupModals() {
      try {
        // Eliminar todos los backdrops
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
          backdrop.remove();
        });
        
        // Limpiar clases y estilos del body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
        
        // Cerrar modales abiertos
        document.querySelectorAll('.modal.show').forEach(modal => {
          if (window.bootstrap && window.bootstrap.Modal && window.bootstrap.Modal.getInstance) {
            try {
              const modalInstance = window.bootstrap.Modal.getInstance(modal);
              if (modalInstance) {
                modalInstance.hide();
              } else {
                // Fallback manual
                modal.classList.remove('show');
                modal.style.display = 'none';
                // NO establecer aria-hidden en modales que pueden tener foco
              }
            } catch (e) {
              // Fallback si falla getInstance
              modal.classList.remove('show');
              modal.style.display = 'none';
              // NO establecer aria-hidden en modales que pueden tener foco
            }
          } else {
            // Limpieza manual si Bootstrap no está disponible
            modal.classList.remove('show');
            modal.style.display = 'none';
            // NO establecer aria-hidden en modales que pueden tener foco
          }
        });
      } catch (err) {
        // Si todo falla, al menos remover las clases básicas
        document.querySelectorAll('.modal.show').forEach(modal => {
          modal.classList.remove('show');
          modal.style.display = 'none';
        });
      }
    },
    toggleDelete(img){ img._delete = !img._delete; if(img._delete) this.deletedImages.add(img.id); else this.deletedImages.delete(img.id); },
    toggleExerciseDelete(img){ img._delete = !img._delete; if(img._delete) this.deletedExerciseImages.add(img.id); else this.deletedExerciseImages.delete(img.id); },
    dragStart(img){ this.dragSource = img; },
    dropImage(target){ if(!this.dragSource || this.dragSource.id===target.id) return; const order = this.sortedImages; const from=order.findIndex(i=>i.id===this.dragSource.id); const to=order.findIndex(i=>i.id===target.id); order.splice(to,0, order.splice(from,1)[0]); order.forEach((i,idx)=> i.position=idx); this.foodForm.images = order; },
    dropExerciseImage(target){ if(!this.dragSource || this.dragSource.id===target.id) return; const order = this.sortedExerciseImages; const from=order.findIndex(i=>i.id===this.dragSource.id); const to=order.findIndex(i=>i.id===target.id); order.splice(to,0, order.splice(from,1)[0]); order.forEach((i,idx)=> i.position=idx); this.exerciseForm.images = order; },
    queueNewImages(e){ 
      const files = Array.from(e.target.files || []);
      files.forEach(f=>{ this.newImages.push(f); this.newImagePreviews.push({ id: Math.random().toString(36).slice(2), url: URL.createObjectURL(f), _file: f }); });
      e.target.value='';
    },
    queueNewExerciseImages(e){ 
      const files = Array.from(e.target.files || []);
      files.forEach(f=>{ this.newExerciseImages.push(f); this.newExerciseImagePreviews.push({ id: Math.random().toString(36).slice(2), url: URL.createObjectURL(f), _file: f }); });
      e.target.value='';
    },
    async submitFood(){
      this.saving=true;
      const fd = new FormData();
      ['name','category','difficulty','description','ingredients','preparation'].forEach(f=> fd.append(f, this.foodForm[f]||''));
      fd.append('image_order', this.sortedImages.filter(i=>!i._delete).map(i=>i.id).join(','));
      this.sortedImages.filter(i=>i._delete).forEach(i=> fd.append('delete_images[]', i.id));
      this.newImages.forEach(f=> fd.append('new_images[]', f));
      fd.append('_token', this.csrf); fd.append('_method','PATCH');
      try {
        const res = await fetch(this.routes.updateFood.replace(':id', this.foodForm.id), { method:'POST', body: fd, headers: { 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' } });
        const json = await res.json();
        if(res.ok){ 
          const idx = this.foods.findIndex(f=>f.id===this.foodForm.id); 
          if(idx>-1) this.foods[idx]= json.food; 
          this.newImages=[]; this.newImagePreviews=[]; this.deletedImages.clear();
          if(this.foodModalInstance) this.foodModalInstance.hide(); else this.closeModal('#foodAdminModal');
        } else { alert('Error: ' + (json.message || 'Validación')); }
      } catch(e){ alert('Error al actualizar la comida: ' + e.message); }
      this.saving=false;
    },
    async submitExercise(){
      this.saving=true;
      const fd = new FormData();
      ['name','category','difficulty','duration','calories_burned','equipment','muscle_group','intensity','description'].forEach(f=> fd.append(f, this.exerciseForm[f]||''));
      if(this.newExerciseImages.length) this.newExerciseImages.forEach(f=> fd.append('new_images[]', f));
      this.deletedExerciseImages.forEach(i=> fd.append('delete_images[]', i));
      fd.append('image_order', this.sortedExerciseImages.filter(i=>!i._delete).map(i=>i.id).join(','));
      fd.append('_token', this.csrf); fd.append('_method','PATCH');
      try {
        const res = await fetch(this.routes.updateExercise.replace(':id', this.exerciseForm.id), { method:'POST', body: fd, headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'} });
        const json = await res.json();
        if(res.ok){
          const idx = this.exercises.findIndex(e=>e.id===this.exerciseForm.id);
            if(idx>-1) this.exercises[idx]= json.exercise;
          this.newExerciseImages=[]; this.newExerciseImagePreviews=[]; this.deletedExerciseImages.clear();
          if(this.exerciseModalInstance) this.exerciseModalInstance.hide(); else this.closeModal('#exerciseAdminModal');
        } else { alert('Error: '+(json.message||'Validación')); }
      } catch(e){ alert('Error al actualizar el ejercicio: '+e.message); }
      this.saving=false;
    },
    closeModal(sel){ 
      const el=document.querySelector(sel); 
      if (!el) return;
      
      try {
        // Intentar usar Bootstrap Modal si está disponible
        if (window.bootstrap && window.bootstrap.Modal && window.bootstrap.Modal.getInstance) {
          const modal = window.bootstrap.Modal.getInstance(el);
          if (modal) {
            modal.hide();
          } else {
            // Crear una nueva instancia y cerrarla
            const newModal = new window.bootstrap.Modal(el);
            newModal.hide();
          }
        } else {
          // Fallback manual si Bootstrap no está disponible
          el.classList.remove('show');
          el.style.display = 'none';
          // NO establecer aria-hidden para evitar problemas de accesibilidad
          
          // Eliminar backdrops
          document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
          });
        }
        
        // Limpiar clases y estilos del body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
      } catch (err) {
        // Limpieza de emergencia
        this.cleanupModals();
      }
    },
    confirmDelete(e){ if(!confirm('¿Eliminar definitivamente?')) return; e.target.submit(); },
    openSearchTelemetry() {
      this.showSearchAnalytics = true;
    },
    removeNewImage(id){
      const idxP = this.newImagePreviews.findIndex(p=>p.id===id); if(idxP>-1){ URL.revokeObjectURL(this.newImagePreviews[idxP].url); this.newImagePreviews.splice(idxP,1); }
      // remover file correspondiente (mismo índice si orden de push coincide)
      // simple: reconstruir newImages excluyendo por índice
      this.newImages = this.newImages.filter((_,i)=> i < this.newImagePreviews.length+1); // best-effort
    },
    removeNewExerciseImage(id){
      const idxP = this.newExerciseImagePreviews.findIndex(p=>p.id===id); if(idxP>-1){ URL.revokeObjectURL(this.newExerciseImagePreviews[idxP].url); this.newExerciseImagePreviews.splice(idxP,1); }
      this.newExerciseImages = this.newExerciseImages.filter((_,i)=> i < this.newExerciseImagePreviews.length+1);
    },
  }
};
</script>

<style scoped>
.hover-row:hover { background: rgba(var(--cn-primary-rgb), .04); }
.table > :not(caption) > * > * { border:0 !important; }
.border-dashed { border:2px dashed rgba(var(--cn-primary-rgb), .35) !important; }
.card-modern { background: #fff; }
</style>
