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
    <div v-if="processedPosts.length">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="post in processedPosts"
          :key="post.id"
          class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105"
        >
          <img
            :src="post.image ? post.image : '/assets/images/noimg.jpg'"
            alt="Post Image"
            class="w-full h-48 object-cover"
            @error="event.target.src = '/assets/images/noimg.jpg'"
          />
          <div class="p-4 relative">
            <h2 class="text-lg font-bold text-gray-800 mb-2">
              {{ post.displayTitle }}
            </h2>
            <p class="text-sm text-gray-600 mb-4">
              {{ post.displayDescription }}
            </p>
            <div class="flex justify-between items-center">
              <span
                class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full absolute top-[-12px]"
                :class="{
                  'bg-green-500': post.status === 'published',
                  'bg-yellow-500': post.status === 'draft',
                  'bg-red-500': post.status === 'archived'
                }"
              >
                {{ post.status }}
              </span>
              <span class="text-xs text-gray-500">
                {{ formatDate(post.published_at) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div v-else>
      <p class="text-center text-gray-500 py-8">No posts available.</p>
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
    
    processedPosts() {
      if (!this.posts || !Array.isArray(this.posts)) {
        return [];
      }
      
      return this.posts.map(post => ({
        ...post,
        displayTitle: this.getPostTitle(post),
        displayDescription: this.getPostDescription(post)
      }));
    }
  },
  
  methods: {
    getPostTitle(post) {
      // Если есть основной title, используем его
      if (post.title && post.title.trim()) {
        return post.title;
      }
      
      // Иначе ищем в переводах default translation
      if (post.translations && post.translations.length > 0) {
        const defaultTranslation = post.translations.find(translation => translation.default === true);
        if (defaultTranslation && defaultTranslation.title && defaultTranslation.title.trim()) {
          return defaultTranslation.title;
        }
        
        // Если нет default, берем первый доступный title
        const translationWithTitle = post.translations.find(translation => 
          translation.title && translation.title.trim()
        );
        if (translationWithTitle) {
          return translationWithTitle.title;
        }
      }
      
      return 'Без заголовка'; // fallback
    },
    
    getPostDescription(post) {
      // Если есть основное description, используем его
      if (post.description && post.description.trim()) {
        return post.description;
      }
      
      // Иначе ищем в переводах default translation
      if (post.translations && post.translations.length > 0) {
        const defaultTranslation = post.translations.find(translation => translation.default === true);
        if (defaultTranslation && defaultTranslation.description && defaultTranslation.description.trim()) {
          return defaultTranslation.description;
        }
        
        // Если нет default, берем первое доступное description
        const translationWithDescription = post.translations.find(translation => 
          translation.description && translation.description.trim()
        );
        if (translationWithDescription) {
          return translationWithDescription.description;
        }
      }
      
      return 'Описание отсутствует'; // fallback
    },
    
    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      return date.toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    }
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