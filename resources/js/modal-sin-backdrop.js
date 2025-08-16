/**
 * Modales sin backdrop - Solución mínima
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('[Modal Sin Backdrop] Iniciando...');
    
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
            console.log('[Modal Sin Backdrop] Ignorando modal de admin:', modal.id);
            return;
        }
        
        console.log('[Modal Sin Backdrop] Configurando modal sin backdrop:', modal.id);
        modal.setAttribute('data-bs-backdrop', 'false');
        setTimeout(removeBackdrops, 10);
    });
    
    // Arreglar problema de ARIA cuando el modal se muestra (solo para modales de vista)
    document.addEventListener('shown.bs.modal', function(e) {
        const modal = e.target;
        
        // No intervenir en modales de admin
        if (modal.id.includes('Admin') || modal.id.includes('admin') || 
            modal.classList.contains('admin-modal')) {
            console.log('[Modal Sin Backdrop] Manteniendo comportamiento normal para modal admin:', modal.id);
            return;
        }
        
        // Eliminar aria-hidden cuando el modal está visible
        modal.removeAttribute('aria-hidden');
        setTimeout(removeBackdrops, 10);
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
                        console.log('[Modal Sin Backdrop] Eliminando backdrop (sin modales admin abiertos)');
                        node.remove();
                    } else {
                        console.log('[Modal Sin Backdrop] Manteniendo backdrop para modal admin');
                        // Agregar clase para identificar backdrops de admin
                        node.classList.add('admin-backdrop');
                    }
                }
            });
        });
    });
    
    observer.observe(document.body, { childList: true });
    
    // Función de emergencia y debug
    window.fixModals = function() {
        console.log('[Modal Debug] Estado actual de modales:');
        
        // Contar modales
        const allModals = document.querySelectorAll('.modal');
        const activeModals = document.querySelectorAll('.modal.show');
        const adminModals = document.querySelectorAll('.modal.show.admin-modal, .modal.show[id*="Admin"], .modal.show[id*="admin"]');
        const backdrops = document.querySelectorAll('.modal-backdrop');
        
        console.log(`- Modales totales: ${allModals.length}`);
        console.log(`- Modales activos: ${activeModals.length}`);
        console.log(`- Modales admin activos: ${adminModals.length}`);
        console.log(`- Backdrops: ${backdrops.length}`);
        
        // Mostrar detalles de modales activos
        activeModals.forEach((modal, i) => {
            console.log(`  Modal ${i + 1}: ID="${modal.id}", Classes="${modal.className}"`);
        });
        
        // Solo limpiar modales que no sean de admin
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            if (adminModals.length === 0) {
                backdrop.remove();
                console.log('[Modal Debug] Backdrop eliminado');
            } else {
                console.log('[Modal Debug] Backdrop mantenido (hay modales admin)');
            }
        });
        
        document.querySelectorAll('.modal').forEach(modal => {
            if (!modal.id.includes('Admin') && !modal.id.includes('admin') && !modal.classList.contains('admin-modal')) {
                modal.setAttribute('data-bs-backdrop', 'false');
                modal.removeAttribute('aria-hidden');
                console.log(`[Modal Debug] Modal "${modal.id}" configurado sin backdrop`);
            } else {
                console.log(`[Modal Debug] Modal admin "${modal.id}" mantenido con backdrop`);
            }
        });
    };
    
    // Función para verificar estado de modal específico
    window.debugModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.log(`[Modal Debug] Modal "${modalId}" no encontrado`);
            return;
        }
        
        console.log(`[Modal Debug] Estado del modal "${modalId}":`);
        console.log(`- Clases: ${modal.className}`);
        console.log(`- data-bs-backdrop: ${modal.getAttribute('data-bs-backdrop')}`);
        console.log(`- aria-hidden: ${modal.getAttribute('aria-hidden')}`);
        console.log(`- Visible: ${modal.classList.contains('show')}`);
        console.log(`- Es admin: ${modal.id.includes('Admin') || modal.id.includes('admin') || modal.classList.contains('admin-modal')}`);
    };
    
    console.log('[Modal Sin Backdrop] Listo. Funciones disponibles:');
    console.log('- window.fixModals() - Reparar problemas de modales');
    console.log('- window.debugModal(id) - Debug de modal específico');
    console.log('✅ Sistema de modales inicializado correctamente');
});
