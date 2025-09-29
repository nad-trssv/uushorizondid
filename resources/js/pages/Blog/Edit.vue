<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-6">
          <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">
              {{ isEdit ? `#${event.id} - Редактирование` : 'Создание мероприятия' }}
            </h1>
            <n-tag v-if="isEdit" :type="event.status === 'published' ? 'success' : 'warning'" size="small">
              {{ event.status === 'published' ? 'Опубликовано' : 'Черновик' }}
            </n-tag>
          </div>
          
          <div class="flex flex-wrap gap-3">
            <n-button secondary @click="$router.back()">
              <template #icon>
                <i class="fas fa-arrow-left"></i>
              </template>
              Вернуться назад
            </n-button>
          </div>
        </div>

        <!-- Horizontal Tabs -->
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8 overflow-x-auto">
            <button
              v-for="tab in tabs"
              :key="tab.name"
              @click="activeTab = tab.name"
              :class="[
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2',
                activeTab === tab.name
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              <i :class="tab.icon"></i>
              {{ tab.label }}
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      
      <!-- Общие ошибки -->
      <n-alert v-if="Object.keys(validationErrors).length > 0" type="error" class="mb-6">
        <template #icon>
          <i class="fas fa-exclamation-triangle"></i>
        </template>
        <div class="font-semibold mb-2">Обнаружены ошибки валидации:</div>
        <ul class="list-disc list-inside space-y-1">
          <li v-for="(errors, field) in validationErrors" :key="field">
            <span class="font-medium">{{ getFieldLabel(field) }}:</span> 
            {{ Array.isArray(errors) ? errors.join(', ') : errors }}
          </li>
        </ul>
      </n-alert>
      
      <!-- Basic Info Tab -->
      <div v-if="activeTab === 'basic'" class="space-y-6">
        <n-card title="Основная информация" size="small">
          <div class="space-y-6">
            <!-- Title with language selector -->
            <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('translations', 'title') }">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Название мероприятия *</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                :value="currentTranslation.title" 
                @update:value="updateTranslationField('title', $event)"
                placeholder="Введите название мероприятия"
                size="large"
                class="w-full"
                :status="hasError('translations', 'title') ? 'error' : null"
              />
              <div v-if="hasError('translations', 'title')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ getError('translations', 'title') }}
              </div>
            </div>

            <!-- Short Description -->
            <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('translations', 'short_description') }">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Краткое описание</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                :value="currentTranslation.short_description" 
                @update:value="updateTranslationField('short_description', $event)"
                type="textarea"
                :rows="3"
                placeholder="Краткое описание мероприятия"
                class="w-full"
                :status="hasError('translations', 'short_description') ? 'error' : null"
              />
              <div v-if="hasError('translations', 'short_description')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ getError('translations', 'short_description') }}
              </div>
            </div>

            <!-- Full Description -->
            <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('translations', 'full_description') }">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Полное описание</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                :value="currentTranslation.full_description" 
                @update:value="updateTranslationField('full_description', $event)"
                type="textarea"
                :rows="6"
                placeholder="Полное описание мероприятия"
                class="w-full"
                :status="hasError('translations', 'full_description') ? 'error' : null"
              />
              <div v-if="hasError('translations', 'full_description')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ getError('translations', 'full_description') }}
              </div>
            </div>

            <!-- Location, Requirements, Included -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('translations', 'location') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Место проведения</label>
                <n-input 
                  :value="currentTranslation.location" 
                  @update:value="updateTranslationField('location', $event)"
                  placeholder="Кафе эстонского языка, Таллинн"
                  class="w-full"
                  :status="hasError('translations', 'location') ? 'error' : null"
                />
                <div v-if="hasError('translations', 'location')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('translations', 'location') }}
                </div>
              </div>

              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('translations', 'requirements') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Требования</label>
                <n-input 
                  :value="currentTranslation.requirements" 
                  @update:value="updateTranslationField('requirements', $event)"
                  type="textarea"
                  :rows="3"
                  placeholder="Базовые знания эстонского языка"
                  class="w-full"
                  :status="hasError('translations', 'requirements') ? 'error' : null"
                />
                <div v-if="hasError('translations', 'requirements')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('translations', 'requirements') }}
                </div>
              </div>

              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('translations', 'included') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Что включено</label>
                <n-input 
                  :value="currentTranslation.included" 
                  @update:value="updateTranslationField('included', $event)"
                  type="textarea"
                  :rows="3"
                  placeholder="Материалы, кофе-брейк, сертификат"
                  class="w-full"
                  :status="hasError('translations', 'included') ? 'error' : null"
                />
                <div v-if="hasError('translations', 'included')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('translations', 'included') }}
                </div>
              </div>
            </div>
          </div>
        </n-card>
      </div>

      <!-- Settings Tab -->
      <div v-if="activeTab === 'settings'" class="space-y-6">
        <n-card title="Настройки мероприятия" size="small">
          <div class="space-y-6">
            <!-- Slug and Image -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('slug') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Slug (ЧПУ) *</label>
                <n-input 
                  v-model:value="localEvent.slug" 
                  placeholder="URL-адрес"
                  class="w-full"
                  :status="hasError('slug') ? 'error' : null"
                />
                <div v-if="hasError('slug')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('slug') }}
                </div>
              </div>

              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('image') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Главное изображение</label>
                <n-input 
                  v-model:value="localEvent.image" 
                  placeholder="URL изображения"
                  class="w-full"
                  :status="hasError('image') ? 'error' : null"
                />
                <div v-if="localEvent.image" class="mt-2">
                  <img :src="localEvent.image" alt="Event Image" class="w-32 h-32 object-cover rounded" />
                </div>
                <div v-if="hasError('image')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('image') }}
                </div>
              </div>
            </div>

            <!-- Date and Time -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('start_time') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Дата и время начала *</label>
                <n-date-picker 
                  v-model:value="startTimeTimestamp" 
                  type="datetime"
                  clearable
                  class="w-full"
                  :status="hasError('start_time') ? 'error' : null"
                />
                <div v-if="hasError('start_time')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('start_time') }}
                </div>
              </div>

              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('end_time') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Дата и время окончания *</label>
                <n-date-picker 
                  v-model:value="endTimeTimestamp" 
                  type="datetime"
                  clearable
                  class="w-full"
                  :status="hasError('end_time') ? 'error' : null"
                />
                <div v-if="hasError('end_time')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('end_time') }}
                </div>
              </div>
            </div>

            <!-- Registration and Participants -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('registration_deadline') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Дедлайн регистрации</label>
                <n-date-picker 
                  v-model:value="registrationDeadlineTimestamp" 
                  type="datetime"
                  clearable
                  class="w-full"
                  :status="hasError('registration_deadline') ? 'error' : null"
                />
                <div v-if="hasError('registration_deadline')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('registration_deadline') }}
                </div>
              </div>

              <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('max_participants') }">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Максимум участников *</label>
                <n-input-number 
                  v-model:value="localEvent.max_participants" 
                  :min="1"
                  :max="100"
                  class="w-full"
                  :status="hasError('max_participants') ? 'error' : null"
                />
                <div v-if="hasError('max_participants')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ getError('max_participants') }}
                </div>
              </div>
            </div>

            <!-- Price -->
            <div class="border rounded-lg p-4 max-w-md" :class="{ 'border-red-300 bg-red-50': hasError('price') }">
              <label class="text-sm font-medium text-gray-700 mb-3 block">Цена (€)</label>
              <n-input-number 
                v-model:value="localEvent.price" 
                :min="0"
                :step="5"
                class="w-full"
                :status="hasError('price') ? 'error' : null"
              />
              <p class="text-xs text-gray-500 mt-2">0 = бесплатное мероприятие</p>
              <div v-if="hasError('price')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ getError('price') }}
              </div>
            </div>
          </div>
        </n-card>
      </div>

      <!-- SEO Tab -->
      <div v-if="activeTab === 'seo'" class="space-y-6">
        <n-alert type="info" class="mb-4">
          SEO настройки для языка: <strong>{{ currentLanguageName }}</strong>
        </n-alert>

        <n-card title="Мета-теги" size="small">
          <div class="space-y-6">
            <!-- Meta Title -->
            <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('seo', 'meta_title') }">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Meta Title</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                :value="currentSeo.meta_title" 
                @update:value="updateSeoField('meta_title', $event)"
                placeholder="Заголовок для поисковых систем"
                class="w-full"
                :status="hasError('seo', 'meta_title') ? 'error' : null"
              />
              <div v-if="hasError('seo', 'meta_title')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ getError('seo', 'meta_title') }}
              </div>
            </div>

            <!-- Meta Description -->
            <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('seo', 'meta_description') }">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Meta Description</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                :value="currentSeo.meta_description" 
                @update:value="updateSeoField('meta_description', $event)"
                type="textarea"
                :rows="3"
                placeholder="Описание для поисковых систем"
                class="w-full"
                :status="hasError('seo', 'meta_description') ? 'error' : null"
              />
              <div v-if="hasError('seo', 'meta_description')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ getError('seo', 'meta_description') }}
              </div>
            </div>

            <!-- Meta Keywords -->
            <div class="border rounded-lg p-4" :class="{ 'border-red-300 bg-red-50': hasError('seo', 'meta_keywords') }">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Meta Keywords</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                :value="currentSeo.meta_keywords" 
                @update:value="updateSeoField('meta_keywords', $event)"
                placeholder="Ключевые слова через запятую"
                class="w-full"
                :status="hasError('seo', 'meta_keywords') ? 'error' : null"
              />
              <div v-if="hasError('seo', 'meta_keywords')" class="text-red-600 text-xs mt-2 flex items-center gap-1">
                <i class="fas fa-exclamation-circle"></i>
                {{ getError('seo', 'meta_keywords') }}
              </div>
            </div>
          </div>
        </n-card>
      </div>

      <!-- Participants Tab -->
      <div v-if="activeTab === 'participants' && isEdit">
        <n-card title="Участники мероприятия" size="small">
          <div v-if="localEvent.participants && localEvent.participants.length > 0">
            <n-data-table
              :columns="participantColumns"
              :data="localEvent.participants"
              :pagination="pagination"
            />
          </div>
          <div v-else class="text-center py-12">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">Пока нет участников</p>
          </div>
        </n-card>
      </div>
    </div>

    <!-- Footer with Status -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 shadow-lg">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3 ml-8">
          <div v-if="isEdit" class="text-sm text-gray-500">
            <span>Создано: {{ formatDate(localEvent.created_at) }}</span>
            <span class="ml-4">Обновлено: {{ formatDate(localEvent.updated_at) }}</span>
          </div>
        </div>
        <div class="flex gap-3">
          <div class="flex items-center gap-3 ml-8 mr-8">
            <span class="text-sm font-medium text-gray-700">Статус:</span>
            <n-switch 
              v-model:value="isPublished" 
              size="small"
              :checked-text="'Опубликовано'" 
              :unchecked-text="'Черновик'"
              @update:value="updateStatus"
            />
          </div>
          <n-button @click="saveEvent" type="primary" size="small" :loading="loading">
            <template #icon>
              <i class="fas fa-save"></i>
            </template>
            Сохранить
          </n-button>
          
          <n-button v-if="!isEdit || localEvent.status === 'draft'" type="success" size="small" @click="publishEvent">
            <template #icon>
              <i class="fas fa-eye"></i>
            </template>
            Опубликовать
          </n-button>
          
          <n-button v-if="isEdit" type="error" size="small" ghost @click="deleteEvent">
            <template #icon>
              <i class="fas fa-trash"></i>
            </template>
            Удалить
          </n-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  NCard,
  NButton,
  NInput,
  NSelect,
  NTag,
  NAlert,
  NDatePicker,
  NSwitch,
  NInputNumber,
  NDataTable
} from 'naive-ui'

export default {
  name: 'EventForm',
  components: {
    NCard,
    NButton,
    NInput,
    NSelect,
    NTag,
    NAlert,
    NDatePicker,
    NSwitch,
    NInputNumber,
    NDataTable
  },
  props: {
    id: {
      type: [String, Number],
      default: null
    }
  },
  data() {
    return {
      activeTab: 'basic',
      currentLanguage: 'ru',
      loading: false,
      pagination: { pageSize: 10 },
      // Локальные реактивные данные
      localEvent: this.getDefaultEvent(),
      localTranslations: {},
      localSeo: {},
      // Ошибки валидации
      validationErrors: {},
      participantColumns: [
        {
          title: 'Имя',
          key: 'first_name',
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
          title: 'Кол-во',
          key: 'participants_count'
        },
        {
          title: 'Дата регистрации',
          key: 'created_at',
          render: (row) => this.formatDate(row.created_at)
        }
      ]
    }
  },
  computed: {
    isEdit() {
      return !!this.id
    },
    event() {
      return this.localEvent
    },
    tabs() {
      const baseTabs = [
        { name: 'basic', label: 'Основная информация', icon: 'fas fa-info-circle' },
        { name: 'settings', label: 'Настройки', icon: 'fas fa-cog' },
        { name: 'seo', label: 'SEO', icon: 'fas fa-search' }
      ]
      
      if (this.isEdit) {
        baseTabs.push({ 
          name: 'participants', 
          label: 'Участники', 
          icon: 'fas fa-users',
          badge: this.localEvent.participants_count 
        })
      }
      
      return baseTabs
    },
    availableLanguages() {
      const languages = this.$store.getters['settings/availableLanguages'] || {};
      console.log('Доступные языки из Vuex:', languages);
      
      // Преобразуем объект в массив и фильтруем включенные языки
      const languagesArray = Object.values(languages).filter(lang => lang && lang.enabled);
      console.log('Отфильтрованные языки:', languagesArray);
      
      return languagesArray;
    },
    languageOptions() {
      return this.availableLanguages.map(lang => ({ 
        label: lang.native_name, 
        value: lang.code 
      }))
    },
    currentTranslation() {
      if (!this.localTranslations[this.currentLanguage]) {
        this.localTranslations[this.currentLanguage] = this.createEmptyTranslation()
      }
      return this.localTranslations[this.currentLanguage]
    },
    currentSeo() {
      if (!this.localSeo[this.currentLanguage]) {
        this.localSeo[this.currentLanguage] = this.createEmptySeo()
      }
      return this.localSeo[this.currentLanguage]
    },
    currentLanguageName() {
      const lang = this.availableLanguages.find(l => l.code === this.currentLanguage)
      return lang ? lang.native_name : this.currentLanguage
    },
    isPublished: {
      get() {
        return this.localEvent.status === 'published'
      },
      set(value) {
        this.localEvent.status = value ? 'published' : 'draft'
      }
    },
    startTimeTimestamp: {
      get() {
        if (!this.localEvent.start_time) return null
        return new Date(this.localEvent.start_time).getTime()
      },
      set(value) {
        if (!value) return
        const date = new Date(value)
        this.localEvent.start_time = date.toISOString().slice(0, 19).replace('T', ' ')
        this.clearError('start_time')
      }
    },
    endTimeTimestamp: {
      get() {
        if (!this.localEvent.end_time) return null
        return new Date(this.localEvent.end_time).getTime()
      },
      set(value) {
        if (!value) return
        const date = new Date(value)
        this.localEvent.end_time = date.toISOString().slice(0, 19).replace('T', ' ')
        this.clearError('end_time')
      }
    },
    registrationDeadlineTimestamp: {
      get() {
        if (!this.localEvent.registration_deadline) return null
        return new Date(this.localEvent.registration_deadline).getTime()
      },
      set(value) {
        if (!value) {
          this.localEvent.registration_deadline = null
          return
        }
        const date = new Date(value)
        this.localEvent.registration_deadline = date.toISOString().slice(0, 19).replace('T', ' ')
        this.clearError('registration_deadline')
      }
    },
    // Исправленный currentLanguageIndex с защитой от ошибок
    currentLanguageIndex() {
      if (!Array.isArray(this.availableLanguages)) {
        console.warn('availableLanguages is not an array:', this.availableLanguages);
        return -1;
      }
      const index = this.availableLanguages.findIndex(lang => lang && lang.code === this.currentLanguage);
      console.log(`Index for language ${this.currentLanguage}:`, index);
      return index;
    }
  },
  methods: {
    getDefaultEvent() {
      return {
        id: null,
        slug: '',
        status: 'draft',
        image: '',
        max_participants: 10,
        current_participants: 0,
        price: 0,
        start_time: null,
        end_time: null,
        registration_deadline: null,
        views: 0,
        published_at: null,
        user_id: null,
        participants: [],
        participants_count: 0,
        confirmed_participants_count: 0
      }
    },
    initializeLocalData(storeEvent) {
      this.localEvent = { ...this.getDefaultEvent(), ...storeEvent }
      
      // Инициализируем переводы
      this.localTranslations = {}
      if (storeEvent.translations) {
        storeEvent.translations.forEach(translation => {
          // Находим код языка по language_id
          const lang = this.availableLanguages.find(l => l && l.id === translation.language_id)
          if (lang) {
            this.localTranslations[lang.code] = { 
              ...translation,
              language: lang.code // Сохраняем код языка для удобства
            }
          }
        })
      }
      
      // Инициализируем SEO
      this.localSeo = {}
      if (storeEvent.seo) {
        storeEvent.seo.forEach(seoItem => {
          // Находим код языка по language_id
          const lang = this.availableLanguages.find(l => l && l.id === seoItem.language_id)
          if (lang) {
            this.localSeo[lang.code] = { 
              ...seoItem,
              language: lang.code // Сохраняем код языка для удобства
            }
          }
        })
      }
    },
    createEmptyTranslation() {
      return {
        language: this.currentLanguage,
        title: '',
        short_description: '',
        full_description: '',
        location: '',
        requirements: '',
        included: ''
      }
    },
    createEmptySeo() {
      return {
        language: this.currentLanguage,
        meta_title: '',
        meta_description: '',
        meta_keywords: ''
      }
    },
    updateTranslationField(field, value) {
      if (!this.localTranslations[this.currentLanguage]) {
        this.localTranslations[this.currentLanguage] = this.createEmptyTranslation()
      }
      this.localTranslations[this.currentLanguage][field] = value
      this.clearError('translations', field)
    },
    updateSeoField(field, value) {
      if (!this.localSeo[this.currentLanguage]) {
        this.localSeo[this.currentLanguage] = this.createEmptySeo()
      }
      this.localSeo[this.currentLanguage][field] = value
      this.clearError('seo', field)
    },
    formatDate(dateString) {
      if (!dateString) return 'Не указано'
      return new Date(dateString).toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    },
    updateStatus(value) {
      this.localEvent.status = value ? 'published' : 'draft'
    },
    
    // Методы для работы с ошибками валидации
    setValidationErrors(errors) {
      this.validationErrors = errors || {}
    },
    
    clearValidationErrors() {
      this.validationErrors = {}
    },
    
    clearError(section, field = null) {
      if (field && section) {
        // Для переводов и SEO
        const errorKey = `${section}.${this.currentLanguageIndex}.${field}`
        if (this.validationErrors[errorKey]) {
          delete this.validationErrors[errorKey]
        }
      } else if (section) {
        // Для основных полей
        if (this.validationErrors[section]) {
          delete this.validationErrors[section]
        }
      } else {
        this.clearValidationErrors()
      }
    },
    
    hasError(section, field = null) {
      if (field && section) {
        // Ищем ошибки для переводов и SEO по текущему языку
        const errorKey = `${section}.${this.currentLanguageIndex}.${field}`
        return !!this.validationErrors[errorKey]
      } else if (section) {
        // Для основных полей
        return !!this.validationErrors[section]
      }
      return false
    },
    
    getError(section, field = null) {
      if (field && section) {
        const errorKey = `${section}.${this.currentLanguageIndex}.${field}`
        const error = this.validationErrors[errorKey]
        return error ? (Array.isArray(error) ? error[0] : error) : ''
      } else if (section) {
        const error = this.validationErrors[section]
        return error ? (Array.isArray(error) ? error[0] : error) : ''
      }
      return ''
    },
    
    getFieldLabel(field) {
      const labels = {
        'slug': 'Slug',
        'image': 'Изображение',
        'max_participants': 'Максимум участников',
        'price': 'Цена',
        'start_time': 'Время начала',
        'end_time': 'Время окончания',
        'registration_deadline': 'Дедлайн регистрации',
        'translations': 'Переводы',
        'seo': 'SEO',
        'title': 'Название',
        'short_description': 'Краткое описание',
        'full_description': 'Полное описание',
        'location': 'Место проведения',
        'requirements': 'Требования',
        'included': 'Что включено',
        'meta_title': 'Meta Title',
        'meta_description': 'Meta Description',
        'meta_keywords': 'Meta Keywords'
      }
      
      // Для вложенных полей
      if (field.includes('.')) {
        const parts = field.split('.')
        if (parts.length >= 3) {
          const fieldName = parts[2]
          return labels[fieldName] || fieldName
        }
      }
      
      return labels[field] || field
    },
    
    async saveEvent() {
      this.loading = true
      this.clearValidationErrors()
      
      try {
        const eventData = this.prepareEventData()
        
        // Отладочная информация
        console.log('Sending event data:', JSON.stringify(eventData, null, 2))
        
        let response
        if (this.isEdit) {
          response = await this.$store.dispatch('events/update', {
            id: this.localEvent.id,
            data: eventData
          })
        } else {
          response = await this.$store.dispatch('events/create', eventData)
        }
        
        this.$message.success('Мероприятие успешно сохранено')
        this.$router.push({ name: 'admin.events.list' })
      } catch (error) {
        console.error('Ошибка сохранения:', error)
        console.error('Response data:', error.response?.data)
        
        if (error.response?.status === 422 && error.response?.data?.errors) {
          // Сохраняем ошибки валидации
          this.setValidationErrors(error.response.data.errors)
          this.$message.error('Пожалуйста, исправьте ошибки в форме')
        } else if (error.response?.data?.message) {
          this.$message.error(error.response.data.message)
        } else {
          this.$message.error(error.message || 'Ошибка при сохранении мероприятия')
        }
      } finally {
        this.loading = false
      }
    },
    
    prepareEventData() {
      // Собираем все переводы
      const translations = this.availableLanguages.map(lang => {
        const translation = this.localTranslations[lang.code] || this.createEmptyTranslation()
        
        return {
          language_id: lang.id, // Используем ID языка из availableLanguages
          title: translation.title || '',
          short_description: translation.short_description || '',
          full_description: translation.full_description || '',
          location: translation.location || '',
          requirements: translation.requirements || '',
          included: translation.included || ''
        }
      })

      // Собираем SEO данные
      const seo = this.availableLanguages.map(lang => {
        const seoData = this.localSeo[lang.code] || this.createEmptySeo()
        
        return {
          language_id: lang.id, // Используем ID языка из availableLanguages
          meta_title: seoData.meta_title || '',
          meta_description: seoData.meta_description || '',
          meta_keywords: seoData.meta_keywords || ''
        }
      })

      return {
        slug: this.localEvent.slug,
        status: this.localEvent.status,
        image: this.localEvent.image,
        max_participants: this.localEvent.max_participants,
        price: parseFloat(this.localEvent.price) || 0,
        start_time: this.localEvent.start_time,
        end_time: this.localEvent.end_time,
        registration_deadline: this.localEvent.registration_deadline,
        translations,
        seo
      }
    },
    
    publishEvent() {
      this.localEvent.status = 'published'
      this.saveEvent()
    },
    
    async deleteEvent() {
      if (!confirm('Вы уверены, что хотите удалить это мероприятие?')) return
      
      try {
        await this.$store.dispatch('events/delete', this.localEvent.id)
        this.$message.success('Мероприятие удалено')
        this.$router.push({ name: 'admin.events.list' })
      } catch (error) {
        console.error('Ошибка удаления:', error)
        this.$message.error('Ошибка при удалении мероприятия')
      }
    }
  },
  
  watch: {
    currentLanguage(newLang) {
      // При смене языка убедимся, что переводы существуют
      if (!this.localTranslations[newLang]) {
        this.localTranslations[newLang] = this.createEmptyTranslation()
      }
      if (!this.localSeo[newLang]) {
        this.localSeo[newLang] = this.createEmptySeo()
      }
    },
    
    'localEvent.slug': function() {
      this.clearError('slug')
    },
    
    'localEvent.image': function() {
      this.clearError('image')
    },
    
    'localEvent.max_participants': function() {
      this.clearError('max_participants')
    },
    
    'localEvent.price': function() {
      this.clearError('price')
    }
  },
  
  mounted() {
    console.log('Available languages:', this.availableLanguages)
    
    if (this.isEdit) {
      this.$store.dispatch('events/show', this.id).then(response => {
        console.log('Event data loaded:', response.data)
        this.initializeLocalData(response.data.event)
        
        if (response.data.event.current_lang) {
          this.currentLanguage = response.data.event.current_lang
        } else {
          const defaultLang = this.availableLanguages.find(lang => lang.default)
          if (defaultLang) {
            this.currentLanguage = defaultLang.code
          }
        }
      }).catch(error => {
        console.error('Error fetching event:', error)
        this.$message.error('Ошибка загрузки мероприятия')
      })
    } else {
      // Для нового мероприятия инициализируем пустые переводы для всех языков
      this.availableLanguages.forEach(lang => {
        this.localTranslations[lang.code] = this.createEmptyTranslation()
        this.localSeo[lang.code] = this.createEmptySeo()
      })
    }
  }
}
</script>

<style scoped>
.border-red-300 {
  border-color: #fca5a5;
}

.bg-red-50 {
  background-color: #fef2f2;
}
</style>