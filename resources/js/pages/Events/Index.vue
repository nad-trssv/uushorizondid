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
    <!-- TOP PAGINATION (всегда) -->
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
          style="width: 100px"
          size="small"
          @update:value="handlePageSizeChange"
        />
      </div>
    </div>
    <div id="eventsResults">
      <!-- GRID -->
      <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <n-card
          v-for="ev in events"
          :key="ev.id"
          class="shadow-sm"
          :title="getEventTitle(ev) || ('#' + ev.id)"
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
              <n-tag :type="ev.status === 'published' ? 'success' : 'warning'" size="small" :bordered="false">
                {{ ev.status === 'published' ? 'Опубликовано' : 'Черновик' }}
              </n-tag>
              <n-tag v-if="ev.price !== null" size="small" type="info" :bordered="false">
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

      <!-- LIST -->
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
                <h3 class="font-semibold truncate">{{ getEventTitle(ev) || ('#' + ev.id) }}</h3>
                <n-tag :type="ev.status === 'published' ? 'success' : 'warning'" size="small" :bordered="false">
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

      <!-- TABLE -->
      <n-card v-else class="shadow-sm rounded-lg" :bordered="false">
        <!-- Верхняя пагинация -->
        <div class="my-4 flex flex-col sm:flex-row justify-between items-center gap-4">
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
      </n-card>
    </div>
    <!-- BOTTOM PAGINATION (всегда) -->
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
          style="width: 100px"
          size="small"
          @update:value="handlePageSizeChange"
        />
      </div>
    </div>
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
  NDropdown
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
      pageSize: 12,
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
        { label: '12', value: 12 },
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
          title: 'Заголовок',
          key: 'title',
          render: (row) =>
            h(
              'a',
              {
                class: 'cursor-pointer hover:underline text-indigo-700',
                onClick: () => this.$router.push({ name: 'admin.events.edit', params: { id: row.id } })
              },
              this.getEventTitle(row)
            ),
          // сортировку по title лучше отключить, если бэк не умеет
          sorter: false,
          minWidth: 200,
          resizable: true
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
    },
    currentLangIsoCode() {
      return this.$store.getters['settings/currentLocale']?.iso || 'ru-RU';
    },
  },
  methods: {
    async fetchEvents() {
      this.loading = true;
      try {
        // отправляем только поддерживаемые бэком ключи
        const params = {
          page: this.page,
          per_page: this.pageSize
        };
        if (this.sortField && this.sortOrder) {
          params.sort_by = this.sortField;
          params.sort_order = this.sortOrder;
        }
        if (this.filters.query) params.search = this.filters.query;
        if (this.filters.status) params.status = this.filters.status;

        if (Array.isArray(this.filters.startRange) && this.filters.startRange[0]) {
          params.date_from = this.tsToIso(this.filters.startRange[0]);
        }
        if (Array.isArray(this.filters.startRange) && this.filters.startRange[1]) {
          params.date_to = this.tsToIso(this.filters.startRange[1]);
        }
        

        await this.$store.dispatch('events/lists', params);
        await this.$store.dispatch('events/stats');
      } catch (e) {
        console.error('Error fetching events:', e);
        Swal.fire({ icon: 'error', title: 'Ошибка', text: 'Не удалось загрузить мероприятия', timer: 3000 });
      } finally {
        this.loading = false;
      }
    },
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
        const wrap = document.querySelector('#eventsResults');
        if (wrap) wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
        text: ev.slug ? `Вы уверены, что хотите удалить "${ev.title}"?` : 'Действие необратимо.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить',
        cancelButtonText: 'Отмена'
      });
      if (!res.isConfirmed) return;

      try {
        await this.$store.dispatch('events/delete', ev.id);
        Swal.fire({ icon: 'success', title: 'Удалено', timer: 2000 });
        // если страница опустела — откатиться на предыдущую
        if (this.events.length - 1 <= 0 && this.page > 1) this.page -= 1;
        this.fetchEvents();
      } catch (e) {
        console.error('Error deleting event:', e);
        Swal.fire({ icon: 'error', title: 'Ошибка', text: 'Не удалось удалить мероприятие', timer: 2500 });
      }
    },
    setView(mode) {
      this.viewMode = mode;
    },
    formatDate(s) {
      if (!s) return '—';
      try {
        let iso = this.currentLangIsoCode;
        return new Date(s).toLocaleString(iso, {
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
      if (!path) return '/storage/placeholders/600x400.svg';
      return /^https?:\/\//i.test(path) ? path : `/storage/${path}`;
    },
    onImgError(e) {
      e.target.src = '/storage/placeholders/600x400.svg';
    },
    tsToIso(ts) {
      // YYYY-MM-DD HH:mm:ss
      const d = new Date(ts);
      const pad = (n) => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    },
    percent(part, total) {
      const p = Number(part) || 0;
      const t = Number(total) || 0;
      if (t === 0) return 0;
      return Math.round((p / t) * 100);
    },
    getEventTitle(ev) {
      if (!ev) return '';
      // прямо на корне вдруг пришёл заголовок
      if (ev.title) return ev.title;

      const trs = Array.isArray(ev.translations) ? ev.translations : [];

      // 1) пробуем по коду (если в ответе translation.language есть как код)
      let t = trs.find(x => x.language === this.currentLangCode && x.title);
      if (t?.title) return t.title;

      // 2) пробуем по id языка (обычный случай)
      const wantedId = this.langIdByCode[this.currentLangCode];
      if (wantedId) {
        t = trs.find(x => Number(x.language_id) === Number(wantedId) && x.title);
        if (t?.title) return t.title;
      }

      // 3) дефолтный язык
      const defId = this.langIdByCode[this.defaultLangCode];
      if (defId) {
        t = trs.find(x => Number(x.language_id) === Number(defId) && x.title);
        if (t?.title) return t.title;
      }

      // 4) любое непустое название
      t = trs.find(x => x.title);
      if (t?.title) return t.title;

      // 5) запасной вариант
      return ev.slug || `#${ev.id}`;
    }
  },
  mounted() {
    this.fetchEvents();
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
