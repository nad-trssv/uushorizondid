<template>
    <div>
      <!-- <h1 class="text-center">Welcome to the Vue.js Component</h1>
      <router-link :to="{ name: 'home' }" class="mx-2">Home</router-link>
      <router-link v-if="authStatus" :to="{ name: 'admin.services' }" class="mx-2">Services</router-link>
      <router-link v-if="!authStatus" :to="{ name: 'authLogin' }" class="mx-2">Login</router-link>
      <router-link v-if="!authStatus" :to="{ name: 'authRegister' }" class="mx-2">Register</router-link>
      <router-link v-if="authStatus" :to="{ name: 'admin.dashboard' }" class="mx-2">Dashboard</router-link>
      <a href="#" v-if="authStatus" @click.prevent="logout">Logout</a> -->
      
      <n-config-provider>
        <router-view />
      </n-config-provider>
    </div>
  </template>
  
  <script>
  import { NConfigProvider } from 'naive-ui';
  export default {
    name: 'Index',
    components: {
      NConfigProvider,
    },
    data() {
        return {
        };
    },
    computed: {
        authStatus() {
            return this.$store.getters['auth/authStatus'];
        },
        authToken() {
            return this.$store.getters['auth/authToken'];
        },
    },
    mounted() {
        this.$store.dispatch('auth/getAccessToken');
    },
    methods: {
      async logout() {
        try {
          await this.$store.dispatch('auth/logout');
          this.$router.push({ name: 'authLogin' });
        } catch (error) {
          console.error('Logout failed:', error);
        }
      },
    },
  };
  </script>
  