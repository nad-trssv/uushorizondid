<template>
    <div class="max-w-7xl mx-auto p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Список Новостей</h1>
        
        <div class="flex items-center gap-4">
          <!-- Переключатель вида -->
          <div class="flex bg-white/10 backdrop-blur-md border border-white/30 rounded-xl p-1">
            <button 
              @click="viewMode = 'grid'"
              class="p-2 rounded-lg transition-all cursor-pointer"
              :class="{
                'bg-white/20 text-gray-800': viewMode === 'grid',
                'text-gray-600 hover:bg-white/10': viewMode !== 'grid'
              }"
              title="Сетка"
            >
              <i class="fas fa-th"></i>
            </button>
            <button 
              @click="viewMode = 'list'"
              class="p-2 rounded-lg transition-all cursor-pointer"
              :class="{
                'bg-white/20 text-gray-800': viewMode === 'list',
                'text-gray-600 hover:bg-white/10': viewMode !== 'list'
              }"
              title="Список"
            >
              <i class="fas fa-list"></i>
            </button>
            <button 
              @click="viewMode = 'table'"
              class="p-2 rounded-lg transition-all cursor-pointer"
              :class="{
                'bg-white/20 text-gray-800': viewMode === 'table',
                'text-gray-600 hover:bg-white/10': viewMode !== 'table'
              }"
              title="Таблица"
            >
              <i class="fas fa-table"></i>
            </button>
          </div>
          
          <router-link
            :to="{ name: 'admin.categories.create' }"
            class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95 cursor-pointer"
          >
            <i class="fas fa-plus"></i>
            <span>Добавить Новость</span>
          </router-link>
        </div>
      </div>
      
      <!-- Content -->
      <div v-if="processedPosts.length">
        <!-- Сеточный вид -->
        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="post in processedPosts"
            :key="post.id"
            class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105 flex flex-col h-[460px] relative group"
          >
            <!-- Рейтинг (абсолютное позиционирование) -->
            <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1.5 flex items-center gap-1 shadow-md z-10 border border-white/30">
              <i class="fas fa-star text-yellow-500 text-sm"></i>
              <span class="text-sm font-bold text-gray-800">{{ post.averageRating }}</span>
            </div>
            
            <!-- Кнопки действий (появляются при наведении) -->
            <div class="absolute top-3 left-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
              <button class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer">
                <i class="fas fa-edit text-xs"></i>
              </button>
              <button class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer">
                <i class="fas fa-trash text-xs"></i>
              </button>
              <button class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer">
                <i class="fas fa-eye text-xs"></i>
              </button>
            </div>
            
            <img
              :src="post.image ? post.image : '/assets/images/noimg.jpg'"
              alt="Post Image"
              class="w-full h-48 object-cover transition-transform group-hover:brightness-90"
              @error="event.target.src = '/assets/images/noimg.jpg'"
            />
            
            <div class="p-4 flex-1 flex flex-col relative">
              <!-- Статус (абсолютное позиционирование) -->
              <span
                class="inline-block px-3 py-1 text-xs font-semibold rounded-full absolute top-[-12px] left-4 border-1 border-gray-200"
                :class="{
                  'bg-white/50 text-green-500': post.status === 'published',
                  'bg-white/50 text-yellow-500': post.status === 'draft',
                  'bg-white/50 text-red-500': post.status === 'archived'
                }"
              >
                {{ post.status }}
              </span>
              
              <!-- Заголовок (максимум 2 строки) -->
              <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 leading-tight">
                {{ post.displayTitle }}
              </h2>
              
              <!-- Описание (максимум 4 строки) -->
              <p class="text-sm text-gray-600 mb-4 description-clamp flex-1">
                {{ post.displayDescription }}
              </p>
              
              <!-- Нижний блок - всегда внизу карточки -->
              <div class="flex justify-between items-center pt-3 mt-auto border-t border-gray-100">
                <span class="text-xs text-gray-500">
                  {{ formatDate(post.published_at) }}
                </span>
                
                <div class="flex items-center gap-3">
                  <!-- Количество фотографий -->
                  <div class="flex items-center gap-1" v-if="post.gallery">
                    <i class="fas fa-image text-blue-500 text-xs"></i>
                    <span class="text-xs text-gray-600">{{ post.gallery.length }}</span>
                  </div>
                  
                  <!-- Количество комментариев -->
                  <div class="flex items-center gap-1">
                    <i class="fas fa-comment text-purple-500 text-xs"></i>
                    <span class="text-xs text-gray-600">{{ post.comments_count }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Списковый вид -->
        <div v-else-if="viewMode === 'list'" class="space-y-3">
          <div
            v-for="post in processedPosts"
            :key="post.id"
            class="bg-white rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg group h-28"
          >
            <div class="flex h-full">
              <!-- Изображение -->
              <div class="w-28 min-w-[7rem] relative">
                <img
                  :src="post.image ? post.image : '/assets/images/noimg.jpg'"
                  alt="Post Image"
                  class="w-full h-full object-cover"
                  @error="event.target.src = '/assets/images/noimg.jpg'"
                />
                
                <!-- Рейтинг -->
                <div class="absolute top-2 right-2 bg-white/20 backdrop-blur-sm rounded-full px-2 py-1 flex items-center gap-1 shadow-md z-10 border border-white/30">
                  <i class="fas fa-star text-yellow-500 text-xs"></i>
                  <span class="text-xs font-bold text-gray-800">{{ post.averageRating }}</span>
                </div>
              </div>
              
              <!-- Контент -->
              <div class="flex-1 p-3 relative">
                <!-- Статус -->
                <span
                  class="absolute top-2 right-2 px-2 py-1 text-xs font-semibold text-white rounded-full"
                  :class="{
                    'bg-green-500': post.status === 'published',
                    'bg-yellow-500': post.status === 'draft',
                    'bg-red-500': post.status === 'archived'
                  }"
                >
                  {{ post.status }}
                </span>
                
                <!-- Кнопки действий -->
                <div class="absolute bottom-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <button class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                    <i class="fas fa-eye text-xs"></i>
                  </button>
                  <button class="bg-green-500 hover:bg-green-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                    <i class="fas fa-edit text-xs"></i>
                  </button>
                  <button class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </div>
                
                <h2 class="text-sm font-bold text-gray-800 mb-1 line-clamp-1 pr-16">
                  {{ post.displayTitle }}
                </h2>
                
                <p class="text-xs text-gray-600 mb-2 line-clamp-1 pr-16">
                  {{ post.displayDescription }}
                </p>
                
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                  <span>{{ formatDate(post.published_at) }}</span>
                  
                  <div class="flex items-center gap-1" v-if="post.gallery">
                    <i class="fas fa-image text-blue-500"></i>
                    <span>{{ post.gallery.length }}</span>
                  </div>
                  
                  <div class="flex items-center gap-1">
                    <i class="fas fa-comment text-purple-500"></i>
                    <span>{{ post.comments_count }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Табличный вид -->
        <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Изображение</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Заголовок</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Рейтинг</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Фото</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Комменты</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="post in processedPosts" :key="post.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <img
                    :src="post.image ? post.image : '/assets/images/noimg.jpg'"
                    alt="Post Image"
                    class="w-12 h-12 object-cover rounded"
                    @error="event.target.src = '/assets/images/noimg.jpg'"
                  />
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ post.displayTitle }}</div>
                  <div class="text-xs text-gray-500 line-clamp-1">{{ post.displayDescription }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full text-white"
                    :class="{
                      'bg-green-500': post.status === 'published',
                      'bg-yellow-500': post.status === 'draft',
                      'bg-red-500': post.status === 'archived'
                    }"
                  >
                    {{ post.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-1">
                    <i class="fas fa-star text-yellow-500 text-sm"></i>
                    <span class="text-sm font-medium text-gray-900">{{ post.averageRating }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(post.published_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-1 text-sm text-gray-900" v-if="post.gallery">
                    <i class="fas fa-image text-blue-500"></i>
                    {{ post.gallery.length }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-1 text-sm text-gray-900">
                    <i class="fas fa-comment text-purple-500"></i>
                    {{ post.comments_count }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex gap-1">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                      <i class="fas fa-eye text-xs"></i>
                    </button>
                    <button class="bg-green-500 hover:bg-green-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <div v-else>
        <p class="text-center text-gray-500 py-8">No posts available.</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'List',
    data() {
      return {
        viewMode: 'grid'
      }
    },
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
          displayDescription: this.getPostDescription(post),
          gallery: post.gallery || []
        }));
      }
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
      this.$store.dispatch('posts/lists', { page: 1, pageSize: 10 }).then((res) => {
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