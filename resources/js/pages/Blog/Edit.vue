<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-6">
          <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-gray-900">#{{ post.id }} - Редактирование</h1>
            <n-tag :type="post.status === 'published' ? 'success' : 'warning'" size="small">
              {{ post.status === 'published' ? 'Опубликовано' : 'Черновик' }}
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

        <!-- Horizontal Tabs like PrestaShop -->
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
              <n-tag v-if="tab.badge" size="tiny" :type="activeTab === tab.name ? 'primary' : 'default'">
                {{ tab.badge }}
              </n-tag>
            </button>
          </nav>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      
      <!-- Content Tab -->
      <div v-if="activeTab === 'content'" class="space-y-6">
        <n-card title="Основная информация" size="small">
          <div class="space-y-6">
            <!-- Title with language selector -->
            <div class="border rounded-lg p-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Заголовок *</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                v-model:value="currentTranslation.title" 
                placeholder="Введите заголовок"
                size="large"
                class="w-full"
              />
            </div>

            <!-- Description with language selector -->
            <div class="border rounded-lg p-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Описание</label>
                <n-select 
                  v-model:value="currentLanguage" 
                  :options="languageOptions" 
                  size="small" 
                  style="width: 150px"
                />
              </div>
              <n-input 
                v-model:value="currentTranslation.description" 
                type="textarea"
                :rows="4"
                placeholder="Введите описание"
                class="w-full"
              />
            </div>

            <!-- Slug and Date -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Slug (ЧПУ)</label>
                <n-input 
                  v-model:value="post.slug" 
                  placeholder="URL-адрес"
                  class="w-full"
                />
              </div>

              <div class="border rounded-lg p-4">
                <label class="text-sm font-medium text-gray-700 mb-3 block">Дата публикации</label>
                <n-date-picker 
                  v-model:value="publishedAtTimestamp" 
                  type="datetime"
                  clearable
                  class="w-full"
                />
              </div>
            </div>
          </div>
        </n-card>

        <!-- Statistics -->
        <n-card title="Статистика" size="small">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 bg-blue-50 rounded-lg">
              <div class="text-2xl font-bold text-blue-600">{{ post.views }}</div>
              <div class="text-sm text-blue-500">Просмотры</div>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
              <div class="text-2xl font-bold text-green-600">{{ post.comments_count }}</div>
              <div class="text-sm text-green-500">Комментарии</div>
            </div>
            <div class="p-4 bg-purple-50 rounded-lg">
              <div class="text-2xl font-bold text-purple-600">{{ post.averageRating }}</div>
              <div class="text-sm text-purple-500">Рейтинг</div>
            </div>
            <div class="p-4 bg-orange-50 rounded-lg">
              <div class="text-2xl font-bold text-orange-600">0</div>
              <div class="text-sm text-orange-500">Лайки</div>
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
            <div class="border rounded-lg p-4">
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
                v-model:value="currentSeo.meta_title" 
                placeholder="Заголовок для поисковых систем"
                class="w-full"
              />
            </div>

            <!-- Meta Description -->
            <div class="border rounded-lg p-4">
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
                v-model:value="currentSeo.meta_description" 
                type="textarea"
                :rows="3"
                placeholder="Описание для поисковых систем"
                class="w-full"
              />
            </div>

            <!-- Meta Keywords -->
            <div class="border rounded-lg p-4">
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
                v-model:value="currentSeo.meta_keywords" 
                placeholder="Ключевые слова через запятую"
                class="w-full"
              />
            </div>
          </div>
        </n-card>
      </div>

      <!-- Gallery Tab -->
      <div v-if="activeTab === 'gallery'">
        <n-card title="Галерея изображений" size="small">
          <div class="text-center py-12">
            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg mb-4">Функционал галереи в разработке</p>
            <n-button type="primary" ghost size="large">
              <template #icon>
                <i class="fas fa-plus"></i>
              </template>
              Добавить изображения
            </n-button>
          </div>
        </n-card>
      </div>

      <!-- Comments Tab -->
      <div v-if="activeTab === 'comments'">
        <n-card title="Комментарии" size="small">
          <n-list>
            <n-list-item v-for="comment in post.comments" :key="comment.id" class="py-4">
              <template #prefix>
                <n-avatar round size="medium" class="bg-blue-100">
                  {{ comment.name.charAt(0) }}
                </n-avatar>
              </template>
              
              <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-3">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                  <strong class="text-sm">{{ comment.name }}</strong>
                  <n-rate readonly :value="comment.rating" size="small" />
                  <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">
                    Рейтинг: {{ comment.rating }}/5
                  </span>
                </div>
                <span class="text-xs text-gray-500 whitespace-nowrap">
                  {{ formatDate(comment.created_at) }}
                </span>
              </div>
              
              <p class="text-sm text-gray-700 break-words bg-gray-50 p-3 rounded-lg">
                {{ comment.content }}
              </p>
              
              <template #suffix>
                <div class="flex gap-2 items-center">
                  <n-tag 
                    v-if="comment.approved" 
                    type="success" 
                    size="small"
                  >
                    Одобрен
                  </n-tag>
                  <n-button 
                    v-if="!comment.approved" 
                    size="small" 
                    type="success" 
                    ghost 
                    @click="approveComment(comment.id)"
                  >
                    <template #icon>
                      <i class="fas fa-check"></i>
                    </template>
                    Одобрить
                  </n-button>
                  <n-button 
                    size="small" 
                    type="error" 
                    ghost 
                    @click="deleteComment(comment.id)"
                  >
                    <template #icon>
                      <i class="fas fa-trash"></i>
                    </template>
                    Удалить
                  </n-button>
                </div>
              </template>
            </n-list-item>
          </n-list>
        </n-card>
      </div>
    </div>

    <!-- Footer with Status -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 shadow-lg">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3 ml-8">
          <div class="text-sm text-gray-500">
            <span>Создано: {{ formatDate(post.created_at) }}</span>
            <span class="ml-4">Обновлено: {{ formatDate(post.updated_at) }}</span>
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
          <n-button @click="savePost" type="primary" size="small">
            <template #icon>
              <i class="fas fa-save"></i>
            </template>
            Сохранить
          </n-button>
          
          <n-button v-if="post.status === 'draft'" type="success" size="small" @click="publishPost">
            <template #icon>
              <i class="fas fa-eye"></i>
            </template>
            Опубликовать
          </n-button>
          
          <n-button type="error" size="small" ghost @click="deletePost">
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
  NList,
  NListItem,
  NAvatar,
  NRate,
  NDatePicker,
  NSwitch
} from 'naive-ui'

export default {
  name: 'Edit',
  components: {
    NCard,
    NButton,
    NInput,
    NSelect,
    NTag,
    NAlert,
    NList,
    NListItem,
    NAvatar,
    NRate,
    NDatePicker,
    NSwitch
  },
  data() {
    return {
      activeTab: 'content',
      currentLanguage: 'ru' 
    }
  },
  computed: {
      post() {
          return this.$store.getters['posts/editPost'] || {
              id: null,
              title: '',
              description: '',
              slug: '',
              status: 'draft',
              image: null,
              views: 0,
              comments_count: 0,
              averageRating: 0,
              published_at: null,
              user_id: null,
              gallery: [],
              comments: [],
              translations: [],
              seo: []
          };
      },
      tabs() {
          const commentsCount = this.post.comments_count ? this.post.comments_count : 0;
          return [
              { name: 'content', label: 'Контент', icon: 'fas fa-file-alt' },
              { name: 'seo', label: 'SEO', icon: 'fas fa-search' },
              { name: 'gallery', label: 'Галерея', icon: 'fas fa-images' },
              { name: 'comments', label: 'Комментарии', icon: 'fas fa-comments', badge: commentsCount }
          ];
      },
      availableLanguages() {
          const languages = this.$store.getters['settings/availableLanguages'] || {};
          return Object.values(languages).filter(lang => lang.enabled);
      },
      languageOptions() {
          return this.availableLanguages.map(lang => ({ 
              label: lang.native_name, 
              value: lang.code 
          }));
      },
      currentTranslation() {
          if (!this.post.translations) return {};
          const translation = this.post.translations.find(t => t.language === this.currentLanguage);
          if (!translation) {
              return {
                  language: this.currentLanguage,
                  title: '',
                  description: '',
              };
          }
          return translation;
      },
      currentSeo() {
          if (!this.post.seo) return {};
          const seo = this.post.seo.find(s => s.language === this.currentLanguage);
          if (!seo) {
              return {
                  language: this.currentLanguage,
                  meta_title: '',
                  meta_description: '',
                  meta_keywords: ''
              };
          }
          return seo;
      },
      currentLanguageName() {
          const lang = this.availableLanguages.find(l => l.code === this.currentLanguage);
          return lang ? lang.native_name : this.currentLanguage;
      },
      isPublished: {
          get() {
              return this.post.status === 'published';
          },
          set(value) {
              this.post.status = value ? 'published' : 'draft';
          }
      },
      publishedAtTimestamp: {
          get() {
              if (!this.post.published_at) return null;
              return new Date(this.post.published_at).getTime();
          },
          set(value) {
              if (!value) return;
              const date = new Date(value);
              const formattedDate = date.toISOString().replace('T', ' ').substring(0, 19);
              this.post.published_at = formattedDate;
          }
      }
  },
  methods: {
      formatDate(dateString) {
          if (!dateString) return 'Не указано';
          return new Date(dateString).toLocaleDateString('ru-RU', {
              year: 'numeric',
              month: 'short',
              day: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
          });
      },
      updateStatus(value) {
          this.post.status = value ? 'published' : 'draft';
      },
      async savePost() {
          try {
              console.log('Сохранение поста:', this.post);
              await new Promise(resolve => setTimeout(resolve, 1000));
              
              if (window.$message) {
                  window.$message.success('Пост успешно сохранен');
              }
          } catch (error) {
              console.error('Ошибка сохранения:', error);
              if (window.$message) {
                  window.$message.error('Ошибка при сохранении поста');
              }
          }
      },
      publishPost() {
          this.post.status = 'published';
          this.savePost();
      },
      deletePost() {
          console.log('Удаление поста:', this.post.id);
          if (window.$message) {
              window.$message.info('Функция удаления в разработке');
          }
      },
      approveComment(commentId) {
          console.log('Одобрение комментария:', commentId);
          if (window.$message) {
              window.$message.success('Комментарий одобрен');
          }
      },
      deleteComment(commentId) {
          console.log('Удаление комментария:', commentId);
          if (window.$message) {
              window.$message.success('Комментарий удален');
          }
      }
  },
  watch: {
    // Следим за загрузкой поста и устанавливаем язык из API
    post: {
      handler(newPost) {
        if (newPost.current_lang && this.currentLanguage !== newPost.current_lang) {
          this.currentLanguage = newPost.current_lang;
        }
      },
      immediate: true,
      deep: true
    }
  },
  mounted() {
      this.$store.dispatch('posts/show', this.$route.params.id).then(response => {
          console.log('Ответ от API:', response);
          console.log('Доступные языки:', this.availableLanguages);
          
          // Устанавливаем язык из API, если он пришел
          if (response.data && response.data.current_lang) {
              this.currentLanguage = response.data.current_lang;
          } else {
              // Иначе используем язык по умолчанию из доступных языков
              const defaultLang = this.availableLanguages.find(lang => lang.default);
              if (defaultLang) {
                  this.currentLanguage = defaultLang.code;
              }
          }
      }).catch(error => {
          console.error('Error fetching post:', error);
      });
  }
};
</script>

<style scoped>
/* Стили для горизонтальных табов как в PrestaShop */
nav::-webkit-scrollbar {
  height: 4px;
}

nav::-webkit-scrollbar-track {
  background: #f1f1f1;
}

nav::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Улучшенные стили для мобильных */
@media (max-width: 640px) {
  .flex-col-mobile {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .gap-mobile {
    gap: 0.5rem;
  }
}

/* Анимации для плавных переходов */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Улучшенное отображение границ */
.border-rounded {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
}

.border-rounded:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>