<template>
  <div class="admin-password-form glass p-4">
    <h4 class="mb-3">Cambiar Contraseña</h4>
    <div class="section-divider mb-4"></div>
    
    <div v-if="status" class="alert" :class="statusClass" role="alert">
      {{ status }}
    </div>
    
    <form @submit.prevent="updatePassword">
      <s-input
        v-model="form.current_password"
        label="Contraseña Actual"
        type="password"
        placeholder="Ingresa tu contraseña actual"
        :error="errors.current_password"
        required
      >
        <template #icon>
          <i class="bi bi-shield-lock"></i>
        </template>
      </s-input>
      
      <s-input
        v-model="form.password"
        label="Nueva Contraseña"
        type="password"
        placeholder="Ingresa tu nueva contraseña"
        :error="errors.password"
        required
      >
        <template #icon>
          <i class="bi bi-shield"></i>
        </template>
      </s-input>
      
      <s-input
        v-model="form.password_confirmation"
        label="Confirmar Nueva Contraseña"
        type="password"
        placeholder="Confirma tu nueva contraseña"
        required
      >
        <template #icon>
          <i class="bi bi-check-circle"></i>
        </template>
      </s-input>
      
      <div class="password-strength" v-if="form.password">
        <div class="strength-label">
          <span>Fortaleza de contraseña:</span>
          <span :class="strengthClass">{{ strengthText }}</span>
        </div>
        <div class="strength-bar">
          <div class="strength-progress" :style="{ width: `${passwordStrength}%`, backgroundColor: strengthColor }"></div>
        </div>
        <div class="strength-tips mt-2 small text-muted" v-if="form.password">
          <p class="mb-1">Para una contraseña fuerte:</p>
          <ul class="ps-3 mb-0">
            <li :class="{ 'text-success': form.password.length >= 8 }">Al menos 8 caracteres</li>
            <li :class="{ 'text-success': /[A-Z]/.test(form.password) }">Al menos una mayúscula</li>
            <li :class="{ 'text-success': /[0-9]/.test(form.password) }">Al menos un número</li>
            <li :class="{ 'text-success': /[^A-Za-z0-9]/.test(form.password) }">Al menos un carácter especial</li>
          </ul>
        </div>
      </div>
      
      <div class="d-flex justify-content-end gap-2 mt-4">
        <s-button type="button" variant="outline-primary">Cancelar</s-button>
        <s-button type="submit" variant="primary" :loading="loading">
          <template #icon>
            <i class="bi bi-check2-circle"></i>
          </template>
          Actualizar Contraseña
        </s-button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        current_password: '',
        password: '',
        password_confirmation: ''
      },
      errors: {},
      status: '',
      statusType: '',
      loading: false
    };
  },
  computed: {
    statusClass() {
      return {
        'alert-success': this.statusType === 'success',
        'alert-danger': this.statusType === 'error'
      };
    },
    passwordStrength() {
      if (!this.form.password) return 0;
      
      let strength = 0;
      const password = this.form.password;
      
      // Length
      if (password.length >= 8) strength += 25;
      
      // Uppercase
      if (/[A-Z]/.test(password)) strength += 25;
      
      // Numbers
      if (/[0-9]/.test(password)) strength += 25;
      
      // Special chars
      if (/[^A-Za-z0-9]/.test(password)) strength += 25;
      
      return strength;
    },
    strengthText() {
      const strength = this.passwordStrength;
      if (strength <= 25) return 'Débil';
      if (strength <= 50) return 'Media';
      if (strength <= 75) return 'Buena';
      return 'Fuerte';
    },
    strengthClass() {
      const strength = this.passwordStrength;
      if (strength <= 25) return 'text-danger';
      if (strength <= 50) return 'text-warning';
      if (strength <= 75) return 'text-info';
      return 'text-success';
    },
    strengthColor() {
      const strength = this.passwordStrength;
      if (strength <= 25) return '#dc3545';
      if (strength <= 50) return '#ffc107';
      if (strength <= 75) return '#0dcaf0';
      return '#198754';
    }
  },
  methods: {
    async updatePassword() {
      this.loading = true;
      this.errors = {};
      this.status = '';
      
      try {
        // Simulated API call
        // In a real application, you would send this to your backend API
        await new Promise(resolve => setTimeout(resolve, 1000)); // Simulated delay
        
        // Simulate validation
        if (this.form.password !== this.form.password_confirmation) {
          this.errors.password = 'Las contraseñas no coinciden';
          throw new Error('Las contraseñas no coinciden');
        }
        
        if (this.passwordStrength < 50) {
          this.errors.password = 'La contraseña es demasiado débil';
          throw new Error('La contraseña es demasiado débil');
        }
        
        // Success message
        this.status = 'Contraseña actualizada correctamente';
        this.statusType = 'success';
        
        // Reset form
        this.form = {
          current_password: '',
          password: '',
          password_confirmation: ''
        };
        
        // Emit toast notification
        window.emitToast('Contraseña actualizada correctamente', 'success');
        
      } catch (error) {
        // Error handling
        this.status = error.message || 'Error al actualizar la contraseña';
        this.statusType = 'error';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.admin-password-form {
  max-width: 650px;
  margin: 2rem auto;
  border-radius: var(--cn-radius);
}

.password-strength {
  margin-top: 1.5rem;
  padding: 1rem;
  background-color: var(--cn-gray-100);
  border-radius: var(--cn-radius-sm);
}

.strength-label {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.strength-bar {
  height: 8px;
  background-color: var(--cn-gray-200);
  border-radius: 4px;
  overflow: hidden;
}

.strength-progress {
  height: 100%;
  border-radius: 4px;
  transition: width 0.3s ease, background-color 0.3s ease;
}

.alert {
  border-radius: var(--cn-radius-sm);
  padding: 1rem;
  margin-bottom: 1.5rem;
}

.alert-success {
  background-color: rgba(25, 135, 84, 0.1);
  border: 1px solid rgba(25, 135, 84, 0.2);
  color: #155724;
}

.alert-danger {
  background-color: rgba(220, 53, 69, 0.1);
  border: 1px solid rgba(220, 53, 69, 0.2);
  color: #721c24;
}
</style>
