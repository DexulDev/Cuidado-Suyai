<template>
  <div class="search-telemetry">
    <!-- Modal -->
    <div class="modal fade modal-modern" id="searchTelemetryModal" tabindex="-1" ref="searchModal">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center gap-2">
              <i class="bi bi-search-heart text-primary"></i> Telemetría de Búsquedas
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <!-- Filters -->
            <div class="filters-container mb-4 p-3 rounded" style="background: rgba(var(--cn-primary-rgb), 0.03);">
              <div class="row g-3">
                <div class="col-md-3">
                  <label class="form-label small fw-medium">Tipo de búsqueda</label>
                  <select v-model="filters.searchType" class="form-select form-select-sm" @change="loadData">
                    <option value="">Todos los tipos</option>
                    <option value="food">Comidas</option>
                    <option value="exercise">Ejercicios</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small fw-medium">Categoría</label>
                  <select v-model="filters.category" class="form-select form-select-sm" @change="loadData">
                    <option value="">Todas las categorías</option>
                    <option v-for="category in categories" :key="category" :value="category">
                      {{ category }}
                    </option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small fw-medium">Período</label>
                  <select v-model="filters.period" class="form-select form-select-sm" @change="loadData">
                    <option value="all">Todo el tiempo</option>
                    <option value="today">Hoy</option>
                    <option value="week">Esta semana</option>
                    <option value="month">Este mes</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small fw-medium">Ordenar por</label>
                  <select v-model="filters.sort" class="form-select form-select-sm" @change="loadData">
                    <option value="newest">Más recientes</option>
                    <option value="oldest">Más antiguos</option>
                    <option value="popular">Más populares</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
              <div class="col-md-3" v-for="(stat, index) in statsCards" :key="index">
                <div class="card-stat rounded p-3" :class="`card-stat--${stat.color}`">
                  <h6 class="small text-muted mb-1">{{ stat.label }}</h6>
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="display-6 fw-bold">{{ stat.value }}</div>
                    <div class="stat-icon">
                      <i :class="stat.icon"></i>
                    </div>
                  </div>
                  <div class="small mt-2" v-if="stat.change !== undefined">
                    <span :class="stat.change >= 0 ? 'text-success' : 'text-danger'">
                      <i :class="stat.change >= 0 ? 'bi bi-graph-up-arrow' : 'bi bi-graph-down-arrow'"></i>
                      {{ Math.abs(stat.change) }}% {{ stat.change >= 0 ? 'incremento' : 'descenso' }}
                    </span>
                    <span class="text-muted ms-1">vs período anterior</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Popular Searches Chart -->
            <div class="chart-container mb-4 p-3 rounded shadow-sm">
              <div class="chart-body">
                <div class="row g-2">
                  <div v-for="term in popularTerms" :key="term.query" class="col-6 col-md-4 col-lg-3">
                    <div class="popular-term p-2 rounded d-flex justify-content-between align-items-center">
                      <span class="text-truncate">{{ term.query || '(vacío)' }}</span>
                      <span class="badge" :class="chartView === 'food' ? 'bg-danger' : 'bg-success'">{{ term.count }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Searches Table -->
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Término</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Resultados</th>
                    <th scope="col">Fecha</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="search in searches" :key="search.id" class="hover-row">
                    <td>{{ search.id }}</td>
                    <td>
                      <span class="fw-medium">{{ search.query || '(vacío)' }}</span>
                    </td>
                    <td>
                      <span class="badge" :class="search.search_type === 'food' ? 'bg-danger' : 'bg-success'">
                        {{ search.search_type === 'food' ? 'Comida' : 'Ejercicio' }}
                      </span>
                    </td>
                    <td>{{ search.category || '-' }}</td>
                    <td>
                      <span class="badge bg-secondary">{{ search.results_count }}</span>
                    </td>
                    <td>
                      <span class="text-muted small">{{ formatDate(search.created_at) }}</span>
                    </td>
                  </tr>
                  <tr v-if="searches.length === 0">
                    <td colspan="6" class="text-center py-4 text-muted">
                      <div><i class="bi bi-search fs-2 mb-2"></i></div>
                      No se encontraron búsquedas con los filtros actuales
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4" v-if="pagination.total_pages > 1">
              <nav>
                <ul class="pagination">
                  <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">
                      <i class="bi bi-chevron-left"></i>
                    </a>
                  </li>
                  <li v-for="page in paginationPages" :key="page" class="page-item" :class="{ active: page === pagination.current_page }">
                    <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                  </li>
                  <li class="page-item" :class="{ disabled: pagination.current_page === pagination.total_pages }">
                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">
                      <i class="bi bi-chevron-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-cn-primary" @click="exportData">
              <i class="bi bi-download me-1"></i> Exportar CSV
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    csrf: { type: String, required: true }
  },
  data() {
    return {
      searches: [],
      popularTerms: [],
      categories: [],
      filters: {
        searchType: '',
        category: '',
        period: 'all',
        sort: 'newest',
        page: 1
      },
      chartView: 'food',
      pagination: {
        current_page: 1,
        total_pages: 1,
        total_records: 0
      },
      stats: {
        total_searches: 0,
        searches_today: 0,
        unique_terms: 0,
        avg_results: 0,
        food_searches: 0,
        exercise_searches: 0,
        growth: 10 // Ejemplo de porcentaje de crecimiento
      },
      loading: false
    };
  },
  computed: {
    paginationPages() {
      const pages = [];
      const current = this.pagination.current_page;
      const total = this.pagination.total_pages;

      // Always show first page
      pages.push(1);
      
      // Show pages around current
      for (let i = Math.max(2, current - 2); i <= Math.min(total - 1, current + 2); i++) {
        pages.push(i);
      }
      
      // Always show last page if more than 1 page
      if (total > 1) {
        pages.push(total);
      }
      
      // Filter and sort
      return [...new Set(pages)].sort((a, b) => a - b);
    },
    
    statsCards() {
      return [
        {
          label: 'Total de búsquedas',
          value: this.stats.total_searches,
          icon: 'bi bi-search',
          color: 'primary',
          change: 12 // Ejemplo
        },
        {
          label: 'Búsquedas hoy',
          value: this.stats.searches_today,
          icon: 'bi bi-calendar-check',
          color: 'success',
          change: 5 // Ejemplo
        },
        {
          label: 'Términos únicos',
          value: this.stats.unique_terms,
          icon: 'bi bi-hash',
          color: 'info'
        },
        {
          label: 'Promedio de resultados',
          value: parseFloat(this.stats.avg_results).toFixed(1),
          icon: 'bi bi-bar-chart',
          color: 'warning'
        }
      ];
    }
  },
  methods: {
    async loadData() {
      this.loading = true;
      try {
        const response = await fetch(`/admin/api/searches?${this.getQueryString()}`, {
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrf
          }
        });
        
        if (response.ok) {
          const data = await response.json();
          this.searches = data.searches;
          this.pagination = data.pagination;
          this.categories = data.categories || [];
          this.stats = data.stats;
          this.popularTerms = data.popular_terms || [];
        } else {
          console.error('Error fetching search telemetry');
        }
      } catch (error) {
        console.error('Error:', error);
      } finally {
        this.loading = false;
      }
    },

    // Método alternativo si prefieres mantener fetchSearches
    async fetchSearches() {
      return this.loadData();
    },

    // Nuevo método para cambiar la vista del gráfico y recargar datos
    changeChartView(view) {
      this.chartView = view;
      this.loadData();
    },
    
    getQueryString() {
      const query = new URLSearchParams();
      for (const [key, value] of Object.entries(this.filters)) {
        if (value) {
          query.append(key, value);
        }
      }
      // Agregar chartView al query string
      if (this.chartView) {
        query.append('chartView', this.chartView);
      }
      return query.toString();
    },
    
    changePage(page) {
      if (page < 1 || page > this.pagination.total_pages) return;
      this.filters.page = page;
      this.loadData();
    },
    
    formatDate(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    },
    
    exportData() {
      // Implement CSV export
      const rows = [
        ['ID', 'Término', 'Tipo', 'Categoría', 'Resultados', 'Fecha']
      ];
      
      this.searches.forEach(search => {
        rows.push([
          search.id,
          search.query || '(vacío)',
          search.search_type === 'food' ? 'Comida' : 'Ejercicio',
          search.category || '-',
          search.results_count,
          this.formatDate(search.created_at)
        ]);
      });
      
      const csvContent = rows.map(row => row.join(',')).join('\n');
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);
      
      link.setAttribute('href', url);
      link.setAttribute('download', `busquedas_${new Date().toISOString().slice(0, 10)}.csv`);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    
    open() {
      this.loadData();
      const bootstrap = window.bootstrap;
      
      // Solución compatible con múltiples versiones de Bootstrap
      try {
        // Primero intentar con el método que ya hemos parchado
        if (bootstrap.Modal.getInstance) {
          const modalInstance = bootstrap.Modal.getInstance(this.$refs.searchModal);
          if (modalInstance) {
            modalInstance.show();
            return;
          }
          
          // Si no existe instancia, crearla
          const newModal = new bootstrap.Modal(this.$refs.searchModal);
          newModal.show();
          return;
        }
        
        // Si getOrCreateInstance está disponible (Bootstrap 5.1+)
        if (bootstrap.Modal.getOrCreateInstance) {
          const modal = bootstrap.Modal.getOrCreateInstance(this.$refs.searchModal);
          modal.show();
          return;
        }
        
        // Fallback: Usar el método de emergencia si está disponible
        if (window.emergencyOpenModal) {
          window.emergencyOpenModal('searchTelemetryModal');
          return;
        }
        
        // Último recurso: Método manual
        const modalEl = this.$refs.searchModal;
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        document.body.classList.add('modal-open');
        
        // Crear backdrop manualmente si es necesario
        if (!document.querySelector('.modal-backdrop')) {
          const backdrop = document.createElement('div');
          backdrop.className = 'modal-backdrop fade show';
          document.body.appendChild(backdrop);
        }
      } catch (error) {
        console.error('Error al abrir modal de telemetría:', error);
        // Intento desesperado: clic en el botón que abre el modal
        const btnOpen = document.querySelector('[data-bs-target="#searchTelemetryModal"]');
        if (btnOpen) btnOpen.click();
      }
    }
  }
};
</script>

<style scoped>
.chart-container, .card-stat {
  background: white;
  border-radius: var(--cn-radius-sm);
  box-shadow: 0 1px 6px rgba(0,0,0,0.05);
}

.card-stat {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  position: relative;
  overflow: hidden;
  border-top: 3px solid transparent;
}

.card-stat:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}

.card-stat--primary { border-top-color: var(--cn-primary); }
.card-stat--success { border-top-color: var(--cn-success); }
.card-stat--info { border-top-color: var(--cn-info); }
.card-stat--warning { border-top-color: var(--cn-warning); }

.stat-icon {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  opacity: 0.2;
  font-size: 1.5rem;
}

.card-stat--primary .stat-icon { background-color: rgba(var(--cn-primary-rgb), 0.1); color: var(--cn-primary); }
.card-stat--success .stat-icon { background-color: rgba(61, 138, 95, 0.1); color: var(--cn-success); }
.card-stat--info .stat-icon { background-color: rgba(74, 111, 168, 0.1); color: var(--cn-info); }
.card-stat--warning .stat-icon { background-color: rgba(245, 166, 35, 0.1); color: var(--cn-warning); }

.popular-term {
  background: var(--cn-surface-alt);
  transition: var(--cn-transition);
}

.popular-term:hover {
  background: var(--cn-surface-warm);
  transform: translateX(3px);
}

.hover-row:hover { 
  background: rgba(var(--cn-primary-rgb), .04); 
}

.page-link {
  color: var(--cn-primary);
  border-color: var(--cn-line);
}

.page-item.active .page-link {
  background-color: var(--cn-primary);
  border-color: var(--cn-primary);
}

.badge.bg-danger {
  background-color: #dc3545 !important;
}

.badge.bg-success {
  background-color: var(--cn-success) !important;
}

.badge.bg-secondary {
  background-color: #6c757d !important;
}

.chart-body {
  min-height: 180px;
}
</style>