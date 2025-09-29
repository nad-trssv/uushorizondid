<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-6">
          <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">
              {{ isEdit ? `#${routeId} — Редактирование` : 'Создание мероприятия' }}
            </h1>
            <n-tag v-if="isEdit" :type="localEvent.status === 'published' ? 'success' : 'warning'" size="small">
              {{ localEvent.status === 'published' ? 'Опубликовано' : 'Черновик' }}
            </n-tag>
          </div>
          <div class="flex flex-wrap gap-3">
            <n-button secondary @click="$router.back()">
              <template #icon><i class="fas fa-arrow-left"></i></template>
              Назад
            </n-button>
            <n-button v-if="isEdit" type="primary" @click="$router.push({ name: 'admin.events.create' })">
              <template #icon><i class="fas fa-plus"></i></template>
              Создать новое
            </n-button>
          </div>
        </div>

        <!-- Tabs -->
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

    <!-- Main -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- BASIC -->
      <div v-if="activeTab === 'basic'" class="space-y-6">
        <n-card title="Основная информация" size="small">
          <!-- Переключатели языков (вкладки) -->
          <n-tabs type="line" animated class="translation-tabs" v-model:value="currentLanguage">
            <n-tab-pane
              v-for="lang in activeLanguages"
              :key="`${lang.code}-basic`"
              :name="lang.code"
            >
              <template #tab>
                <div class="tab-label">
                  <img v-if="lang.flag" :src="`/storage/${lang.flag}`" :alt="lang.name" class="language-flag" />
                  <span class="language-name">{{ lang.native_name || lang.name || lang.code }}</span>
                </div>
              </template>

              <div class="space-y-6">
                <!-- Title -->
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Название *</label>
                  <n-input
                    v-model:value="ensureTrans(lang.code).title"
                    placeholder="Введите название мероприятия"
                    size="large"
                  />
                </div>

                <!-- Short description -->
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Краткое описание</label>
                  <n-input
                    v-model:value="ensureTrans(lang.code).short_description"
                    type="textarea"
                    :rows="3"
                    placeholder="Краткое описание"
                  />
                </div>

                <!-- Full description -->
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Полное описание</label>
                  <n-input
                    v-model:value="ensureTrans(lang.code).full_description"
                    type="textarea"
                    :rows="6"
                    placeholder="Подробное описание"
                  />
                </div>

                <!-- Location / Requirements / Included -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <div class="border rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Место проведения</label>
                    <n-input
                      v-model:value="ensureTrans(lang.code).location"
                      placeholder="Кафе эстонского языка, Таллинн"
                    />
                  </div>

                  <div class="border rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Требования</label>
                    <n-input
                      v-model:value="ensureTrans(lang.code).requirements"
                      type="textarea"
                      :rows="3"
                      placeholder="Напр.: Базовые знания A2+"
                    />
                  </div>

                  <div class="border rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-700 mb-2 block">Что включено</label>
                    <n-input
                      v-model:value="ensureTrans(lang.code).included"
                      type="textarea"
                      :rows="3"
                      placeholder="Материалы, кофе-брейк, сертификат"
                    />
                  </div>
                </div>
              </div>
            </n-tab-pane>
          </n-tabs>
        </n-card>
      </div>

      <!-- SETTINGS -->
      <div v-if="activeTab === 'settings'" class="space-y-6">
        <n-card title="Настройки мероприятия" size="small">
          <div class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Slug (ЧПУ) *</label>
                <n-input v-model:value="localEvent.slug" placeholder="URL-адрес" />
              </div>

              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Главное изображение</label>
                <n-input v-model:value="localEvent.image" placeholder="Путь/URL изображения" />
                <div v-if="localEvent.image" class="mt-2">
                  <img :src="previewSrc(localEvent.image)" alt="Event Image" class="w-32 h-32 object-cover rounded" />
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Дата и время начала *</label>
                <n-date-picker v-model:value="startTimeTs" type="datetime" clearable class="w-full" />
              </div>
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Дата и время окончания *</label>
                <n-date-picker v-model:value="endTimeTs" type="datetime" clearable class="w-full" />
              </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Дедлайн регистрации</label>
                <n-date-picker v-model:value="deadlineTs" type="datetime" clearable class="w-full" />
              </div>
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Максимум участников *</label>
                <n-input-number v-model:value="localEvent.max_participants" :min="1" :max="1000" class="w-full" />
              </div>
            </div>

            <div class="border rounded-lg p-4 max-w-md">
              <label class="text-sm font-medium text-gray-700 mb-2 block">Цена (€)</label>
              <n-input-number v-model:value="localEvent.price" :min="0" :step="1" class="w-full" />
              <p class="text-xs text-gray-500 mt-2">0 = бесплатное мероприятие</p>
            </div>

            <div class="border rounded-lg p-4 max-w-md flex items-center gap-3">
              <span class="text-sm font-medium text-gray-700">Статус:</span>
              <n-switch v-model:value="isPublished" size="small" :checked-text="'Опубликовано'" :unchecked-text="'Черновик'" />
            </div>
          </div>
        </n-card>
      </div>

      <!-- SEO -->
      <div v-if="activeTab === 'seo'" class="space-y-6">
        <n-card title="SEO" size="small">
          <n-tabs type="line" animated v-model:value="currentLanguage">
            <n-tab-pane
              v-for="lang in activeLanguages"
              :key="`${lang.code}-seo`"
              :name="lang.code"
            >
              <template #tab>
                <div class="tab-label">
                  <img v-if="lang.flag" :src="`/storage/${lang.flag}`" :alt="lang.name" class="language-flag" />
                  <span class="language-name">{{ lang.native_name || lang.name || lang.code }}</span>
                </div>
              </template>

              <div class="space-y-6">
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Meta Title</label>
                  <n-input v-model:value="ensureSeo(lang.code).meta_title" placeholder="Заголовок для поисковых систем" />
                </div>
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Meta Description</label>
                  <n-input
                    v-model:value="ensureSeo(lang.code).meta_description"
                    type="textarea"
                    :rows="3"
                    placeholder="Описание для поисковых систем"
                  />
                </div>
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Meta Keywords</label>
                  <n-input v-model:value="ensureSeo(lang.code).meta_keywords" placeholder="Ключевые слова через запятую" />
                </div>
              </div>
            </n-tab-pane>
          </n-tabs>
        </n-card>
      </div>

      <!-- PARTICIPANTS -->
      <div v-if="activeTab === 'participants' && isEdit">
        <n-card title="Участники" size="small">
          <div v-if="Array.isArray(localEvent.participants) && localEvent.participants.length">
            <n-data-table :columns="participantColumns" :data="localEvent.participants" :pagination="pagination" />
          </div>
          <div v-else class="text-center py-12">
            <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">Пока нет участников</p>
          </div>
        </n-card>
      </div>
    </div>

    <!-- Footer -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 shadow-lg z-20">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3 ml-8">
          <div v-if="isEdit" class="text-sm text-gray-500">
            <span>Создано: {{ formatDate(localEvent.created_at) }}</span>
            <span class="ml-4">Обновлено: {{ formatDate(localEvent.updated_at) }}</span>
          </div>
        </div>
        <div class="flex gap-3">
          <n-button @click="onSave" type="primary" size="small" :loading="loading">
            <template #icon><i class="fas fa-save"></i></template>
            Сохранить
          </n-button>
          <n-button v-if="!isEdit || localEvent.status === 'draft'" type="success" size="small" @click="onPublish">
            <template #icon><i class="fas fa-eye"></i></template>
            Опубликовать
          </n-button>
          <n-button v-if="isEdit" type="error" size="small" ghost @click="onDelete">
            <template #icon><i class="fas fa-trash"></i></template>
            Удалить
          </n-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  NCard, NButton, NInput, NSelect, NTag, NAlert, NDatePicker,
  NSwitch, NInputNumber, NDataTable, NTabs, NTabPane
} from 'naive-ui';
import Swal from 'sweetalert2';

export default {
  name: 'EventForm',
  components: {
    NCard, NButton, NInput, NSelect, NTag, NAlert, NDatePicker,
    NSwitch, NInputNumber, NDataTable, NTabs, NTabPane
  },
  props: {
    id: { type: [String, Number], default: null }
  },
  data() {
    return {
      activeTab: 'basic',
      currentLanguage: 'ru',
      loading: false,
      pagination: { pageSize: 10 },
      // локальные данные формы
      localEvent: this.getDefaultEvent(),
      translationsByCode: {}, // { 'ru': {...}, 'en': {...} }
      seoByCode: {},          // { 'ru': {...}, 'en': {...} }
      participantColumns: [
        { title: 'Имя', key: 'first_name', render: (row) => `${row.first_name || ''} ${row.last_name || ''}` },
        { title: 'Email', key: 'email' },
        { title: 'Телефон', key: 'phone' },
        { title: 'Статус', key: 'status' },
        { title: 'Кол-во', key: 'participants_count' },
        { title: 'Дата регистрации', key: 'created_at', render: (row) => this.formatDate(row.created_at) }
      ]
    }
  },
  computed: {
    event() {
      return this.$store.getters["events/editEvent"] || {};
    },
    availableLanguages() {
      const languages = this.$store.getters['settings/availableLanguages'] || {};
      return Object.values(languages).filter(lang => lang.enabled);
    },
    languagesV2() {
      const arr = this.$store.getters['settings/languagesV2'] || [];
      return Array.isArray(arr) ? arr : Object.values(arr || {});
    },
    activeLanguages() {
      return this.languagesV2.filter(l => !!(+l.enabled));
    },
    routeId() {
      return this.$route.params.id || this.id || null;
    },
    isEdit() {
      return !!(this.event && this.event.id);
    },
    tabs() {
      const base = [
        { name: 'basic', label: 'Основная информация', icon: 'fas fa-info-circle' },
        { name: 'settings', label: 'Настройки', icon: 'fas fa-cog' },
        { name: 'seo', label: 'SEO', icon: 'fas fa-search' }
      ];
      if (this.isEdit) base.push({ name: 'participants', label: 'Участники', icon: 'fas fa-users' });
      return base;
    },
    isPublished: {
      get() { return this.localEvent.status === 'published' },
      set(v) { this.localEvent.status = v ? 'published' : 'draft' }
    },
    // date pickers <-> string
    startTimeTs: {
      get() { return this.localEvent.start_time ? new Date(this.localEvent.start_time).getTime() : null },
      set(v) { this.localEvent.start_time = v ? this.toApiDate(v) : null }
    },
    endTimeTs: {
      get() { return this.localEvent.end_time ? new Date(this.localEvent.end_time).getTime() : null },
      set(v) { this.localEvent.end_time = v ? this.toApiDate(v) : null }
    },
    deadlineTs: {
      get() { return this.localEvent.registration_deadline ? new Date(this.localEvent.registration_deadline).getTime() : null },
      set(v) { this.localEvent.registration_deadline = v ? this.toApiDate(v) : null }
    }
  },

  async mounted() {
    if (this.$store.state.settings.lists.length === 0) {
      await this.$store.dispatch('settings/lists');
    }

    if (this.routeId) {
      await this.$store.dispatch("events/getById", { id: this.routeId });
    } else {
      this.initializeNewEvent();
    }
  },

  watch: {
    event: {
      handler(newVal) {
        if (!newVal || !Object.keys(newVal).length) return;
        this.hydrateFromStore(newVal);

        if (newVal.current_lang) this.currentLanguage = newVal.current_lang;
        else {
          const def = this.activeLanguages.find(this.isLangDefault);
          if (def) this.currentLanguage = def.code;
        }
      },
      immediate: true
    },
    activeLanguages: {
      handler(newVal) {
        if (!this.routeId && newVal.length > 0) {
          this.initializeNewEvent();
        }
      },
      immediate: true
    }
  },

  methods: {
    // Инициализация нового события
    initializeNewEvent() {
      if (this.routeId) return;

      this.localEvent = this.getDefaultEvent();
      this.translationsByCode = {};
      this.seoByCode = {};
      
      this.availableLanguages.forEach(lang => {
        this.ensureTrans(lang.code);
        this.ensureSeo(lang.code);
      });
      
      // Устанавливаем язык по умолчанию
      const defaultLang = this.availableLanguages.find(l => l.default);
      if (defaultLang) {
        this.currentLanguage = defaultLang.code;
      } else if (this.availableLanguages.length > 0) {
        this.currentLanguage = this.availableLanguages[0].code;
      }
    },

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
        confirmed_participants_count: 0,
        created_at: null,
        updated_at: null
      }
    },
    formatDate(s) {
      if (!s) return '—';
      return new Date(s).toLocaleString('ru-RU', { year:'numeric', month:'short', day:'2-digit', hour:'2-digit', minute:'2-digit' });
    },
    toApiDate(ts) {
      const d = new Date(ts);
      // YYYY-MM-DD HH:mm:ss (без Z, как обычно ждут бекенды)
      const pad = n => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    },
    previewSrc(path) {
      // позволяет показывать и абсолютные URL, и относительные пути из storage
      if (!path) return '';
      if (/^https?:\/\//i.test(path)) return path;
      return `/storage/${path}`;
    },
    isLangDefault(l) {
      const raw = (l?.is_default ?? l?.default ?? 0);
      return Number(raw) === 1 || raw === true;
    },
    hydrateFromStore(storeEvent) {
      this.localEvent = { ...this.getDefaultEvent(), ...storeEvent };

      this.translationsByCode = {};
      const hasLangId = !!this.activeLanguages.find(l => typeof l.id !== 'undefined');
      const langById = id => this.activeLanguages.find(l => l.id === id);
      const langCodeSafe = it => {
        if (it.language) return it.language; // уже код
        if (hasLangId && it.language_id && langById(it.language_id)) return langById(it.language_id).code;
        return null;
      };

      if (Array.isArray(storeEvent.translations)) {
        storeEvent.translations.forEach(t => {
          const code = langCodeSafe(t);
          if (!code) return;
          this.translationsByCode[code] = {
            language: code,
            title: t.title || '',
            short_description: t.short_description || '',
            full_description: t.full_description || '',
            location: t.location || '',
            requirements: t.requirements || '',
            included: t.included || ''
          };
        });
      }
      this.activeLanguages.forEach(l => this.ensureTrans(l.code));

      this.seoByCode = {};
      if (Array.isArray(storeEvent.seo)) {
        storeEvent.seo.forEach(s => {
          const code = langCodeSafe(s);
          if (!code) return;
          this.seoByCode[code] = {
            language: code,
            meta_title: s.meta_title || '',
            meta_description: s.meta_description || '',
            meta_keywords: s.meta_keywords || ''
          };
        });
      }
      this.activeLanguages.forEach(l => this.ensureSeo(l.code));
    },
    ensureTrans(code) {
      if (!this.translationsByCode[code]) {
        this.translationsByCode[code] = {
          language: code,
          title: '',
          short_description: '',
          full_description: '',
          location: '',
          requirements: '',
          included: ''
        };
      }
      return this.translationsByCode[code];
    },
    ensureSeo(code) {
      if (!this.seoByCode[code]) {
        this.seoByCode[code] = {
          language: code,
          meta_title: '',
          meta_description: '',
          meta_keywords: ''
        };
      }
      return this.seoByCode[code];
    },
    buildPayload() {
      const translations = this.activeLanguages.map(l => {
        const tr = this.ensureTrans(l.code);
        const { language, ...rest } = tr;
        return (typeof l.id !== 'undefined')
          ? { language_id: l.id, ...rest }
          : { ...rest };
      });

      const seo = this.activeLanguages.map(l => {
        const s = this.ensureSeo(l.code);
        const { language, ...rest } = s;
        return (typeof l.id !== 'undefined')
          ? { language_id: l.id, ...rest }
          : { ...rest };
      });

      return {
        slug: this.localEvent.slug || '',
        status: this.localEvent.status || 'draft',
        image: this.localEvent.image || '',
        max_participants: Number(this.localEvent.max_participants) || 0,
        price: Number(this.localEvent.price) || 0,
        start_time: this.localEvent.start_time,
        end_time: this.localEvent.end_time,
        registration_deadline: this.localEvent.registration_deadline,
        translations,
        seo
      };
    },
    onSave() {
      const data = this.buildPayload();
      const id = this.event?.id || this.localEvent?.id;

      console.log('Saving event payload:', JSON.stringify(data, null, 2));

      if (this.isEdit) {
        this.$store.dispatch('events/update', { id, data })
          .then(() => {
            Swal.fire({
              icon: 'success',
              title: 'Успех!',
              text: 'Мероприятие успешно обновлено.',
              timer: 2000,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast success' }
            });
          })
          .catch((error) => {
            console.error('Ошибка при обновлении мероприятия:', error);
            Swal.fire({
              icon: 'error',
              title: 'Ошибка!',
              text: error?.response?.data?.message || 'Не удалось обновить мероприятие.',
              timer: 2500,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast error' }
            });
          });
      } else {
        this.$store.dispatch('events/store', { data })
          .then((resp) => {
            Swal.fire({
              icon: 'success',
              title: 'Успех!',
              text: 'Мероприятие успешно создано.',
              timer: 2000,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast success' }
            });
            
            const newId = resp?.data?.event.id || null;
            if (newId) {
              this.$router.replace({ name: 'admin.events.edit', params: { id: newId } });
            }
          })
          .catch((error) => {
            console.error('Ошибка при создании мероприятия:', error);
            Swal.fire({
              icon: 'error',
              title: 'Ошибка!',
              text: error?.response?.data?.message || 'Не удалось создать мероприятие.',
              timer: 2500,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast error' }
            });
          });
      }
    },
    onPublish() {
      this.localEvent.status = 'published';
      this.onSave();
    },
    onDelete() {
      if (!this.isEdit) {
        this.$message?.warning?.('Удалять нечего: это новый черновик.');
        return;
      }
      console.log('EVENT DELETE INTENT →', { id: this.localEvent.id });
      this.$message?.success?.('Запрос на удаление сформирован (смотри консоль).');
    }
  }
}
</script>

<style scoped>
.translation-tabs :deep(.n-tabs-nav) {
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  margin-bottom: 12px;
}
.tab-label {
  display: flex; align-items: center; gap: 8px; font-size: 0.9rem;
}
.language-flag { width: 20px; height: 20px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(0,0,0,0.05); }
</style>
