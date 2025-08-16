<template>
  <button
    :class="[
      'btn',
      `btn-${variant}`,
      { 
        'btn-sm': size === 'sm',
        'btn-lg': size === 'lg',
        'w-100': block,
        'rounded-pill': pill,
        'd-flex align-items-center justify-content-center gap-2': $slots.icon
      }
    ]"
    :type="type"
    :disabled="disabled || loading"
    v-bind="$attrs"
  >
    <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
    <slot name="icon"></slot>
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: 'SButton',
  props: {
    variant: {
      type: String,
      default: 'primary',
      validator: value => ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link', 'outline-primary', 'outline-secondary', 'outline-success', 'outline-danger', 'outline-warning', 'outline-info', 'outline-light', 'outline-dark'].includes(value)
    },
    size: {
      type: String,
      default: '',
      validator: value => ['', 'sm', 'lg'].includes(value)
    },
    type: {
      type: String,
      default: 'button'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    block: {
      type: Boolean,
      default: false
    },
    pill: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped>
.btn {
  transition: var(--cn-transition);
  position: relative;
  overflow: hidden;
  font-weight: 500;
}

.btn-primary {
  background-color: var(--cn-primary);
  border-color: var(--cn-primary);
  box-shadow: 0 4px 12px -4px rgba(var(--cn-primary-rgb), 0.4);
}

.btn-primary:hover:not(:disabled) {
  background-color: var(--cn-primary);
  filter: brightness(1.1);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px -3px rgba(var(--cn-primary-rgb), 0.5);
}

.btn-secondary {
  background-color: var(--cn-secondary);
  border-color: var(--cn-secondary);
}

.btn-accent {
  background-color: var(--cn-accent);
  border-color: var(--cn-accent);
  color: var(--cn-dark);
  box-shadow: 0 4px 14px -4px rgba(var(--cn-accent-rgb), 0.5);
}

.btn-accent:hover:not(:disabled) {
  background-color: var(--cn-accent);
  filter: brightness(1.05);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px -3px rgba(var(--cn-accent-rgb), 0.6);
  color: var(--cn-dark);
}

.btn-outline-primary {
  color: var(--cn-primary);
  border-color: var(--cn-primary);
}

.btn-outline-primary:hover:not(:disabled) {
  background-color: var(--cn-primary);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 6px 15px -3px rgba(var(--cn-primary-rgb), 0.3);
}

.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}
</style>
