<template>
    <div class="max-w-md mx-auto mt-16 p-8 bg-white rounded-2xl shadow-md">
      <h1 class="text-2xl font-bold text-center mb-6">Login</h1>
      <p v-if="errorMessage" class="text-red-500 text-sm mb-4">{{ errorMessage }}</p>
      <form @submit.prevent="login" class="space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
          <input
            type="email"
            v-model="email"
            id="email"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
  
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input
            type="password"
            v-model="password"
            id="password"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
  
        <button
          @click.prevent="login"
          type="submit"
          class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition"
        >
          Login
        </button>
      </form>
    </div>
  </template>
  
<script>
import axios from 'axios';

export default {
    name: 'authLogin',
    data() {
        return {
            email: null,
            password: null,
            errorMessage: null,
        }
    },
    methods: {
        login() {
          const data = {
            email: this.email,
            password: this.password
          }
          this.$store.dispatch('auth/login', data)
            .then(() => {
              this.$router.push({ name: 'admin.dashboard' });
            })
            .catch(error => {
              if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;

                // Пример: вывести первую ошибку email или password
                this.errorMessage = 
                  (errors.email && errors.email[0]) ||
                  (errors.password && errors.password[0]) ||
                  'Ошибка валидации';

              } else if (error.response && error.response.status === 401) {
                this.errorMessage = error.response.data.message;
              } else {
                alert('Произошла ошибка. Попробуйте позже.');
              }
            });

        }
    }
}
</script>
