<template>
  <div class="form-group">
    <label v-if="label" :for="id" class="form-label">{{ label }}</label>
    <div class="input-wrapper" :class="{ 'has-icon': $slots.icon }">
      <div class="input-icon" v-if="$slots.icon">
        <slot name="icon"></slot>
      </div>
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="form-control"
        :class="{
          'is-invalid': !!error,
          'form-control-sm': size === 'sm',
          'form-control-lg': size === 'lg',
          'has-icon-padding': $slots.icon
        }"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        v-bind="$attrs"
      />
    </div>
    <div v-if="hint && !error" class="form-text text-muted small">{{ hint }}</div>
    <div v-if="error" class="invalid-feedback">{{ error }}</div>
  </div>
</template>

<script>
export default {
  name: 'SInput',
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'text'
    },
    placeholder: {
      type: String,
      default: ''
    },
    hint: {
      type: String,
      default: ''
    },
    error: {
      type: String,
      default: ''
    },
    id: {
      type: String,
      default() {
        return `input-${Math.random().toString(36).substring(2, 9)}`;
      }
    },
    disabled: {
      type: Boolean,
      default: false
    },
    required: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: '',
      validator: value => ['', 'sm', 'lg'].includes(value)
    }
  },
  emits: ['update:modelValue']
}
</script>

<style scoped>
.form-group {
  margin-bottom: 1.25rem;
}

.form-label {
  font-weight: 500;
  margin-bottom: 0.5rem;
  color: var(--cn-dark-soft);
}

.input-wrapper {
  position: relative;
}

.input-icon {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  color: var(--cn-secondary);
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
}

.has-icon-padding {
  padding-left: 40px;
}

.form-control {
  padding: 0.65rem 0.85rem;
  border-radius: var(--cn-radius-sm);
  border: 1px solid var(--cn-gray-300);
  background-color: var(--cn-surface);
  transition: var(--cn-transition);
  box-shadow: var(--cn-shadow-sm);
}

.form-control:focus {
  border-color: rgba(var(--cn-primary-rgb), 0.4);
  box-shadow: 0 0 0 0.25rem rgba(var(--cn-primary-rgb), 0.25);
}

.form-control.is-invalid:focus {
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.form-control:disabled {
  background-color: var(--cn-gray-100);
  opacity: 0.7;
}

.form-text {
  margin-top: 0.25rem;
}
</style>
