<template>
  <div class="dashboard-container">
    <!-- Welcome Card -->
    <n-card class="welcome-card" content-style="padding: 24px;">
      <div class="welcome-content">
        <div class="welcome-text">
          <h1 class="welcome-title">{{ $t('msg.hello') }}</h1>
          <n-text class="welcome-subtitle">{{ $t('msg.subhello') }}</n-text>
        </div>
        <button class="download-button">
          <i class="fas fa-download button-icon"></i>
          {{ $t('msg.download_report') }}
        </button>
      </div>
    </n-card>

    <!-- Quick Actions Section (выносим в отдельный блок) -->
    <n-card class="quick-actions-card" hoverable>
      <h3 class="quick-actions-title">{{ $t('msg.quick_actions') }}</h3>
      <div class="quick-actions-grid">
        <router-link :to="{ name: 'admin.services' }" class="no-underline">
          <n-button block ghost size="large" class="action-button">
            <template #icon>
              <i class="fas fa-list"></i>
            </template>
            {{ $t('msg.all_services') }}
          </n-button>
        </router-link>
        
        <router-link :to="{ name: 'admin.services.create' }" class="no-underline">
          <n-button block ghost size="large" class="action-button">
            <template #icon>
              <i class="fas fa-plus"></i>
            </template>
            {{ $t('msg.add_service') }}
          </n-button>
        </router-link>
        
        <router-link :to="{ name: 'admin.calendar' }" class="no-underline">
          <n-button block text color="white" size="large" class="action-button bg-gradient-to-r from-indigo-500 to-purple-600 text-white"
              :style="{
                height: '40px',
            }">
            <template #icon>
              <i class="fas fa-calendar-alt"></i>
            </template>
            {{ $t('msg.menu.calendar') }}
          </n-button>
        </router-link>
        
        <router-link :to="{ name: 'admin.categories' }" class="no-underline">
          <n-button block ghost size="large" class="action-button">
            <template #icon>
              <i class="fas fa-tags"></i>
            </template>
            {{ $t('msg.menu.categories') }}
          </n-button>
        </router-link>
      </div>
    </n-card>

    <!-- Services Section -->
    <n-card class="services-card" content-style="padding: 0;">
      <div class="section-header">
        <div class="section-title">
          <i class="fas fa-spa section-icon"></i>
          <h2>{{ $t('msg.services_stats') }}</h2>
        </div>
        <router-link :to="{ name: 'admin.services.create' }" class="no-underline">
          <button class="add-button">
            <i class="fas fa-plus button-icon"></i>
            {{ $t('msg.new_service') }}
          </button>
        </router-link>
      </div>

      <div class="section-content">
        <!-- Services Summary Cards -->
        <div class="stats-grid">
          <n-card class="stat-card" hoverable>
            <div class="stat-content">
              <div class="stat-main">
                <div class="stat-value">{{ servicesStats.total_count || 0 }}</div>
                <h3 class="stat-title">{{ $t('msg.total_services') }}</h3>
              </div>
              <i class="fas fa-list-alt stat-icon"></i>
            </div>
            <n-text class="stat-footer">{{ $t('msg.all_time') }}</n-text>
          </n-card>
          
          <n-card class="stat-card" hoverable>
            <div class="stat-content">
              <div class="stat-main">
                <div class="stat-value">{{ servicesStats.active_count || 0 }}</div>
                <h3 class="stat-title">{{ $t('msg.active') }}</h3>
              </div>
              <i class="fas fa-check-circle stat-icon active-icon"></i>
            </div>
            <n-text class="stat-footer">
              {{ Math.round((servicesStats.active_count / servicesStats.total_count) * 100) || 0 }}% {{ $t('msg.of_total') }}
            </n-text>
          </n-card>
          
          <n-card class="stat-card" hoverable>
            <div class="stat-content">
              <div class="stat-main">
                <div class="stat-value">{{ servicesStats.price_avg ? servicesStats.price_avg.toFixed(2) : '0.00' }}</div>
                <h3 class="stat-title">{{ $t('msg.average_price') }}</h3>
              </div>
              <i class="fas fa-euro-sign stat-icon price-icon"></i>
            </div>
            <n-text class="stat-footer">
              {{ $t('msg.price_range') }}: {{ servicesStats.price_min || 0 }} - {{ servicesStats.price_max || 0 }}
            </n-text>
          </n-card>
          
          <n-card class="stat-card" hoverable>
            <div class="stat-content">
              <div class="stat-main">
                <div class="stat-value">{{ servicesStats.duration_avg || 0 }}</div>
                <h3 class="stat-title">{{ $t('msg.duration') }}</h3>
              </div>
              <i class="fas fa-clock stat-icon duration-icon"></i>
            </div>
            <n-text class="stat-footer">
              {{ $t('msg.price_range') }}: {{ servicesStats.duration_min || 0 }} - {{ servicesStats.duration_max || 0 }} {{ $t('msg.minutes') }}
            </n-text>
          </n-card>
        </div>

        <!-- Additional Services Stats -->
        <div class="detailed-stats-grid">
          <n-card class="detailed-card" hoverable>
            <h3 class="detailed-title">{{ $t('msg.time_distribution') }}</h3>
            <div class="time-distribution">
              <div class="time-item">
                <div class="time-label">
                  <i class="fas fa-sun morning-icon"></i>
                  <span>{{ $t('msg.morning') }}:</span>
                </div>
                <n-tag type="warning" size="medium" round>
                  {{ servicesStats.morning_count || 0 }} ({{ Math.round((servicesStats.morning_count / servicesStats.total_count) * 100) || 0 }}%)
                </n-tag>
              </div>
              <div class="time-item">
                <div class="time-label">
                  <i class="fas fa-sun afternoon-icon"></i>
                  <span>{{ $t('msg.afternoon') }}:</span>
                </div>
                <n-tag type="info" size="medium" round>
                  {{ servicesStats.afternoon_count || 0 }} ({{ Math.round((servicesStats.afternoon_count / servicesStats.total_count) * 100) || 0 }}%)
                </n-tag>
              </div>
              <div class="time-item">
                <div class="time-label">
                  <i class="fas fa-clock fixed-time-icon"></i>
                  <span>{{ $t('msg.fixed_time') }}:</span>
                </div>
                <n-tag type="success" size="medium" round>
                  {{ servicesStats.has_fixed_time_count || 0 }}
                </n-tag>
              </div>
            </div>
          </n-card>
          
          <n-card class="detailed-card" hoverable>
            <h3 class="detailed-title">{{ $t('msg.service_status') }}</h3>
            <div class="status-chart">
              <div class="status-item">
                <div class="status-info">
                  <n-badge dot type="success" />
                  <span>{{ $t('msg.active') }}</span>
                </div>
                <n-progress 
                  type="line" 
                  :percentage="Math.round((servicesStats.active_count / servicesStats.total_count) * 100) || 0" 
                  status="success"
                  :height="8"
                  :border-radius="4"
                  :show-indicator="false"
                />
              </div>
              <div class="status-item">
                <div class="status-info">
                  <n-badge dot type="error" />
                  <span>{{ $t('msg.inactive') }}</span>
                </div>
                <n-progress 
                  type="line" 
                  :percentage="Math.round((servicesStats.inactive_count / servicesStats.total_count) * 100) || 0" 
                  status="error"
                  :height="8"
                  :border-radius="4"
                  :show-indicator="false"
                />
              </div>
            </div>
          </n-card>
        </div>

        <!-- Recent Services -->
        <n-card class="recent-services-card" hoverable>
          <div class="recent-header">
            <h3 class="recent-title">{{ $t('msg.recent_services') }}</h3>
            <router-link :to="{ name: 'admin.services' }" class="no-underline">
              <n-button text type="primary" class="view-all">
                {{ $t('msg.view_all') }}
                <template #icon>
                  <i class="fas fa-arrow-right"></i>
                </template>
              </n-button>
            </router-link>
          </div>
          <n-data-table
            :columns="recentServicesColumns"
            :data="recentServices"
            :bordered="false"
            :loading="loadingRecentServices"
            class="recent-table"
            :row-props="getRowProps"
          />
        </n-card>
      </div>
    </n-card>
  </div>
</template>

<script>
import { NCard, NText, NButton, NTag, NBadge, NProgress, NDataTable } from 'naive-ui';
import { useStore } from 'vuex';
import { useI18n } from 'vue-i18n';
import { h } from 'vue';

export default {
  name: 'Dashboard',
  components: {
    NCard,
    NText,
    NButton,
    NTag,
    NBadge,
    NProgress,
    NDataTable
  },
  data() {
    return {
      servicesStats: {},
      recentServices: [],
      loadingRecentServices: false
    };
  },
  computed: {
    currentMonth() {
      return new Date().toLocaleString('default', { month: 'long', year: 'numeric' })
    },
    recentServicesColumns() {
      const { t } = useI18n();
      return [
        {
          title: t('msg.label.title'),
          key: 'name',
          render: (row) => h('div', { 
            class: 'flex items-center gap-2 hover:text-indigo-600 cursor-pointer',
            onClick: () => this.$router.push({ name: 'admin.services.edit', params: { id: row.id } })
          }, [
            row.name || t('msg.label.unnamed'),
            row.price_can_change && h(NTag, {
              type: 'warning',
              size: 'small',
              round: true,
              bordered: false
            }, () => t('msg.price_can_change'))
          ])
        },
        {
          title: t('msg.label.category'),
          key: 'category_name',
          render: (row) => row.category_name || t('msg.label.uncategorized')
        },
        {
          title: t('msg.label.price'),
          key: 'price',
          render: (row) => `${row.price} €`
        },
        {
          title: t('msg.label.status'),
          key: 'status',
          render: (row) => h(NTag, {
            type: row.status ? 'success' : 'error',
            bordered: false,
            round: true
          }, {
            default: () => h('div', { class: 'flex items-center gap-1' }, [
              h('i', {
                class: row.status ? 'fas fa-check-circle' : 'fas fa-times-circle',
                style: { fontSize: '14px' }
              }),
              h('span', row.status ? t('msg.active') : t('msg.inactive'))
            ])
          })
        }
      ];
    }
  },
  methods: {
    getRowProps(row) {
      return {
        style: 'cursor: pointer; transition: background-color 0.2s ease;',
        onMouseenter: (e) => { e.currentTarget.style.backgroundColor = 'var(--hover-color)' },
        onMouseleave: (e) => { e.currentTarget.style.backgroundColor = '' }
      };
    },
    async fetchServicesStats() {
      try {
        const store = useStore();
        const response = await store.dispatch('services/lists', {
          page: 1,
          pageSize: 1,
          statsOnly: true
        });
        
        this.servicesStats = response.data.stats || {};
      } catch (error) {
        console.error('Error fetching services stats:', error);
      }
    },
    
    async fetchRecentServices() {
      this.loadingRecentServices = true;
      try {
        const store = useStore();
        const response = await store.dispatch('services/lists', {
          page: 1,
          pageSize: 5,
          sortField: 'created_at',
          sortOrder: 'desc'
        });
        
        this.recentServices = response.data.paginated.data || [];
      } catch (error) {
        console.error('Error fetching recent services:', error);
      } finally {
        this.loadingRecentServices = false;
      }
    }
  },
  mounted() {
    this.fetchServicesStats();
    this.fetchRecentServices();
  }
};
</script>

<style scoped>
.dashboard-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
}

/* Welcome Card */
.welcome-card {
  border-radius: 12px;
  background: linear-gradient(135deg, var(--primary-color), var(--primary-color-contrast));
  color: white;
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
}

.welcome-content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 20px;
}

@media (min-width: 768px) {
  .welcome-content {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

.welcome-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin: 0;
  color: white;
}

.welcome-subtitle {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1rem;
}

.download-button {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  border-radius: 8px;
  background: white;
  color: var(--primary-color);
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.download-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.button-icon {
  margin-right: 8px;
}

/* Services Section */
.services-card {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.section-header {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 16px;
  padding: 20px 24px;
  background-color: white;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

@media (min-width: 768px) {
  .section-header {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

.section-title {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1a1a1a;
}

.section-icon {
  color: var(--primary-color);
  font-size: 1.5rem;
}

.add-button {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px 16px;
  border-radius: 8px;
  background: white;
  color: var(--primary-color);
  border: 1px solid var(--primary-color);
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.add-button:hover {
  background: var(--primary-color);
  color: white;
}

.section-content {
  padding: 24px;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}

@media (min-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .stats-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.stat-card {
  border-radius: 10px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.stat-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
}

.stat-main {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #1a1a1a;
  line-height: 1;
}

.stat-icon {
  font-size: 1.75rem;
  color: var(--primary-color);
  margin-top: 8px;
}

.active-icon {
  color: #10b981;
}

.price-icon {
  color: #3b82f6;
}

.duration-icon {
  color: #8b5cf6;
}

.stat-title {
  font-size: 1rem;
  font-weight: 600;
  color: #4b5563;
  margin: 8px 0 0;
}

.stat-footer {
  font-size: 0.875rem;
  color: #6b7280;
}

/* Detailed Stats */
.detailed-stats-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}

@media (min-width: 1024px) {
  .detailed-stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.detailed-card {
  border-radius: 10px;
  height: 100%;
}

.detailed-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1a1a1a;
  margin-bottom: 16px;
}

.time-distribution {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.time-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.time-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9375rem;
}

.morning-icon {
  color: #f59e0b;
}

.afternoon-icon {
  color: #f97316;
}

.fixed-time-icon {
  color: #fbbf24;
}

.status-chart {
  display: flex;
  flex-direction: column;
  gap: 24px;
  padding: 8px 0;
}

.status-item {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.status-info {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9375rem;
}

.quick-actions {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 12px;
}

@media (min-width: 768px) {
  .quick-actions {
    grid-template-columns: repeat(2, 1fr);
  }
}

.action-button {
  transition: transform 0.2s ease;
}

.action-button:hover {
  transform: translateY(-2px);
}

/* Recent Services */
.recent-services-card {
  border-radius: 10px;
}

.recent-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.recent-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1a1a1a;
  margin: 0;
}

.view-all {
  font-weight: 600;
}

.recent-table {
  --n-border-color: transparent;
}

.recent-table :deep(.n-data-table-tr):hover {
  background-color: rgba(99, 102, 241, 0.05);
}

.recent-table :deep(.n-data-table-th) {
  background-color: #f9fafb;
  font-weight: 600;
}

.recent-table :deep(.n-data-table-td) {
  padding: 12px 16px;
}

.no-underline {
  text-decoration: none;
}
.quick-actions-card {
  border-radius: 10px;
  margin-bottom: 24px;
}

.quick-actions-title {
  font-size: 1rem;
  font-weight: 600;
  color: #1a1a1a;
  margin-bottom: 16px;
}

.quick-actions-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 12px;
}

@media (min-width: 768px) {
  .quick-actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .quick-actions-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* Categories Stats */
.categories-stats {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.category-item {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.category-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.category-count {
  font-size: 0.875rem;
  color: #6b7280;
}
.service_action__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 8px;
}

.service_action__title {
  font-size: 14px;
  font-weight: 600;
  margin: 0;
}
</style>