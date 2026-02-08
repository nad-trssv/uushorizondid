<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <!-- Заголовок и действия -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Новости</h1>

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
          :to="{ name: 'admin.posts.create' }"
          class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95"
        >
          <i class="fas fa-plus"></i>
          <span>Добавить Новость</span>
        </router-link>
      </div>
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

        <n-form-item label="Период (создано)" :show-feedback="false" class="lg:col-span-2">
          <n-date-picker
            v-model:value="filters.createdRange"
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

    <!-- TOP PAGINATION -->
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

    <!-- CONTENT -->
    <div id="postsResults">
      <!-- GRID -->
      <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <n-card
          v-for="post in posts"
          :key="post.id"
          class="shadow-sm"
          :title="getPostTitle(post) || ('#' + post.id)"
        >
          <template #cover>
            <img
              :src="previewSrc(post.image)"
              alt="cover"
              class="w-full h-44 object-cover"
              @error="onImgError"
            />
          </template>

          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <n-tag :type="post.status === 'published' ? 'success' : 'warning'" size="small" :bordered="false">
                {{ post.status === 'published' ? 'Опубликовано' : 'Черновик' }}
              </n-tag>
              <n-tag v-if="Number.isFinite(post.averageRating)" size="small" type="info" :bordered="false">
                <i class="fas fa-star mr-1"></i>{{ Number(post.averageRating).toFixed(1) }}
              </n-tag>
              <n-tag v-if="post.comments_count" size="small" type="default" :bordered="false">
                <i class="fas fa-comment mr-1"></i>{{ post.comments_count }}
              </n-tag>
            </div>

            <div class="text-sm text-gray-600">
              <i class="far fa-calendar-alt mr-1"></i>
              {{ formatDate(post.published_at) }}
            </div>

            <div class="text-xs text-gray-400">
              <i class="fas fa-eye mr-1"></i>{{ post.views || 0 }}
            </div>
          </div>

          <template #action>
            <div class="flex justify-between items-center">
              <n-button size="small" @click="$router.push({ name: 'admin.posts.edit', params: { id: post.id } })" type="primary">
                <template #icon><i class="fas fa-edit"></i></template>
                Редактировать
              </n-button>

              <n-button size="small" ghost type="error" @click="confirmDelete(post)">
                <template #icon><i class="fas fa-trash"></i></template>
                Удалить
              </n-button>
            </div>
          </template>
        </n-card>
      </div>

      <!-- LIST -->
      <div v-else-if="viewMode === 'list'" class="space-y-3">
        <n-card v-for="post in posts" :key="post.id" class="shadow-sm">
          <div class="flex gap-4">
            <img
              :src="previewSrc(post.image)"
              alt=""
              class="w-24 h-24 object-cover rounded"
              @error="onImgError"
            />
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2">
                <h3 class="font-semibold truncate">{{ getPostTitle(post) || ('#' + post.id) }}</h3>
                <n-tag :type="post.status === 'published' ? 'success' : 'warning'" size="small" :bordered="false">
                  {{ post.status === 'published' ? 'Опубликовано' : 'Черновик' }}
                </n-tag>
              </div>
              <div class="text-sm text-gray-600 mt-1">
                <i class="far fa-calendar-alt mr-1"></i>
                {{ formatDate(post.published_at) }}
              </div>
              <div class="text-xs text-gray-500 mt-1">
                <i class="fas fa-eye mr-1"></i>{{ post.views || 0 }}
                <i class="fas fa-comment ml-3 mr-1"></i>{{ post.comments_count || 0 }}
                <i class="fas fa-star ml-3 mr-1 text-yellow-500"></i>{{ Number(post.averageRating ?? 0).toFixed(1) }}
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <n-button size="small" @click="$router.push({ name: 'admin.posts.edit', params: { id: post.id } })" type="primary">
                <template #icon><i class="fas fa-edit"></i></template>
                Редактировать
              </n-button>
              <n-button size="small" ghost type="error" @click="confirmDelete(post)">
                <template #icon><i class="fas fa-trash"></i></template>
                Удалить
              </n-button>
            </div>
          </div>
        </n-card>
      </div>

      <!-- TABLE -->
      <n-card v-else class="shadow-sm rounded-lg" :bordered="false">
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

        <div class="overflow-x-auto postsTableContainer">
          <n-data-table
            :columns="columns"
            :data="posts"
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

    <!-- BOTTOM PAGINATION -->
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
  NCard, NButton, NDataTable, NPagination, NSelect,
  NFormItem, NInput, NDatePicker, NTag, NDropdown
} from 'naive-ui';
import Swal from 'sweetalert2';

export default {
  name: 'PostsIndex',
  components: {
    NCard, NButton, NDataTable, NPagination, NSelect,
    NFormItem, NInput, NDatePicker, NTag, NDropdown
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
        status: null,         // 'published' | 'draft' | null
        createdRange: null    // [tsStart, tsEnd] | null
      }
    };
  },
  computed: {
    posts() {
      return this.$store.getters['posts/lists'] || [];
    },
    totalPages() {
      return this.$store.getters['posts/totalPages'] || 1;
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
      return [
        { title: 'ID', key: 'id', width: 80, sorter: true, class: isMobile ? 'hidden sm:table-cell' : '' },
        {
          title: 'Заголовок',
          key: 'title',
          render: (row) =>
            h(
              'a',
              {
                class: 'cursor-pointer hover:underline text-indigo-700',
                onClick: () => this.$router.push({ name: 'admin.posts.edit', params: { id: row.id } })
              },
              this.getPostTitle(row)
            ),
          sorter: false,
          minWidth: 200,
          resizable: true
        },
        {
          title: 'Статус',
          key: 'status',
          render: (row) =>
            h(NTag, { size: 'small', bordered: false, type: row.status === 'published' ? 'success' : 'warning' }, { default: () => (row.status === 'published' ? 'Опубликовано' : 'Черновик') }),
          sorter: true,
          width: 120
        },
        {
          title: 'Опубликовано',
          key: 'published_at',
          render: (row) => this.formatDate(row.published_at),
          sorter: true,
          minWidth: 160
        },
        {
          title: 'Просмотры',
          key: 'views',
          sorter: true,
          width: 110
        },
        {
          title: 'Комментарии',
          key: 'comments_count',
          sorter: true,
          width: 130
        },
        {
          title: 'Рейтинг',
          key: 'averageRating',
          render: (row) => (Number.isFinite(row.averageRating) ? Number(row.averageRating).toFixed(1) : '—'),
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
                  if (key === 'edit') this.$router.push({ name: 'admin.posts.edit', params: { id: row.id } });
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
    },
    currentLangIsoCode() {
      return this.$store.getters['settings/currentLocale']?.iso || 'ru-RU';
    },
  },
  methods: {
    async fetchPosts() {
      this.loading = true;
      try {
        const params = {
          page: this.page,
          per_page: this.pageSize
        };
        if (this.sortField && this.sortOrder) {
          params.sort_by = this.sortField;
          params.sort_direction = this.sortOrder;
        }
        if (this.filters.query) params.search = this.filters.query;
        if (this.filters.status) params.status = this.filters.status;
        if (Array.isArray(this.filters.createdRange) && this.filters.createdRange[0]) {
          params.date_from = this.tsToIso(this.filters.createdRange[0]);
        }
        if (Array.isArray(this.filters.createdRange) && this.filters.createdRange[1]) {
          params.date_to = this.tsToIso(this.filters.createdRange[1]);
        }

        await this.$store.dispatch('posts/lists', params);
      } catch (e) {
        console.error('Error fetching posts:', e);
        Swal.fire({ icon: 'error', title: 'Ошибка', text: 'Не удалось загрузить новости', timer: 3000 });
      } finally {
        this.loading = false;
      }
    },
    handleSort({ columnKey, order }) {
      // n-data-table -> order: 'ascend' | 'descend' | false
      this.sortField = columnKey;
      this.sortOrder = order === 'ascend' ? 'asc' : order === 'descend' ? 'desc' : null;
      this.page = 1;
      this.fetchPosts();
    },
    handlePageChange(p) {
      this.page = p;
      this.fetchPosts();
      this.$nextTick(() => {
        const wrap = document.querySelector('#postsResults');
        if (wrap) wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    },
    handlePageSizeChange(size) {
      this.pageSize = size;
      this.page = 1;
      this.fetchPosts();
    },
    applyFilters() {
      this.page = 1;
      this.fetchPosts();
    },
    resetFilters() {
      this.filters = { query: null, status: null, createdRange: null };
      this.page = 1;
      this.fetchPosts();
    },

    async confirmDelete(post) {
      const res = await Swal.fire({
        title: 'Удалить новость?',
        text: post.slug ? `Вы уверены, что хотите удалить "${this.getPostTitle(post)}"?` : 'Действие необратимо.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Да, удалить',
        cancelButtonText: 'Отмена'
      });
      if (!res.isConfirmed) return;

      try {
        await this.$store.dispatch('posts/delete', post.id);
        Swal.fire({ icon: 'success', title: 'Удалено', timer: 2000 });
        if (this.posts.length - 1 <= 0 && this.page > 1) this.page -= 1;
        this.fetchPosts();
      } catch (e) {
        console.error('Error deleting post:', e);
        Swal.fire({ icon: 'error', title: 'Ошибка', text: 'Не удалось удалить новость', timer: 2500 });
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

    // Извлекаем заголовок/описание как в EventIndex
    getPostTitle(post) {
      if (!post) return '';
      if (post.title) return post.title;
      const trs = Array.isArray(post.translations) ? post.translations : [];
      // сначала def=true
      let t = trs.find(x => x.default && x.title);
      if (t?.title) return t.title;
      // любое непустое
      t = trs.find(x => x.title);
      return t?.title || post.slug || `#${post.id}`;
    },
  },
  mounted() {
    this.fetchPosts();
  }
};
</script>

<style scoped>
.n-data-table :deep(.n-data-table-th) {
  background-color: #f9fafb;
  font-weight: 600;
  color: #374151;
}
.n-data-table :deep(.n-data-table-td) {
  padding: 12px 16px;
}
@media (max-width: 768px) {
  .n-card { border-radius: 0; box-shadow: none; }
  .n-data-table :deep(.n-data-table-th),
  .n-data-table :deep(.n-data-table-td) { padding: 8px 12px; }
}
.action-menu-button { transition: all .2s ease; }
</style>
