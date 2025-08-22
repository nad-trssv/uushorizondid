<template>
    <div class="max-w-md mx-auto mt-16 p-8 bg-white rounded-2xl shadow-md">
      <h1 class="text-2xl font-bold text-center mb-6">Register</h1>
      <form @submit.prevent="register" class="space-y-6">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input
            type="text"
            v-model="name"
            id="name"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
  
        <!-- Email -->
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
  
        <!-- Password -->
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
  
        <!-- Confirm Password -->
        <div>
          <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input
            type="password"
            v-model="confirmPassword"
            id="confirmPassword"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
  
        <!-- Submit -->
        <button
          @click.prevent="register"
          type="submit"
          class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition"
        >
          Register
        </button>
      </form>
    </div>
  </template>
  
<script>
export default {
    name: 'authRegister',
    data() {
        return {
            name: null,
            email: null,
            password: null,
            confirmPassword: null
        }
    },
    mounted() {
        if (localStorage.getItem('auth')) {
          console.log('User is already logged in', JSON.parse(localStorage.getItem('auth')).access_token);
        }
    },
    methods: {
        register() {
            const data = {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.confirmPassword
            };

            axios.post('/api/v1/auth/register', data)
                .then(response => {
                  const data = {
                    user: response.data.user,
                    access_token: response.data.access_token,
                  }
                  localStorage.setItem('auth', JSON.stringify(data));
                  this.$router.push({ name: 'dashboard' });
                })
                .catch(error => {
                    console.error('Registration error:', error.response.data);
                });
        }
    }
}
</script>