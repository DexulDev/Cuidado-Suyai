<template>
  <span 
    class="s-badge"
    :class="[
      `s-badge-${variant}`,
      {
        's-badge-pill': pill,
        's-badge-outline': outline
      }
    ]"
  >
    <slot name="icon"></slot>
    <slot></slot>
  </span>
</template>

<script>
export default {
  name: 'SBadge',
  props: {
    variant: {
      type: String,
      default: 'primary',
      validator: value => ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'accent'].includes(value)
    },
    pill: {
      type: Boolean,
      default: false
    },
    outline: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped>
.s-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.35em 0.65em;
  font-size: 0.75em;
  font-weight: 600;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: var(--cn-radius-sm);
  transition: var(--cn-transition);
  letter-spacing: 0.03em;
}

.s-badge-pill {
  border-radius: 50rem;
  padding-right: 0.8em;
  padding-left: 0.8em;
}

.s-badge-primary {
  background-color: var(--cn-primary);
  color: white;
}

.s-badge-secondary {
  background-color: var(--cn-secondary);
  color: white;
}

.s-badge-accent {
  background-color: var(--cn-accent);
  color: var(--cn-dark);
}

.s-badge-success {
  background-color: var(--cn-success);
  color: white;
}

.s-badge-info {
  background-color: var(--cn-info);
  color: white;
}

.s-badge-warning {
  background-color: var(--cn-accent);
  color: var(--cn-dark);
}

.s-badge-dark {
  background-color: var(--cn-dark);
  color: white;
}

.s-badge-light {
  background-color: var(--cn-light);
  color: var(--cn-dark);
}

/* Outline variants */
.s-badge-outline {
  background-color: transparent;
}

.s-badge-outline.s-badge-primary {
  color: var(--cn-primary);
  border: 1px solid var(--cn-primary);
}

.s-badge-outline.s-badge-secondary {
  color: var(--cn-secondary);
  border: 1px solid var(--cn-secondary);
}

.s-badge-outline.s-badge-accent {
  color: var(--cn-dark);
  border: 1px solid var(--cn-accent);
}

.s-badge-outline.s-badge-success {
  color: var(--cn-success);
  border: 1px solid var(--cn-success);
}

.s-badge-outline.s-badge-info {
  color: var(--cn-info);
  border: 1px solid var(--cn-info);
}

.s-badge-outline.s-badge-warning {
  color: var(--cn-dark);
  border: 1px solid var(--cn-accent);
}

.s-badge-outline.s-badge-dark {
  color: var(--cn-dark);
  border: 1px solid var(--cn-dark);
}

.s-badge-outline.s-badge-light {
  color: var(--cn-dark-soft);
  border: 1px solid var(--cn-light);
}
</style>
