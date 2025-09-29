<template>
    <div class="max-w-7xl mx-auto p-4 sm:p-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Участники мероприятия</h1>
          <p class="text-gray-600 mt-1">{{ event.displayTitle }}</p>
        </div>
        
        <div class="flex items-center gap-4">
          <n-button secondary @click="$router.back()">
            <template #icon>
              <i class="fas fa-arrow-left"></i>
            </template>
            Назад
          </n-button>
          
          <n-button type="primary" @click="exportParticipants">
            <template #icon>
              <i class="fas fa-download"></i>
            </template>
            Экспорт
          </n-button>
        </div>
      </div>
  
      <!-- Статистика -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
          <div class="text-2xl font-bold text-blue-600">{{ event.confirmed_participants_count || 0 }}</div>
          <div class="text-sm text-gray-500">Подтверждено</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
          <div class="text-2xl font-bold text-yellow-600">{{ pendingCount }}</div>
          <div class="text-sm text-gray-500">Ожидание</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
          <div class="text-2xl font-bold text-green-600">{{ event.available_spots || 0 }}</div>
          <div class="text-sm text-gray-500">Свободно мест</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
          <div class="text-2xl font-bold text-purple-600">{{ event.max_participants }}</div>
          <div class="text-sm text-gray-500">Всего мест</div>
        </div>
      </div>
  
      <!-- Фильтры -->
      <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
            <n-select 
              v-model:value="statusFilter"
              :options="statusOptions"
              clearable
              placeholder="Все статусы"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Поиск</label>
            <n-input 
              v-model:value="searchQuery"
              placeholder="Поиск по имени или email..."
              clearable
            />
          </div>
          <div class="flex items-end">
            <n-button @click="applyFilters" type="primary" class="w-full">
              Применить фильтры
            </n-button>
          </div>
        </div>
      </div>
  
      <!-- Таблица участников -->
      <n-card title="Список участников" size="small">
        <n-data-table
          :columns="columns"
          :data="filteredParticipants"
          :pagination="pagination"
          :loading="loading"
        />
      </n-card>
    </div>
  </template>
  
  <script>
  import { NButton, NTag, NSelect, NInput, NCard, NDataTable } from 'naive-ui'
  
  export default {
    name: 'EventParticipants',
    components: {
      NButton,
      NTag,
      NSelect,
      NInput,
      NCard,
      NDataTable
    },
    props: {
      id: {
        type: [String, Number],
        required: true
      }
    },
    data() {
      return {
        loading: false,
        searchQuery: '',
        statusFilter: null,
        pagination: { pageSize: 10 },
        statusOptions: [
          { label: 'Ожидание', value: 'pending' },
          { label: 'Подтвержден', value: 'confirmed' },
          { label: 'Отменен', value: 'cancelled' },
          { label: 'Лист ожидания', value: 'waiting_list' }
        ]
      }
    },
    computed: {
      event() {
        const event = this.$store.getters['events/editEvent'] || {}
        return {
          ...event,
          displayTitle: this.getEventTitle(event)
        }
      },
      participants() {
        return this.event.participants || []
      },
      pendingCount() {
        return this.participants.filter(p => p.status === 'pending').length
      },
      filteredParticipants() {
        let filtered = this.participants
  
        if (this.statusFilter) {
          filtered = filtered.filter(p => p.status === this.statusFilter)
        }
  
        if (this.searchQuery) {
          const query = this.searchQuery.toLowerCase()
          filtered = filtered.filter(p => 
            p.first_name.toLowerCase().includes(query) ||
            p.last_name.toLowerCase().includes(query) ||
            p.email.toLowerCase().includes(query)
          )
        }
  
        return filtered
      },
      columns() {
        return [
          {
            title: 'Имя',
            key: 'name',
            render: (row) => `${row.first_name} ${row.last_name}`
          },
          {
            title: 'Email',
            key: 'email'
          },
          {
            title: 'Телефон',
            key: 'phone'
          },
          {
            title: 'Статус',
            key: 'status',
            render: (row) => {
              const statusMap = {
                'pending': { type: 'warning', text: 'Ожидание' },
                'confirmed': { type: 'success', text: 'Подтвержден' },
                'cancelled': { type: 'error', text: 'Отменен' },
                'waiting_list': { type: 'default', text: 'Лист ожидания' }
              }
              const status = statusMap[row.status] || { type: 'default', text: row.status }
              return this.$createElement(NTag, { type: status.type, size: 'small' }, { default: () => status.text })
            }
          },
          {
            title: 'Кол-во человек',
            key: 'participants_count'
          },
          {
            title: 'Заметки',
            key: 'notes',
            render: (row) => row.notes || '-'
          },
          {
            title: 'Дата регистрации',
            key: 'created_at',
            render: (row) => this.formatDate(row.created_at)
          },
          {
            title: 'Действия',
            key: 'actions',
            render: (row) => {
              return this.$createElement('div', { class: 'flex gap-1' }, [
                this.$createElement(NButton, {
                  size: 'small',
                  type: row.status === 'confirmed' ? 'default' : 'success',
                  onClick: () => this.updateStatus(row.id, 'confirmed')
                }, { default: () => 'Подтвердить' }),
                
                this.$createElement(NButton, {
                  size: 'small',
                  type: row.status === 'cancelled' ? 'default' : 'error',
                  onClick: () => this.updateStatus(row.id, 'cancelled')
                }, { default: () => 'Отменить' }),
                
                this.$createElement(NButton, {
                  size: 'small',
                  type: 'warning',
                  onClick: () => this.editParticipant(row.id)
                }, { default: () => 'Редактировать' })
              ])
            }
          }
        ]
      }
    },
    methods: {
      getEventTitle(event) {
        if (event.title) return event.title
        if (event.translations && event.translations.length > 0) {
          return event.translations[0].title || 'Без названия'
        }
        return 'Без названия'
      },
      formatDate(dateString) {
        if (!dateString) return ''
        return new Date(dateString).toLocaleDateString('ru-RU', {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        })
      },
      applyFilters() {
        // Фильтры применяются автоматически через computed
      },
      async updateStatus(participantId, status) {
        try {
          // Здесь будет вызов API для обновления статуса
          console.log(`Update participant ${participantId} to ${status}`)
          this.$message.success('Статус обновлен')
          
          // Перезагружаем данные мероприятия
          await this.$store.dispatch('events/show', this.id)
        } catch (error) {
          console.error('Error updating status:', error)
          this.$message.error('Ошибка обновления статуса')
        }
      },
      editParticipant(participantId) {
        // Редактирование участника
        console.log('Edit participant:', participantId)
      },
      exportParticipants() {
        // Экспорт участников в CSV/Excel
        console.log('Export participants')
        this.$message.info('Функция экспорта в разработке')
      }
    },
    mounted() {
      this.loading = true
      this.$store.dispatch('events/show', this.id)
        .then(() => {
          this.loading = false
        })
        .catch(error => {
          console.error('Error loading event:', error)
          this.loading = false
          this.$message.error('Ошибка загрузки данных мероприятия')
        })
    }
  }
  </script>