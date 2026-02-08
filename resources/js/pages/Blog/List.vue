<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <!-- Заголовок и действия -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Список Новостей</h1>

      <div class="flex items-center gap-4 flex-wrap">
        <!-- Переключатель вида -->
        <div class="flex bg-white/10 backdrop-blur-md border border-white/30 rounded-xl p-1">
          <button @click="setView('grid')" class="p-2 rounded-lg transition-all cursor-pointer"
            :class="viewMode === 'grid' ? 'bg-white/20 text-gray-800' : 'text-gray-600 hover:bg-white/10'" title="Сетка">
            <i class="fas fa-th"></i>
          </button>
          <button @click="setView('list')" class="p-2 rounded-lg transition-all cursor-pointer"
            :class="viewMode === 'list' ? 'bg-white/20 text-gray-800' : 'text-gray-600 hover:bg-white/10'" title="Список">
            <i class="fas fa-list"></i>
          </button>
          <button @click="setView('table')" class="p-2 rounded-lg transition-all cursor-pointer"
            :class="viewMode === 'table' ? 'bg-white/20 text-gray-800' : 'text-gray-600 hover:bg-white/10'" title="Таблица">
            <i class="fas fa-table"></i>
          </button>
        </div>

        <button
          @click="$router.push({ name: 'admin.blog.create' })"
          class="toggle-weekends active w-full sm:w-auto action-button bg-blue-600 text-white px-4 py-2 flex items-center justify-center hover:bg-blue-700 transition">
          <i class="fas fa-plus mr-2"></i>
          {{ $t('msg.buttons.add_news') }}
        </button>
      </div>
    </div>

    <!-- Тумблер фильтров на мобилках -->
    <div class="mb-4 sm:hidden">
      <button @click="isFilterVisible = !isFilterVisible" class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded-lg flex items-center justify-center">
        <span v-if="isFilterVisible">Скрыть фильтры</span>
        <span v-else>Показать фильтры</span>
      </button>
    </div>

    <!-- Панель фильтров -->
    <div v-show="isFilterVisible" class="transition-all">
      <n-card title="Фильтры и поиск" :bordered="false" class="modern-filter-card" size="small">
        <!-- Строка 1 -->
        <div class="filter-row">
          <div class="filter-group">
            <n-form-item label="Поиск по новостям" class="compact-form-item">
              <n-input
                v-model:value="filters.search"
                @input="handleSearch"
                type="text"
                placeholder="Поиск по ID, slug, заголовку или описанию..."
                clearable
                class="modern-input"
                round>
                <template #prefix><i class="fas fa-search text-gray-400"></i></template>
              </n-input>
            </n-form-item>
          </div>

          <div class="filter-group">
            <n-form-item label="Статус" class="compact-form-item">
              <n-select
                v-model:value="filters.status"
                :options="statusOptions"
                @update:value="loadPosts(1)"
                placeholder="Выберите статус"
                class="modern-select"
                clearable />
            </n-form-item>
          </div>

          <div class="filter-group">
            <n-form-item label="Дата (диапазон по created_at)" class="compact-form-item">
              <n-date-picker
                v-model:value="filters.dateRange"
                type="daterange"
                @update:value="loadPosts(1)"
                placeholder="Выберите период"
                clearable
                class="compact-date-picker" />
            </n-form-item>
          </div>
        </div>

        <!-- Строка 2 -->
        <div class="filter-actions-row">
          <div class="filter-actions-left">
            <n-select
              v-model:value="filters.sort"
              :options="sortOptions"
              @update:value="loadPosts(1)"
              placeholder="Сортировка"
              size="small"
              style="width: 240px;" />
          </div>

          <div class="filter-actions-right">
            <n-space :size="8">
              <n-button secondary @click="resetFilters" size="small">
                <template #icon><i class="fas fa-undo"></i></template>
                Сбросить
              </n-button>
              <n-button type="primary" @click="loadPosts(1)" size="small">
                <template #icon><i class="fas fa-filter"></i></template>
                Применить
              </n-button>
            </n-space>
          </div>
        </div>
      </n-card>
    </div>

    <!-- Контент -->
    <div v-if="processedPosts.length">
      <!-- GRID -->
      <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="post in processedPosts" :key="post.id" class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105 flex flex-col h-[420px] relative group">
          <div class="absolute top-3 right-3 bg-white/20 backdrop-blur-sm rounded-full px-3 py-1.5 flex items-center gap-1 shadow-md z-10 border border-white/30">
            <i class="fas fa-star text-yellow-500 text-sm"></i>
            <span class="text-sm font-bold text-gray-800">{{ fmtRating(post.averageRating) }}</span>
          </div>

          <div class="absolute top-3 left-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
            <router-link :to="{ name: 'admin.blog.edit', params: { id: post.id } }" class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer">
              <i class="fas fa-edit text-xs"></i>
            </router-link>
            <button @click="confirmDelete(post)" class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer">
              <i class="fas fa-trash text-xs"></i>
            </button>
            <router-link :to="{ name: 'admin.blog.edit', params: { id: post.id } }" class="bg-white/20 backdrop-blur-md border border-white/30 text-gray-700 px-3.5 py-1 rounded-xl shadow-md transition-all hover:bg-white/30 hover:scale-110 active:scale-95 cursor-pointer">
              <i class="fas fa-eye text-xs"></i>
            </router-link>
          </div>

          <img
            :src="previewSrc(post.image)"
            alt="Post Image"
            class="w-full h-48 object-cover transition-transform group-hover:brightness-90"
            @error="$event.target.src = '/storage/placeholders/600x400.svg'" />

          <div class="p-4 flex-1 flex flex-col relative">
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full absolute top-[-12px] left-4 border-1 border-gray-200"
              :class="{
                'bg-white/50 text-green-500': post.status === 'published',
                'bg-white/50 text-yellow-500': post.status === 'draft',
                'bg-white/50 text-red-500': post.status === 'archived'
              }">
              {{ post.status }}
            </span>

            <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 leading-tight">
              {{ post.displayTitle }}
            </h2>

            <p class="text-sm text-gray-600 mb-4 description-clamp flex-1">
                {{ post.displayDescription.replace(/<\/?[^>]+(>|$)/g, '') }}
            </p>

            <div class="flex justify-between items-center pt-3 mt-auto border-t border-gray-100">
              <span class="text-xs text-gray-500">{{ formatDate(post.published_at) }}</span>
              <div class="flex items-center gap-3">
                <div class="flex items-center gap-1" v-if="post.gallery">
                  <i class="fas fa-image text-blue-500 text-xs"></i>
                  <span class="text-xs text-gray-600">{{ post.gallery.length }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <i class="fas fa-comment text-purple-500 text-xs"></i>
                  <span class="text-xs text-gray-600">{{ post.comments_count || 0 }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <i class="fas fa-eye text-green-500 text-xs"></i>
                  <span class="text-xs text-gray-600">{{ post.views || 0 }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- LIST -->
      <div v-else-if="viewMode === 'list'" class="space-y-3">
        <div v-for="post in processedPosts" :key="post.id" class="bg-white rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg group h-24 sm:h-28">
          <div class="flex h-full">
            <div class="w-20 sm:w-28 min-w-[5rem] sm:min-w-[7rem] relative">
              <img :src="previewSrc(post.image)" alt="Post Image" class="w-full h-full object-cover" @error="$event.target.src = '/storage/placeholders/600x400.svg'" />
              <div class="absolute top-1 right-1 sm:top-2 sm:right-2 bg-white/20 backdrop-blur-sm rounded-full px-1.5 sm:px-2 py-0.5 sm:py-1 flex items-center gap-1 shadow-md z-10 border border-white/30">
                <i class="fas fa-star text-yellow-500 text-xs"></i>
                <span class="text-[10px] sm:text-xs font-bold text-gray-800">{{ fmtRating(post.averageRating) }}</span>
              </div>
            </div>

            <div class="flex-1 p-2 sm:p-3 relative">
              <span v-if="!isMobile" class="absolute top-1 right-1 sm:top-2 sm:right-2 px-1.5 sm:px-2 py-0.5 sm:py-1 text-[10px] sm:text-xs font-semibold text-white rounded-full"
                :class="{
                  'bg-green-500': post.status === 'published',
                  'bg-yellow-500': post.status === 'draft',
                  'bg-red-500': post.status === 'archived'
                }">
                {{ post.status }}
              </span>

              <div class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <router-link :to="{ name: 'admin.blog.edit', params: { id: post.id } }" class="bg-blue-500 hover:bg-blue-600 text-white p-1 sm:p-1.5 rounded shadow-md transition-colors cursor-pointer">
                  <i class="fas fa-eye text-[10px] sm:text-xs"></i>
                </router-link>
                <router-link :to="{ name: 'admin.blog.edit', params: { id: post.id } }" class="bg-green-500 hover:bg-green-600 text-white p-1 sm:p-1.5 rounded shadow-md transition-colors cursor-pointer">
                  <i class="fas fa-edit text-[10px] sm:text-xs"></i>
                </router-link>
                <button @click="confirmDelete(post)" class="bg-red-500 hover:bg-red-600 text-white p-1 sm:p-1.5 rounded shadow-md transition-colors cursor-pointer">
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
                  <span>{{ post.comments_count || 0 }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <i class="fas fa-eye text-green-500"></i>
                  <span>{{ post.views || 0 }}</span>
                </div>
                <i v-if="isMobile"
                   :class="{
                     'fas fa-check-circle text-green-500': post.status === 'published',
                     'fas fa-pencil-alt text-yellow-500': post.status === 'draft',
                     'fas fa-archive text-red-500': post.status === 'archived'
                   }"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TABLE -->
      <div v-else class="bg-white rounded-lg shadow-md overflow-hidden overflow-x-auto">
        <table class="w-full">
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
                  <img :src="previewSrc(post.image)" alt="Post Image" class="w-12 h-12 object-cover rounded" @error="$event.target.src = '/storage/placeholders/600x400.svg'" />
                  <div class="ml-4 flex flex-col justify-center text-sm text-gray-900">
                    <div class="flex items-center gap-1">
                      <i class="fas fa-image text-blue-500 text-xs"></i>
                      <span class="text-xs" v-if="post.gallery">{{ post.gallery.length }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <i class="fas fa-comment text-purple-500 text-xs"></i>
                      <span class="text-xs">{{ post.comments_count || 0 }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                      <i class="fas fa-eye text-green-500 text-xs"></i>
                      <span class="text-xs">{{ post.views || 0 }}</span>
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
                  }">
                  {{ post.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-1">
                  <i class="fas fa-star text-yellow-500 text-sm"></i>
                  <span class="text-sm font-medium text-gray-900">{{ fmtRating(post.averageRating) }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(post.published_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex gap-1">
                  <router-link :to="{ name: 'admin.blog.edit', params: { id: post.id } }" class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                    <i class="fas fa-eye text-xs"></i>
                  </router-link>
                  <router-link :to="{ name: 'admin.blog.edit', params: { id: post.id } }" class="bg-green-500 hover:bg-green-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
                    <i class="fas fa-edit text-xs"></i>
                  </router-link>
                  <button @click="confirmDelete(post)" class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded shadow-md transition-colors cursor-pointer">
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

    <!-- Пагинация -->
    <paginate
      v-if="totalPages > 1"
      :total-pages="totalPages"
      :current-page="page"
      :visible-pages="visiblePages"
      @page-changed="changePage" />
  </div>
</template>

<script>
import Paginate from '@/components/layouts/admin/Pagination.vue';
import { NCard, NFormItem, NInput, NSelect, NDatePicker, NButton, NSpace } from 'naive-ui';
import Swal from 'sweetalert2';

export default {
  name: 'PostsList',
  components: { Paginate, NCard, NFormItem, NInput, NSelect, NDatePicker, NButton, NSpace },
  data() {
    return {
      isFilterVisible: true,
      viewMode: 'grid',
      isMobile: false,
      filters: {
        search: '',
        status: '',
        dateRange: null, // [tsStart, tsEnd]
        sort: 'created_at_desc' // field_direction
      },
      searchTimeout: null,
      sortOptions: [
        { label: 'Создано (новые → старые)', value: 'created_at_desc' },
        { label: 'Создано (старые → новые)', value: 'created_at_asc' },
        { label: 'Опубликовано (новые → старые)', value: 'published_at_desc' },
        { label: 'Опубликовано (старые → новые)', value: 'published_at_asc' },
        { label: 'Статус (A→Z)', value: 'status_asc' },
        { label: 'Статус (Z→A)', value: 'status_desc' },
        { label: 'Просмотры (больше → меньше)', value: 'views_desc' },
        { label: 'Просмотры (меньше → больше)', value: 'views_asc' },
      ],
      statusOptions: [
        { label: 'Все статусы', value: '' },
        { label: 'Опубликовано', value: 'published' },
        { label: 'Черновик', value: 'draft' },
        { label: 'В архиве', value: 'archived' }
      ],
    };
  },
  computed: {
    posts() {
      return this.$store.getters['posts/lists'] || [];
    },
    totalPages() {
      return this.$store.getters['posts/totalPages'] || 1;
    },
    page() {
      return this.$store.getters['posts/page'] || 1;
    },
    perPage() {
      return this.$store.getters['posts/perPage'] || 18;
    },
    visiblePages() {
      const current = this.page;
      const total = this.totalPages;
      const range = 1;
      const pages = [];
      let start = Math.max(1, current - range);
      let end = Math.min(total, current + range);

      if (total <= 5) {
        for (let i = 1; i <= total; i++) pages.push(i);
        return pages;
      }
      if (current - range <= 1) end = Math.min(total, end + (range - current + 2));
      if (current + range >= total) start = Math.max(1, start - (current + range - total + 1));
      for (let i = start; i <= end; i++) pages.push(i);
      return pages;
    },
    processedPosts() {
      if (!Array.isArray(this.posts)) return [];
      return this.posts.map(p => ({
        ...p,
        displayTitle: this.getPostTitle(p),
        displayDescription: this.getPostDescription(p),
        gallery: p.gallery || []
      }));
    }
  },
  methods: {
    setView(mode) { this.viewMode = mode; },
    fmtRating(val) {
      const n = Number(val);
      return Number.isFinite(n) ? n.toFixed(1) : '—';
    },
    previewSrc(path) {
      if (!path) return '/storage/placeholders/600x400.svg';
      return /^https?:\/\//i.test(path) ? path : `/storage/${path}`;
    },
    getPostTitle(post) {
      if (post?.title?.trim()) return post.title;
      const trs = Array.isArray(post?.translations) ? post.translations : [];
      let t = trs.find(x => x.default && x.title);
      if (t?.title) return t.title;
      t = trs.find(x => x.title);
      return t?.title || post?.slug || `#${post?.id}`;
    },
    getPostDescription(post) {
      if (post?.short_description?.trim()) return post.short_description;
      const trs = Array.isArray(post?.translations) ? post.translations : [];
      let t = trs.find(x => x.default && x.short_description);
      if (t?.short_description) return t.short_description;
      t = trs.find(x => x.short_description);
      return t?.short_description || 'Описание отсутствует';
    },
    formatDate(s) {
      if (!s) return '';
      try {
        return new Date(s).toLocaleDateString('ru-RU', { year: 'numeric', month: 'short', day: 'numeric' });
      } catch { return ''; }
    },
    changePage(pageNumber) { this.loadPosts(pageNumber); },
    handleSearch() {
      clearTimeout(this.searchTimeout);
      this.searchTimeout = setTimeout(() => this.loadPosts(1), 400);
    },
    sortMapping(value) {
      if (!value) return { field: 'created_at', dir: 'desc' };
      const i = value.lastIndexOf('_');
      if (i === -1) return { field: value, dir: 'desc' };
      const field = value.slice(0, i);         // "created_at"
      const dirRaw = value.slice(i + 1);       // "desc"
      const dir = dirRaw.toLowerCase() === 'asc' ? 'asc'
                : dirRaw.toLowerCase() === 'desc' ? 'desc'
                : 'desc';
      return { field, dir };
    },

    tsToSql(ts, endOfDay = false) {
      const d = new Date(ts);
      if (endOfDay) d.setHours(23, 59, 59, 999);
      const pad = (n) => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    },
    async loadPosts(page = 1) {
      const params = {
        page,
        perPage: this.perPage
      };

      // поиск
      if (this.filters.search) params.search = this.filters.search;

      // статус
      if (this.filters.status) params.status = this.filters.status;

      // даты: если выбран один день — шлём "date", если диапазон — шлём date_from/date_to (если бэк не поддерживает, просто игнорнёт)
      if (Array.isArray(this.filters.dateRange) && this.filters.dateRange.length === 2) {
        const [from, to] = this.filters.dateRange;
        if (from && to && new Date(from).toDateString() === new Date(to).toDateString()) {
          params.date = new Date(from).toISOString().slice(0, 10); // YYYY-MM-DD
        } else {
          if (from) params.date_from = this.tsToSql(from, false);
          if (to)   params.date_to   = this.tsToSql(to,   true);
        }
      }

      // сортировка → camelCase
      const { field, dir } = this.sortMapping(this.filters.sort || 'created_at_desc');
      params.sortBy = field;          // ← "created_at"
      params.sortDirection = dir;     // ← "asc" | "desc"

      await this.$store.dispatch('posts/lists', params);
    },
    resetFilters() {
      this.filters = { search: '', status: '', dateRange: null, sort: 'created_at_desc' };
      this.loadPosts(1);
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
        await Swal.fire({ icon: 'success', title: 'Удалено', timer: 1500, showConfirmButton: false });
        // если текущая страница опустела — шаг назад
        if (this.processedPosts.length - 1 <= 0 && this.page > 1) this.changePage(this.page - 1);
        else this.loadPosts(this.page);
      } catch (e) {
        console.error(e);
        Swal.fire({ icon: 'error', title: 'Ошибка', text: 'Не удалось удалить новость', timer: 2500 });
      }
    },
    handleResize() { this.isMobile = window.innerWidth <= 768; }
  },
  mounted() {
    this.loadPosts(1);
    this.isFilterVisible = window.innerWidth > 768; // спрячем фильтры на мобилке
    this.handleResize();
    window.addEventListener('resize', this.handleResize);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.handleResize);
  }
};
</script>

<style scoped>
/* твои стили оставил и чуть подчистил */
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.description-clamp { display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; max-height: 5.6em; }
.h-\[460px\] { height: 460px; }
.text-lg { line-height: 1.3; }
.group { transition: all 0.3s ease; }
.group-hover\:brightness-90 { filter: brightness(0.9); }

/* карточка фильтров */
.modern-filter-card { border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.05); margin-bottom: 24px; }
.filter-row { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; }
.filter-group { flex: 1; min-width: 200px; }
.compact-form-item { margin-bottom: 0; }
.compact-form-item :deep(.n-form-item-label) { font-size: 13px; margin-bottom: 4px; }
.compact-date-picker { width: 100%; }

.filter-actions-row { display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.05); }
.filter-actions-left, .filter-actions-right { display: flex; align-items: center; }

/* адаптив */
@media (max-width: 768px) {
  .filter-row { flex-direction: column; gap: 8px; }
  .filter-group { min-width: 100%; width: 100%; }
  .filter-actions-row { flex-direction: column; gap: 12px; align-items: stretch; }
  .filter-actions-left, .filter-actions-right { width: 100%; }
}
</style>
