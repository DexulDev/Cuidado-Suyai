import SButton from './SButton.vue';
import SInput from './SInput.vue';
import SCard from './SCard.vue';
import SBadge from './SBadge.vue';
import ModalPortal from '../ui/ModalPortal.vue';
import ModalPlugin from '../ui/ModalPlugin';

export {
  SButton,
  SInput,
  SCard,
  SBadge,
  ModalPortal
};

export default {
  install(app) {
    // Registrar componentes
    app.component('SButton', SButton);
    app.component('SInput', SInput);
    app.component('SCard', SCard);
    app.component('SBadge', SBadge);
    app.component('ModalPortal', ModalPortal);
    
    // Instalar el plugin de modales
    app.use(ModalPlugin);
  }
};
