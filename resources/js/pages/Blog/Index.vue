<template>
    <div class="max-w-7xl mx-auto p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Список Новостей</h1>
        <router-link    
          :to="{ name: 'admin.categories.create' }" 
          class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95"
        >
          <i class="fas fa-plus"></i>
          <span>Добавить Новость</span>
        </router-link>
      </div>

      <!-- Content -->
      <div v-if="posts.length">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="post in posts" 
            :key="post.id" 
            class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105"
          >
            <img
              :src="post.image ? post.image : '/assets/images/noimg.jpg'"
              alt="Post Image"
              class="w-full h-48 object-cover"
              @error="event.target.src = '/assets/images/noimg.jpg'"
            />
            <div class="p-4">
              <h2 class="text-lg font-bold text-gray-800 mb-2">{{ post.title }}</h2>
              <p class="text-sm text-gray-600 mb-4">{{ post.description }}</p>
              <span 
          class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full"
          :class="{
            'bg-green-500': post.status === 'published',
            'bg-yellow-500': post.status === 'draft',
            'bg-red-500': post.status === 'archived'
          }"
              >
          {{ post.status }}
              </span>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <p>No posts available.</p>
      </div>

    </div>
</template>

<script>

export default {
    name: 'BlogIndex',
    computed: {
        posts() {
            return this.$store.getters['posts/lists'];
        },
    },
    mounted() {
        this.$store.dispatch('posts/lists', { page: 1, pageSize: 10 }).then((res) => {
            console.log('Posts loaded:', this.posts);
        }).catch(error => {
            console.error('Error loading posts:', error);
        });
        
    },
}
</script>

<style scoped>
/* Add your styles here */
</style>