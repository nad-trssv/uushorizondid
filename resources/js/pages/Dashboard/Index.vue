<template>
  <div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
      <n-card class="welcome-card">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
          <div class="flex-1">
            <h1 class="welcome-title">
              Добро пожаловать, <span class="gradient-text">{{ userName }}</span>! 👋
            </h1>
            <p class="welcome-subtitle">
              Вот что происходит в вашей системе сегодня, {{ currentDate }}
            </p>
            
            <!-- Quick Stats -->
            <div class="quick-stats-grid">
              <div class="stat-item">
                <div class="stat-icon blog">
                  <i class="fas fa-newspaper"></i>
                </div>
                <div class="stat-content">
                  <div class="stat-number">{{ blogStats.total_count || 0 }}</div>
                  <div class="stat-label text-white">Статей в блоге</div>
                </div>
              </div>
              
              <div class="stat-item">
                <div class="stat-icon events">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                  <div class="stat-number">{{ eventsStats.total_count || 0 }}</div>
                  <div class="stat-label">Всего мероприятий</div>
                </div>
              </div>
              
              <div class="stat-item">
                <div class="stat-icon comments">
                  <i class="fas fa-comments"></i>
                </div>
                <div class="stat-content">
                  <div class="stat-number">{{ blogStats.total_comments || 0 }}</div>
                  <div class="stat-label">Комментариев</div>
                </div>
              </div>

              <div class="stat-item">
                <div class="stat-icon views">
                  <i class="fas fa-eye"></i>
                </div>
                <div class="stat-content">
                  <div class="stat-number">{{ formatCompactNumber(totalViews) }}</div>
                  <div class="stat-label">Всего просмотров</div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="welcome-illustration">
            <div class="illustration-container">
              <i class="fas fa-chart-line main-icon"></i>
              <div class="floating-elements">
                <i class="fas fa-newspaper element-1"></i>
                <i class="fas fa-calendar element-2"></i>
                <i class="fas fa-users element-3"></i>
              </div>
            </div>
          </div>
        </div>
      </n-card>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
      <n-card title="Быстрые действия" size="small">
        <div class="quick-actions-grid">
          <n-button 
            class="quick-action-btn" 
            type="primary" 
            @click="$router.push({ name: 'admin.blog.create' })"
          >
            <template #icon>
              <i class="fas fa-edit"></i>
            </template>
            Новая статья
          </n-button>
          
          <n-button 
            class="quick-action-btn" 
            type="info" 
            @click="$router.push({ name: 'admin.events.create' })"
          >
            <template #icon>
              <i class="fas fa-calendar-plus"></i>
            </template>
            Новое событие
          </n-button>
          
          <n-button 
            class="quick-action-btn" 
            type="success" 
            @click="$router.push({ name: 'admin.blog.stats' })"
          >
            <template #icon>
              <i class="fas fa-chart-bar"></i>
            </template>
            Статистика новостей
          </n-button>

          <n-button 
            class="quick-action-btn" 
            type="warning" 
            @click="$router.push({ name: 'admin.events.stats' })"
          >
            <template #icon>
              <i class="fas fa-chart-pie"></i>
            </template>
            Статистика мероприятий
          </n-button>
        </div>
      </n-card>
    </div>

    <!-- Main Content Grid -->
    <div class="main-grid">
      <!-- Left Column -->
      <div class="left-column">
        <!-- Statistics Charts -->
        <div class="charts-section">
          <n-card title="Динамика по месяцам" size="small">
            <div class="charts-grid">
              <!-- Blog Posts Chart -->
              <div class="chart-container">
                <h3 class="chart-title">Статьи</h3>
                <apexchart 
                  v-if="blogChart.series[0].data.length > 0"
                  height="200" 
                  type="line" 
                  :options="blogChart.options" 
                  :series="blogChart.series"
                />
                <div v-else class="chart-placeholder">
                  <i class="fas fa-chart-line"></i>
                  <p>Нет данных за последние месяцы</p>
                </div>
              </div>

              <!-- Events Chart -->
              <div class="chart-container">
                <h3 class="chart-title">Мероприятия</h3>
                <apexchart 
                  v-if="eventsChart.series[0].data.length > 0"
                  height="200" 
                  type="line" 
                  :options="eventsChart.options" 
                  :series="eventsChart.series"
                />
                <div v-else class="chart-placeholder">
                  <i class="fas fa-chart-line"></i>
                  <p>Нет данных за последние месяцы</p>
                </div>
              </div>
            </div>
          </n-card>
        </div>

        <!-- Statistics Overview -->
        <n-card title="Общая статистика" size="small">
          <div class="stats-overview">
            <div class="stat-card">
              <div class="stat-card-icon blog">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-card-content">
                <div class="stat-card-value">{{ blogStats.by_status?.published || 0 }}</div>
                <div class="stat-card-label">Опубликовано статей</div>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-card-icon events">
                <i class="fas fa-users"></i>
              </div>
              <div class="stat-card-content">
                <div class="stat-card-value">{{ formatCompactNumber(eventsStats.total_participants || 0) }}</div>
                <div class="stat-card-label">Участников мероприятий</div>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-card-icon rating">
                <i class="fas fa-star"></i>
              </div>
              <div class="stat-card-content">
                <div class="stat-card-value">{{ blogStats.average_rating || 0 }}</div>
                <div class="stat-card-label">Средний рейтинг</div>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-card-icon recent">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-card-content">
                <div class="stat-card-value">{{ blogStats.recent_posts || 0 }}</div>
                <div class="stat-card-label">Статей за 30 дней</div>
              </div>
            </div>
          </div>
        </n-card>
        <div class="my-4"></div>
        <!-- Recent Blog Posts -->
        <n-card title="Последние статьи" size="small">
          <n-list v-if="recentPosts.length > 0">
            <n-list-item v-for="post in recentPosts" :key="post.id">
              <div class="post-item">
                <div class="post-image">
                  <img 
                    :src="getPostImage(post)" 
                    :alt="getPostTitle(post)"
                    @error="onImageError"
                  />
                </div>
                <div class="post-content">
                  <div class="post-title">{{ getPostTitle(post) }}</div>
                  <div class="post-meta">
                    <n-tag size="small" :type="post.status === 'published' ? 'success' : 'default'">
                      {{ post.status === 'published' ? 'Опубликовано' : 'Черновик' }}
                    </n-tag>
                    <span class="post-date">{{ formatDate(post.created_at) }}</span>
                    <span class="post-views" v-if="post.views">
                      <i class="fas fa-eye"></i> {{ formatCompactNumber(post.views) }}
                    </span>
                  </div>
                </div>
                <n-button 
                  size="small" 
                  text 
                  @click="$router.push({ name: 'admin.blog.edit', params: { id: post.id } })"
                >
                  <i class="fas fa-edit"></i>
                </n-button>
              </div>
            </n-list-item>
          </n-list>
          <div v-else class="text-center py-4 text-gray-400">
            <i class="fas fa-newspaper text-4xl mb-2"></i>
            <p>Нет статей</p>
          </div>
          
          <template #footer>
            <n-button 
              block 
              type="primary" 
              ghost 
              @click="$router.push({ name: 'admin.blog.list' })"
            >
              Все статьи
            </n-button>
          </template>
        </n-card>
      </div>

      <!-- Right Column -->
      <div class="right-column">

        <!-- Today's and Upcoming Events -->
        <n-card title="Ближайшие мероприятия" size="small">
          <!-- Today's Events -->
          <div v-if="todaysEvents.length > 0" class="events-section">
            <h4 class="events-subtitle">Сегодня</h4>
            <n-list>
              <n-list-item v-for="event in todaysEvents" :key="event.id">
                <div class="event-item">
                  <div class="event-date today">
                    <div class="event-day">{{ formatEventDay(event.start_time) }}</div>
                    <div class="event-month">{{ formatEventMonth(event.start_time) }}</div>
                  </div>
                  <div class="event-content">
                    <div class="event-title">{{ getEventTitle(event) }}</div>
                    <div class="event-time">
                      <i class="fas fa-clock"></i>
                      {{ formatEventTime(event.start_time) }}
                    </div>
                    <div class="event-participants" v-if="event.confirmed_participants_count">
                      <i class="fas fa-user-check"></i>
                      {{ formatCompactNumber(event.confirmed_participants_count) }}
                    </div>
                  </div>
                  <n-button 
                    size="small" 
                    text 
                    @click="$router.push({ name: 'admin.events.edit', params: { id: event.id } })"
                  >
                    <i class="fas fa-arrow-right"></i>
                  </n-button>
                </div>
              </n-list-item>
            </n-list>
          </div>

          <!-- Upcoming Events -->
          <div v-if="upcomingEvents.length > 0" class="events-section">
            <h4 class="events-subtitle" :class="{ 'mt-4': todaysEvents.length > 0 }">Ближайшие</h4>
            <n-list>
              <n-list-item v-for="event in upcomingEvents" :key="event.id">
                <div class="event-item">
                  <div class="event-date">
                    <div class="event-day">{{ formatEventDay(event.start_time) }}</div>
                    <div class="event-month">{{ formatEventMonth(event.start_time) }}</div>
                  </div>
                  <div class="event-content">
                    <div class="event-title">{{ getEventTitle(event) }}</div>
                    <div class="event-time">
                      <i class="fas fa-clock"></i>
                      {{ formatEventTime(event.start_time) }}
                    </div>
                    <div class="event-participants" v-if="event.confirmed_participants_count">
                      <i class="fas fa-user-check"></i>
                      {{ formatCompactNumber(event.confirmed_participants_count) }}
                    </div>
                  </div>
                  <n-button 
                    size="small" 
                    text 
                    @click="$router.push({ name: 'admin.events.edit', params: { id: event.id } })"
                  >
                    <i class="fas fa-arrow-right"></i>
                  </n-button>
                </div>
              </n-list-item>
            </n-list>
          </div>

          <div v-if="todaysEvents.length === 0 && upcomingEvents.length === 0" class="text-center py-4 text-gray-400">
            <i class="fas fa-calendar-times text-4xl mb-2"></i>
            <p>Нет предстоящих событий</p>
          </div>
          
          <template #footer>
            <n-button 
              block 
              type="primary" 
              ghost 
              @click="$router.push({ name: 'admin.events.list' })"
            >
              Все события
            </n-button>
          </template>
        </n-card>

        <div class="my-4"></div>
        <!-- Popular Content -->
        <n-card title="Популярный контент" size="small">
          <div class="popular-content">
            <div v-if="blogStats.most_viewed_post" class="popular-item clickable" @click="goToPost(blogStats.most_viewed_post.id)">
              <div class="popular-icon">
                <i class="fas fa-fire text-red-500"></i>
              </div>
              <div class="popular-details">
                <div class="popular-title">Самая просматриваемая статья</div>
                <div class="popular-name">{{ getPostTitle(blogStats.most_viewed_post) }}</div>
                <div class="popular-meta">
                  <i class="fas fa-eye"></i> {{ formatCompactNumber(blogStats.most_viewed_post.views || 0) }} просмотров
                </div>
              </div>
              <i class="fas fa-external-link-alt popular-link"></i>
            </div>

            <div v-if="eventsStats.most_viewed_event" class="popular-item clickable" @click="goToEvent(eventsStats.most_viewed_event.id)">
              <div class="popular-icon">
                <i class="fas fa-eye text-purple-500"></i>
              </div>
              <div class="popular-details">
                <div class="popular-title">Самое просматриваемое событие</div>
                <div class="popular-name">{{ getEventTitle(eventsStats.most_viewed_event) }}</div>
                <div class="popular-meta">
                  <i class="fas fa-eye"></i> {{ formatCompactNumber(safe(eventsStats.most_viewed_event.views)) }} просмотров
                </div>
              </div>
              <i class="fas fa-external-link-alt popular-link"></i>
            </div>

            <div v-if="!blogStats.most_viewed_post && !eventsStats.most_viewed_event" class="text-center py-4 text-gray-400">
              <i class="fas fa-chart-line text-2xl mb-2"></i>
              <p>Нет данных о популярном контенте</p>
            </div>
          </div>
        </n-card>
      </div>
    </div>
  </div>
</template>

<script>
import { NCard, NButton, NList, NListItem, NTag } from 'naive-ui'
import VueApexCharts from 'vue3-apexcharts'

export default {
  name: 'DashboardIndex',
  components: {
    NCard,
    NButton,
    NList,
    NListItem,
    NTag,
    apexchart: VueApexCharts
  },
  data() {
    return {
      recentPosts: [],
      upcomingEvents: [],
      todaysEvents: [],
      loading: false,
      blogChart: {
        series: [{
          name: 'Статьи',
          data: []
        }],
        options: {
          chart: {
            height: 200,
            type: 'line',
            zoom: {
              enabled: false
            },
            toolbar: {
              show: false
            }
          },
          colors: ['#667eea'],
          stroke: {
            curve: 'smooth',
            width: 3
          },
          markers: {
            size: 4,
          },
          xaxis: {
            categories: []
          },
          yaxis: {
            labels: {
              formatter: (val) => Math.round(val)
            }
          },
          tooltip: {
            y: {
              formatter: (val) => `${val} статей`
            }
          }
        }
      },
      eventsChart: {
        series: [{
          name: 'Мероприятия',
          data: []
        }],
        options: {
          chart: {
            height: 200,
            type: 'line',
            zoom: {
              enabled: false
            },
            toolbar: {
              show: false
            }
          },
          colors: ['#48dbfb'],
          stroke: {
            curve: 'smooth',
            width: 3
          },
          markers: {
            size: 4,
            colors: ['#48dbfb']
          },
          xaxis: {
            categories: []
          },
          yaxis: {
            labels: {
              formatter: (val) => Math.round(val)
            }
          },
          tooltip: {
            y: {
              formatter: (val) => `${val} мероприятий`
            }
          }
        }
      }
    }
  },
  computed: {
    userName() {
      return this.$store.getters['auth/authInfo']?.name || 'Администратор'
    },
    currentDate() {
      return new Date().toLocaleDateString('ru-RU', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    },
    lastUpdate() {
      return new Date().toLocaleTimeString('ru-RU', {
        hour: '2-digit',
        minute: '2-digit'
      })
    },
    blogStats() {
      return this.$store.getters['posts/stats'] || {}
    },
    eventsStats() {
      return this.$store.getters['events/stats'] || {}
    },
    totalViews() {
      return (this.blogStats.total_views || 0) + (this.eventsStats.total_views || 0)
    }
  },
  methods: {
    // Методы из Blog/Stats.vue
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
    
    getPostImage(post) {
      if (post.image) {
        return /^https?:\/\//i.test(post.image) ? post.image : `/storage/${post.image}`;
      }
      return '/storage/placeholders/600x400.svg';
    },

    // Методы из Events/Stats.vue
    getEventTitle(event) {
      return event?.title || event?.translation?.title || event?.translations?.[0]?.title || event?.slug || `Событие #${event.id}`;
    },

    safe(v) {
      return Number(v || 0);
    },

    previewSrc(path) {
      if (!path) return '/storage/placeholders/600x400.svg';
      return /^https?:\/\//i.test(path) ? path : `/storage/${path}`;
    },

    onImageError(e) {
      e.target.src = '/storage/placeholders/600x400.svg';
    },

    // Форматирование чисел с сокращениями (тыс., млн)
    formatCompactNumber(number) {
      if (number >= 1000000) {
        return (number / 1000000).toFixed(1).replace(/\.0$/, '') + ' млн';
      }
      if (number >= 1000) {
        return (number / 1000).toFixed(1).replace(/\.0$/, '') + ' тыс';
      }
      return number.toString();
    },

    // Форматирование дат
    formatDate(dateString) {
      if (!dateString) return ''
      return new Date(dateString).toLocaleDateString('ru-RU')
    },

    formatEventDay(dateString) {
      if (!dateString) return ''
      return new Date(dateString).getDate()
    },

    formatEventMonth(dateString) {
      if (!dateString) return ''
      return new Date(dateString).toLocaleDateString('ru-RU', { month: 'short' })
    },

    formatEventTime(dateString) {
      if (!dateString) return ''
      return new Date(dateString).toLocaleTimeString('ru-RU', {
        hour: '2-digit',
        minute: '2-digit'
      })
    },

    goToWebsite() {
      window.open('/', '_blank')
    },

    // Навигация по популярному контенту
    goToPost(postId) {
      this.$router.push({ name: 'admin.blog.edit', params: { id: postId } });
    },

    goToEvent(eventId) {
      this.$router.push({ name: 'admin.events.edit', params: { id: eventId } });
    },

    // Генерация данных для графиков с правильными месяцами
    generateChartData() {
      const months = [];
      const blogData = [];
      const eventsData = [];
      
      const currentDate = new Date();
      
      // Генерируем последние 6 месяцев
      for (let i = 5; i >= 0; i--) {
        const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
        const monthName = date.toLocaleDateString('ru-RU', { month: 'short' });
        months.push(monthName);
        
        // Моковые данные - в реальном приложении замените на данные из API
        blogData.push(Math.floor(Math.random() * 30) + 10);
        eventsData.push(Math.floor(Math.random() * 15) + 5);
      }

      this.blogChart.series[0].data = blogData;
      this.blogChart.options.xaxis.categories = months;

      this.eventsChart.series[0].data = eventsData;
      this.eventsChart.options.xaxis.categories = months;
    },

    // Разделение событий на сегодняшние и ближайшие
    separateEvents(events) {
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      const tomorrow = new Date(today);
      tomorrow.setDate(tomorrow.getDate() + 1);
      
      const todays = [];
      const upcoming = [];
      
      events.forEach(event => {
        const eventDate = new Date(event.start_time);
        eventDate.setHours(0, 0, 0, 0);
        
        if (eventDate.getTime() === today.getTime()) {
          todays.push(event);
        } else if (eventDate >= today) {
          upcoming.push(event);
        }
      });
      
      // Сортируем по дате и берем только 3 ближайших
      upcoming.sort((a, b) => new Date(a.start_time) - new Date(b.start_time));
      
      return {
        todaysEvents: todays,
        upcomingEvents: upcoming.slice(0, 3) // Только 3 ближайших
      };
    },

    // Загрузка данных
    async fetchDashboardData() {
      this.loading = true;
      try {
        // Загружаем статистику блога
        await this.$store.dispatch('posts/stats', { page: 1, pageSize: 10 });
        
        // Загружаем статистику мероприятий
        await this.$store.dispatch('events/stats');

        // Загружаем последние статьи
        const postsResponse = await this.$store.dispatch('posts/lists', { 
          page: 1, 
          per_page: 5, 
          order_by: 'created_at', 
          order: 'desc' 
        });
        
        if (postsResponse && postsResponse.data) {
          const postsData = postsResponse.data.posts || {};
          this.recentPosts = postsData.data || [];
        } else {
          this.recentPosts = [];
        }

        // Загружаем все будущие события
        const today = new Date().toISOString().split('T')[0];
        const eventsResponse = await this.$store.dispatch('events/lists', { 
          page: 1, 
          per_page: 50, // Больше событий чтобы охватить ближайшие
          order_by: 'start_time', 
          order: 'asc',
          date_from: today
        });
        
        if (eventsResponse && eventsResponse.data) {
          const eventsData = eventsResponse.data.events || {};
          const allEvents = eventsData.data || [];
          
          // Разделяем события на сегодняшние и ближайшие
          const { todaysEvents, upcomingEvents } = this.separateEvents(allEvents);
          this.todaysEvents = todaysEvents;
          this.upcomingEvents = upcomingEvents;
        } else {
          this.todaysEvents = [];
          this.upcomingEvents = [];
        }

        // Генерируем данные для графиков
        this.generateChartData();

      } catch (error) {
        console.error('Ошибка загрузки данных дашборда:', error);
        this.recentPosts = [];
        this.todaysEvents = [];
        this.upcomingEvents = [];
      } finally {
        this.loading = false;
      }
    }
  },
  async mounted() {
    await this.fetchDashboardData();
  }
}
</script>

<style scoped>
.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
  space-y: 6;
}

/* Welcome Section */
.welcome-section {
  margin-bottom: 24px;
}

.welcome-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
}

:deep(.welcome-card .n-card__content) {
  padding: 30px;
}

.welcome-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: white;
  margin-bottom: 12px;
  line-height: 1.2;
}

.gradient-text {
  background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.welcome-subtitle {
  font-size: 1.1rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 24px;
}

.quick-stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-top: 24px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  backdrop-filter: blur(10px);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: white;
}

.stat-icon.blog {
  background: linear-gradient(135deg, #ff6b6b, #ee5a24);
}

.stat-icon.events {
  background: linear-gradient(135deg, #48dbfb, #0abde3);
}

.stat-icon.comments {
  background: linear-gradient(135deg, #1dd1a1, #10ac84);
}

.stat-icon.views {
  background: linear-gradient(135deg, #a55eea, #8854d0);
}

.stat-content {
  color: white;
}

.stat-number {
  font-size: 1.4rem;
  font-weight: 700;
  line-height: 1;
  word-break: break-all;
}

.stat-label {
  font-size: 0.9rem;
  opacity: 0.9;
  color: white;
}

.welcome-illustration {
  flex-shrink: 0;
}

.illustration-container {
  position: relative;
  width: 150px;
  height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.main-icon {
  font-size: 4rem;
  color: rgba(255, 255, 255, 0.9);
  z-index: 2;
}

.floating-elements {
  position: absolute;
  width: 100%;
  height: 100%;
}

.floating-elements i {
  position: absolute;
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.7);
  animation: float 3s ease-in-out infinite;
}

.element-1 {
  top: 10%;
  left: 20%;
  animation-delay: 0s;
}

.element-2 {
  top: 20%;
  right: 10%;
  animation-delay: 1s;
}

.element-3 {
  bottom: 30%;
  left: 10%;
  animation-delay: 2s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

/* Quick Actions */
.quick-actions-section {
  margin-bottom: 24px;
}

.quick-actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
}

.quick-action-btn {
  height: 60px;
  font-size: 1rem;
  font-weight: 500;
}

/* Main Grid */
.main-grid {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 24px;
}

@media (max-width: 1200px) {
  .main-grid {
    grid-template-columns: 1fr;
  }
}

/* Charts Section */
.charts-section {
  margin-bottom: 24px;
}

.charts-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.chart-container {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 16px;
}

.chart-title {
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 12px;
  text-align: center;
  color: #333;
}

.chart-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 150px;
  color: #8c8c8c;
}

.chart-placeholder i {
  font-size: 2rem;
  margin-bottom: 8px;
}

.chart-placeholder p {
  font-size: 0.8rem;
}

/* Posts */
.post-item {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
}

.post-image {
  width: 50px;
  height: 50px;
  border-radius: 8px;
  overflow: hidden;
  flex-shrink: 0;
}

.post-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.post-content {
  flex: 1;
  min-width: 0; /* Предотвращает выход за рамки */
  flex-wrap: wrap;
}

.post-title {
  font-weight: 500;
  margin-bottom: 4px;
  line-height: 1.3;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.post-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.post-date {
  font-size: 0.8rem;
  color: #8c8c8c;
}

.post-views {
  font-size: 0.8rem;
  color: #8c8c8c;
  display: flex;
  align-items: center;
  gap: 2px;
}

/* Stats Overview */
.stats-overview {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: #f8f9fa;
  border-radius: 8px;
}

.stat-card-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  color: white;
}

.stat-card-icon.blog {
  background: linear-gradient(135deg, #ff6b6b, #ee5a24);
}

.stat-card-icon.events {
  background: linear-gradient(135deg, #48dbfb, #0abde3);
}

.stat-card-icon.rating {
  background: linear-gradient(135deg, #feca57, #ff9ff3);
}

.stat-card-icon.recent {
  background: linear-gradient(135deg, #1dd1a1, #10ac84);
}

.stat-card-content {
  flex: 1;
  min-width: 0;
}

.stat-card-value {
  font-size: 1.5rem;
  font-weight: 700;
  line-height: 1;
}

.stat-card-label {
  font-size: 0.8rem;
  color: #8c8c8c;
  margin-top: 4px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Events Section */
.events-section {
  margin-bottom: 16px;
}

.events-subtitle {
  font-size: 0.9rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 12px;
  padding-bottom: 8px;
  border-bottom: 1px solid #f0f0f0;
}

.events-subtitle.mt-4 {
  margin-top: 16px;
}

.event-item {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  min-height: 60px;
}

.event-date {
  width: 50px;
  height: 50px;
  border-radius: 8px;
  background: #f0f2f5;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.event-date.today {
  background: linear-gradient(135deg, #ff6b6b, #ee5a24);
  color: white;
}

.event-date.today .event-day,
.event-date.today .event-month {
  color: white;
}

.event-day {
  font-size: 1.2rem;
  font-weight: 700;
  line-height: 1;
  color: #333;
}

.event-month {
  font-size: 0.7rem;
  color: #8c8c8c;
  text-transform: uppercase;
}

.event-content {
  flex: 1;
  min-width: 0;
  overflow: hidden;
  flex-wrap: wrap;
}

.event-title {
  font-weight: 500;
  margin-bottom: 4px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.event-time {
  font-size: 0.8rem;
  color: #8c8c8c;
  display: flex;
  align-items: center;
  gap: 4px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.event-participants {
  font-size: 0.8rem;
  color: #52c41a;
  display: flex;
  align-items: center;
  gap: 4px;
  margin-top: 2px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Popular Content */
.popular-content {
  space-y: 3;
}

.popular-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #f0f0f0;
  transition: all 0.2s ease;
  position: relative;
}

.popular-item.clickable {
  cursor: pointer;
}

.popular-item.clickable:hover {
  background: #f8f9fa;
  border-color: #d9d9d9;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.popular-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: #f0f2f5;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.popular-details {
  flex: 1;
  min-width: 0;
}

.popular-title {
  font-size: 0.9rem;
  font-weight: 500;
  margin-bottom: 4px;
  color: #333;
}

.popular-name {
  font-size: 0.8rem;
  color: #8c8c8c;
  margin-bottom: 4px;
  line-height: 1.3;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.popular-meta {
  font-size: 0.7rem;
  color: #52c41a;
  display: flex;
  align-items: center;
  gap: 4px;
}

.popular-link {
  color: #8c8c8c;
  font-size: 0.8rem;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.popular-item.clickable:hover .popular-link {
  opacity: 1;
}
</style>