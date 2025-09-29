<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <!-- Заголовок и действия -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Мероприятия</h1>

      <div class="flex items-center gap-4">
        <!-- Переключатель вида -->
        <div class="flex bg-white/10 backdrop-blur-md border border-white/30 rounded-xl p-1">
          <button
            @click="setView('grid')"
            class="p-2 rounded-lg transition-all"
            :class="viewMode === 'grid' ? 'bg-white/20 text-gray-800' : 'text-gray-600 hover:bg-white/10'"
            title="Сетка"
          >
            <i class="fas fa-th"></i>
          </button>
          <button
            @click="setView('list')"
            class="p-2 rounded-lg transition-all"
            :class="viewMode === 'list' ? 'bg-white/20 text-gray-800' : 'text-gray-600 hover:bg-white/10'"
            title="Список"
          >
            <i class="fas fa-list"></i>
          </button>
          <button
            @click="setView('table')"
            class="p-2 rounded-lg transition-all"
            :class="viewMode === 'table' ? 'bg-white/20 text-gray-800' : 'text-gray-600 hover:bg-white/10'"
            title="Таблица"
          >
            <i class="fas fa-table"></i>
          </button>
        </div>

        <router-link
          :to="{ name: 'admin.events.create' }"
          class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95"
        >
          <i class="fas fa-plus"></i>
          <span>Добавить Мероприятие</span>
        </router-link>
      </div>
    </div>

    <!-- Статистика -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <n-statistic label="Всего" class="bg-white p-4 rounded-lg shadow">
        <template #prefix><i class="fas fa-layer-group text-indigo-500 mr-2"></i></template>
        {{ stats.total_count || 0 }}
      </n-statistic>

      <n-statistic label="Опубликовано" class="bg-white p-4 rounded-lg shadow">
        <template #prefix><i class="fas fa-bullhorn text-green-500 mr-2"></i></template>
        {{ stats.by_status?.published || 0 }}
        <template #suffix>
          <n-tag :bordered="false" type="success" size="small">
            {{ percent(stats.by_status?.published, stats.total_count) }}%
          </n-tag>
        </template>
      </n-statistic>

      <n-statistic label="Черновики" class="bg-white p-4 rounded-lg shadow">
        <template #prefix><i class="fas fa-file text-yellow-500 mr-2"></i></template>
        {{ stats.by_status?.draft || 0 }}
        <template #suffix>
          <n-tag :bordered="false" type="warning" size="small">
            {{ percent(stats.by_status?.draft, stats.total_count) }}%
          </n-tag>
        </template>
      </n-statistic>

      <n-statistic label="Просмотры (всего)" class="bg-white p-4 rounded-lg shadow">
        <template #prefix><i class="fas fa-eye text-purple-500 mr-2"></i></template>
        {{ stats.total_views || 0 }}
      </n-statistic>
    </div>

    <!-- Доп. статистика -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <n-statistic label="Предстоящие" class="bg-white p-4 rounded-lg shadow">
        <template #prefix><i class="fas fa-calendar-plus text-blue-500 mr-2"></i></template>
        {{ stats.upcoming_events || 0 }}
        <template #suffix>
          <n-tag :bordered="false" type="info" size="small">Прошедшие: {{ stats.past_events || 0 }}</n-tag>
        </template>
      </n-statistic>

      <n-statistic label="Участники (всего)" class="bg-white p-4 rounded-lg shadow">
        <template #prefix><i class="fas fa-users text-indigo-500 mr-2"></i></template>
        {{ stats.total_participants || 0 }}
        <template #suffix>
          <n-tag :bordered="false" type="success" size="small">
            Подтв.: {{ stats.total_confirmed_participants || 0 }}
          </n-tag>
        </template>
      </n-statistic>

      <n-statistic label="Средняя заполняемость" class="bg-white p-4 rounded-lg shadow">
        <template #prefix><i class="fas fa-percentage text-rose-500 mr-2"></i></template>
        {{ Math.round((stats.average_participation_rate || 0) * 100) }}%
      </n-statistic>
    </div>

    <!-- Фильтры -->
    <n-card class="mb-6" :bordered="false">
      <template #header>
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-medium text-gray-800">Фильтры</h3>
          <n-button text @click="resetFilters" class="!text-indigo-500">
            <template #icon><i class="fas fa-sync-alt mr-1"></i></template>
            Сбросить
          </n-button>
        </div>
      </template>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <n-form-item label="Поиск" :show-feedback="false" class="col-span-full">
          <n-input
            v-model:value="filters.query"
            placeholder="ID, slug, заголовок..."
            clearable
            round
            size="large"
            @keydown.enter="applyFilters"
          >
            <template #prefix><i class="fas fa-search text-gray-400"></i></template>
          </n-input>
        </n-form-item>

        <n-form-item label="Статус" :show-feedback="false">
          <n-select
            v-model:value="filters.status"
            :options="statusOptions"
            placeholder="Все статусы"
            clearable
            @update:value="applyFilters"
          />
        </n-form-item>

        <n-form-item label="Период (старт)" :show-feedback="false" class="lg:col-span-2">
          <n-date-picker
            v-model:value="filters.startRange"
            type="daterange"
            clearable
            class="w-full"
            @update:value="applyFilters"
          />
        </n-form-item>
      </div>

      <div class="mt-4 flex justify-end">
        <n-button type="default" size="medium" round @click="applyFilters" class="w-full sm:w-auto">
          <template #icon><i class="fas fa-filter"></i></template>
          Применить фильтры
        </n-button>
      </div>
    </n-card>

    <!-- Представление: GRID -->
    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <n-card
        v-for="ev in events"
        :key="ev.id"
        class="shadow-sm"
        :title="ev.slug || ('#' + ev.id)"
      >
        <template #cover>
          <img
            :src="previewSrc(ev.image)"
            alt="cover"
            class="w-full h-44 object-cover"
            @error="onImgError"
          />
        </template>

        <div class="space-y-2">
          <div class="flex items-center gap-2">
            <n-tag :type="ev.status === 'published' ? 'success' : 'warning'" size="small" bordered="false">
              {{ ev.status === 'published' ? 'Опубликовано' : 'Черновик' }}
            </n-tag>
            <n-tag v-if="ev.price !== null" size="small" type="info" bordered="false">
              € {{ formatPrice(ev.price) }}
            </n-tag>
          </div>

          <div class="text-sm text-gray-600">
            <i class="far fa-calendar-alt mr-1"></i>
            {{ formatDate(ev.start_time) }} — {{ formatDate(ev.end_time) }}
          </div>

          <div class="text-xs text-gray-500">
            <i class="fas fa-user-friends mr-1"></i>
            {{ ev.confirmed_participants_count || 0 }} / {{ ev.max_participants || 0 }} подтверждено
          </div>

          <div class="text-xs text-gray-400">
            <i class="fas fa-eye mr-1"></i>{{ ev.views || 0 }}
          </div>
        </div>

        <template #action>
          <div class="flex justify-between items-center">
            <n-button size="small" @click="$router.push({ name: 'admin.events.edit', params: { id: ev.id } })" type="primary">
              <template #icon><i class="fas fa-edit"></i></template>
              Редактировать
            </n-button>

            <n-button size="small" ghost type="error" @click="confirmDelete(ev)">
              <template #icon><i class="fas fa-trash"></i></template>
              Удалить
            </n-button>
          </div>
        </template>
      </n-card>
    </div>

    <!-- Представление: LIST -->
    <div v-else-if="viewMode === 'list'" class="space-y-3">
      <n-card v-for="ev in events" :key="ev.id" class="shadow-sm">
        <div class="flex gap-4">
          <img
            :src="previewSrc(ev.image)"
            alt=""
            class="w-24 h-24 object-cover rounded"
            @error="onImgError"
          />
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
              <h3 class="font-semibold truncate">{{ ev.slug || ('#' + ev.id) }}</h3>
              <n-tag :type="ev.status === 'published' ? 'success' : 'warning'" size="small" bordered="false">
                {{ ev.status === 'published' ? 'Опубликовано' : 'Черновик' }}
              </n-tag>
            </div>
            <div class="text-sm text-gray-600 mt-1">
              <i class="far fa-calendar-alt mr-1"></i>
              {{ formatDate(ev.start_time) }} — {{ formatDate(ev.end_time) }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
              <i class="fas fa-user-friends mr-1"></i>
              {{ ev.confirmed_participants_count || 0 }} / {{ ev.max_participants || 0 }} •
              <i class="fas fa-eye ml-2 mr-1"></i>{{ ev.views || 0 }}
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <n-button size="small" @click="$router.push({ name: 'admin.events.edit', params: { id: ev.id } })" type="primary">
              <template #icon><i class="fas fa-edit"></i></template>
              Редактировать
            </n-button>
            <n-button size="small" ghost type="error" @click="confirmDelete(ev)">
              <template #icon><i class="fas fa-trash"></i></template>
              Удалить
            </n-button>
          </div>
        </div>
      </n-card>
    </div>

    <!-- Представление: TABLE -->
    <n-card v-else class="shadow-sm rounded-lg" :bordered="false">
      <!-- Верхняя пагинация -->
      <div class="my-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <n-pagination
          v-model:page="page"
          :page-count="totalPages"
          :page-slot="5"
          @update:page="handlePageChange"
        />
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-600">На странице:</span>
          <n-select
            v-model:value="pageSize"
            :options="pageSizeOptions"
            style="width: 90px"
            size="small"
            @update:value="handlePageSizeChange"
          />
        </div>
      </div>

      <div class="overflow-x-auto eventsTableContainer">
        <n-data-table
          :columns="columns"
          :data="events"
          :bordered="false"
          :loading="loading"
          :remote="true"
          @update:sorter="handleSort"
          striped
          class="min-h-[400px]"
        />
      </div>

      <!-- Нижняя пагинация -->
      <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
        <n-pagination
          v-model:page="page"
          :page-count="totalPages"
          :page-slot="5"
          @update:page="handlePageChange"
        />
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-600">На странице:</span>
          <n-select
            v-model:value="pageSize"
            :options="pageSizeOptions"
            style="width: 90px"
            size="small"
            @update:value="handlePageSizeChange"
          />
        </div>
      </div>
    </n-card>
  </div>
</template>

<script>
import { h } from 'vue';
import {
  NStatistic,
  NCard,
  NButton,
  NDataTable,
  NPagination,
  NSelect,
  NFormItem,
  NInput,
  NDatePicker,
  NTag,
  NBadge,
  NDropdown,
  useMessage
} from 'naive-ui';
import Swal from 'sweetalert2';

export default {
  name: 'EventsIndex',
  components: {
    NStatistic,
    NCard,
    NButton,
    NDataTable,
    NPagination,
    NSelect,
    NFormItem,
    NInput,
    NDatePicker,
    NTag,
    NBadge,
    NDropdown
  },
  data() {
    return {
      loading: false,
      viewMode: 'grid', // 'grid' | 'list' | 'table'
      page: 1,
      pageSize: 10,
      sortField: null,
      sortOrder: null, // 'asc' | 'desc' | null
      filters: {
        query: null,
        status: null,      // 'published' | 'draft' | null
        startRange: null   // [tsStart, tsEnd] | null
      }
    };
  },
  computed: {
    events() {
      return this.$store.getters['events/lists'] || [];
    },
    stats() {
      return this.$store.getters['events/stats'] || {};
    },
    totalPages() {
      return this.$store.getters['events/totalPages'] || 1;
    },
    pageSizeOptions() {
      return [
        { label: '10', value: 10 },
        { label: '25', value: 25 },
        { label: '50', value: 50 },
        { label: '100', value: 100 }
      ];
    },
    statusOptions() {
      return [
        { label: 'Опубликовано', value: 'published' },
        { label: 'Черновик', value: 'draft' }
      ];
    },
    columns() {
      const isMobile = typeof window !== 'undefined' && window.innerWidth < 768;
      const columns = [
        {
          title: 'ID',
          key: 'id',
          width: 80,
          sorter: true,
          class: isMobile ? 'hidden sm:table-cell' : ''
        },
        {
          title: 'Изображение',
          key: 'image',
          render: (row) =>
            h('img', {
              src: this.previewSrc(row.image),
              class: 'w-12 h-12 object-cover rounded',
              onError: this.onImgError
            }),
          width: 80
        },
        {
          title: 'Slug / Название',
          key: 'slug',
          render: (row) =>
            h(
              'a',
              {
                class: 'cursor-pointer hover:underline text-indigo-700',
                onClick: () => this.$router.push({ name: 'admin.events.edit', params: { id: row.id } })
              },
              row.slug || `#${row.id}`
            ),
          sorter: true,
          minWidth: 180,
          resizable: true
        },
        {
          title: 'Статус',
          key: 'status',
          render: (row) =>
            h(
              NBadge,
              { type: row.status === 'published' ? 'success' : 'warning', dot: true },
              {
                default: () =>
                  h(
                    NTag,
                    { type: row.status === 'published' ? 'success' : 'warning', bordered: false, size: 'small' },
                    { default: () => (row.status === 'published' ? 'Опубликовано' : 'Черновик') }
                  )
              }
            ),
          width: 130
        },
        {
          title: 'Даты',
          key: 'start_time',
          render: (row) =>
            `${this.formatDate(row.start_time)} — ${this.formatDate(row.end_time)}`,
          sorter: true,
          minWidth: 210
        },
        {
          title: 'Дедлайн',
          key: 'registration_deadline',
          render: (row) => this.formatDate(row.registration_deadline),
          sorter: true,
          class: isMobile ? 'hidden sm:table-cell' : '',
          minWidth: 160
        },
        {
          title: 'Подтв./Лимит',
          key: 'confirmed_participants_count',
          render: (row) =>
            `${row.confirmed_participants_count || 0} / ${row.max_participants || 0}`,
          width: 130
        },
        {
          title: 'Цена',
          key: 'price',
          render: (row) => `€ ${this.formatPrice(row.price)}`,
          sorter: true,
          width: 100
        },
        {
          title: 'Просмотры',
          key: 'views',
          render: (row) => `${row.views || 0}`,
          sorter: true,
          width: 110
        },
        {
          title: 'Действия',
          key: 'actions',
          render: (row) =>
            h(
              NDropdown,
              {
                trigger: 'click',
                placement: isMobile ? 'top-end' : 'bottom-end',
                options: [
                  { label: 'Редактировать', key: 'edit', icon: () => h('i', { class: 'fas fa-edit mr-2 text-blue-500' }) },
                  { type: 'divider' },
                  { label: 'Удалить', key: 'delete', icon: () => h('i', { class: 'fas fa-trash mr-2 text-red-500' }) }
                ],
                onSelect: (key) => {
                  if (key === 'edit') this.$router.push({ name: 'admin.events.edit', params: { id: row.id } });
                  if (key === 'delete') this.confirmDelete(row);
                }
              },
              {
                default: () =>
                  h(
                    NButton,
                    { size: 'small', type: 'primary', ghost: true, class: 'action-menu-button hover:bg-indigo-500' },
                    { default: () => h('i', { class: 'fas fa-ellipsis-v' }) }
                  )
              }
            ),
          width: 70
        }
      ];

      return columns;
    }
  },
  methods: {
    // API
    async fetchEvents() {
      this.loading = true;
      try {
        const payload = {
          page: this.page,
          per_page: this.pageSize,
          sort_field: this.sortField,
          sort_order: this.sortOrder,
          query: this.filters.query,
          status: this.filters.status,
          start_from: Array.isArray(this.filters.startRange) && this.filters.startRange[0] ? this.tsToIso(this.filters.startRange[0]) : null,
          start_to: Array.isArray(this.filters.startRange) && this.filters.startRange[1] ? this.tsToIso(this.filters.startRange[1]) : null
        };
        await this.$store.dispatch('events/lists', payload);
        await this.$store.dispatch('events/stats');
      } catch (e) {
        console.error('Error fetching events:', e);
        Swal.fire({ icon: 'error', title: 'Ошибка', text: 'Не удалось загрузить мероприятия', timer: 3000 });
      } finally {
        this.loading = false;
      }
    },

    // Handlers
    handleSort({ columnKey, order }) {
      this.sortField = columnKey;
      this.sortOrder = order === 'ascend' ? 'asc' : order === 'descend' ? 'desc' : null;
      this.page = 1;
      this.fetchEvents();
    },
    handlePageChange(p) {
      this.page = p;
      this.fetchEvents();
      this.$nextTick(() => {
        const el = document.querySelector('.eventsTableContainer');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    },
    handlePageSizeChange(size) {
      this.pageSize = size;
      this.page = 1;
      this.fetchEvents();
    },
    applyFilters() {
      this.page = 1;
      this.fetchEvents();
    },
    resetFilters() {
      this.filters = { query: null, status: null, startRange: null };
      this.page = 1;
      this.fetchEvents();
    },

    async confirmDelete(ev) {
      const res = await Swal.fire({
        title: 'Удалить мероприятие?',
        text: ev.slug ? `Вы уверены, что хотите удалить "${ev.slug}"?` : 'Действие необратимо.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить',
        cancelButtonText: 'Отмена'
      });
      if (!res.isConfirmed) return;

      try {
        await this.$store.dispatch('events/delete', ev.id);
        Swal.fire({ icon: 'success', title: 'Удалено', timer: 2000 });
        // если осталась пустая страница после удаления — откатиться на предыдущую
        const afterCount = this.events.length - 1;
        if (afterCount <= 0 && this.page > 1) this.page -= 1;
        this.fetchEvents();
      } catch (e) {
        console.error('Error deleting event:', e);
        Swal.fire({ icon: 'error', title: 'Ошибка', text: 'Не удалось удалить мероприятие', timer: 2500 });
      }
    },

    // Utils
    setView(mode) {
      this.viewMode = mode;
      // при переключении на таблицу сразу подгружаем (если нужно) — у нас уже есть данные
    },
    formatDate(s) {
      if (!s) return '—';
      try {
        return new Date(s).toLocaleString('ru-RU', {
          year: 'numeric', month: 'short', day: '2-digit',
          hour: '2-digit', minute: '2-digit'
        });
      } catch { return '—'; }
    },
    formatPrice(val) {
      if (val === null || val === undefined) return '0.00';
      const num = Number(val);
      return Number.isFinite(num) ? num.toFixed(2) : '0.00';
      },
    previewSrc(path) {
      if (!path) return 'https://via.placeholder.com/600x320?text=Event';
      return /^https?:\/\//i.test(path) ? path : `/storage/${path}`;
    },
    onImgError(e) {
      e.target.src = 'https://via.placeholder.com/96?text=—';
    },
    tsToIso(ts) {
      // NaiveUI возвращает миллисекунды
      const d = new Date(ts);
      const pad = (n) => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    },
    percent(part, total) {
      const p = Number(part) || 0;
      const t = Number(total) || 0;
      if (t === 0) return 0;
      return Math.round((p / t) * 100);
    }
  },
  async mounted() {
    await this.fetchEvents();
  }
};
</script>

<style scoped>
/* Таблица */
.n-data-table :deep(.n-data-table-th) {
  background-color: #f9fafb;
  font-weight: 600;
  color: #374151;
}
.n-data-table :deep(.n-data-table-td) {
  padding: 12px 16px;
}

/* Адаптив */
@media (max-width: 768px) {
  .n-card {
    border-radius: 0;
    box-shadow: none;
  }
  .n-data-table :deep(.n-data-table-th),
  .n-data-table :deep(.n-data-table-td) {
    padding: 8px 12px;
  }
}

/* Кнопка действий */
.action-menu-button {
  transition: all .2s ease;
}
</style>
