<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <!-- Заголовок и кнопка -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Список услуг</h1>
      <router-link    
        :to="{ name: 'admin.services.create' }"
        class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95"
      >
        <i class="fas fa-plus"></i>
        <span>Создать услугу</span>
      </router-link>

    </div>

    <!-- Статистика -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <n-statistic label="Всего услуг" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-spa text-green-500 mr-2"></i>
        </template>
        {{ stats.total_count || 0 }}
      </n-statistic>

      <n-statistic label="Активные" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-check-circle text-blue-500 mr-2"></i>
        </template>
        {{ stats.active_count || 0 }}
        <template #suffix>
          <n-tag :bordered="false" type="success" size="small">
            {{ Math.round((stats.active_count / stats.total_count) * 100) || 0 }}%
          </n-tag>
        </template>
      </n-statistic>

      <n-statistic label="Неактивные" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-times-circle text-red-500 mr-2"></i>
        </template>
        {{ stats.inactive_count || 0 }}
        <template #suffix>
          <n-tag :bordered="false" type="error" size="small">
            {{ Math.round((stats.inactive_count / stats.total_count) * 100) || 0 }}%
          </n-tag>
        </template>
      </n-statistic>

      <n-statistic label="С фикс. временем" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-clock text-yellow-500 mr-2"></i>
        </template>
        {{ stats.has_fixed_time_count || 0 }}
      </n-statistic>
    </div>

    <!-- Дополнительная статистика -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <n-statistic label="Средняя цена" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-euro-sign text-purple-500 mr-2"></i>
        </template>
        {{ stats.price_avg ? stats.price_avg.toFixed(2) : '0.00' }}
        <template #suffix>
          <n-tag :bordered="false" type="info" size="small">
            {{ stats.price_min }}-{{ stats.price_max }}
          </n-tag>
        </template>
      </n-statistic>

      <n-statistic label="Средняя длительность" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-hourglass-half text-blue-500 mr-2"></i>
        </template>
        {{ stats.duration_avg || 0 }} мин
        <template #suffix>
          <n-tag :bordered="false" type="info" size="small">
            {{ stats.duration_min }}-{{ stats.duration_max }} мин
          </n-tag>
        </template>
      </n-statistic>

      <n-statistic label="Утренние услуги" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-sun text-yellow-500 mr-2"></i>
        </template>
        {{ stats.morning_count || 0 }}
        <template #suffix>
          <n-tag :bordered="false" type="warning" size="small">
            Дневных: {{ stats.afternoon_count || 0 }}
          </n-tag>
        </template>
      </n-statistic>
    </div>

    <!-- Фильтры - новая версия -->
    <n-card class="mb-6" :bordered="false">
      <template #header>
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-medium text-gray-800">Фильтры</h3>
          <n-button text @click="resetFilters" class="!text-indigo-500">
            <template #icon>
              <i class="fas fa-sync-alt mr-1"></i>
            </template>
            Сбросить
          </n-button>
        </div>
      </template>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <n-form-item label="Название" path="name" :show-feedback="false" class="w-full  col-span-full">
          <n-input 
            v-model:value="filters.name" 
            placeholder="Поиск по названию" 
            clearable 
            round
            size="large"
            @keydown.enter="handleFilterChange"
          >
            <template #prefix>
              <i class="fas fa-search text-gray-400"></i>
            </template>
          </n-input>
        </n-form-item>
        
        <n-form-item label="Категория" path="category_id" :show-feedback="false">
          <n-cascader
            v-model:value="filters.category_id"
            :options="categoryOptions"
            placeholder="Выберите категорию"
            clearable
            check-strategy="all"
            @update:value="handleFilterChange"
            class="w-full"
          />
        </n-form-item>
        
        <n-form-item label="Статус" path="status" :show-feedback="false">
          <n-select
            v-model:value="filters.status"
            :options="statusOptions"
            placeholder="Все статусы"
            clearable
            @update:value="handleFilterChange"
            class="w-full"
          />
        </n-form-item>
        
        <n-form-item label="Фикс. время" path="has_fixed_time" :show-feedback="false">
          <n-select
            v-model:value="filters.has_fixed_time"
            :options="fixedTimeOptions"
            placeholder="Все типы"
            clearable
            @update:value="handleFilterChange"
            class="w-full"
          />
        </n-form-item>
      </div>
      <div class="mt-4 flex justify-end">
        <n-button 
          type="default" 
          size="medium"
          round
          @click="handleFilterChange"
          class="w-full sm:w-auto"
        >
          <template #icon>
            <i class="fas fa-filter"></i>
          </template>
          Применить фильтры
        </n-button>
        
      </div>
    </n-card>

    <!-- Таблица -->
    <n-card class="shadow-sm rounded-lg" :bordered="false">
      <!-- Пагинация -->
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
      
      <div class="overflow-x-auto">
        <n-data-table
          :columns="columns"
          :data="services"
          :bordered="false"
          :loading="loading"
          :remote="true"
          @update:sorter="handleSort"
          striped
          class="min-h-[400px]"
          :row-class-name="rowClassName"
        />
      </div>
      
      <!-- Пагинация -->
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
import { h, ref } from 'vue';
import { 
  NTag, 
  NCard, 
  NButton, 
  NStatistic, 
  NDataTable, 
  NPagination, 
  NSelect,
  NTime,
  NBadge,
  NSpace,
  NForm,
  NFormItem,
  NInput,
  NCascader,
  NDropdown
} from 'naive-ui';
import { format } from 'date-fns';

export default {
  name: 'Services',
  components: {
    NTag,
    NCard,
    NButton,
    NStatistic,
    NDataTable,
    NPagination,
    NSelect,
    NTime,
    NBadge,
    NSpace,
    NForm,
    NFormItem,
    NInput,
    NCascader,
    NDropdown
  },
  setup() {
    return {
      loading: ref(false),
      page: ref(1),
      pageSize: ref(10),
      sortField: ref(null),
      sortOrder: ref(null),
      stats: ref({}),
      filters: ref({
        name: null,
        category_id: null,
        status: null,
        has_fixed_time: null
      })
    }
  },
  computed: {
    services() {
      return this.$store.getters['services/lists'] || [];
    },
    categories() {
      return this.$store.getters['categories/lists'] || [];
    },
    totalPages() {
      return this.$store.getters['services/totalPages'] || 1;
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
        { label: 'Активные', value: 1 },
        { label: 'Неактивные', value: 0 }
      ];
    },
    fixedTimeOptions() {
      return [
        { label: 'С фикс. временем', value: 1 },
        { label: 'Без фикс. времени', value: 0 }
      ];
    },
    categoryOptions() {
      const options = [];
      
      this.categories.forEach(category => {
        if (!category) return;
        
        const option = {
          label: category.name,
          value: category.id,
          depth: 1
        };
        
        if (category.children && category.children.length > 0) {
          option.children = category.children.map(child => ({
            label: child.name,
            value: child.id,
            depth: 2
          }));
        }
        
        options.push(option);
      });
      
      return options;
    },
    columns() {
      const isMobile = window.innerWidth < 768;
      
      const columns = [
        {
          title: 'ID',
          key: 'id',
          width: 80,
          sorter: true,
          class: isMobile ? 'hidden sm:table-cell' : ''
        },
        {
          title: this.$t('msg.label.title'),
          key: 'name',
          render: (row) => h(
            'div',
            { class: 'flex items-center' },
            [
              h('div', {
                class: 'w-3 h-3 rounded-full mr-2',
                style: { backgroundColor: row.eventColor }
              }),
              h('a', {
                class: row.name ? 'cursor-pointer hover:underline cursor-pointer text-indigo-700' : 'nodata text-gray-400',
                onClick: () => {
                  this.$router.push({ name: 'admin.services.edit', params: { id: row.id } });
                }
              }, row.name || this.$t('msg.label.unnamed'))
            ]
          ),
          sorter: true,
          minWidth: 180,
          resizable: true
        },
        {
          title: this.$t('msg.label.category'), 
          key: 'category_name',
          render: (row) => {
            const category = this.findCategoryWithParent(row.category_id);
            const parts = [];
            if (category.parent) {
              parts.push(h('span', { class: 'text-gray-400 mr-1 hidden sm:inline whitespace-nowrap' }, `${category.parent.name} → `));
            }
            parts.push(h('span', { class: row.category_id ? 'whitespace-nowrap' : 'whitespace-nowrap text-gray-400'  }, category.name || this.$t('msg.label.uncategorized')));
            return h('div', { class: 'flex flex-col items-start' }, parts);
          },
          class: isMobile ? 'hidden sm:table-cell' : '',
          minWidth: 120,
          resizable: true
        },
        {
          title: this.$t('msg.label.price'),
          key: 'price',
          render: (row) => {
            const price = h('span', { class: 'font-medium' }, row.price);

            const priceTag = row.price_can_change
              ? h(
                  NTag,
                  { type: 'warning', size: 'small', class: 'ml-1 hidden sm:inline' },
                  { default: () => this.$t('msg.price_can_change') }
                )
              : null;

            return h('div', { class: 'flex items-center flex-col gap-1' }, [price, priceTag]);
          },
          sorter: true,
          minWidth: 100
        },
        {
          title: this.$t('msg.label.duration'),
          key: 'duration_minutes',
          render: (row) => `${row.duration_minutes_min} - ${row.duration_minutes} ${this.$t('msg.label.min')}`,
          sorter: true,
          class: isMobile ? 'hidden sm:table-cell' : '',
          minWidth: 130
        },
        {
          title: this.$t('msg.label.status'),
          key: 'status',
          render: (row) => h(
            NBadge,
            {
              type: row.status ? 'success' : 'error',
              dot: true
            },
            {
              default: () => h(
                NTag,
                { 
                  type: row.status ? 'success' : 'error',
                  bordered: false 
                },
                {
                  default: () => h(
                    'div', 
                    { class: 'flex items-center gap-1' }, 
                    [
                      h('i', {
                        class: row.status ? 'fas fa-check-circle' : 'fas fa-times-circle',
                        style: { fontSize: '14px' }
                      }),
                      h('span', { class: 'hidden sm:inline' }, row.status ? this.$t('msg.label.active') : this.$t('msg.label.inactive'))
                    ]
                  )
                }
              )
            }
          ),
          width: 120
        },
        {
          title: this.$t('msg.label.actions'),
          key: 'actions',
          render: (row) => h(
            NDropdown,
            {
              trigger: 'click',
              placement: isMobile ? 'top-end' : 'bottom-end',
              options: [
                {
                  label: 'Редактировать',
                  key: 'edit',
                  icon: () => h('i', { class: 'fas fa-edit mr-2 text-blue-500' })
                },
                {
                  label: 'Мастера',
                  key: 'masters',
                  icon: () => h('i', { class: 'fas fa-users mr-2 text-indigo-500' })
                },
                {
                  type: 'divider'
                },
                {
                  label: row.status ? 'Деактивировать' : 'Активировать',
                  key: 'toggle',
                  icon: () => h('i', { 
                    class: row.status ? 'fas fa-ban mr-2 text-red-500' : 'fas fa-check mr-2 text-green-500' 
                  })
                }
              ],
              onSelect: (key) => {
                switch(key) {
                  case 'edit':
                    this.$router.push({ name: 'admin.services.edit', params: { id: row.id } });
                    break;
                  case 'masters':
                    this.$router.push({ name: 'admin.services.masters', params: { id: row.id } });
                    break;
                  case 'toggle':
                    this.toggleStatus(row);
                    break;
                }
              }
            },
            {
              default: () => h(
                NButton,
                {
                  size: 'small',
                  type: 'primary',
                  ghost: true,
                  class: 'action-menu-button hover:bg-indigo-500',
                },
                {
                  default: () => h('div', { class: 'flex items-center gap-1' }, [
                    h('i', { class: 'fas fa-ellipsis-v' })
                  ])
                }
              )
            }
          ),
          width: 60
        }
      ];

      // Добавляем колонку времени только для десктопной версии
      if (!isMobile) {
        columns.splice(5, 0, {
          title: this.$t('msg.label.booking_time'),
          key: 'has_fixed_time',
          render: (row) => row.has_fixed_time ? h(
            'div',
            { class: 'flex items-center gap-2' },
            [
              h('i', { class: 'fas fa-clock text-yellow-500' }),
              h('span', `${row.time_from} - ${row.time_to}`)
            ]
          ) : h('span', { class: 'text-gray-400' }, this.$t('msg.label.flexible')),
          minWidth: 150
        });
      }

      return columns;
    }
  },
  methods: {
    rowClassName(row, index) {
      return index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
    },
    async fetchServices() {
      this.loading = true;
      try {
        const payload = {
          page: this.page,
          pageSize: this.pageSize,
          sortField: this.sortField,
          sortOrder: this.sortOrder,
          ...this.filters
        };
        
        const response = await this.$store.dispatch('services/lists', payload);
        this.stats = response.data.stats || {};
      } catch (error) {
        console.error('Error fetching services:', error);
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: 'Не удалось загрузить список услуг',
          timer: 3000
        });
      } finally {
        this.loading = false;
      }
    },
    handleSort({ columnKey, order }) {
      this.sortField = columnKey;
      this.sortOrder = order === 'ascend' ? 'asc' : 'desc'; 
      this.page = 1;
      this.fetchServices();
    },
    handlePageChange(page) {
      this.page = page;
      this.fetchServices();
      this.$nextTick(() => {
        const tableContainer = document.querySelector('.servicesTableContainer');
        if (tableContainer) {
          tableContainer.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
          });
        }
      });
    },
    handlePageSizeChange(size) {
      this.pageSize = size;
      this.page = 1;
      this.fetchServices();
    },
    handleFilterChange() {
      this.page = 1;
      this.fetchServices();
    },
    resetFilters() {
      this.filters = {
        name: null,
        category_id: null,
        status: null,
        has_fixed_time: null
      };
      this.page = 1;
      this.fetchServices();
    },
    async toggleStatus(service) {
      try {
        const result = await Swal.fire({
          title: service.status ? 'Деактивировать услугу?' : 'Активировать услугу?',
          text: `Вы уверены, что хотите ${service.status ? 'деактивировать' : 'активировать'} услугу "${service.name}"?`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Да',
          cancelButtonText: 'Отмена'
        });

        if (result.isConfirmed) {
          await this.$store.dispatch('services/toggleStatus', { id: service.id, status: !service.status });
          
          Swal.fire({
            icon: 'success',
            title: 'Успех!',
            text: `Услуга "${service.name}" успешно ${service.status ? 'деактивирована' : 'активирована'}`,
            timer: 2000
          });
          
          this.fetchServices();
        }
      } catch (error) {
        console.error('Error toggling service status:', error);
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: 'Не удалось изменить статус услуги',
          timer: 3000
        });
      }
    },
    formatDate(dateString) {
      return format(new Date(dateString), 'dd.MM.yyyy HH:mm');
    },
    getCategories() {
      return this.$store.dispatch('categories/lists')
        .catch(error => {
          console.error('Ошибка загрузки категорий:', error);
        });
    },
    findCategoryWithParent(categoryId) {
      if (!this.categories || this.categories.length === 0) {
        return { name: 'Загрузка...', parent: null };
      }

      for (const category of this.categories) {
        if (!category) continue;
        
        // Проверяем родительские категории
        if (category.id === categoryId) {
          return { ...category, parent: null };
        }
        // Проверяем дочерние категории
        if (category.children) {
          const child = category.children.find(c => c.id === categoryId);
          if (child) {
            return { ...child, parent: category };
          }
        }
      }
      return { name: 'Неизвестная категория', parent: null }
    },
  },
  mounted() {
    this.fetchServices();
    this.getCategories();
  }
}
</script>

<style scoped>
/* Общие стили для таблицы */
.n-data-table :deep(.n-data-table-th) {
  background-color: #f9fafb;
  font-weight: 600;
  color: #374151;
}

.n-data-table :deep(.n-data-table-td) {
  padding: 12px 16px;
}

/* Адаптивные стили */
@media (max-width: 768px) {
  .n-card {
    @apply rounded-none shadow-none;
  }
  
  .n-data-table :deep(.n-data-table-th),
  .n-data-table :deep(.n-data-table-td) {
    padding: 8px 12px;
  }
}

/* Стили для кнопки действий */
.action-menu-button {
  @apply transition-all;
}


/* Горизонтальная прокрутка для таблицы */
.overflow-x-auto {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

</style>