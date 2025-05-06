// stores/auth.js
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isAuthenticated: !!localStorage.getItem('token'),
    user: JSON.parse(localStorage.getItem('user')) || null,
  }),
  actions: {
    login(user) {
      this.user = user;
      this.isAuthenticated = true;

      // Store user data and token in localStorage
      const token = 'token_' + Math.random().toString(36).substr(2);
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));
    },
    logout() {
      this.user = null;
      this.isAuthenticated = false;

      // Remove from localStorage
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  }
});
