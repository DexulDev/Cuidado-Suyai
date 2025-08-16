<template>
  <ModalPortal :show="show" @hide="$emit('hide')" size="xl">
    <template #header>
      <h5 class="modal-title text-white d-flex align-items-center">
        <i class="bi bi-graph-up me-2"></i>
        Telemetría de Búsquedas
      </h5>
    </template>
    
    <template #body>
      <div class="search-analytics-container p-4">
        <!-- Filtros superiores -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Tipo de búsqueda</label>
            <select v-model="filters.searchType" @change="loadData" class="form-select form-select-sm">
              <option value="">Todos los tipos</option>
              <option value="food">Comidas</option>
              <option value="exercise">Ejercicios</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Categoría</label>
            <select v-model="filters.category" @change="loadData" class="form-select form-select-sm">
              <option value="">Todas las categorías</option>
              <option v-for="category in availableCategories" :key="category" :value="category">
                {{ category }}
              </option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Período</label>
            <select v-model="filters.period" @change="loadData" class="form-select form-select-sm">
              <option value="">Todo el tiempo</option>
              <option value="today">Hoy</option>
              <option value="week">Esta semana</option>
              <option value="month">Este mes</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Ordenar por</label>
            <select v-model="filters.sort" @change="loadData" class="form-select form-select-sm">
              <option value="newest">Más recientes</option>
              <option value="oldest">Más antiguos</option>
              <option value="popular">Más populares</option>
            </select>
          </div>
        </div>

        <!-- Estadísticas principales -->
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stats-card p-3 text-center">
              <div class="stats-icon mb-2">
                <i class="bi bi-search"></i>
              </div>
              <h6 class="mb-1 text-muted small">Total de búsquedas</h6>
              <div class="stats-number">{{ stats.total || 0 }}</div>
              <div v-if="stats.total_change" class="stats-change">
                <i :class="stats.total_change > 0 ? 'bi bi-arrow-up text-success' : 'bi bi-arrow-down text-danger'"></i>
                <span :class="stats.total_change > 0 ? 'text-success' : 'text-danger'">
                  {{ Math.abs(stats.total_change) }}% vs período anterior
                </span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stats-card p-3 text-center">
              <div class="stats-icon mb-2">
                <i class="bi bi-calendar-day"></i>
              </div>
              <h6 class="mb-1 text-muted small">Búsquedas hoy</h6>
              <div class="stats-number">{{ stats.today || 0 }}</div>
              <div v-if="stats.today_change" class="stats-change">
                <i :class="stats.today_change > 0 ? 'bi bi-arrow-up text-success' : 'bi bi-arrow-down text-danger'"></i>
                <span :class="stats.today_change > 0 ? 'text-success' : 'text-danger'">
                  {{ Math.abs(stats.today_change) }}% vs período anterior
                </span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stats-card p-3 text-center">
              <div class="stats-icon mb-2">
                <i class="bi bi-hash"></i>
              </div>
              <h6 class="mb-1 text-muted small">Términos únicos</h6>
              <div class="stats-number">{{ stats.unique_terms || 0 }}</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stats-card p-3 text-center">
              <div class="stats-icon mb-2">
                <i class="bi bi-bar-chart"></i>
              </div>
              <h6 class="mb-1 text-muted small">Promedio de resultados</h6>
              <div class="stats-number">{{ stats.average_results || '0.0' }}</div>
            </div>
          </div>
        </div>

        <!-- Términos populares -->
        <div v-if="popularTerms && popularTerms.length" class="mb-4">
          <div class="popular-terms-container">
            <span 
              v-for="term in filteredPopularTerms" 
              :key="`${term.term}-${term.search_type}`"
              :class="['badge', 'popular-term', 'clickable-term', term.search_type === 'food' ? 'bg-danger' : 'bg-primary']"
              @click="filterByTerm(term.term, term.search_type)"
              :title="`Filtrar por: ${term.term}`"
            >
              {{ term.term }}
              <span class="badge bg-light text-dark ms-1">{{ term.count }}</span>
            </span>
          </div>
        </div>
        
        <!-- Tabla de búsquedas -->
        <div class="card border-0 shadow-sm">
          <div class="card-body p-0">
            <div v-if="loading" class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
              </div>
              <p class="mt-3 text-muted">Cargando datos de telemetría...</p>
            </div>
            
            <div v-else-if="error" class="text-center py-5">
              <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
              <p class="mt-3 text-danger">{{ error }}</p>
              <button @click="loadData" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-clockwise me-1"></i>Reintentar
              </button>
            </div>
            
            <div v-else-if="searches.length === 0" class="text-center py-5">
              <i class="bi bi-search fs-1 text-muted"></i>
              <p class="mt-3 text-muted">No hay búsquedas con los filtros actuales</p>
            </div>
            
            <div v-else>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="px-4 py-3 small fw-bold text-uppercase">#</th>
                      <th class="px-4 py-3 small fw-bold text-uppercase">Término</th>
                      <th class="px-4 py-3 small fw-bold text-uppercase">Tipo</th>
                      <th class="px-4 py-3 small fw-bold text-uppercase">Categoría</th>
                      <th class="px-4 py-3 small fw-bold text-uppercase">Resultados</th>
                      <th class="px-4 py-3 small fw-bold text-uppercase">Fecha</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(search, index) in searches" :key="search.id" class="border-bottom">
                      <td class="px-4 py-3">
                        <small class="text-muted">{{ (pagination.current_page - 1) * pagination.per_page + index + 1 }}</small>
                      </td>
                      <td class="px-4 py-3">
                        <span v-if="search.query" class="fw-medium">{{ search.query }}</span>
                        <em v-else class="text-muted">(vacío)</em>
                      </td>
                      <td class="px-4 py-3">
                        <span 
                          :class="['badge', search.search_type === 'food' ? 'bg-danger' : 'bg-primary']"
                        >
                          {{ search.search_type === 'food' ? 'Comida' : 'Ejercicio' }}
                        </span>
                      </td>
                      <td class="px-4 py-3">
                        <span v-if="search.category" class="badge bg-light text-dark border">
                          {{ search.category }}
                        </span>
                        <span v-else class="text-muted">-</span>
                      </td>
                      <td class="px-4 py-3">
                        <span class="badge" :class="search.results_count > 0 ? 'bg-success' : 'bg-secondary'">
                          {{ search.results_count }}
                        </span>
                      </td>
                      <td class="px-4 py-3">
                        <small class="text-muted">{{ formatDate(search.created_at) }}</small>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Paginación y exportar -->
              <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light">
                <div class="text-muted small">
                  Mostrando {{ (pagination.current_page - 1) * pagination.per_page + 1 }} - 
                  {{ Math.min(pagination.current_page * pagination.per_page, pagination.total_records) }} 
                  de {{ pagination.total_records }} resultados
                </div>
                <div class="d-flex align-items-center gap-2">
                  <button 
                    @click="previousPage" 
                    :disabled="pagination.current_page === 1"
                    class="btn btn-outline-primary btn-sm"
                  >
                    <i class="bi bi-chevron-left"></i>
                  </button>
                  <span class="px-3 py-1 bg-primary text-white rounded small">
                    {{ pagination.current_page }} / {{ pagination.total_pages }}
                  </span>
                  <button 
                    @click="nextPage" 
                    :disabled="pagination.current_page === pagination.total_pages"
                    class="btn btn-outline-primary btn-sm"
                  >
                    <i class="bi bi-chevron-right"></i>
                  </button>
                  <div class="vr mx-2"></div>
                  <button @click="exportData" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-download me-1"></i>Exportar CSV
                  </button>
                  <button @click="$emit('hide')" class="btn btn-outline-secondary btn-sm">
                    Cerrar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </ModalPortal>
</template>

<script>
import ModalPortal from '../ui/ModalPortal.vue';

export default {
  name: 'SearchAnalyticsModal',
  components: {
    ModalPortal
  },
  props: {
    show: {
      type: Boolean,
      required: true
    },
    apiRoute: {
      type: String,
      required: true
    }
  },
  emits: ['hide'],
  data() {
    return {
      loading: false,
      error: null,
      searches: [],
      stats: {
        total: 0,
        today: 0,
        unique_terms: 0,
        average_results: '0.0'
      },
      popularTerms: [],
      availableCategories: [],
      pagination: {
        current_page: 1,
        total_pages: 1,
        total_records: 0,
        per_page: 15
      },
      filters: {
        searchType: '',
        category: '',
        period: '',
        sort: 'newest'
      },
      termFilter: 'food' // Cambiado de 'all' a 'food' para mostrar comidas por defecto
    };
  },
  computed: {
    filteredPopularTerms() {
      if (!this.popularTerms || this.popularTerms.length === 0) {
        return [];
      }
      
      // Si termFilter es 'food', mostrar solo comidas
      if (this.termFilter === 'food') {
        return this.popularTerms.filter(term => term.search_type === 'food');
      }
      
      // Si termFilter es 'exercise', mostrar solo ejercicios  
      if (this.termFilter === 'exercise') {
        return this.popularTerms.filter(term => term.search_type === 'exercise');
      }
      
      // Si es 'all', mostrar todos
      return this.popularTerms;
    }
  },
  watch: {
    show(newValue) {
      if (newValue && this.searches.length === 0) {
        this.loadData();
      }
    }
  },
  methods: {
    async loadData() {
      this.loading = true;
      this.error = null;
      
      try {
        const params = new URLSearchParams();
        
        // Agregar filtros
        if (this.filters.searchType) params.append('searchType', this.filters.searchType);
        if (this.filters.category) params.append('category', this.filters.category);
        if (this.filters.period) params.append('period', this.filters.period);
        if (this.filters.sort) params.append('sort', this.filters.sort);
        params.append('page', this.pagination.current_page);
        
        const url = `${this.apiRoute}?${params.toString()}`;
        const response = await fetch(url);
        
        if (!response.ok) {
          throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        this.searches = data.searches || [];
        this.stats = data.stats || this.stats;
        this.popularTerms = data.popular_terms || [];
        this.availableCategories = data.categories || [];
        this.pagination = data.pagination || this.pagination;
        
      } catch (error) {
        this.error = 'Error al cargar los datos. Intente nuevamente.';
      } finally {
        this.loading = false;
      }
    },
    
    previousPage() {
      if (this.pagination.current_page > 1) {
        this.pagination.current_page--;
        this.loadData();
      }
    },
    
    nextPage() {
      if (this.pagination.current_page < this.pagination.total_pages) {
        this.pagination.current_page++;
        this.loadData();
      }
    },
    
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    
    exportData() {
      // Implementar exportación CSV
      const csvData = this.searches.map(search => ({
        id: search.id,
        termino: search.query || '(vacío)',
        tipo: search.search_type === 'food' ? 'Comida' : 'Ejercicio',
        categoria: search.category || '-',
        resultados: search.results_count,
        fecha: this.formatDate(search.created_at)
      }));
      
      const headers = ['ID', 'Término', 'Tipo', 'Categoría', 'Resultados', 'Fecha'];
      const csvContent = [
        headers.join(','),
        ...csvData.map(row => Object.values(row).map(value => 
          typeof value === 'string' && value.includes(',') ? `"${value}"` : value
        ).join(','))
      ].join('\n');
      
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);
      link.setAttribute('href', url);
      link.setAttribute('download', `telemetria_busquedas_${new Date().toISOString().split('T')[0]}.csv`);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    
    filterByTerm(term, searchType) {
      // Aplicar filtros basados en el término clickeado
      this.filters.searchType = searchType;
      
      // Reset página y recargar datos
      this.pagination.current_page = 1;
      this.loadData();
      
      // Mostrar feedback visual
      this.showFilterNotification(term, searchType);
    },
    
    showFilterNotification(term, searchType) {
      // Crear una notificación temporal para mostrar el filtro aplicado
      const notification = document.createElement('div');
      notification.className = 'alert alert-info alert-dismissible fade show position-fixed';
      notification.style.cssText = 'top: 20px; right: 20px; z-index: 99999999; min-width: 300px;';
      notification.innerHTML = `
        <strong>Filtro aplicado:</strong> ${searchType === 'food' ? 'Comidas' : 'Ejercicios'} - "${term}"
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
      `;
      
      document.body.appendChild(notification);
      
      // Auto-remover después de 3 segundos
      setTimeout(() => {
        if (notification.parentElement) {
          notification.remove();
        }
      }, 3000);
    }
  }
};
</script>

<style scoped>
.search-analytics-container {
  min-height: 600px;
  background: #f8f9fa;
}

.stats-card {
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stats-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stats-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #dc3545, #fd7e14);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  color: white;
  font-size: 1.2rem;
}

.stats-number {
  font-size: 2rem;
  font-weight: 700;
  color: #2d3436;
  line-height: 1;
}

.stats-change {
  font-size: 0.75rem;
  margin-top: 0.5rem;
}

.popular-terms-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 1rem;
}

.popular-term {
  font-size: 0.875rem;
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

.clickable-term {
  cursor: pointer;
  transition: all 0.2s ease;
  user-select: none;
}

.clickable-term:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  filter: brightness(1.1);
}

.clickable-term:active {
  transform: translateY(0);
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

.table {
  font-size: 0.875rem;
}

.table th {
  border-bottom: 2px solid #dee2e6;
  background-color: #f8f9fa;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.table tbody tr:hover {
  background-color: rgba(13, 110, 253, 0.05);
}

.table tbody tr.border-bottom {
  border-bottom: 1px solid #f1f3f4;
}

.badge {
  font-size: 0.75rem;
  padding: 0.35em 0.65em;
  font-weight: 500;
}

.btn-group .btn {
  border-radius: 4px;
}

.btn-group .btn:first-child {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.btn-group .btn:last-child {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.form-select:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Scrollbar personalizado para la tabla */
.table-responsive {
  max-height: 400px;
  overflow-y: auto;
}

.table-responsive::-webkit-scrollbar {
  width: 6px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}

/* Animaciones */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .stats-number {
    font-size: 1.5rem;
  }
  
  .popular-terms-container {
    justify-content: center;
  }
  
  .btn-group {
    width: 100%;
  }
  
  .btn-group .btn {
    flex: 1;
  }
}
</style>
