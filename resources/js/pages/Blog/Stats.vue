<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <!-- Заголовок и переключатели -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Статистика Новостей</h1>
      
      <div class="flex items-center gap-4">
        <router-link
          :to="{ name: 'admin.blog.list' }"
          class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95 cursor-pointer"
        >
          <i class="fas fa-list"></i>
          <span>Список Новостей</span>
        </router-link>
      </div>
    </div>
    <!-- Статистика -->
    <div v-if="stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Общее количество постов -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <div class="flex items-center">
          <div class="rounded-full bg-blue-100/20 p-3 backdrop-blur-sm">
            <i class="fas fa-newspaper text-blue-500 text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Всего постов</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.total_count || 0 }}</p>
          </div>
        </div>
      </div>

      <!-- Общее количество просмотров -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <div class="flex items-center">
          <div class="rounded-full bg-green-100/20 p-3 backdrop-blur-sm">
            <i class="fas fa-eye text-green-500 text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Всего просмотров</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.total_views || 0 }}</p>
          </div>
        </div>
      </div>

      <!-- Общее количество комментариев -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <div class="flex items-center">
          <div class="rounded-full bg-purple-100/20 p-3 backdrop-blur-sm">
            <i class="fas fa-comment text-purple-500 text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Всего комментариев</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.total_comments || 0 }}</p>
          </div>
        </div>
      </div>

      <!-- Средний рейтинг -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <div class="flex items-center">
          <div class="rounded-full bg-yellow-100/20 p-3 backdrop-blur-sm">
            <i class="fas fa-star text-yellow-500 text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Средний рейтинг</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.average_rating || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Сравнение периодов -->
    <div v-if="stats" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Сравнение с предыдущим месяцем -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">За месяц</h3>
        <div class="flex items-center justify-between">
          <div>
            <p class="text-2xl font-bold text-gray-900">{{ stats.monthly_comparison?.current || 0 }}</p>
            <p class="text-sm text-gray-600">Текущий месяц</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600">Предыдущий месяц</p>
            <p class="text-lg font-medium text-gray-800">{{ stats.monthly_comparison?.previous || 0 }}</p>
          </div>
        </div>
        <div v-if="stats.monthly_comparison" class="mt-4 flex items-center" :class="stats.monthly_comparison.difference >= 0 ? 'text-green-600' : 'text-red-600'">
          <i class="fas" :class="stats.monthly_comparison.difference >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'"></i>
          <span class="ml-2 font-medium">
            {{ Math.abs(stats.monthly_comparison.difference) }} 
            ({{ Math.abs(stats.monthly_comparison.percentage) }}%)
          </span>
        </div>
      </div>

      <!-- Сравнение с предыдущим годом -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">За год</h3>
        <div class="flex items-center justify-between">
          <div>
            <p class="text-2xl font-bold text-gray-900">{{ stats.yearly_comparison?.current || 0 }}</p>
            <p class="text-sm text-gray-600">Текущий год</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600">Предыдущий год</p>
            <p class="text-lg font-medium text-gray-800">{{ stats.yearly_comparison?.previous || 0 }}</p>
          </div>
        </div>
        <div v-if="stats.yearly_comparison" class="mt-4 flex items-center" :class="stats.yearly_comparison.difference >= 0 ? 'text-green-600' : 'text-red-600'">
          <i class="fas" :class="stats.yearly_comparison.difference >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'"></i>
          <span class="ml-2 font-medium">
            {{ Math.abs(stats.yearly_comparison.difference) }} 
            ({{ Math.abs(stats.yearly_comparison.percentage) }}%)
          </span>
        </div>
      </div>
    </div>

    <!-- Дополнительная статистика -->
    <div v-if="stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- По статусам -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">По статусам</h3>
        <div v-for="(count, status) in stats.by_status" :key="status" class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
          <span class="text-sm font-medium text-gray-700 capitalize">{{ status }}</span>
          <span class="text-sm font-bold text-gray-900">{{ count }}</span>
        </div>
      </div>

      <!-- Популярный контент -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Популярный контент</h3>
        <div v-if="stats.most_viewed_post" class="mb-4">
          <p class="text-sm font-medium text-gray-700">Самый просматриваемый</p>
          <p class="text-sm text-gray-900 truncate">{{ stats.most_viewed_post.title }}</p>
          <p class="text-xs text-gray-600">{{ stats.most_viewed_post.views }} просмотров</p>
        </div>
        <div v-if="stats.most_commented_post" class="mb-4">
          <p class="text-sm font-medium text-gray-700">Самый комментируемый</p>
          <p class="text-sm text-gray-900 truncate">{{ stats.most_commented_post.title }}</p>
          <p class="text-xs text-gray-600">{{ stats.most_commented_post.comments_count }} комментариев</p>
        </div>
        <div v-if="stats.most_rated_post">
          <p class="text-sm font-medium text-gray-700">Самый рейтинговый</p>
          <p class="text-sm text-gray-900 truncate">{{ stats.most_rated_post.title }}</p>
          <p class="text-xs text-gray-600">Рейтинг: {{ stats.most_rated_post.rating }}</p>
        </div>
      </div>

      <!-- Дополнительные метрики -->
      <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-6 shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Дополнительно</h3>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="text-sm font-medium text-gray-700">С галереей</span>
          <span class="text-sm font-bold text-gray-900">{{ stats.with_gallery || 0 }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="text-sm font-medium text-gray-700">С изображениями</span>
          <span class="text-sm font-bold text-gray-900">{{ stats.with_images || 0 }}</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="text-sm font-medium text-gray-700">За последние 30 дней</span>
          <span class="text-sm font-bold text-gray-900">{{ stats.recent_posts || 0 }}</span>
        </div>
        <div class="flex justify-between items-center py-2">
          <span class="text-sm font-medium text-gray-700">Активные (7 дней)</span>
          <span class="text-sm font-bold text-gray-900">{{ stats.recent_active_posts || 0 }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Stats',
  data() {
    return {
      viewMode: 'grid'
    }
  },
  computed: {
    stats() {
      return this.$store.getters['posts/stats'] || {};
    },
  },
  
  methods: {
    getPostTitle(post) {
      if (post.title && post.title.trim()) {
        return post.title;
      }
      
      if (post.translations && post.translations.length > 0) {
        const defaultTranslation = post.translations.find(translation => translation.default === true);
        if (defaultTranslation && defaultTranslation.title && defaultTranslation.title.trim()) {
          return defaultTranslation.title;
        }
        
        const translationWithTitle = post.translations.find(translation => 
          translation.title && translation.title.trim()
        );
        if (translationWithTitle) {
          return translationWithTitle.title;
        }
      }
      
      return 'Без заголовка';
    },
    
    getPostDescription(post) {
      if (post.description && post.description.trim()) {
        return post.description;
      }
      
      if (post.translations && post.translations.length > 0) {
        const defaultTranslation = post.translations.find(translation => translation.default === true);
        if (defaultTranslation && defaultTranslation.description && defaultTranslation.description.trim()) {
          return defaultTranslation.description;
        }
        
        const translationWithDescription = post.translations.find(translation => 
          translation.description && translation.description.trim()
        );
        if (translationWithDescription) {
          return translationWithDescription.description;
        }
      }
      
      return 'Описание отсутствует';
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
    this.$store.dispatch('posts/stats', { page: 1, pageSize: 10 }).then((res) => {
      console.log('Posts loaded:', this.posts);
    }).catch(error => {
      console.error('Error loading posts:', error);
    });
  },
}
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.description-clamp {
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.4;
  max-height: 5.6em;
}

/* Гарантируем что все карточки одинаковой высоты */
.h-\[460px\] {
  height: 460px;
}

/* Дополнительная страховка для ограничения текста */
.text-lg {
  line-height: 1.3;
}

/* Плавные переходы для кнопок */
.group {
  transition: all 0.3s ease;
}

.group-hover\:brightness-90 {
  filter: brightness(0.9);
}
</style>