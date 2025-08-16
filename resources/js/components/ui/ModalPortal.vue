<template>
  <Teleport to="body">
    <div 
      v-if="isVisible" 
      class="modal-portal-overlay"
      @click.self="closeModal"
    >
      <div 
        class="modal-portal-container"
        :class="sizeClass"
      >
        <div class="modal-portal-content">
          <div class="modal-portal-header" v-if="title || $slots.header">
            <slot name="header">
              <h5 class="modal-portal-title">{{ title }}</h5>
            </slot>
            <button 
              type="button" 
              class="modal-portal-close" 
              @click="closeModal"
              aria-label="Cerrar"
            >
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          
          <div class="modal-portal-body">
            <slot name="body">
              <slot></slot>
            </slot>
          </div>
          
          <div class="modal-portal-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
export default {
  name: 'ModalPortal',
  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: 'xl',
      validator: value => ['sm', 'md', 'lg', 'xl', 'xxl'].includes(value)
    }
  },
  emits: ['update:modelValue', 'close', 'hide'],
  computed: {
    isVisible() {
      return this.modelValue || this.show;
    },
    sizeClass() {
      return `modal-portal-${this.size}`;
    }
  },
  methods: {
    closeModal() {
      this.$emit('update:modelValue', false);
      this.$emit('close');
      this.$emit('hide');
    }
  },
  watch: {
    isVisible(newVal) {
      // Controlar scroll del body cuando el modal está abierto
      if (newVal) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    }
  },
  beforeUnmount() {
    // Asegurar que se restaure el scroll si el componente se desmonta
    document.body.style.overflow = '';
  }
}
</script>

<style scoped>
.modal-portal-overlay {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  right: 0 !important;
  bottom: 0 !important;
  width: 100vw !important;
  height: 100vh !important;
  background-color: rgba(0, 0, 0, 0.6);
  z-index: 99999999 !important;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  overflow-y: auto;
  isolation: isolate; /* Crea un nuevo stacking context */
}

.modal-portal-container {
  width: 100%;
  max-height: 95vh;
  display: flex;
  flex-direction: column;
  animation: modalFadeIn 0.2s ease-out;
}

.modal-portal-content {
  background: white;
  border-radius: 12px;
  box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
  display: flex;
  flex-direction: column;
  max-height: 95vh;
  overflow: hidden;
}

.modal-portal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  justify-content: between;
  align-items: center;
  background: linear-gradient(135deg, #8B0000, #A52A2A);
  color: white;
  border-radius: 12px 12px 0 0;
}

.modal-portal-title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  flex: 1;
}

.modal-portal-close {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-left: 1rem;
}

.modal-portal-close:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: scale(1.1);
}

.modal-portal-body {
  padding: 1.5rem;
  overflow-y: auto;
  flex: 1;
  max-height: calc(95vh - 120px);
}

.modal-portal-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e9ecef;
  background: #f8f9fa;
  border-radius: 0 0 12px 12px;
}

/* Tamaños responsivos */
.modal-portal-sm {
  max-width: 300px;
}

.modal-portal-md {
  max-width: 500px;
}

.modal-portal-lg {
  max-width: 800px;
}

.modal-portal-xl {
  max-width: 90%;
}

.modal-portal-xxl {
  max-width: 95%;
}

/* Responsive breakpoints */
@media (min-width: 768px) {
  .modal-portal-xl {
    max-width: 85%;
  }
  .modal-portal-xxl {
    max-width: 90%;
  }
}

@media (min-width: 992px) {
  .modal-portal-xl {
    max-width: 80%;
  }
  .modal-portal-xxl {
    max-width: 85%;
  }
}

@media (min-width: 1200px) {
  .modal-portal-xl {
    max-width: 75%;
  }
  .modal-portal-xxl {
    max-width: 80%;
  }
}

@media (min-width: 1400px) {
  .modal-portal-xl {
    max-width: 70%;
  }
  .modal-portal-xxl {
    max-width: 75%;
  }
}

/* Animación */
@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: scale(0.9) translateY(-50px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* Scroll personalizado */
.modal-portal-body::-webkit-scrollbar {
  width: 8px;
}

.modal-portal-body::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.modal-portal-body::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}

.modal-portal-body::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}
</style>
