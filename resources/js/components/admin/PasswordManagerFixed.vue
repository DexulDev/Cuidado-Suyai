<template>
  <div class="password-manager">
    <div class="auth-container">
      <div class="auth-card">
        <div class="auth-header">
          <div class="auth-logo">
            <i class="bi bi-shield-lock"></i>
          </div>
          <h1>{{ title }}</h1>
          <p>{{ subtitle }}</p>
        </div>

        <form @submit.prevent="submitForm" class="auth-form">
          <div v-if="formErrors.length" class="auth-alert auth-alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul>
              <li v-for="(error, index) in formErrors" :key="index">{{ error }}</li>
            </ul>
          </div>

          <div v-if="success" class="auth-alert auth-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ success }}</span>
          </div>

          <div class="form-group">
            <label for="new_password">Nueva contraseña</label>
            <div class="input-group">
              <input 
                :type="showPassword ? 'text' : 'password'"
                id="new_password"
                v-model="form.new_password" 
                class="form-control" 
                :class="{'is-invalid': errors.new_password}"
                minlength="5"
                autocomplete="new-password"
                required
              />
              <button 
                type="button" 
                class="btn-toggle-password"
                @click="showPassword = !showPassword" 
                tabindex="-1"
              >
                <i class="bi" :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
              </button>
            </div>
            <div v-if="errors.new_password" class="invalid-feedback">{{ errors.new_password }}</div>
            <div class="password-strength">
              <div class="password-meter" :class="strengthClass"></div>
              <small>{{ strengthText }}</small>
            </div>
          </div>

          <div class="form-group">
            <label for="new_password_confirmation">Confirmar contraseña</label>
            <div class="input-group">
              <input 
                :type="showConfirmPassword ? 'text' : 'password'"
                id="new_password_confirmation"
                v-model="form.new_password_confirmation" 
                class="form-control" 
                :class="{'is-invalid': errors.new_password_confirmation}"
                minlength="5"
                autocomplete="new-password" 
                required
              />
              <button 
                type="button" 
                class="btn-toggle-password"
                @click="showConfirmPassword = !showConfirmPassword" 
                tabindex="-1"
              >
                <i class="bi" :class="showConfirmPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
              </button>
            </div>
            <div v-if="errors.new_password_confirmation" class="invalid-feedback">
              {{ errors.new_password_confirmation }}
            </div>
            <div v-if="passwordsMatch === false && form.new_password_confirmation" class="invalid-match">
              Las contraseñas no coinciden
            </div>
          </div>

          <div class="auth-guidelines">
            <h6><i class="bi bi-info-circle me-2"></i>Recomendaciones de seguridad:</h6>
            <ul>
              <li :class="{ 'fulfilled': form.new_password.length >= 5 }">Al menos 5 caracteres</li>
              <li :class="{ 'fulfilled': hasUpperCase }">Al menos una mayúscula</li>
              <li :class="{ 'fulfilled': hasLowerCase }">Al menos una minúscula</li>
              <li :class="{ 'fulfilled': hasNumber }">Al menos un número</li>
              <li :class="{ 'fulfilled': hasSpecial }">Al menos un carácter especial</li>
            </ul>
          </div>

          <button type="submit" class="btn-auth-submit" :disabled="isSubmitting || !isFormValid">
            <span v-if="isSubmitting">
              <i class="bi bi-arrow-repeat spinner"></i> Guardando...
            </span>
            <span v-else>
              <i class="bi bi-shield-check"></i> Guardar contraseña
            </span>
          </button>

          <div class="auth-footer">
            <a href="/foods" class="auth-back-link">
              <i class="bi bi-arrow-left"></i> Volver al sitio
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PasswordManagerFixed',
  props: {
    csrfToken: { type: String, required: true },
    isFirstTime: { type: Boolean, default: false },
    apiErrors: { type: Array, default: () => [] },
    successMessage: { type: String, default: '' },
    updateUrl: { type: String, required: true },
  },
  data() {
    return {
      form: {
        new_password: '',
        new_password_confirmation: '',
      },
      showPassword: false,
      showConfirmPassword: false,
      errors: {},
      formErrors: [],
      success: '',
      isSubmitting: false,
      showingWeakPasswordModal: false
    }
  },
  computed: {
    title() {
      return this.isFirstTime ? 'Configura tu contraseña' : 'Cambiar contraseña';
    },
    subtitle() {
      return this.isFirstTime 
        ? 'Establece una contraseña segura para acceder al panel de administración' 
        : 'Actualiza tu contraseña de administrador';
    },
    passwordsMatch() {
      if (!this.form.new_password_confirmation) return null;
      return this.form.new_password === this.form.new_password_confirmation;
    },
    hasLength() {
      return this.form.new_password.length >= 8;
    },
    hasUpperCase() {
      return /[A-Z]/.test(this.form.new_password);
    },
    hasLowerCase() {
      return /[a-z]/.test(this.form.new_password);
    },
    hasNumber() {
      return /[0-9]/.test(this.form.new_password);
    },
    hasSpecial() {
      return /[!@#$%^&*(),.?":{}|<>]/.test(this.form.new_password);
    },
    passwordStrength() {
      if (!this.form.new_password) return 0;
      
      let strength = 0;
      if (this.hasLength) strength += 1;
      if (this.hasUpperCase) strength += 1;
      if (this.hasLowerCase) strength += 1;
      if (this.hasNumber) strength += 1;
      if (this.hasSpecial) strength += 1;
      
      return Math.min(strength, 5);
    },
    strengthClass() {
      const strength = this.passwordStrength;
      if (strength === 0) return 'empty';
      if (strength === 1) return 'very-weak';
      if (strength === 2) return 'weak';
      if (strength === 3) return 'medium';
      if (strength === 4) return 'strong';
      return 'very-strong';
    },
    strengthText() {
      const strength = this.passwordStrength;
      if (strength === 0) return 'Sin contraseña';
      if (strength === 1) return 'Muy débil';
      if (strength === 2) return 'Débil';
      if (strength === 3) return 'Media';
      if (strength === 4) return 'Fuerte';
      return 'Muy fuerte';
    },
    isFormValid() {
      return this.form.new_password.length >= 5 && this.passwordsMatch !== false && this.form.new_password.length > 0;
    }
  },
  mounted() {
    if (this.apiErrors.length) {
      this.formErrors = this.apiErrors;
    }
    if (this.successMessage) {
      this.success = this.successMessage;
    }
  },
  methods: {
    validateForm() {
      this.errors = {};
      this.formErrors = [];
      
      if (!this.form.new_password) {
        this.errors.new_password = 'La contraseña es obligatoria';
        this.formErrors.push('La contraseña es obligatoria');
        return false;
      } else if (this.form.new_password.length < 5) {
        // Mínimo 5 caracteres en vez de 8
        this.errors.new_password = 'La contraseña debe tener al menos 5 caracteres';
        this.formErrors.push('La contraseña debe tener al menos 5 caracteres');
        return false;
      }
      
      if (!this.form.new_password_confirmation) {
        this.errors.new_password_confirmation = 'Debes confirmar la contraseña';
        this.formErrors.push('Debes confirmar la contraseña');
        return false;
      } else if (this.form.new_password !== this.form.new_password_confirmation) {
        this.errors.new_password_confirmation = 'Las contraseñas no coinciden';
        this.formErrors.push('Las contraseñas no coinciden');
        return false;
      }
      
      // Permitir contraseñas débiles, solo mostrar advertencia
      if (this.passwordStrength < 3 && this.form.new_password.length >= 5) {
        this.showWeakPasswordModal();
        return true;
      }
      
      return true;
    },
    
    showWeakPasswordModal() {
      if (this.showingWeakPasswordModal) return;
      
      this.showingWeakPasswordModal = true;
      
      // Crear modal de advertencia
      const modalDiv = document.createElement('div');
      modalDiv.className = 'weak-password-modal';
      modalDiv.innerHTML = `
        <div class="weak-password-dialog">
          <div class="weak-password-header">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            <h3>Advertencia de Seguridad</h3>
          </div>
          <div class="weak-password-body">
            <p>La contraseña que has elegido es <strong>débil y fácil de adivinar</strong>.</p>
            <p>Para mayor seguridad, se recomienda usar una contraseña que:</p>
            <ul>
              <li>Tenga al menos 8 caracteres</li>
              <li>Incluya letras mayúsculas y minúsculas</li>
              <li>Incluya números y símbolos</li>
            </ul>
          </div>
          <div class="weak-password-footer">
            <button type="button" class="btn-cancel">Mejorar contraseña</button>
            <button type="button" class="btn-continue">Continuar de todos modos</button>
          </div>
        </div>
      `;
      
      document.body.appendChild(modalDiv);
      
      // Estilos para el modal
      const style = document.createElement('style');
      style.textContent = `
        .weak-password-modal {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: rgba(0,0,0,0.5);
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 2000;
          animation: fadeIn 0.3s ease;
        }
        .weak-password-dialog {
          width: 90%;
          max-width: 450px;
          background: white;
          border-radius: var(--cn-radius);
          box-shadow: 0 15px 40px rgba(0,0,0,0.15);
          overflow: hidden;
        }
        .weak-password-header {
          padding: 1.5rem;
          background: #fff3cd;
          display: flex;
          align-items: center;
          gap: 0.75rem;
        }
        .weak-password-header h3 {
          margin: 0;
          font-size: 1.1rem;
          color: #856404;
        }
        .weak-password-body {
          padding: 1.5rem;
        }
        .weak-password-footer {
          padding: 1rem 1.5rem;
          display: flex;
          justify-content: flex-end;
          gap: 0.75rem;
          border-top: 1px solid #e9ecef;
        }
        .btn-cancel {
          padding: 0.5rem 1rem;
          border: 1px solid #ddd;
          background: white;
          border-radius: var(--cn-radius-sm);
          cursor: pointer;
        }
        .btn-continue {
          padding: 0.5rem 1rem;
          background: var(--cn-primary);
          color: white;
          border: none;
          border-radius: var(--cn-radius-sm);
          cursor: pointer;
        }
        @keyframes fadeIn {
          from { opacity: 0; }
          to { opacity: 1; }
        }
      `;
      
      document.head.appendChild(style);
      
      // Event listeners
      const btnCancel = modalDiv.querySelector('.btn-cancel');
      const btnContinue = modalDiv.querySelector('.btn-continue');
      
      btnCancel.addEventListener('click', () => {
        document.body.removeChild(modalDiv);
        document.head.removeChild(style);
        this.showingWeakPasswordModal = false;
      });
      
      btnContinue.addEventListener('click', () => {
        document.body.removeChild(modalDiv);
        document.head.removeChild(style);
        this.showingWeakPasswordModal = false;
        this.submitFormFinal();
      });
    },
    
    async submitForm() {
      if (!this.validateForm()) return;
      
      // Si la contraseña es fuerte o si pasó la validación y no se muestra el modal,
      // continuar directamente
      if (this.passwordStrength >= 3 || !this.showingWeakPasswordModal) {
        this.submitFormFinal();
      }
    },
    
    async submitFormFinal() {
      this.isSubmitting = true;
      this.errors = {};
      this.formErrors = [];
      
      try {
        
        // Crear un formulario HTML para enviar usando el método POST tradicional
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.updateUrl;
        form.style.display = 'none';
        
        // Añadir campos al formulario
        const passwordField = document.createElement('input');
        passwordField.type = 'password';
        passwordField.name = 'new_password';
        passwordField.value = this.form.new_password;
        
        const confirmField = document.createElement('input');
        confirmField.type = 'password';
        confirmField.name = 'new_password_confirmation';
        confirmField.value = this.form.new_password_confirmation;
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = this.csrfToken;
        
        // Añadir campos al formulario
        form.appendChild(passwordField);
        form.appendChild(confirmField);
        form.appendChild(tokenField);
        
        // Añadir al documento y enviar
        document.body.appendChild(form);
        
        this.success = 'Enviando formulario...';
        
        // Enviar el formulario de manera tradicional
        form.submit();
        
      } catch (error) {
        console.error('Error en la actualización de contraseña:', error);
        this.formErrors = ['Ocurrió un error al procesar la solicitud: ' + error.message];
        this.isSubmitting = false;
      }
    }
  }
}
</script>

<style scoped>
.password-manager {
  display: flex;
  min-height: 60vh;
  align-items: center;
  justify-content: center;
  padding: 2rem 0;
}

.auth-container {
  width: 100%;
  max-width: 460px;
}

.auth-card {
  background: var(--cn-surface);
  border-radius: var(--cn-radius-lg);
  box-shadow: 0 15px 35px rgba(var(--cn-primary-rgb), 0.1);
  overflow: hidden;
  padding: 0;
  position: relative;
}

.auth-header {
  background: linear-gradient(135deg, #8d2b2b, var(--cn-primary));
  padding: 2rem 1.5rem;
  text-align: center;
  color: #fff !important;
}

.auth-logo {
  width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.auth-logo i {
  font-size: 1.8rem;
}

.auth-header h1 {
  font-size: 1.4rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: white !important;
}

.auth-header p {
  font-size: 0.9rem;
  opacity: 0.85;
  margin-bottom: 0;
  color: white !important;
}

.auth-form {
  padding: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
  font-weight: 500;
}

.input-group {
  position: relative;
}

.form-control {
  width: 100%;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  border: 1px solid rgba(0, 0, 0, 0.12);
  border-radius: var(--cn-radius-sm);
  background: #fff;
  transition: all 0.2s;
}

.form-control:focus {
  border-color: var(--cn-primary);
  box-shadow: 0 0 0 3px rgba(var(--cn-primary-rgb), 0.15);
  outline: none;
}

.form-control.is-invalid {
  border-color: #dc3545;
}

.btn-toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #6c757d;
  cursor: pointer;
  padding: 0.25rem 0.5rem;
  font-size: 0.9rem;
  opacity: 0.7;
  transition: opacity 0.2s;
}

.btn-toggle-password:hover {
  opacity: 1;
}

.invalid-feedback, .invalid-match {
  font-size: 0.8rem;
  color: #dc3545;
  margin-top: 0.25rem;
}

.password-strength {
  margin-top: 0.75rem;
}

.password-meter {
  height: 6px;
  border-radius: 3px;
  background: #e9ecef;
  margin-bottom: 0.25rem;
  position: relative;
  overflow: hidden;
}

.password-meter::after {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 0;
  transition: width 0.3s, background-color 0.3s;
}

.password-meter.empty::after { width: 0; }
.password-meter.very-weak::after { width: 20%; background-color: #dc3545; }
.password-meter.weak::after { width: 40%; background-color: #fd7e14; }
.password-meter.medium::after { width: 60%; background-color: #ffc107; }
.password-meter.strong::after { width: 80%; background-color: #20c997; }
.password-meter.very-strong::after { width: 100%; background-color: #198754; }

.password-strength small {
  font-size: 0.75rem;
  color: #6c757d;
}

.auth-guidelines {
  background: rgba(var(--cn-primary-rgb), 0.05);
  border-radius: var(--cn-radius-sm);
  padding: 1rem;
  margin-bottom: 1.5rem;
}

.auth-guidelines h6 {
  font-size: 0.9rem;
  margin-bottom: 0.75rem;
  font-weight: 600;
  color: var(--cn-primary);
  display: flex;
  align-items: center;
}

.auth-guidelines ul {
  margin: 0;
  padding-left: 1.5rem;
  font-size: 0.85rem;
  color: #6c757d;
}

.auth-guidelines li {
  margin-bottom: 0.25rem;
  transition: color 0.3s;
}

.auth-guidelines li.fulfilled {
  color: #198754;
  font-weight: 500;
}

.btn-auth-submit {
  width: 100%;
  background: var(--cn-primary);
  color: white;
  border: none;
  border-radius: var(--cn-radius);
  padding: 0.8rem 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.btn-auth-submit:hover:not(:disabled) {
  background: #8d2b2b;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(var(--cn-primary-rgb), 0.2);
}

.btn-auth-submit:active:not(:disabled) {
  transform: translateY(0);
}

.btn-auth-submit:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.auth-footer {
  text-align: center;
  margin-top: 1.5rem;
}

.auth-back-link {
  color: var(--cn-primary);
  font-size: 0.9rem;
  text-decoration: none;
  transition: opacity 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
}

.auth-back-link:hover {
  opacity: 0.85;
}

.auth-alert {
  padding: 0.8rem 1rem;
  border-radius: var(--cn-radius-sm);
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.auth-alert i {
  font-size: 1.1rem;
  flex-shrink: 0;
}

.auth-alert ul {
  margin: 0;
  padding-left: 1.5rem;
}

.auth-alert-error {
  background-color: rgba(220, 53, 69, 0.1);
  color: #dc3545;
}

.auth-alert-success {
  background-color: rgba(25, 135, 84, 0.1);
  color: #198754;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.spinner {
  animation: spin 1s linear infinite;
  display: inline-block;
}

@media (max-width: 576px) {
  .auth-card {
    border-radius: 0;
    box-shadow: none;
  }
  
  .auth-container {
    max-width: 100%;
  }
  
  .auth-form {
    padding: 1.5rem;
  }
}
</style>
