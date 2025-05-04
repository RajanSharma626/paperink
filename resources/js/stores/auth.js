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

      // Store the user data and token in localStorage
      localStorage.setItem('token', 'your_token_here'); // Make sure to set the correct token here
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
