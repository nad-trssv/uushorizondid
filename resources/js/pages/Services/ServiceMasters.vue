<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <!-- Хлебные крошки и заголовок -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
      
      <div class="flex items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
          <span v-if="service">{{ service.name }}</span>
          <n-skeleton v-else text width="200px" />
          <n-tag :bordered="false" type="info" size="small" v-if="service">
            ID: {{ service.id }}
          </n-tag>
        </h1>
      </div>
      <div class="">
        <n-button 
          v-if="service"
          text
          tag="a"
          :href="`/admin/services/${service.id}/edit`"
          class="!text-indigo-500 hover:!text-indigo-700"
        >
          <template #icon>
            <i class="fas fa-edit"></i>
          </template>
          Редактировать услугу
        </n-button>
      </div>
    </div>

    <!-- Статистика -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <n-statistic label="Всего мастеров" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-users text-indigo-500 mr-2"></i>
        </template>
        {{ service?.masters?.length || 0 }}
      </n-statistic>

      <n-statistic label="Доступно мастеров" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i class="fas fa-user-plus text-green-500 mr-2"></i>
        </template>
        {{ availableMasters.length }}
      </n-statistic>

      <n-statistic label="Статус услуги" class="bg-white p-4 rounded-lg shadow">
        <template #prefix>
          <i :class="service?.status ? 'fas fa-check-circle text-green-500' : 'fas fa-times-circle text-red-500'" class="mr-2"></i>
        </template>
        <n-tag :type="service?.status ? 'success' : 'error'" size="small" :bordered="false">
          {{ service?.status ? 'Активна' : 'Неактивна' }}
        </n-tag>
      </n-statistic>
    </div>

    <!-- Основной контент -->
    <n-card title="Мастера услуги" class="mb-6" :segmented="{ content: true }">
      <template #header-extra>
        <n-button 
          type="primary" 
          size="small" 
          @click="showAddMasterModal = true"
          :disabled="availableMasters.length === 0"
        >
          <template #icon>
            <i class="fas fa-plus"></i>
          </template>
          Добавить мастера
        </n-button>
      </template>

      <!-- Список мастеров -->
      <n-empty 
        v-if="!loading && service?.masters?.length === 0" 
        description="Нет назначенных мастеров"
        class="py-8"
      >
        <template #extra>
          <n-button size="small" @click="showAddMasterModal = true" :disabled="availableMasters.length === 0">
            Добавить первого мастера
          </n-button>
        </template>
      </n-empty>

      <n-list v-else bordered hoverable>
        <n-list-item v-for="master in service?.masters" :key="master.id">
          <template #prefix>
            <n-avatar round size="medium" class="bg-gray-100">
              <i class="fas fa-user text-gray-400"></i>
            </n-avatar>
          </template>
          
          <n-thing :title="master.user_name" content-style="margin-top: 8px;">
            <template #description>
              <n-space size="small" class="mt-1">
                <n-tag size="small" :bordered="false" type="info">
                  ID: {{ master.user_id }}
                </n-tag>
              </n-space>
            </template>
          </n-thing>

          <template #suffix>
            <n-button 
              type="error" 
              size="small" 
              tertiary
              @click="confirmRemoveMaster(master)"
            >
              <template #icon>
                <i class="fas fa-trash-alt"></i>
              </template>
              <span class="hidden sm:inline">Удалить</span>
            </n-button>
          </template>
        </n-list-item>
      </n-list>
    </n-card>

    <!-- Модальное окно добавления мастера -->
    <n-modal 
      v-model:show="showAddMasterModal" 
      preset="card"
      :style="{
        width: '90%',
        maxWidth: '600px',
        borderRadius: '12px',
        boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        backgroundColor: 'rgba(255, 255, 255, 0.7)',
      }"
      title="Добавить мастера"
      :bordered="false"
      size="huge"
      class="modern-modal"
    >
      <div class="modal-content">
        <div class="search-container mb-4">
          <n-input
            v-model:value="searchQuery"
            placeholder="Поиск по имени или email"
            clearable
            round
          >
            <template #prefix>
              <i class="fas fa-search text-gray-400"></i>
            </template>
          </n-input>
        </div>

        <n-empty 
          v-if="filteredAvailableMasters.length === 0" 
          :description="searchQuery ? 'Ничего не найдено' : 'Нет доступных мастеров'"
          class="empty-state"
        >
          <template #extra>
            <n-button 
              size="small" 
              @click="$router.push({ name: 'admin.users.create' })"
              type="primary"
              ghost
            >
              <template #icon>
                <i class="fas fa-plus"></i>
              </template>
              Создать нового мастера
            </n-button>
          </template>
        </n-empty>

        <n-list 
          v-else 
          bordered 
          hoverable 
          class="masters-list"
        >
          <n-list-item 
            v-for="master in filteredAvailableMasters" 
            :key="master.id"
            class="master-item"
          >
            <template #prefix>
              <n-avatar 
                round 
                size="small" 
                :style="{ backgroundColor: stringToColor(master.name) }"
              >
                <span class="avatar-text">
                  {{ getInitials(master.name) }}
                </span>
              </n-avatar>
            </template>
            
            <n-thing 
              :title="master.name" 
              content-style="margin-top: 4px;"
              class="master-info"
            >
              <template #description>
                <n-space size="small" class="mt-1 tags">
                  <n-tag size="tiny" :bordered="false" type="info">
                    ID: {{ master.id }}
                  </n-tag>
                  <n-tag size="tiny" :bordered="false" type="default">
                    {{ master.email }}
                  </n-tag>
                </n-space>
              </template>
            </n-thing>

            <template #suffix>
              <n-button 
                type="primary" 
                size="small" 
                @click="addMaster(master)"
                class="add-button"
                secondary
              >
                <i class="fas fa-plus"></i>
                <span class="button-text">Добавить</span>
              </n-button>
            </template>
          </n-list-item>
        </n-list>
      </div>
    </n-modal>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { 
  NAvatar, 
  NButton, 
  NCard, 
  NEmpty, 
  NList, 
  NListItem, 
  NModal, 
  NSpace, 
  NTag, 
  NThing,
  NStatistic,
  NSkeleton,
  NInput
} from 'naive-ui';

export default {
  name: 'ServiceMasters',
  components: {
    NAvatar, 
    NButton, 
    NCard, 
    NEmpty, 
    NList, 
    NListItem, 
    NModal, 
    NSpace, 
    NTag, 
    NThing,
    NStatistic,
    NSkeleton,
    NInput
  },
  setup() {
    const showAddMasterModal = ref(false);
    const searchQuery = ref('');
    
    return {
      showAddMasterModal,
      searchQuery
    }
  },
  data() {
    return {
      loading: true
    }
  },
  computed: {
    serviceId() {
      return this.$route.params.id;
    },
    // Мастера, которые еще не назначены на услугу
    availableMasters() {
      const serviceMasters = this.service.masters;
      if (!serviceMasters|| !this.allMasters.length) return [];
      
      const assignedMasterIds = serviceMasters.map(m => m.user_id);
      return this.allMasters.filter(master => !assignedMasterIds.includes(master.id));
    },
    // Отфильтрованные мастера по поисковому запросу
    filteredAvailableMasters() {
      if (!this.searchQuery) return this.availableMasters;
      
      const query = this.searchQuery.toLowerCase();
      return this.availableMasters.filter(master => 
        master.name.toLowerCase().includes(query) || 
        (master.email && master.email.toLowerCase().includes(query))
      );
    },
    allMasters() {
      return this.$store.getters['users/masters'];
    },
    service() {
      return this.$store.getters['services/editService'] || null;
    }
  },
  methods: {
    getService() {
      try {
        this.loading = true;
        this.$store.dispatch('services/getById', this.serviceId);
        
      } catch (error) {
        console.error('Ошибка загрузки услуги:', error);
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: 'Не удалось загрузить данные услуги',
          timer: 3000,
          customClass: {
            popup: 'modern-toast'
          }
        });
      } finally {
        this.loading = false;
      }
    },
    getAllMasters() {
      try {
        this.loading = true;
        this.$store.dispatch('users/getMasters');
      } catch (error) {
        console.error('Ошибка загрузки мастеров:', error);
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: 'Не удалось загрузить список мастеров',
          timer: 3000,
          customClass: {
            popup: 'modern-toast'
          }
        });
      } finally {
        this.loading = false;
      }
    },
    addMaster(master) {
      try {
        this.$store.dispatch('services/addMaster', { serviceId: this.serviceId, master_id: master.id })
          .then((res) => {
            if(res.success) {
              // this.showAddMasterModal = false;
              this.searchQuery = '';
              this.getService();
              this.getAllMasters();
              this.$alert.success(`Мастер ${master.name} успешно добавлен.`);

            } else {
              throw new Error(res.message || 'Не удалось добавить мастера');
            }
          });
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: 'Не удалось добавить мастера',
          timer: 3000,
          customClass: {
            popup: 'modern-toast'
          }
        });
      }
    },
    confirmRemoveMaster(master) {
      this.$alert.confirm(`Вы уверены, что хотите удалить мастера <b>${master.user_name}</b> из этой услуги?`, 'Удалить мастера?')
        .then((result) => {
          if (result.isConfirmed) {
            this.removeMaster(master);
          }
        });
    },
    removeMaster(master) {
      try {
        this.$store.dispatch('services/removeMaster', { serviceId: this.serviceId, master_id: master.user_id })
        .then((res) => {
          if(res.success) {
            this.getService();
            this.$alert.success(`Мастер ${master.user_name} успешно удален из услуги.`);
          }
        });
      } catch (error) {
        console.error('Ошибка удаления мастера:', error);
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: 'Не удалось удалить мастера',
          timer: 3000,
          customClass: {
            popup: 'modern-toast'
          }
        });
      }
    },
    stringToColor(str) {
      let hash = 0;
      for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
      }
      const color = `hsl(${hash % 360}, 70%, 80%)`;
      return color;
    },
    
    getInitials(name) {
      return name.split(' ').map(part => part[0]).join('').toUpperCase();
    }
  },
  mounted() {
    this.getService();
    this.getAllMasters();
  }
}
</script>

<style scoped>
/* Адаптивные стили */
@media (max-width: 640px) {
  .n-list-item__prefix {
    margin-right: 12px;
  }
  
  .n-list-item__suffix {
    margin-left: 12px;
  }
  
  .n-thing {
    min-width: 0;
    overflow: hidden;
  }
  
  .n-thing-title {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}

/* Анимации */
.n-list-item {
  transition: all 0.2s ease;
}

.n-list-item:hover {
  background-color: rgba(241, 245, 249, 0.8);
}
.modern-modal {
  --n-padding: 20px;
}

.modal-content {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.search-container {
  padding: 0 4px;
}

.empty-state {
  padding: 40px 0;
}

.masters-list {
  max-height: 60vh;
  overflow-y: auto;
  border-radius: 8px;
  border: 1px solid rgba(224, 224, 224, 0.6);
}

.master-item {
  padding: 12px 16px;
  transition: all 0.2s ease;
}

.master-item:hover {
  background-color: rgba(241, 245, 249, 0.6);
}

.master-info {
  flex-grow: 1;
  min-width: 0;
}

.master-info :deep(.n-thing-main) {
  overflow: hidden;
}

.master-info :deep(.n-thing-title) {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-size: 0.95rem;
}

.tags {
  flex-wrap: wrap;
}

.avatar-text {
  font-size: 0.7rem;
  font-weight: 600;
  color: #333;
}

.add-button {
  flex-shrink: 0;
}

@media (max-width: 640px) {
  .modern-modal {
    --n-padding: 16px;
    width: 95% !important;
  }
  
  .master-item {
    padding: 10px 12px;
  }
  
}@media (max-width: 640px) {
  .add-button .button-text {
    display: none;
  }
}

</style>