/**
 * Modales sin backdrop - Solución mínima
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Función simple para eliminar backdrops
    function removeBackdrops() {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    }
    
    // Solo configurar modales cuando se abran (excluir modales de admin)
    document.addEventListener('show.bs.modal', function(e) {
        const modal = e.target;
        
        // No intervenir en modales de admin (que necesitan backdrop)
        if (modal.id.includes('Admin') || modal.id.includes('admin') || 
            modal.classList.contains('admin-modal')) {
            return;
        }
        
        modal.setAttribute('data-bs-backdrop', 'false');
        setTimeout(removeBackdrops, 10);
    });
    
    // Arreglar problema de ARIA cuando el modal se muestra
    document.addEventListener('shown.bs.modal', function(e) {
        const modal = e.target;
        
        // SIEMPRE quitar aria-hidden de modales que están siendo mostrados
        modal.removeAttribute('aria-hidden');
        
        // Para modales de admin, asegurar accesibilidad completa
        if (modal.id.includes('Admin') || modal.id.includes('admin') || 
            modal.classList.contains('admin-modal')) {
            // Monitorear cambios de aria-hidden en modales de admin
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'aria-hidden' && 
                        modal.classList.contains('show') && 
                        modal.getAttribute('aria-hidden') === 'true') {
                        modal.removeAttribute('aria-hidden');
                    }
                });
            });
            
            observer.observe(modal, {
                attributes: true,
                attributeFilter: ['aria-hidden']
            });
            
            // Limpiar observer cuando el modal se oculte
            modal.addEventListener('hidden.bs.modal', function() {
                observer.disconnect();
            }, { once: true });
        }
    });
    
    // Eliminar backdrops cuando aparezcan (pero solo los no-admin)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.classList && node.classList.contains('modal-backdrop')) {
                    // Verificar si hay modales de admin abiertos
                    const adminModalsOpen = document.querySelectorAll('.modal.show.admin-modal, .modal.show[id*="Admin"], .modal.show[id*="admin"]');
                    
                    // Solo eliminar backdrop si no hay modales de admin abiertos
                    if (adminModalsOpen.length === 0) {
                        node.remove();
                    } else {
                        // Agregar clase para identificar backdrops de admin
                        node.classList.add('admin-backdrop');
                    }
                }
            });
        });
    });
    
    observer.observe(document.body, { childList: true });
    
    // Auto-limpieza cada 2 segundos para prevenir problemas de aria-hidden
    setInterval(function() {
        document.querySelectorAll('.modal.show[aria-hidden="true"]').forEach(modal => {
            modal.removeAttribute('aria-hidden');
        });
    }, 2000);
    
    // Función de emergencia para reparar modales
    window.fixModals = function() {
        // Contar modales
        const allModals = document.querySelectorAll('.modal');
        const activeModals = document.querySelectorAll('.modal.show');
        const adminModals = document.querySelectorAll('.modal.show.admin-modal, .modal.show[id*="Admin"], .modal.show[id*="admin"]');
        const backdrops = document.querySelectorAll('.modal-backdrop');
        
        // ARREGLO CRÍTICO: Quitar aria-hidden de modales visibles
        activeModals.forEach(modal => {
            if (modal.getAttribute('aria-hidden') === 'true') {
                modal.removeAttribute('aria-hidden');
            }
        });
        
        // Solo limpiar modales que no sean de admin
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            if (adminModals.length === 0) {
                backdrop.remove();
            }
        });
        
        document.querySelectorAll('.modal').forEach(modal => {
            if (!modal.id.includes('Admin') && !modal.id.includes('admin') && !modal.classList.contains('admin-modal')) {
                modal.setAttribute('data-bs-backdrop', 'false');
                if (!modal.classList.contains('show')) {
                    modal.removeAttribute('aria-hidden');
                }
            }
        });
    };
    
    // Función para verificar estado de modal específico
    window.debugModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            return;
        }
        
        return {
            classes: modal.className,
            backdrop: modal.getAttribute('data-bs-backdrop'),
            ariaHidden: modal.getAttribute('aria-hidden'),
            visible: modal.classList.contains('show'),
            isAdmin: modal.id.includes('Admin') || modal.id.includes('admin') || modal.classList.contains('admin-modal')
        };
    };
});
