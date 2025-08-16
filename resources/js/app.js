import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import '../css/theme.css';
import '../css/admin-fixes.css';
import '../css/modal-fixes.css'; // Estilos simplificados para modales sin backdrop
// SOLUCIÓN SIMPLE: Modales sin backdrop (elimina el filtro gris)
import './modal-sin-backdrop';

import { createApp } from 'vue';
import ExerciseList from './components/exercises/ExerciseList.vue';
import ExerciseSearch from './components/exercises/ExerciseSearch.vue';
import FoodList from './components/foods/FoodList.vue';
import FoodSearch from './components/foods/FoodSearch.vue';
import SearchContainer from './components/SearchContainer.vue';
import AdminDashboard from './components/admin/AdminDashboard.vue';
import PasswordManager from './components/admin/PasswordManager.vue';
import PasswordManagerFixed from './components/admin/PasswordManagerFixed.vue';
import SearchTelemetry from './components/admin/SearchTelemetry.vue';
import Toasts from './components/ui/Toasts.vue';

// Import UI System
import UISystem from './components/ui-system';

// Global utility functions
window.showNotification = window.showNotification || function(message, type = 'success') {
  if (window.emitToast) {
    window.emitToast(message, type);
  } else {
    console.log(message);
  }
};

// Instancia vacía para no reemplazar el HTML de Blade
const app = createApp({});

// Register UI System
app.use(UISystem);

// Lazy directive
app.directive('lazy', {
  mounted(el) {
    if (el.tagName !== 'IMG') return;
    const src = el.getAttribute('data-src');
    if (!src) return;
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          el.src = src;
            el.onload = () => el.classList.add('loaded');
            observer.unobserve(el);
        }
      });
    }, { rootMargin: '100px' });
    observer.observe(el);
  }
});

// Registro de componentes globales
app.component('exercise-list', ExerciseList);
app.component('exercise-search', ExerciseSearch);
app.component('food-list', FoodList);
app.component('food-search', FoodSearch);
app.component('search-container', SearchContainer);
app.component('admin-dashboard', AdminDashboard);
app.component('password-manager', PasswordManagerFixed);
app.component('toasts-container', Toasts);

// Bus de eventos simple para toasts
window.emitToast = (message, type = 'success', timeout = 4000) => {
  window.dispatchEvent(new CustomEvent('app:toast', { detail: { message, type, timeout } }));
};

// Utility function to add to window
window.showNotification = (message, type = 'success') => {
  window.emitToast(message, type);
};

app.mount('#app');