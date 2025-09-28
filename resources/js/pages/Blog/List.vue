<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Список Новостей</h1>
      
      <div class="flex items-center gap-4 flex-wrap">
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
        <button 
          @click="$router.push({ name: 'admin.posts.create' })"
          class="toggle-weekends active w-full sm:w-auto action-button bg-blue-600 text-white px-4 py-2 flex items-center justify-center hover:bg-blue-700 transition" 
        >
          <i class="fas fa-plus mr-2"></i>
          {{ $t('msg.buttons.add_news') }}
        </button>
      </div>
    </div>

    <!-- Кнопка для скрытия/отображения панели фильтров -->
    <div class="mb-4 sm:hidden">
      <button 
        @click="isFilterVisible = !isFilterVisible"
        class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded-lg flex items-center justify-center"
      >
        <span v-if="isFilterVisible">Скрыть фильтры</span>
        <span v-else>Показать фильтры</span>
      </button>
    </div>

    <!-- Панель поиска и фильтров -->
    <div v-show="isFilterVisible" class="transition-all">
      <n-card
        title="Фильтры и поиск"
        :bordered="false"
        class="modern-filter-card"
        size="small"
      >
        <!-- Первая строка: поиск, статус, дата -->
        <div class="filter-row">
          <!-- Поиск -->
          <div class="filter-group">
            <n-form-item label="Поиск по новостям" class="compact-form-item">
              <n-input
                v-model:value="filters.search"
                @input="handleSearch"
                type="text"
                placeholder="Поиск по ID, названию, описанию..."
                clearable
                class="modern-input"
                round
              >
                <template #prefix>
                  <i class="fas fa-search text-gray-400"></i>
                </template>
              </n-input>
            </n-form-item>
          </div>
          
          <!-- Статус -->
          <div class="filter-group">
            <n-form-item label="Статус" class="compact-form-item">
              <n-select
                v-model:value="filters.status"
                :options="statusOptions"
                @update:value="loadPosts(1)"
                placeholder="Выберите статус"
                class="modern-select"
                clearable
              />
            </n-form-item>
          </div>
          
          <!-- Дата -->
          <div class="filter-group">
            <n-form-item label="Дата создания" class="compact-form-item">
              <n-date-picker
                v-model:value="filters.date"
                type="date"
                @update:value="loadPosts(1)"
                placeholder="Выберите дату" 
                clearable
                class="compact-date-picker"
              />
            </n-form-item>
          </div>
        </div>

        <!-- Вторая строка: сортировка слева, кнопки справа -->
        <div class="filter-actions-row">
          <div class="filter-actions-left">
            <!-- Сортировка -->
            <n-select
              v-model:value="filters.sortBy"
              :options="sortOptions"
              @update:value="loadPosts(1)"
              placeholder="Сортировка"
              size="small"
              style="width: 200px;"
              clearable
            />
          </div>
          
          <div class="filter-actions-right">
            <n-space :size="8">
              <n-button
                secondary
                @click="resetFilters"
                size="small"
              >
                <template #icon>
                  <i class="fas fa-undo"></i>
                </template>
                Сбросить
              </n-button>
              <n-button
                type="primary"
                @click="loadPosts(1)"
                size="small"
              >
                <template #icon>
                  <i class="fas fa-filter"></i>
                </template>
                Применить
              </n-button>
            </n-space>
          </div>
        </div>
      </n-card>
    </div>
    
    <!-- Остальной код компонента без изменений -->
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
            <router-link :to="{ name: 'admin.blog.edit', params: { id: post.id } }" class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer">
              <i class="fas fa-edit text-xs"></i>
            </router-link>
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
                <!-- Количество просмотров -->
                <div class="flex items-center gap-1">
                  <i class="fas fa-eye text-green-500 text-xs"></i>
                  <span class="text-xs text-gray-600">{{ post.views }}</span>
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
          class="bg-white rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg group h-24 sm:h-28"
        >
          <div class="flex h-full">
        <!-- Изображение -->
        <div class="w-20 sm:w-28 min-w-[5rem] sm:min-w-[7rem] relative">
          <img
            :src="post.image ? post.image : '/assets/images/noimg.jpg'"
            alt="Post Image"
            class="w-full h-full object-cover"
            @error="event.target.src = '/assets/images/noimg.jpg'"
          />
          
          <!-- Рейтинг -->
          <div class="absolute top-1 right-1 sm:top-2 sm:right-2 bg-white/20 backdrop-blur-sm rounded-full px-1.5 sm:px-2 py-0.5 sm:py-1 flex items-center gap-1 shadow-md z-10 border border-white/30">
            <i class="fas fa-star text-yellow-500 text-xs"></i>
            <span class="text-[10px] sm:text-xs font-bold text-gray-800">{{ post.averageRating }}</span>
          </div>
        </div>
        
        <!-- Контент -->
        <div class="flex-1 p-2 sm:p-3 relative">
          <!-- Статус -->
          <span
            v-if="!isMobile"
            class="absolute top-1 right-1 sm:top-2 sm:right-2 px-1.5 sm:px-2 py-0.5 sm:py-1 text-[10px] sm:text-xs font-semibold text-white rounded-full"
            :class="{
          'bg-green-500': post.status === 'published',
          'bg-yellow-500': post.status === 'draft',
          'bg-red-500': post.status === 'archived'
            }"
          >
            {{ post.status }}
          </span>
          
          <!-- Кнопки действий -->
          <div class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <button class="bg-blue-500 hover:bg-blue-600 text-white p-1 sm:p-1.5 rounded shadow-md transition-colors cursor-pointer">
          <i class="fas fa-eye text-[10px] sm:text-xs"></i>
            </button>
            <button class="bg-green-500 hover:bg-green-600 text-white p-1 sm:p-1.5 rounded shadow-md transition-colors cursor-pointer">
          <i class="fas fa-edit text-[10px] sm:text-xs"></i>
            </button>
            <button class="bg-red-500 hover:bg-red-600 text-white p-1 sm:p-1.5 rounded shadow-md transition-colors cursor-pointer">
          <i class="fas fa-trash text-[10px] sm:text-xs"></i>
            </button>
          </div>
          
          <h2 class="text-xs sm:text-sm font-bold text-gray-800 mb-0.5 sm:mb-1 line-clamp-1 pr-12 sm:pr-16">
            {{ post.displayTitle }}
          </h2>
          
          <p class="text-[10px] sm:text-xs text-gray-600 mb-1 sm:mb-2 line-clamp-1 pr-12 sm:pr-16">
            {{ post.displayDescription }}
          </p>
          
          <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[10px] sm:text-xs text-gray-500">
            <span>{{ formatDate(post.published_at) }}</span>
            
            <div class="flex items-center gap-1" v-if="post.gallery">
          <i class="fas fa-image text-blue-500"></i>
          <span>{{ post.gallery.length }}</span>
            </div>
            
            <div class="flex items-center gap-1">
          <i class="fas fa-comment text-purple-500"></i>
          <span>{{ post.comments_count }}</span>
            </div>
            <div class="flex items-center gap-1">
              <i class="fas fa-eye text-green-500"></i>
              <span>{{ post.views }}</span>
            </div>
            <div class="flex items-center gap-1">
              <i 
                v-if="isMobile" 
                :class="{
                  'fas fa-check-circle text-green-500': post.status === 'published',
                  'fas fa-pencil-alt text-yellow-500': post.status === 'draft',
                  'fas fa-archive text-red-500': post.status === 'archived'
                }"
              ></i>
            </div>
          </div>
        </div>
          </div>
        </div>
      </div>
      
      <!-- Табличный вид -->
      <div v-else class="bg-white rounded-lg shadow-md overflow-hidden overflow-x-scroll">
        <table class="w-full overflow-x-scroll">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Изображение</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Детали</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Рейтинг</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="post in processedPosts" :key="post.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex">
                  <img
                    :src="post.image ? post.image : '/assets/images/noimg.jpg'"
                    alt="Post Image"
                    class="w-12 h-12 object-cover rounded"
                    @error="event.target.src = '/assets/images/noimg.jpg'"
                  />
                  <div class="ml-4 flex flex-col justify-center text-sm text-gray-900">
                    <div class="flex items-center gap-1">
                      <i class="fas fa-image text-blue-500 text-xs"></i>
                      <span class="text-xs" v-if="post.gallery">{{ post.gallery.length }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <i class="fas fa-comment text-purple-500 text-xs"></i>
                      <span class="text-xs">{{ post.comments_count }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <i class="fas fa-eye text-green-500 text-xs"></i>
                      <span class="text-xs">{{ post.views }}</span>
                    </div>
                  </div>
                </div>
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
      <p class="text-center text-gray-500 py-8">Новости не найдены</p>
    </div>

    <!-- Pagination -->
    <paginate
      v-if="totalPages > 1"
      :total-pages="totalPages"
      :current-page="page"
      :visible-pages="visiblePages"
      @page-changed="changePage"
    />
  </div>
</template>

<script>
import Paginate from '@/components/layouts/admin/Pagination.vue';
import { 
  NCard, 
  NGrid, 
  NGi, 
  NFormItem, 
  NInput, 
  NSelect, 
  NDatePicker, 
  NButton, 
  NSpace 
} from 'naive-ui';

export default {
  name: 'List',
  components: {
    Paginate,
    NCard, 
    NGrid, 
    NGi, 
    NFormItem, 
    NInput, 
    NSelect, 
    NDatePicker, 
    NButton, 
    NSpace
  },
  data() {
    return {
      isFilterVisible: true,
      viewMode: 'grid',
      filters: {
        search: '',
        sortBy: 'created_at',
        status: '',
        date: null
      },
      searchTimeout: null,
      sortOptions: [
        { label: 'Дата создания (новые)', value: 'created_at' },
        { label: 'Дата создания (старые)', value: 'created_at_asc' },
      ],
      statusOptions: [
        { label: 'Все статусы', value: '' },
        { label: 'Опубликовано', value: 'published' },
        { label: 'Черновик', value: 'draft' },
        { label: 'В архиве', value: 'archived' }
      ],
      isMobile: false,
    }
  },
  computed: {
    posts() {
      return this.$store.getters['posts/lists']; 
    },
    totalPages() {
      return this.$store.getters['posts/totalPages'];
    },
    page() {
      return this.$store.getters['posts/page'] || 1;
    },
    perPage() {
      return this.$store.getters['posts/perPage'] || 18;
    },
    totalPosts() {
      return this.$store.getters['posts/total'];
    },
    visiblePages() {
      const current = this.page;
      const total = this.totalPages;
      const range = 1;
      const pages = [];
      
      let start = Math.max(1, current - range);
      let end = Math.min(total, current + range);
      
      if (total <= 5) {
        for (let i = 1; i <= total; i++) {
          pages.push(i);
        }
        return pages;
      }
      
      if (current - range <= 1) {
        end = Math.min(total, end + (range - current + 2));
      }
      
      if (current + range >= total) {
        start = Math.max(1, start - (current + range - total + 1));
      }
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      
      return pages;
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
    },
    
    changePage(pageNumber) {
      this.loadPosts(pageNumber);
    },
    
    handleSearch() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => {
        this.loadPosts(1);
      }, 500);
    },
    
    loadPosts(page = 1) {
      const params = {
        page: page,
        perPage: this.perPage,
        search: this.filters.search,
        status: this.filters.status,
        date: this.filters.date ? this.formatDateForAPI(this.filters.date) : ''
      };
      
      // Обработка сортировки
      if (this.filters.sortBy.includes('_desc')) {
        params.sortBy = this.filters.sortBy.replace('_desc', '');
        params.sortDirection = 'desc';
      } else {
        params.sortBy = this.filters.sortBy;
        params.sortDirection = 'asc';
      }
      
      // Убедимся, что сортировка разрешена только по определенным полям
      const allowedSortFields = ['id', 'created_at', 'published_at', 'status'];
      if (!allowedSortFields.includes(params.sortBy)) {
        params.sortBy = 'created_at';
        params.sortDirection = 'desc';
      }
      
      this.$store.dispatch('posts/lists', params);
    },
    
    formatDateForAPI(timestamp) {
      const date = new Date(timestamp);
      return date.toISOString().split('T')[0]; // YYYY-MM-DD
    },
    
    disableFutureDates(timestamp) {
      return timestamp > Date.now();
    },
    
    resetFilters() {
      this.filters = {
        search: '',
        sortBy: 'created_at',
        status: '',
        date: null
      };
      this.loadPosts(1);
    },

    checkIfMobile() {
      this.isMobile = window.innerWidth <= 768;
    }
  },
  
  mounted() {
    this.loadPosts(1);
    if (window.innerWidth <= 768) {
      this.isFilterVisible = false;
    }
    this.checkIfMobile();
  }
}
</script>

<style scoped>
/* Ваши существующие стили */
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

.h-\[460px\] {
  height: 460px;
}

.text-lg {
  line-height: 1.3;
}

.group {
  transition: all 0.3s ease;
}

.group-hover\:brightness-90 {
  filter: brightness(0.9);
}

/* Стили для панели фильтров в стиле Naive UI */
.modern-filter-card {
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
  border: 1px solid rgba(0, 0, 0, 0.05);
  margin-bottom: 24px;
}

.modern-input,
.modern-select,
.modern-date-picker {
  width: 100%;
  border-radius: 8px;
}

.action-button {
  border-radius: 8px;
  font-weight: 500;
}
.filter-panel.compact {
  padding: 16px;
  background: #f8fafc;
  border-radius: 8px;
  margin-bottom: 24px;
  border: 1px solid #e2e8f0;
}

.filter-item {
  display: flex;
  align-items: center;
}
/* Стили для панели фильтров */
.modern-filter-card {
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.08);
  margin-bottom: 20px;
}

/* Первая строка с фильтрами */
.filter-row {
  display: flex;
  gap: 12px;
  align-items: flex-end;
  flex-wrap: wrap;
}

.filter-group {
  flex: 1;
  min-width: 200px;
}

/* Компактные формы */
.compact-form-item {
  margin-bottom: 0;
}

.compact-form-item :deep(.n-form-item-label) {
  font-size: 13px;
  margin-bottom: 4px;
}

.compact-form-item :deep(.n-form-item-blank) {
  margin-bottom: 0;
}

.compact-date-picker {
  width: 100%;
}

/* Строка с действиями фильтров */
.filter-actions-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.filter-actions-left {
  display: flex;
  align-items: center;
}

.filter-actions-right {
  display: flex;
  align-items: center;
}

/* Убираем лишние отступы у компонентов Naive UI */
:deep(.n-form-item) {
  margin-bottom: 0;
}

:deep(.n-form-item .n-form-item-feedback-wrapper) {
  min-height: auto;
}

/* Адаптивность для мобильных устройств */
@media (max-width: 768px) {
  .filter-row {
    flex-direction: column;
    gap: 8px;
  }
  
  .filter-group {
    min-width: 100%;
    width: 100%;
  }
  
  .filter-actions-row {
    flex-direction: column;
    gap: 12px;
    align-items: stretch;
  }
  
  .filter-actions-left,
  .filter-actions-right {
    width: 100%;
  }
  
  .filter-actions-left .n-select {
    width: 100% !important;
  }
  
  .filter-actions-right .n-space {
    width: 100%;
    justify-content: space-between;
  }
}

@media (max-width: 1024px) {
  .filter-group {
    min-width: 180px;
  }
}
</style>