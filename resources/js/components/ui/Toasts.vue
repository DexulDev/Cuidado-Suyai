<template>
  <div class="toast-stack" aria-live="polite" aria-atomic="true">
    <transition-group name="toast" tag="div">
      <div v-for="t in toasts" :key="t.id" class="toast-item" :class="`toast-${t.type}`" role="status">
        <div class="d-flex align-items-start gap-2">
          <i class="bi" :class="iconFor(t.type)"></i>
          <div class="flex-grow-1 small fw-medium">{{ t.message }}</div>
          <button class="btn-close btn-close-white" @click="remove(t.id)"></button>
        </div>
        <div class="progress mt-2 progress-thin">
          <div class="progress-bar" :style="{ width: t.progress + '%' }"></div>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
export default {
  name: 'Toasts',
  data(){
    return { toasts: [] };
  },
  mounted(){
    window.addEventListener('app:toast', this.enqueue);
  },
  beforeUnmount(){
    window.removeEventListener('app:toast', this.enqueue);
  },
  methods:{
    iconFor(type){
      return {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        info: 'bi-info-circle-fill',
        warning: 'bi-exclamation-triangle-fill'
      }[type] || 'bi-bell-fill';
    },
    enqueue(e){
      const { message, type='success', timeout=4000 } = e.detail || {};
      const id = crypto.randomUUID();
      const toast = { id, message, type, timeout, created: Date.now(), progress: 100 };
      this.toasts.push(toast);
      const interval = setInterval(()=>{
        const elapsed = Date.now() - toast.created;
        toast.progress = 100 - (elapsed / timeout)*100;
        if(elapsed >= timeout){ this.remove(id); clearInterval(interval); }
      }, 100);
    },
    remove(id){ this.toasts = this.toasts.filter(t=> t.id !== id); }
  }
}
</script>

<style scoped>
.toast-stack { position: fixed; top: 1rem; right: 1rem; z-index: 2000; display:flex; flex-direction:column; gap:.75rem; max-width:320px; }
.toast-item { background: var(--cn-gradient); color:#fff; padding:.85rem 1rem .75rem; border-radius: var(--cn-radius); box-shadow: var(--cn-shadow-lg); font-size:.85rem; overflow:hidden; }
.toast-item .bi { font-size:1.1rem; }
.toast-success { --cn-gradient: linear-gradient(135deg,#0d8a4d,#36b37e); }
.toast-error { --cn-gradient: linear-gradient(135deg,#8b0000,#c53030); }
.toast-info { --cn-gradient: linear-gradient(135deg,#2460b9,#2e80e8); }
.toast-warning { --cn-gradient: linear-gradient(135deg,#b8860b,#ffb703); }
.toast-enter-active, .toast-leave-active { transition: all .4s cubic-bezier(.4,.2,.2,1); }
.toast-enter-from, .toast-leave-to { opacity:0; transform:translateX(40px) scale(.95); }
.progress-thin { height:4px; background: rgba(255,255,255,.25); border-radius:50rem; overflow:hidden; }
.progress-bar { background: rgba(255,255,255,.85); transition: width .2s linear; }
</style>
