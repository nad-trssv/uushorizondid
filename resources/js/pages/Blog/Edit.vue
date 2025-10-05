<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-6">
          <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">
              {{ isEdit ? `#${routeId} — Редактирование` : 'Создание новости' }}
            </h1>
            <n-tag v-if="isEdit" :type="localPost.status === 'published' ? 'success' : 'warning'" size="small">
              {{ localPost.status === 'published' ? 'Опубликовано' : 'Черновик' }}
            </n-tag>
          </div>
          <div class="flex flex-wrap gap-3">
            <n-button secondary @click="$router.back()">
              <template #icon><i class="fas fa-arrow-left"></i></template>
              Назад
            </n-button>
            <n-button v-if="isEdit" type="primary" @click="openPage('create')">
              <template #icon><i class="fas fa-plus"></i></template>
              Создать новую
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
          <n-tabs type="line" animated class="translation-tabs" v-model:value="currentLanguage">
            <n-tab-pane
              v-for="(lang, i) in activeLanguages"
              :key="`${lang.code}-basic-${i}`"
              :name="lang.code"
            >
              <template #tab>
                <div
                  class="tab-label"
                  :style="lang.code === 'et' && hasError(`translations.${i}.title`) && isLangDefault(lang) && !ensureTrans(lang.code).title ? { color: 'red' } : {}"
                >
                  <img v-if="lang.flag" :src="`/storage/${lang.flag}`" :alt="lang.name" class="language-flag" />
                  <span class="language-name">{{ lang.native_name || lang.name || lang.code }}</span>
                </div>
              </template>

              <div class="space-y-6">
                <!-- Title -->
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">
                    Заголовок
                    <span v-if="isLangDefault(lang)" class="text-red-500">*</span>
                  </label>
                  <n-input
                    v-model:value="ensureTrans(lang.code).title"
                    placeholder="Введите заголовок новости"
                    size="large"
                    :status="hasError(`translations.${i}.title`) && isLangDefault(lang) && !ensureTrans(lang.code).title ? 'error' : undefined"
                  />
                  <p v-if="hasError(`translations.${i}.title`) && isLangDefault(lang) && !ensureTrans(lang.code).title" class="text-xs text-red-500 mt-1">
                    {{ firstError(`translations.${i}.title`) }}
                  </p>
                </div>
                <!-- Short Description -->
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Краткое описание</label>
                  <n-input
                    v-model:value="ensureTrans(lang.code).short_description"
                    type="textarea"
                    :rows="3"
                    placeholder="Введите краткое описание новости"
                  />
                </div>

                <!-- Description -->
                <div class="border rounded-lg p-4">
                  <label class="text-sm font-medium text-gray-700 mb-2 block">Текст новости</label>
                  <RichEditor
                    v-model="ensureTrans(lang.code).description"
                    :placeholder="'Введите текст новости...'"
                    :height="400"
                  />
                </div>
              </div>
            </n-tab-pane>
          </n-tabs>
        </n-card>
      </div>

      <!-- SETTINGS -->
      <div v-if="activeTab === 'settings'" class="space-y-6">
        <n-card title="Настройки публикации" size="small">
          <div class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Slug (ЧПУ) *</label>
                <n-input
                  v-model:value="localPost.slug"
                  placeholder="URL-адрес, например: news-my-article"
                  :status="hasError('slug') ? 'error' : undefined"
                />
                <p v-if="hasError('slug')" class="text-xs text-red-500 mt-1">
                  {{ firstError('slug') }}
                </p>
              </div>

              <!-- Главное изображение -->
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Главное изображение</label>

                <div class="flex items-start gap-4 flex-wrap">
                  <!-- Превью -->
                  <div class="w-32">
                    <img
                      :src="previewSrc(localPost.image) || '/storage/placeholders/600x400.svg'"
                      alt="Post Image"
                      class="w-32 h-32 object-cover rounded border"
                    />
                    <div class="mt-2 flex gap-2">
                      <n-button
                        v-if="localPost.image"
                        size="tiny"
                        type="error"
                        ghost
                        @click="clearImage"
                      >
                        <template #icon><i class="fas fa-trash"></i></template>
                        Удалить
                      </n-button>
                    </div>
                  </div>

                  <!-- Загрузка файла -->
                  <div class="flex-1 min-w-[260px]">
                    <n-upload
                      :custom-request="uploadMainImage"
                      :show-file-list="false"
                      :max="1"
                      accept="image/*"
                      :disabled="uploadingImage"
                    >
                      <n-button :loading="uploadingImage">
                        <template #icon><i class="fas fa-upload"></i></template>
                        Загрузить файл
                      </n-button>
                    </n-upload>

                    <!-- Вставка URL -->
                    <div class="mt-3">
                      <label class="text-xs text-gray-500 block mb-1">или вставьте URL</label>
                      <n-input
                        v-model:value="localPost.image"
                        placeholder="https://... или относительный путь в storage"
                        clearable
                        @change="onImageUrlChange"
                      />
                      <p class="text-xs text-gray-400 mt-1">
                        После загрузки файла сюда автоматически подставится путь (например: <code>posts/abc123.jpg</code>).
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Published At & Status -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-2 block">Дата и время публикации</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <n-config-provider :date-locale="dateFnsLocale">
                    <n-date-picker
                      v-model:value="publishedDateTs"
                      type="date"
                      clearable
                      class="w-full"
                      :first-day-of-week="1"
                      :status="hasError('published_at') ? 'error' : undefined"
                    />
                  </n-config-provider>

                  <n-time-picker
                    v-model:value="publishedTimeMs"
                    format="HH:mm"
                    :actions="['now', 'confirm']"
                    :clearable="true"
                    class="w-full"
                    :status="hasError('published_at') ? 'error' : undefined"
                  />

                  <p class="text-xs text-gray-500 mt-2">
                    <code>{{ localPost.published_at || '—' }}</code>
                  </p>
                </div>
              </div>

              <div class="border rounded-lg p-4 flex items-center gap-3">
                <span class="text-sm font-medium text-gray-700">Статус:</span>
                <n-switch v-model:value="isPublished" size="small" :checked-text="'Опубликовано'" :unchecked-text="'Черновик'" />
              </div>
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

      <!-- COMMENTS -->
      <div v-if="activeTab === 'comments' && isEdit">
        <n-card :title="`Комментарии (${localPost.comments?.length || 0})`" size="small">
          <div v-if="Array.isArray(localPost.comments) && localPost.comments.length">
            <n-data-table :columns="commentColumns" :data="localPost.comments" :pagination="pagination" />
          </div>
          <div v-else class="text-center py-12">
            <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">Комментариев пока нет</p>
          </div>
        </n-card>
      </div>
    </div>

    <!-- Footer -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 shadow-lg z-20">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3 ml-8">
          <div v-if="isEdit" class="text-sm text-gray-500">
            <span>Создано: {{ formatDate(localPost.created_at) }}</span>
            <span class="ml-4">Обновлено: {{ formatDate(localPost.updated_at) }}</span>
          </div>
        </div>
        <div class="flex gap-3">
          <n-button @click="onSave" type="primary" size="small" :loading="loading">
            <template #icon><i class="fas fa-save"></i></template>
            Сохранить
          </n-button>
          <n-button v-if="!isEdit || localPost.status === 'draft'" type="success" size="small" @click="onPublish">
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
import RichEditor from '@/components/elements/RichEditor.vue';
import {
  NCard, NButton, NInput, NTag, NDatePicker,
  NSwitch, NDataTable, NTabs, NTabPane, NUpload,
  NTimePicker, NConfigProvider
} from 'naive-ui';
import Swal from 'sweetalert2';
import { ru, enUS, et, uk, lv } from 'date-fns/locale';

export default {
  name: 'PostForm',
  components: {
    NCard, NButton, NInput, NTag, NDatePicker,
    NSwitch, NDataTable, NTabs, NTabPane, NUpload,
    NTimePicker, NConfigProvider, RichEditor
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

      localPost: this.getDefaultPost(),
      translationsByCode: {}, // { 'ru': {...}, 'en': {...} }
      seoByCode: {},          // { 'ru': {...}, 'en': {...} }

      commentColumns: [
        { title: 'Имя', key: 'name' },
        { title: 'Рейтинг', key: 'rating' },
        { title: 'Комментарий', key: 'content' },
        { title: 'Одобрен', key: 'approved', render: (row) => row.approved ? 'Да' : 'Нет' },
        { title: 'Дата', key: 'created_at', render: (row) => this.formatDate(row.created_at) }
      ],

      uploadingImage: false,
      publishedDateTs: null,
      publishedTimeMs: null,

      errorsValidation: {}
    }
  },
  computed: {
    post() {
      return this.$store.getters["posts/editPost"] || {};
    },
    currentLangIsoCode() {
      return this.$store.getters['settings/currentLocale']?.iso || 'ru-RU';
    },
    dateFnsLocale() {
      const iso = this.currentLangIsoCode || 'ru-RU';
      if (iso.startsWith('en')) return enUS;
      if (iso.startsWith('et')) return et;
      if (iso.startsWith('uk')) return uk;
      if (iso.startsWith('lv')) return lv;
      return ru;
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
      return !!(this.post && this.post.id);
    },
    tabs() {
      const base = [
        { name: 'basic', label: 'Основная информация', icon: 'fas fa-info-circle' },
        { name: 'settings', label: 'Настройки', icon: 'fas fa-cog' },
        { name: 'seo', label: 'SEO', icon: 'fas fa-search' }
      ];
      if (this.isEdit) base.push({ name: 'comments', label: 'Комментарии', icon: 'fas fa-comments' });
      return base;
    },
    isPublished: {
      get() { return this.localPost.status === 'published' },
      set(v) { this.localPost.status = v ? 'published' : 'draft' }
    }
  },

  async mounted() {
    if (this.$store.state.settings.lists.length === 0) {
      await this.$store.dispatch('settings/lists');
    }

    if (this.routeId) {
      await this.$store.dispatch("posts/show", this.routeId);
    } else {
      this.initializeNewPost();
    }
  },

  watch: {
    post: {
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
          this.initializeNewPost();
        }
      },
      immediate: true
    },
    publishedDateTs() { this.syncPublishedAtToLocalPost(); },
    publishedTimeMs() { this.syncPublishedAtToLocalPost(); },
    $route: {
      immediate: true,
      handler(to, from) {
        if (to.name === 'admin.blog.create') {
          this.resetForm();
        }
      }
    },
  },

  methods: {
    resetForm() {
      this.localPost = this.getDefaultPost();
      this.translationsByCode = {};
      this.seoByCode = {};
      this.errorsValidation = {};
      this.publishedDateTs = null;
      this.publishedTimeMs = null;
      this.activeTab = 'basic';
      this.currentLanguage = 'ru';
      
      this.$store.commit('posts/resetEditPost');
      this.initializeNewPost();
    },
    initializeNewPost() {
      if (this.routeId) return;

      this.localPost = this.getDefaultPost();
      this.translationsByCode = {};
      this.seoByCode = {};

      this.availableLanguages.forEach(lang => {
        this.ensureTrans(lang.code);
        this.ensureSeo(lang.code);
      });

      const defaultLang = this.availableLanguages.find(l => l.default);
      if (defaultLang) this.currentLanguage = defaultLang.code;
      else if (this.availableLanguages.length > 0) this.currentLanguage = this.availableLanguages[0].code;
    },

    getDefaultPost() {
      return {
        id: null,
        slug: '',
        status: 'draft',
        image: '',
        published_at: null,
        views: 0,
        user_id: null,

        // hydrate-only fields
        created_at: null,
        updated_at: null,

        comments: []
      };
    },
    formatDate(s) {
      if (!s) return '—';
      return new Date(s).toLocaleString('ru-RU', { year:'numeric', month:'short', day:'2-digit', hour:'2-digit', minute:'2-digit' });
    },
    toApiDate(ts) {
      const d = new Date(ts);
      const pad = n => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    },
    previewSrc(path) {
      if (!path) return '';
      if (/^https?:\/\//i.test(path)) return path;
      return `/storage/${path}`;
    },
    isLangDefault(l) {
      const raw = (l?.is_default ?? l?.default ?? 0);
      return Number(raw) === 1 || raw === true;
    },
    hydrateFromStore(storePost) {
      this.localPost = { ...this.getDefaultPost(), ...storePost };

      // published_at → pickers
      if (this.localPost.published_at) {
        const d = new Date(this.localPost.published_at);
        this.publishedDateTs = new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime();
        this.publishedTimeMs = new Date(1970, 0, 1, d.getHours(), d.getMinutes(), 0).getTime();
      } else {
        this.publishedDateTs = null;
        this.publishedTimeMs = null;
      }

      // translations
      this.translationsByCode = {};
      const hasLangId = !!this.activeLanguages.find(l => typeof l.id !== 'undefined');
      const langById = id => this.activeLanguages.find(l => l.id === id);
      const langCodeSafe = it => {
        if (it.language) return it.language; // already code
        if (hasLangId && it.language_id && langById(it.language_id)) return langById(it.language_id).code;
        return null;
      };

      if (Array.isArray(storePost.translations)) {
        storePost.translations.forEach(t => {
          const code = langCodeSafe(t);
          if (!code) return;
          this.translationsByCode[code] = {
            language: code,
            title: t.title || '',
            description: t.description || '',
            short_description: t.short_description || ''
          };
        });
      }
      this.activeLanguages.forEach(l => this.ensureTrans(l.code));

      // SEO
      this.seoByCode = {};
      if (Array.isArray(storePost.seo)) {
        storePost.seo.forEach(s => {
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
          description: '',
          short_description: '',
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
        slug: this.localPost.slug || '',
        status: this.localPost.status || 'draft',
        image: this.localPost.image || '',
        published_at: this.localPost.published_at,
        translations,
        seo
      };
    },
    setErrors(errors) {
      this.errorsValidation = errors || {};
    },
    hasError(path) {
      if (!this.errorsValidation) return false;
      const v = this.errorsValidation[path];
      return Array.isArray(v) ? v.length > 0 : !!v;
    },
    firstError(path) {
      if (!this.errorsValidation) return '';
      const v = this.errorsValidation[path];
      if (Array.isArray(v)) return v[0] || '';
      return typeof v === 'string' ? v : '';
    },
    clearErrors() {
      this.errorsValidation = {};
    },
    onSave() {
      this.clearErrors();
      this.syncPublishedAtToLocalPost();
      const data = this.buildPayload();
      const id = this.post?.id || this.localPost?.id;
      this.loading = true;

      if (this.isEdit) {
        this.$store.dispatch('posts/update', { id, data })
          .then(() => {
            Swal.fire({
              icon: 'success',
              title: 'Успех!',
              text: 'Новость успешно обновлена.',
              timer: 2000,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast success' }
            });
          })
          .catch((error) => {
            this.setErrors(error?.response?.data?.errors);
            Swal.fire({
              icon: 'error',
              title: 'Ошибка!',
              text: error?.response?.data?.message || 'Не удалось обновить новость.',
              timer: 2500,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast error' }
            });
          })
          .finally(() => { this.loading = false; });
      } else {
        this.$store.dispatch('posts/store', { data })
          .then((resp) => {
            Swal.fire({
              icon: 'success',
              title: 'Успех!',
              text: 'Новость успешно создана.',
              timer: 2000,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast success' }
            });
            const newId = resp?.data?.post?.id || null;
            if (newId) {
              this.$router.replace({ name: 'admin.blog.edit', params: { id: newId } });
            }
          })
          .catch((error) => {
            this.setErrors(error?.response?.data?.errors);
            Swal.fire({
              icon: 'error',
              title: 'Ошибка!',
              text: error?.response?.data?.message || 'Не удалось создать новость.',
              timer: 2500,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              customClass: { popup: 'modern-toast error' }
            });
          })
          .finally(() => { this.loading = false; });
      }
    },

    onPublish() {
      this.localPost.status = 'published';
      if (!this.localPost.published_at) {
        // Если даты публикации нет — проставим "сейчас"
        const now = Date.now();
        this.publishedDateTs = new Date().setHours(0,0,0,0);
        this.publishedTimeMs = new Date(1970,0,1,new Date(now).getHours(), new Date(now).getMinutes(),0).getTime();
        this.syncPublishedAtToLocalPost();
      }
      this.onSave();
    },

    onDelete() {
      if (!this.isEdit) {
        this.$message?.warning?.('Удалять нечего: это новый черновик.');
        return;
      }
      Swal.fire({
        title: 'Вы уверены?',
        text: 'Это действие нельзя будет отменить!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена'
      }).then((result) => {
        if (result.isConfirmed) {
          this.$store.dispatch('posts/delete', this.localPost.id )
            .then(() => {
              Swal.fire({
                icon: 'success',
                title: 'Удалено!',
                text: 'Новость успешно удалена.',
                timer: 2000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                customClass: { popup: 'modern-toast success' }
              });
              this.$router.replace({ name: 'admin.blog.list' });
            })
            .catch((error) => {
              console.error('Ошибка при удалении новости:', error);
              Swal.fire({
                icon: 'error',
                title: 'Ошибка!',
                text: error?.response?.data?.message || 'Не удалось удалить новость.',
                timer: 2500,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                customClass: { popup: 'modern-toast error' }
              });
            });
        }
      });
      console.log('POST DELETE INTENT →', { id: this.localPost.id });
      this.$message?.success?.('Запрос на удаление сформирован (смотри консоль).');
    },
    async uploadMainImage({ file, onFinish, onError, onProgress }) {
      try {
        this.uploadingImage = true;

        const fd = new FormData();
        fd.append('file', file.file ?? file);

        const resp = await this.$store.dispatch('posts/uploadImage', {
          formData: fd,
          onProgress
        });
        const path = resp?.data?.path || '';
        if (!path) throw new Error('Не получен путь к файлу');

        this.localPost.image = path;
        onProgress?.({ percent: 100 });
        onFinish?.();
        this.$message?.success?.('Изображение загружено');
      } catch (e) {
        console.error('Upload error:', e);
        this.$message?.error?.('Не удалось загрузить изображение');
        onError?.();
      } finally {
        this.uploadingImage = false;
      }
    },
    clearImage() {
      this.localPost.image = '';
    },
    onImageUrlChange() {
      // опционально: валидация пути/URL
    },

    // PublishedAt pickers
    combineDateTime(dateTs, timeMs) {
      if (!dateTs) return null;
      const dDate = new Date(dateTs);
      let h = 0, m = 0, s = 0;
      if (timeMs) {
        const t = new Date(timeMs);
        h = t.getHours(); m = t.getMinutes(); s = 0;
      }
      const d = new Date(
        dDate.getFullYear(),
        dDate.getMonth(),
        dDate.getDate(),
        h, m, s, 0
      );
      const pad = (n) => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    },
    syncPublishedAtToLocalPost() {
      this.localPost.published_at = this.combineDateTime(this.publishedDateTs, this.publishedTimeMs);
    },
    openPage(type) {
      if (type === 'create') {
        this.$router.push({ name: 'admin.blog.create' });
      }
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
