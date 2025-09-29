<template>
    <div class="max-w-7xl mx-auto p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Мероприятия</h1>
        
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
            :to="{ name: 'admin.events.create' }"
            class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95 cursor-pointer"
          >
            <i class="fas fa-plus"></i>
            <span>Добавить Мероприятие</span>
          </router-link>
        </div>
      </div>
      
      <!-- Фильтры -->
      <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
            <input 
              v-model="searchQuery" 
              @input="onSearchChange"
              type="text" 
              placeholder="Название мероприятия..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
            <select 
              v-model="statusFilter"
              @change="onFilterChange"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Все статусы</option>
              <option value="draft">Черновик</option>
              <option value="published">Опубликовано</option>
              <option value="archived">Архив</option>
              <option value="cancelled">Отменено</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">С</label>
            <input 
              v-model="dateFrom"
              @change="onFilterChange"
              type="date" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">По</label>
            <input 
              v-model="dateTo"
              @change="onFilterChange"
              type="date" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>
        </div>
      </div>
      
      <!-- Content -->
      <div v-if="processedEvents.length">
        <!-- Сеточный вид -->
        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="event in processedEvents"
            :key="event.id"
            class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105 flex flex-col h-[500px] relative group"
          >
            <!-- Статус (абсолютное позиционирование) -->
            <span
              class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold text-white rounded-full z-10"
              :class="{
                'bg-green-500': event.status === 'published',
                'bg-yellow-500': event.status === 'draft',
                'bg-red-500': event.status === 'cancelled',
                'bg-gray-500': event.status === 'archived'
              }"
            >
              {{ getStatusText(event.status) }}
            </span>
            
            <!-- Кнопки действий (появляются при наведении) -->
            <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
              <router-link 
                :to="{ name: 'admin.events.edit', params: { id: event.id } }"
                class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer"
              >
                <i class="fas fa-edit text-xs"></i>
              </router-link>
              <button 
                @click="deleteEvent(event.id)"
                class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer"
              >
                <i class="fas fa-trash text-xs"></i>
              </button>
              <router-link 
                :to="{ name: 'admin.events.participants', params: { id: event.id } }"
                class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer"
              >
                <i class="fas fa-users text-xs"></i>
              </router-link>
            </div>
            
            <img
              :src="event.image ? event.image : '/assets/images/noimg.jpg'"
              alt="Event Image"
              class="w-full h-48 object-cover transition-transform group-hover:brightness-90"
              @error="event.target.src = '/assets/images/noimg.jpg'"
            />
            
            <div class="p-4 flex-1 flex flex-col">
              <!-- Заголовок (максимум 2 строки) -->
              <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 leading-tight">
                {{ event.displayTitle }}
              </h2>
              
              <!-- Краткое описание (максимум 3 строки) -->
              <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-1">
                {{ event.displayShortDescription }}
              </p>
              
              <!-- Информация о мероприятии -->
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                  <i class="fas fa-calendar text-blue-500"></i>
                  <span>{{ formatDate(event.start_time) }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-clock text-green-500"></i>
                  <span>{{ formatTime(event.start_time) }} - {{ formatTime(event.end_time) }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-map-marker-alt text-red-500"></i>
                  <span>{{ event.location || 'Место не указано' }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-users text-purple-500"></i>
                  <span>{{ event.confirmed_participants_count || 0 }} / {{ event.max_participants }} участников</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-euro-sign text-yellow-500"></i>
                  <span>{{ event.price > 0 ? event.price + ' €' : 'Бесплатно' }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Списковый вид -->
        <div v-else-if="viewMode === 'list'" class="space-y-3">
          <div
            v-for="event in processedEvents"
            :key="event.id"
            class="bg-white rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg group"
          >
            <div class="flex h-32">
              <!-- Изображение -->
              <div class="w-32 min-w-[8rem] relative">
                <img
                  :src="event.image ? event.image : '/assets/images/noimg.jpg'"
                  alt="Event Image"
                  class="w-full h-full object-cover"
                  @error="event.target.src = '/assets/images/noimg.jpg'"
                />
                
                <!-- Статус -->
                <span
                  class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold text-white rounded-full"
                  :class="{
                    'bg-green-500': event.status === 'published',
                    'bg-yellow-500': event.status === 'draft',
                    'bg-red-500': event.status === 'cancelled',
                    'bg-gray-500': event.status === 'archived'
                  }"
                >
                  {{ getStatusText(event.status) }}
                </span>
              </div>
              
              <!-- Контент -->
              <div class="flex-1 p-3 relative">
                <!-- Кнопки действий -->
                <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <router-link 
                    :to="{ name: 'admin.events.edit', params: { id: event.id } }"
                    class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer"
                  >
                    <i class="fas fa-edit text-xs"></i>
                  </router-link>
                  <button 
                    @click="deleteEvent(event.id)"
                    class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer"
                  >
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                  <router-link 
                    :to="{ name: 'admin.events.participants', params: { id: event.id } }"
                    class="bg-green-500 hover:bg-green-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer"
                  >
                    <i class="fas fa-users text-xs"></i>
                  </router-link>
                </div>
                
                <h2 class="text-sm font-bold text-gray-800 mb-1 line-clamp-1 pr-16">
                  {{ event.displayTitle }}
                </h2>
                
                <p class="text-xs text-gray-600 mb-2 line-clamp-1 pr-16">
                  {{ event.displayShortDescription }}
                </p>
                
                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                  <div class="flex items-center gap-1">
                    <i class="fas fa-calendar"></i>
                    <span>{{ formatDate(event.start_time) }}</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fas fa-clock"></i>
                    <span>{{ formatTime(event.start_time) }}</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fas fa-users"></i>
                    <span>{{ event.confirmed_participants_count || 0 }}/{{ event.max_participants }}</span>
                  </div>
                  <div class="flex items-center gap-1">
                    <i class="fas fa-euro-sign"></i>
                    <span>{{ event.price > 0 ? event.price + ' €' : 'Бесплатно' }}</span>
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата и время</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Участники</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="event in processedEvents" :key="event.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <img
                    :src="event.image ? event.image : '/assets/images/noimg.jpg'"
                    alt="Event Image"
                    class="w-12 h-12 object-cover rounded"
                    @error="event.target.src = '/assets/images/noimg.jpg'"
                  />
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ event.displayTitle }}</div>
                  <div class="text-xs text-gray-500 line-clamp-1">{{ event.displayShortDescription }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(event.start_time) }}</div>
                  <div class="text-xs text-gray-500">{{ formatTime(event.start_time) }} - {{ formatTime(event.end_time) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ event.confirmed_participants_count || 0 }} / {{ event.max_participants }}
                  </div>
                  <div class="text-xs text-gray-500" :class="{ 'text-red-500': !event.has_available_spots }">
                    {{ event.available_spots }} свободно
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ event.price > 0 ? event.price + ' €' : 'Бесплатно' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-semibold rounded-full text-white"
                    :class="{
                      'bg-green-500': event.status === 'published',
                      'bg-yellow-500': event.status === 'draft',
                      'bg-red-500': event.status === 'cancelled',
                      'bg-gray-500': event.status === 'archived'
                    }"
                  >
                    {{ getStatusText(event.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex gap-1">
                    <router-link 
                      :to="{ name: 'admin.events.edit', params: { id: event.id } }"
                      class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer"
                    >
                      <i class="fas fa-edit text-xs"></i>
                    </router-link>
                    <button 
                      @click="deleteEvent(event.id)"
                      class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer"
                    >
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                    <router-link 
                      :to="{ name: 'admin.events.participants', params: { id: event.id } }"
                      class="bg-green-500 hover:bg-green-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer"
                    >
                      <i class="fas fa-users text-xs"></i>
                    </router-link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <div v-else>
        <p class="text-center text-gray-500 py-8">Мероприятия не найдены.</p>
      </div>
  
      <!-- Пагинация -->
      <div v-if="totalPages > 1" class="flex justify-center mt-6">
        <div class="flex gap-1">
          <button
            v-for="page in totalPages"
            :key="page"
            @click="changePage(page)"
            :class="{
              'bg-blue-500 text-white': page === currentPage,
              'bg-white text-gray-700 hover:bg-gray-50': page !== currentPage
            }"
            class="px-3 py-1 border border-gray-300 rounded-md text-sm font-medium transition-colors"
          >
            {{ page }}
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'EventsIndex',
    data() {
      return {
        viewMode: 'grid', // 'grid', 'list' или 'table'
        searchQuery: '',
        statusFilter: '',
        dateFrom: '',
        dateTo: ''
      }
    },
    computed: {
      events() {
        return this.$store.getters['events/lists'];
      },
      currentPage() {
        return this.$store.getters['events/page'];
      },
      totalPages() {
        return this.$store.getters['events/totalPages'];
      },
      
      processedEvents() {
        if (!this.events || !Array.isArray(this.events)) {
          return [];
        }
        
        return this.events.map(event => ({
          ...event,
          displayTitle: this.getEventTitle(event),
          displayShortDescription: this.getEventShortDescription(event),
          has_available_spots: event.available_spots > 0
        }));
      }
    },
    
    methods: {
      getEventTitle(event) {
        if (event.title && event.title.trim()) {
          return event.title;
        }
        
        if (event.translations && event.translations.length > 0) {
          const defaultTranslation = event.translations.find(translation => translation.default === true);
          if (defaultTranslation && defaultTranslation.title && defaultTranslation.title.trim()) {
            return defaultTranslation.title;
          }
          
          const translationWithTitle = event.translations.find(translation => 
            translation.title && translation.title.trim()
          );
          if (translationWithTitle) {
            return translationWithTitle.title;
          }
        }
        
        return 'Без названия';
      },
      
      getEventShortDescription(event) {
        if (event.short_description && event.short_description.trim()) {
          return event.short_description;
        }
        
        if (event.translations && event.translations.length > 0) {
          const defaultTranslation = event.translations.find(translation => translation.default === true);
          if (defaultTranslation && defaultTranslation.short_description && defaultTranslation.short_description.trim()) {
            return defaultTranslation.short_description;
          }
          
          const translationWithDescription = event.translations.find(translation => 
            translation.short_description && translation.short_description.trim()
          );
          if (translationWithDescription) {
            return translationWithDescription.short_description;
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
      
      formatTime(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        return date.toLocaleTimeString('ru-RU', {
          hour: '2-digit',
          minute: '2-digit'
        });
      },
      
      getStatusText(status) {
        const statusMap = {
          'draft': 'Черновик',
          'published': 'Опубликовано',
          'archived': 'Архив',
          'cancelled': 'Отменено'
        };
        return statusMap[status] || status;
      },
      
      onSearchChange() {
        this.$store.dispatch('events/updateSearchQuery', this.searchQuery);
      },
      
      onFilterChange() {
        this.$store.dispatch('events/updateStatusFilter', this.statusFilter);
        this.$store.dispatch('events/updateDateFilter', {
          dateFrom: this.dateFrom,
          dateTo: this.dateTo
        });
      },
      
      changePage(page) {
        this.$store.dispatch('events/lists', { page });
      },
      
      async deleteEvent(id) {
        if (confirm('Вы уверены, что хотите удалить это мероприятие?')) {
          try {
            await this.$store.dispatch('events/delete', id);
            this.$store.dispatch('events/lists', { page: this.currentPage });
          } catch (error) {
            console.error('Error deleting event:', error);
          }
        }
      }
    },
    
    mounted() {
      this.$store.dispatch('events/lists', { page: 1 }).then((res) => {
        console.log('Events loaded:', this.events);
      }).catch(error => {
        console.error('Error loading events:', error);
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
  
  .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  /* Гарантируем что все карточки одинаковой высоты */
  .h-\[500px\] {
    height: 500px;
  }
  
  /* Плавные переходы для кнопок */
  .group {
    transition: all 0.3s ease;
  }
  
  .group-hover\:brightness-90 {
    filter: brightness(0.9);
  }
  </style>