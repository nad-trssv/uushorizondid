<template>
    <div class="max-w-7xl mx-auto p-4 sm:p-6">
      <!-- Заголовок + фильтры -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Статистика мероприятий</h1>
  
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
          <n-date-picker
            v-model:value="dateRange"
            type="daterange"
            clearable
            :actions="['confirm']"
            placeholder="Период"
            class="min-w-[260px]"
            @update:value="applyFilters"
          />
          <n-select
            v-model:value="statusFilter"
            :options="statusOptions"
            placeholder="Все статусы"
            clearable
            class="min-w-[160px]"
            @update:value="applyFilters"
          />
          <n-button type="default" @click="resetFilters" :disabled="!dateRange && !statusFilter">
            <template #icon><i class="fas fa-sync-alt"></i></template>
            Сбросить
          </n-button>
        </div>
      </div>
  
      <!-- KPI -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <n-statistic label="Всего мероприятий" class="bg-white p-4 rounded-lg shadow">
          <template #prefix><i class="fas fa-layer-group text-indigo-500 mr-2"></i></template>
          {{ safe(stats.total_count) }}
        </n-statistic>
  
        <n-statistic label="Просмотры (всего)" class="bg-white p-4 rounded-lg shadow">
          <template #prefix><i class="fas fa-eye text-purple-500 mr-2"></i></template>
          {{ safe(stats.total_views) }}
        </n-statistic>
  
        <n-statistic label="Участников всего" class="bg-white p-4 rounded-lg shadow">
          <template #prefix><i class="fas fa-users text-green-500 mr-2"></i></template>
          {{ safe(stats.total_participants) }}
          <template #suffix>
            <n-tag :bordered="false" type="success" size="small">
              Подтв.: {{ safe(stats.total_confirmed_participants) }}
            </n-tag>
          </template>
        </n-statistic>
  
        <n-statistic label="Средняя конверсия" class="bg-white p-4 rounded-lg shadow">
          <template #prefix><i class="fas fa-percentage text-yellow-500 mr-2"></i></template>
          {{ Math.round((Number(stats.average_participation_rate) || 0) * 100) }}%
        </n-statistic>
      </div>
  
      <!-- Диаграммы -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <n-card :bordered="false" class="shadow-sm">
          <template #header>
            <div class="flex items-center gap-2">
              <i class="fas fa-bullhorn text-green-500"></i>
              Опубликовано / Черновики
            </div>
          </template>
          <apexchart height="260" type="donut" :options="statusDonut.options" :series="statusDonut.series" />
        </n-card>
  
        <n-card :bordered="false" class="shadow-sm">
          <template #header>
            <div class="flex items-center gap-2">
              <i class="fas fa-chart-line text-indigo-500"></i>
              Мероприятия по месяцам (12 мес)
            </div>
          </template>
          <apexchart height="260" type="area" :options="monthlyEvents.options" :series="monthlyEvents.series" />
        </n-card>
  
        <n-card :bordered="false" class="shadow-sm">
          <template #header>
            <div class="flex items-center gap-2">
              <i class="fas fa-calendar-day text-blue-500"></i>
              За последние 30 дней
            </div>
          </template>
          <apexchart height="260" type="bar" :options="dailyEvents.options" :series="dailyEvents.series" />
        </n-card>
      </div>
  
      <!-- ТОПы -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <n-card :bordered="false" class="shadow-sm">
          <template #header>
            <div class="flex items-center gap-2">
              <i class="fas fa-fire text-red-500"></i>
              Топ по просмотрам (Top 10)
            </div>
          </template>
          <apexchart height="320" type="bar" :options="topViews.options" :series="topViews.series" />
        </n-card>
  
        <n-card :bordered="false" class="shadow-sm">
          <template #header>
            <div class="flex items-center gap-2">
              <i class="fas fa-user-check text-emerald-500"></i>
              Топ по подтверждённым участникам (Top 10)
            </div>
          </template>
          <apexchart height="320" type="bar" :options="topConfirmed.options" :series="topConfirmed.series" />
        </n-card>
      </div>
  
      <!-- Most viewed / popular -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <n-card :bordered="false" class="shadow-sm">
          <template #header>
            <div class="flex items-center gap-2">
              <i class="fas fa-star text-amber-500"></i>
              Самое просматриваемое
            </div>
          </template>
          <div v-if="stats.most_viewed_event" class="flex items-center gap-4">
            <img :src="previewSrc(stats.most_viewed_event.image)" class="w-20 h-20 object-cover rounded" @error="onImgError" />
            <div>
              <div class="font-semibold truncate">
                {{ getEventTitle(stats.most_viewed_event) || stats.most_viewed_event.slug || ('#' + stats.most_viewed_event.id) }}
              </div>
              <div class="text-sm text-gray-500">
                <i class="fas fa-eye mr-1"></i>{{ safe(stats.most_viewed_event.views) }}
              </div>
            </div>
          </div>
          <div v-else class="text-gray-400">—</div>
        </n-card>
  
        <n-card :bordered="false" class="shadow-sm">
          <template #header>
            <div class="flex items-center gap-2">
              <i class="fas fa-crown text-amber-600"></i>
              Самое «посещаемое»
            </div>
          </template>
          <div v-if="stats.most_popular_event" class="flex items-center gap-4">
            <img :src="previewSrc(stats.most_popular_event.image)" class="w-20 h-20 object-cover rounded" @error="onImgError" />
            <div>
              <div class="font-semibold truncate">
                {{ getEventTitle(stats.most_popular_event) || stats.most_popular_event.slug || ('#' + stats.most_popular_event.id) }}
              </div>
              <div class="text-sm text-gray-500">
                <i class="fas fa-user-check mr-1"></i>
                {{ safe(stats.most_popular_event.confirmed_participants_count) }} подтверждено
              </div>
            </div>
          </div>
          <div v-else class="text-gray-400">—</div>
        </n-card>
      </div>
    </div>
  </template>
  
  <script>
  import {
    NCard, NButton, NStatistic, NDatePicker, NSelect, NTag
  } from 'naive-ui';
  import VueApexCharts from 'vue3-apexcharts';
  
  export default {
    name: 'EventsStats',
    components: {
      NCard, NButton, NStatistic, NDatePicker, NSelect, NTag,
      apexchart: VueApexCharts
    },
  
    data() {
      return {
        // фильтры
        dateRange: null,       // [tsStart, tsEnd]
        statusFilter: null,    // 'published' | 'draft' | null
        statusOptions: [
          { label: 'Опубликовано', value: 'published' },
          { label: 'Черновик', value: 'draft' }
        ],
  
        // локальные данные для графиков
        allEvents: [],
        loading: false
      };
    },
  
    computed: {
      stats() {
        return this.$store.getters['events/stats'] || {};
      },
  
      // donut по статусам
      statusDonut() {
        const published = this.safe(this.stats?.by_status?.published);
        const draft = this.safe(this.stats?.by_status?.draft);
        return {
          series: [published, draft],
          options: {
            labels: ['Опубликовано', 'Черновик'],
            legend: { position: 'bottom' },
            dataLabels: { enabled: true },
            tooltip: { y: { formatter: (v) => `${v}` } }
          }
        };
      },
  
      // по месяцам (12 мес)
      monthlyEvents() {
        const serverMonthly = this.stats?.monthly_events;
        if (Array.isArray(serverMonthly) && serverMonthly.length) {
          const labels = serverMonthly.map(i => i.label || i.month || '');
          const data = serverMonthly.map(i => Number(i.count) || 0);
          return this.areaSeries(labels, data, 'События');
        }
        const map = this.buildLastNMonthsMap(12);
        (this.allEvents || []).forEach(ev => {
          const k = this.ymKey(ev.start_time);
          if (k in map) map[k] += 1;
        });
        const labels = Object.keys(map);
        const data = Object.values(map);
        return this.areaSeries(labels, data, 'События');
      },
  
      // по дням (30 дней)
      dailyEvents() {
        const map = this.buildLastNDaysMap(30);
        (this.allEvents || []).forEach(ev => {
          const k = this.ymdKey(ev.start_time);
          if (k in map) map[k] += 1;
        });
        const labels = Object.keys(map);
        const data = Object.values(map);
        return this.barSeries(labels, data, 'События в день');
      },
  
      // топы
      topViews() {
        const arr = [...(this.allEvents || [])]
          .sort((a, b) => (Number(b.views) || 0) - (Number(a.views) || 0))
          .slice(0, 10);
        const labels = arr.map(e => this.shortName(e));
        const data = arr.map(e => Number(e.views) || 0);
        return this.horizontalBar(labels, data, 'Просмотры');
      },
      topConfirmed() {
        const arr = [...(this.allEvents || [])]
          .sort((a, b) => (Number(b.confirmed_participants_count) || 0) - (Number(a.confirmed_participants_count) || 0))
          .slice(0, 10);
        const labels = arr.map(e => this.shortName(e));
        const data = arr.map(e => Number(e.confirmed_participants_count) || 0);
        return this.horizontalBar(labels, data, 'Подтверждено');
      }
    },
  
    methods: {
      // загрузки
      async fetchStats() {
        await this.$store.dispatch('events/stats');
      },
  
      async fetchAllEvents() {
        this.loading = true;
        try {
          const base = {
            page: 1,
            per_page: 50,
            ...(this.statusFilter ? { status: this.statusFilter } : {})
          };
          if (Array.isArray(this.dateRange) && this.dateRange[0]) {
            base.date_from = this.tsToIso(this.dateRange[0]);
          }
          if (Array.isArray(this.dateRange) && this.dateRange[1]) {
            base.date_to = this.tsToIso(this.dateRange[1]);
          }
  
          let items = [];
  
          const first = await this.$store.dispatch('events/lists', base);
          const payload = first?.data?.events || {};
          const totalPages = payload?.last_page || 1;
          items = items.concat(payload?.data || []);
  
          for (let p = 2; p <= totalPages; p++) {
            const resp = await this.$store.dispatch('events/lists', { ...base, page: p });
            const pl = resp?.data?.events || {};
            items = items.concat(pl?.data || []);
          }
  
          // локальная подстраховка фильтрации
          items = this.filterLocal(items);
          this.allEvents = items;
        } catch (e) {
          console.error('fetchAllEvents error', e);
        } finally {
          this.loading = false;
        }
      },
  
      applyFilters() {
        this.fetchAllEvents();
      },
      resetFilters() {
        this.dateRange = null;
        this.statusFilter = null;
        this.fetchAllEvents();
      },
  
      // utils: формат, фильтрация, подписи
      safe(v) {
        return Number(v || 0);
      },
      previewSrc(path) {
        if (!path) return '/storage/placeholders/600x400.svg';
        return /^https?:\/\//i.test(path) ? path : `/storage/${path}`;
      },
      onImgError(e) {
        e.target.src = '/storage/placeholders/600x400.svg';
      },
      getEventTitle(ev) {
        return ev?.title || ev?.translation?.title || ev?.translations?.[0]?.title || '';
      },
      shortName(ev) {
        const title = this.getEventTitle(ev) || ev.slug || `#${ev.id}`;
        return title.length > 26 ? (title.slice(0, 24) + '…') : title;
      },
      tsToIso(ts) {
        const d = new Date(ts);
        const pad = (n) => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
      },
      filterLocal(items) {
        let out = [...(items || [])];
        if (this.statusFilter) {
          out = out.filter(i => i.status === this.statusFilter);
        }
        if (Array.isArray(this.dateRange) && this.dateRange[0] && this.dateRange[1]) {
          const from = new Date(this.dateRange[0]).getTime();
          const to = new Date(this.dateRange[1]).getTime();
          out = out.filter(i => {
            const t = new Date(i.start_time).getTime();
            return t >= from && t <= to;
          });
        }
        return out;
      },
  
      // helpers для Apex
      areaSeries(labels, data, name) {
        return {
          series: [{ name, data }],
          options: {
            chart: { toolbar: { show: false }, animations: { enabled: true } },
            stroke: { curve: 'smooth' },
            dataLabels: { enabled: false },
            xaxis: { categories: labels },
            yaxis: { labels: { formatter: (v) => String(v) } },
            tooltip: { y: { formatter: (v) => String(v) } },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.1 } }
          }
        };
      },
      barSeries(labels, data, name) {
        return {
          series: [{ name, data }],
          options: {
            chart: { toolbar: { show: false } },
            dataLabels: { enabled: false },
            xaxis: { categories: labels },
            yaxis: { labels: { formatter: (v) => String(v) } },
            tooltip: { y: { formatter: (v) => String(v) } }
          }
        };
      },
      horizontalBar(labels, data, name) {
        return {
          series: [{ name, data }],
          options: {
            chart: { toolbar: { show: false } },
            plotOptions: { bar: { horizontal: true } },
            dataLabels: { enabled: false },
            xaxis: { categories: labels },
            tooltip: { y: { formatter: (v) => String(v) } }
          }
        };
      },
  
      // генерация ключей и пустых промежутков дат
      ymKey(date) {
        const d = new Date(date);
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        return `${y}-${m}`;
      },
      ymdKey(date) {
        const d = new Date(date);
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
      },
      buildLastNMonthsMap(n) {
        const map = {};
        const now = new Date();
        for (let i = n - 1; i >= 0; i--) {
          const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
          map[this.ymKey(d)] = 0;
        }
        return map;
      },
      buildLastNDaysMap(n) {
        const map = {};
        const now = new Date();
        for (let i = n - 1; i >= 0; i--) {
          const d = new Date(now);
          d.setDate(now.getDate() - i);
          map[this.ymdKey(d)] = 0;
        }
        return map;
      }
    },
  
    async mounted() {
      await this.fetchStats();
      await this.fetchAllEvents();
    }
  };
  </script>
  
  <style scoped>
  .n-card { border-radius: 14px; }
  </style>
  