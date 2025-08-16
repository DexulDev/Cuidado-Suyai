<template>
  <div 
    class="s-card"
    :class="{ 
      'glass-card': glass,
      'shadow-card': shadow,
      'hover-effect': hover 
    }"
  >
    <div v-if="$slots.image" class="s-card-image">
      <slot name="image"></slot>
    </div>
    <div class="s-card-body">
      <div v-if="$slots.title || title" class="s-card-title">
        <slot name="title">{{ title }}</slot>
      </div>
      <div v-if="$slots.subtitle || subtitle" class="s-card-subtitle">
        <slot name="subtitle">{{ subtitle }}</slot>
      </div>
      <slot></slot>
    </div>
    <div v-if="$slots.footer" class="s-card-footer">
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SCard',
  props: {
    title: {
      type: String,
      default: ''
    },
    subtitle: {
      type: String,
      default: ''
    },
    glass: {
      type: Boolean,
      default: false
    },
    shadow: {
      type: Boolean,
      default: true
    },
    hover: {
      type: Boolean,
      default: true
    }
  }
}
</script>

<style scoped>
.s-card {
  border-radius: var(--cn-radius);
  background-color: var(--cn-surface);
  border: 1px solid var(--cn-gray-200);
  transition: var(--cn-transition);
  overflow: hidden;
}

.shadow-card {
  box-shadow: var(--cn-shadow);
}

.glass-card {
  backdrop-filter: blur(16px) saturate(160%);
  background: rgba(255,255,255,.8);
  border: 1px solid rgba(255,255,255,.6);
}

.hover-effect:hover {
  transform: translateY(-5px);
  box-shadow: var(--cn-shadow-lg);
}

.s-card-image {
  width: 100%;
  overflow: hidden;
  line-height: 0;
}

.s-card-image img {
  width: 100%;
  object-fit: cover;
  transition: transform 0.6s;
}

.hover-effect:hover .s-card-image img {
  transform: scale(1.05);
}

.s-card-body {
  padding: 1.5rem;
}

.s-card-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--cn-primary);
}

.s-card-subtitle {
  font-size: 0.95rem;
  color: var(--cn-dark-soft);
  margin-bottom: 1rem;
}

.s-card-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--cn-gray-200);
  background-color: var(--cn-gray-100);
}
</style>
