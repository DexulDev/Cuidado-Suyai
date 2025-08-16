/**
 * Plugin para Vue que corrige problemas de modales en componentes Vue
 * Especialmente útil para los componentes FoodSearch y ExerciseSearch
 */

export default {
  install(app) {
    // Añadir una directiva para modales
    app.directive('modal-fix', {
      // Se ejecuta cuando se monta el elemento
      mounted(el, binding) {
        // Asignar una clase especial para identificar fácilmente
        el.classList.add('vue-modal');
        
        // Si es un contenedor principal, aplicar estilos específicos
        if (el.classList.contains('modal')) {
          // Encontrar y ajustar los elementos internos
          const dialog = el.querySelector('.modal-dialog');
          const content = el.querySelector('.modal-content');
          
          if (dialog) dialog.classList.add('vue-modal-dialog');
          if (content) content.classList.add('vue-modal-content');
          
          // Escuchar eventos de modal
          el.addEventListener('shown.bs.modal', function() {
            // Eliminar aria-hidden cuando el modal esté completamente mostrado
            el.removeAttribute('aria-hidden');
            
            // Asegurar que el foco vaya a un lugar adecuado
            const closeButton = el.querySelector('.btn-close');
            if (closeButton) {
              closeButton.focus();
            }
          });
          
          // Prevenir que aria-hidden se añada mientras el modal está visible
          const observer = new MutationObserver((mutations) => {
            mutations.forEach(mutation => {
              if (mutation.attributeName === 'aria-hidden' && 
                  el.classList.contains('show') && 
                  el.getAttribute('aria-hidden') === 'true') {
                el.removeAttribute('aria-hidden');
              }
            });
          });
          
          // Configurar observador
          observer.observe(el, {
            attributes: true,
            attributeFilter: ['aria-hidden', 'class']
          });
          
          // Limpiar cuando el componente se desmonta
          el._modalFixObserver = observer;
        }
      },
      
      // Cuando el componente se destruye
      unmounted(el) {
        // Limpiar observadores para evitar memory leaks
        if (el._modalFixObserver) {
          el._modalFixObserver.disconnect();
          delete el._modalFixObserver;
        }
      }
    });
    
    // Parchar los modales Bootstrap cuando se crean a través de Javascript
    const originalModalConstructor = window.bootstrap?.Modal;
    if (originalModalConstructor) {
      window.bootstrap.Modal = function(element, options) {
        // Llamar al constructor original
        const modalInstance = new originalModalConstructor(element, options);
        
        // Parchar el método show
        const originalShow = modalInstance.show;
        modalInstance.show = function() {
          // Ejecutar el show original
          originalShow.call(this);
          
          // Ajustes post-show
          setTimeout(() => {
            if (element.getAttribute('aria-hidden') === 'true' && 
                element.classList.contains('show')) {
              element.removeAttribute('aria-hidden');
            }
          }, 50);
          
          return this;
        };
        
        return modalInstance;
      };
      
      // Copiar propiedades estáticas INCLUYENDO getInstance
      Object.keys(originalModalConstructor).forEach(key => {
        window.bootstrap.Modal[key] = originalModalConstructor[key];
      });
      
      // Asegurar que getInstance esté disponible
      window.bootstrap.Modal.getInstance = originalModalConstructor.getInstance;
      window.bootstrap.Modal.getOrCreateInstance = originalModalConstructor.getOrCreateInstance;
    }
  }
};
