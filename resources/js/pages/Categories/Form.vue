<template>
    <n-card
      :title="isEditMode ? $t('msg.label.category_edit') + ': ' + form.name : $t('msg.label.category_create')"
      :bordered="false"
      class="modern-form-card"
      size="small"
    >
      <!-- Хедер карточки -->
      <div class="card-header">
        <div class="status-control">
          <n-tag :bordered="false" :type="form.status ? 'success' : 'error'" size="small" round>
            {{ form.status ? 'Активна' : 'Неактивна' }}
          </n-tag>
          <n-switch
            v-model:value="form.status" 
            :round="true"
            :checked-value="true"
            :unchecked-value="false"
            size="medium"
          />
        </div>
      </div>
  
      <!-- Основная форма -->
      <n-form
        ref="formRef"
        :model="form"
        :rules="rules"
        label-placement="top"
        size="large"
        class="modern-form"
      >
        <!-- Секция названия -->
        <section class="form-section">
          <div class="section-header">
            <n-h3 class="section-title">
              <span class="status-indicator" :class="{'active': form.status, 'inactive': !form.status}"></span>
              {{ $t('msg.label.title') }}
            </n-h3>
            
            <n-button 
              v-if="isEditMode"
              type="error"
              tertiary
              size="small"
              @click="confirmDeleteCategory"
              class="delete-button"
            >
              <template #icon>
                <i class="fas fa-trash"></i>
              </template>
              Удалить категорию
            </n-button>
          </div>
          
          <n-alert v-if="showEnglishNameWarning" type="error" class="mb-4" closable>
            <template #icon>
              <i class="fas fa-exclamation-circle"></i>
            </template>
            Название на английском языке обязательно для заполнения
          </n-alert>
          
          <!-- Улучшенные табы с переводами -->
          <n-tabs type="line" animated class="translation-tabs">
            <n-tab-pane
              v-for="lang in availableLanguages"
              :key="`${lang.code}-name`"
              :name="lang.code"
            >
              <template #tab>
                <div class="tab-label">
                  <img 
                    :src="`/storage/${lang.flag}`" 
                    :alt="lang.name" 
                    class="language-flag"
                  />
                  <span class="language-name">{{ lang.native_name }}</span>
                  <i 
                    class="fas validation-icon"
                    :class="{
                      'fa-check-circle text-success': isTranslationFilled(lang.code, 'name'),
                      'fa-times-circle text-error': !isTranslationFilled(lang.code, 'name')
                    }"
                  ></i>
                </div>
              </template>
              <n-input
                v-model:value="getTranslation(lang.code).name"
                @update:value="() => resetFieldErrors(`translations.${lang.code}.name`)"
                :placeholder="`Введите название на ${lang.native_name}`"
                :status="validationErrors.translations?.[lang.code]?.name ? 'error' : undefined"
                class="modern-input"
                round
              />
            </n-tab-pane>
          </n-tabs>
        </section>
  
        <!-- Секция параметров -->
        <section class="form-section">
          <n-h3 class="section-title">
            <i class="fas fa-cog mr-2"></i>
            Основные параметры
          </n-h3>
          
          <n-grid :cols="12" :x-gap="24" :y-gap="16" responsive="screen" item-responsive>
            <!-- Родительская категория -->
            <n-form-item-gi :span="12" label="Категория" path="category_id" v-if="categoryOptions.length > 0">
              <n-cascader
                v-model:value="form.parent_id"
                :options="categoryOptions"
                placeholder="Выберите категорию"
                check-strategy="child"
                clearable
                filterable
                class="modern-select"
              />
            </n-form-item-gi>
          </n-grid>
        </section>
  
        <!-- Описание -->
        <section class="form-section">
          <n-h3 class="section-title">
            <i class="fas fa-align-left mr-2"></i>
            Описание
          </n-h3>
          
          <n-tabs type="line" animated class="translation-tabs">
            <n-tab-pane
              v-for="lang in availableLanguages"
              :key="`${lang.code}-description`"
              :name="lang.code"
            >
              <template #tab>
                <div class="tab-label">
                  <img 
                    :src="`/storage/${lang.flag}`" 
                    :alt="lang.name" 
                    class="language-flag"
                  />
                  <span class="language-name">{{ lang.native_name }}</span>
                  <i 
                    class="fas validation-icon"
                    :class="{
                      'fa-check-circle text-success': isTranslationFilled(lang.code, 'description'),
                      'fa-times-circle text-error': !isTranslationFilled(lang.code, 'description')
                    }"
                  ></i>
                </div>
              </template>
              <n-input
                v-model:value="getTranslation(lang.code).description"
                type="textarea"
                :placeholder="`Введите описание на ${lang.native_name}`"
                :autosize="{ minRows: 3, maxRows: 6 }"
                class="modern-textarea"
              />
            </n-tab-pane>
          </n-tabs>
        </section>
  
        <!-- Кнопки действий -->
        <section class="form-actions">
          <n-space justify="space-between" class="w-full">
            <n-button
              secondary
              @click="handleCancel"
              class="action-button cancel-button"
              size="large"
            >
              <template #icon>
                <i class="fas fa-times"></i>
              </template>
              Отмена
            </n-button>
            
            <n-button
              type="primary"
              :loading="loading"
              class="action-button submit-button"
              size="large"
              @click="saveData"
            >
              <template #icon>
                <i class="fas" :class="isEditMode ? 'fa-save' : 'fa-plus'"></i>
              </template>
              {{ isEditMode ? 'Сохранить изменения' : 'Создать категорию' }}
            </n-button>
          </n-space>
        </section>
      </n-form>
    </n-card>
  </template>
  
  <script>
  import { 
    NGrid,
    NFormItemGi,
    NCard,
    NForm,
    NButton,
    NSpace,
    NInput,
    NInputNumber,
    NSwitch,
    NCascader,
    NColorPicker,
    NH3,
    NTabs,
    NTabPane,
    NAlert,
    NFormItem,
    NTag
  } from 'naive-ui';
  import Swal from 'sweetalert2';
  
  export default {
    name: 'ModernCategoryForm',
    components: {
      NGrid,
      NFormItemGi,
      NCard,
      NForm,
      NButton,
      NSpace,
      NInput,
      NInputNumber,
      NSwitch,
      NCascader,
      NColorPicker,
      NH3,
      NTabs,
      NTabPane,
      NAlert,
      NFormItem,
      NTag
    },
    data() {
      return {
        formRef: null,
        loading: false,
        form: {
          parent_id: null,
          status: true,
          sort_order: 0,
          translations: [],
        },
        rules: {
          'translations.en.name': [
            { required: true, message: 'Название на английском языке обязательно', trigger: ['blur', 'change'] }
          ]
        },
        validationErrors: {},
        showEnglishNameWarning: false 
      };
    },
    computed: {
      isEditMode() {
        return this.$route.name === 'admin.categories.edit';
      },
      categoryId() {
        return this.$route.params.id;
      },
      availableLanguages() {
        const languages = this.$store.getters['settings/availableLanguages'] || {};
        return Object.values(languages).filter(lang => lang.enabled);
      },
      categoryOptions() {
        const options = [];
        const categories = this.$store.getters['categories/lists'] || [];
        
        categories.forEach(category => {
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
    },
    methods: {
      isTranslationFilled(langCode, field) {
        const translation = this.getTranslation(langCode);
        return translation && translation[field] && translation[field].trim() !== '';
      },
      getTranslation(langCode) {
        let translation = this.form.translations.find(t => t.locale === langCode);
        
        if (!translation) {
          translation = {
            locale: langCode,
            name: '',
            description: ''
          };
          this.form.translations.push(translation);
        }
        
        return translation;
      },
      loadCategoryData() {
        if (!this.isEditMode) return;
        
        this.loading = true;
        this.$store.dispatch('categories/getById', this.categoryId)
          .then(category => {
            if (category) {
              this.form = category.data.data || {};
              this.form.sort_order = this.form.sort_order !== null ? parseInt(this.form.sort_order) : 0;
              
              if (!this.form.translations) {
                this.form.translations = [];
              }
            }
          })
          .catch(error => {
            console.error('Error loading category:', error);
            this.showErrorNotification('Не удалось загрузить данные категории');
            this.$router.push({ name: 'admin.categories.list' });
          })
          .finally(() => {
            this.loading = false;
          });
      },
      handleCancel() {
        this.$router.push({ name: 'admin.categories.list' });
      },
      clearErrors() {
        this.validationErrors = {
          translations: {} 
        };
        this.showEnglishNameWarning = false;
      },
      loadCategories() {
        this.$store.dispatch('categories/lists');
      },
      handleValidationErrors(errors) {
        this.validationErrors = { translations: {} };
        
        for (const field in errors) {
          if (!field.includes('translations')) {
            this.validationErrors[field] = errors[field][0];
          }
        }
        
        for (const field in errors) {
          if (field.includes('translations')) {
            const parts = field.split('.');
            if (parts.length === 3) {
              const [_, locale, nestedField] = parts;
              if (!this.validationErrors.translations[locale]) {
                this.validationErrors.translations[locale] = {};
              }
              this.validationErrors.translations[locale][nestedField] = errors[field][0];
            }
          }
        }
        
        this.scrollToFirstError();
      },
      checkEngName() {
        const enTranslation = this.form.translations.find(t => t.locale === 'en');
        if (!enTranslation?.name?.trim()) {
          this.showEnglishNameWarning = true;
          if (!this.validationErrors.translations.en) {
            this.validationErrors.translations.en = {};
          }
          this.validationErrors.translations.en.name = 'Название на английском языке обязательно';
        }
      },
      resetFieldErrors(fieldPath) {
        const parts = fieldPath.split('.');
        
        if (parts.length === 1) {
          this.$set(this.validationErrors, fieldPath, null);
        } else if (parts.length === 3 && parts[0] === 'translations') {
          const [_, locale, fieldName] = parts;
          if (this.validationErrors.translations?.[locale]) {
            this.$set(this.validationErrors.translations[locale], fieldName, null);
            
            if (fieldName === 'name' && locale === 'en') {
              this.showEnglishNameWarning = false;
            }
          }
        }
      },
      scrollToFirstError() {
        this.$nextTick(() => {
          const firstError = document.querySelector('.n-form-item-feedback--error, [aria-invalid="true"]');
          if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        });
      },
      showSuccessNotification(message) {
        Swal.fire({
          icon: 'success',
          title: 'Успех!',
          text: message,
          timer: 2000,
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          customClass: {
            popup: 'modern-toast success'
          }
        });
      },
      showErrorNotification(message) {
        Swal.fire({
          icon: 'error',
          title: 'Ошибка',
          text: message,
          timer: 3000,
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          customClass: {
            popup: 'modern-toast error'
          }
        });
      },
      confirmDeleteCategory() {
        Swal.fire({
          title: 'Вы уверены?',
          text: "Вы действительно хотите удалить эту категорию? Это действие нельзя отменить!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Да, удалить!',
          cancelButtonText: 'Отмена',
          customClass: {
            popup: 'modern-toast warning'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            this.$store.dispatch('categories/delete', this.categoryId)
              .then(() => {
                this.showSuccessNotification('Категория успешно удалена');
                this.$router.push({ name: 'admin.categories.list' });
              })
              .catch((error) => {
                this.showErrorNotification('Ошибка при удалении категории');
              });
          }
        });
      },
      saveData() {
        this.clearErrors();
        this.checkEngName();
        
        if (this.validationErrors.translations.en?.name) {
          this.scrollToFirstError();
          return;
        }
        
        this.loading = true;
        
        const action = this.isEditMode ? 'update' : 'create';
        
        const payload = { ...this.form };
        payload.translations = payload.translations.filter(trans => 
          trans.name || trans.short_description || trans.full_description
        );
        
        this.$store.dispatch(`categories/${action}`, { id: this.categoryId, data: payload })
          .then(() => {
            this.showSuccessNotification(this.isEditMode ? 'Категория успешно обновлена' : 'Категория успешно создана');
            this.$router.push({ name: 'admin.categories.list' });
          })
          .catch(error => {
            if (error.response && error.response.data && error.response.data.errors) {
              this.handleValidationErrors(error.response.data.errors);
            } else {
              this.showErrorNotification('Произошла ошибка при сохранении данных');
            }
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    mounted() {
      this.loadCategoryData();
      this.loadCategories();
    }
  };
  </script>
  
  <style scoped>
  .modern-form-card {
    max-width: 1200px;
    margin: 0 auto;
    border-radius: 16px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
    background: white;
    overflow: hidden;
  }
  
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    background: rgba(249, 250, 251, 0.7);
  }
  
  .status-control {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  
  .modern-form {
    padding: 24px;
  }
  
  .form-section {
    margin-bottom: 32px;
    padding: 24px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
  }
  
  .form-section:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  }
  
  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }
  
  .section-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  
  .status-indicator {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
  }
  
  .status-indicator.active {
    background-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
  }
  
  .status-indicator.inactive {
    background-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
  }
  
  .delete-button {
    font-weight: 500;
  }
  
  .translation-tabs {
    margin-top: 16px;
  }
  
  .translation-tabs :deep(.n-tabs-nav) {
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: 16px;
  }
  
  .translation-tabs :deep(.n-tabs-tab) {
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  
  .tab-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
  }
  
  .language-flag {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid rgba(0, 0, 0, 0.05);
  }
  
  .validation-icon {
    font-size: 0.9rem;
  }
  
  .modern-input,
  .modern-textarea,
  .modern-select,
  .modern-time-picker {
    width: 100%;
    border-radius: 8px;
    transition: all 0.2s ease;
  }
  
  .modern-input:hover,
  .modern-textarea:hover,
  .modern-select:hover,
  .modern-time-picker:hover {
    border-color: #6366f1;
  }
  
  .modern-input:focus,
  .modern-textarea:focus,
  .modern-select:focus,
  .modern-time-picker:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
  }
  
  .modern-color-picker {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
  }
  
  .modern-input-number {
    width: 100%;
    border-radius: 8px;
  }
  
  .form-actions {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
  }
  
  .action-button {
    min-width: 180px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  
  .cancel-button {
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
  }
  
  .cancel-button:hover {
    background: #f9fafb;
    border-color: rgba(0, 0, 0, 0.2);
  }
  
  .submit-button {
    background: #4f46e5;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  }
  
  .submit-button:hover {
    background: #4338ca;
  }
  
  .switch-label {
    font-weight: 500;
    font-size: 0.9rem;
  }
  
  @media (max-width: 768px) {
    .modern-form-card {
      border-radius: 0;
      box-shadow: none;
    }
    
    .card-header {
      flex-direction: column;
      gap: 16px;
      align-items: flex-start;
    }
    
    .modern-form {
      padding: 16px;
    }
    
    .form-section {
      padding: 16px;
    }
    
    .action-button {
      width: 100%;
      margin-bottom: 12px;
    }
  }
  </style>
  
  <style>
  /* Глобальные стили для уведомлений */
  .modern-toast {
    border-radius: 12px !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
    padding: 16px !important;
  }
  
  .modern-toast.success {
    background: #f0fdf4 !important;
    color: #166534 !important;
    border-left: 4px solid #22c55e !important;
  }
  
  .modern-toast.error {
    background: #fef2f2 !important;
    color: #991b1b !important;
    border-left: 4px solid #ef4444 !important;
  }
  
  .modern-toast.warning {
    background: #fffbeb !important;
    color: #92400e !important;
    border-left: 4px solid #f59e0b !important;
  }
  
  .modern-toast .swal2-title {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
  }
  
  .modern-toast .swal2-content {
    font-size: 0.95rem !important;
  }
  </style>