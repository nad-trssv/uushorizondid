<template>
  <n-card
    :title="isEditMode ? 'Редактирование услуги' : 'Создание новой услуги'"
    :bordered="false"
    class="modern-form-card"
    size="small"
  >
    <!-- Хедер карточки -->
    <div class="card-header">
      <n-button 
        v-if="isEditMode"
        text
        tag="a"
        :href="`/admin/services/${serviceId}/masters`"
        class="action-button"
      >
        <template #icon>
          <i class="fas fa-users"></i>
        </template>
        Перейти к мастерам услуги
      </n-button>
      
      <div class="status-control">
        <n-tag :bordered="false" :type="form.status ? 'success' : 'error'" size="small" round>
          {{ form.status ? 'Активна' : 'Неактивна' }}
        </n-tag>
        <n-switch
          v-model:value="form.status" 
          :round="true"
          :checked-value="1"
          :unchecked-value="0"
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
            {{ isEditMode ? form.name : 'Создание новой услуги' }}
          </n-h3>
          
          <n-button 
            v-if="isEditMode"
            type="error"
            tertiary
            size="small"
            @click="confirmDeleteService"
            class="delete-button"
          >
            <template #icon>
              <i class="fas fa-trash"></i>
            </template>
            Удалить услугу
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
            <n-form-item v-if="validationErrors.translations?.[lang.code]?.name" type="error">
              {{ validationErrors.translations[lang.code].name }}
            </n-form-item>
          </n-tab-pane>
        </n-tabs>
      </section>

      <!-- Секция параметров с современным гридом -->
      <section class="form-section">
        <n-h3 class="section-title">
          <i class="fas fa-cog mr-2"></i>
          Основные параметры
        </n-h3>
        
        <n-grid :cols="12" :x-gap="24" :y-gap="16" responsive="screen" item-responsive>
          <!-- Цвет события -->
          <n-form-item-gi :span="12" label="Цвет события" path="eventColor">
            <n-color-picker
              v-model:value="form.eventColor"
              :swatches="colorSwatches"
              :modes="['hex']"
              :show-alpha="false"
              class="modern-color-picker"
            />
          </n-form-item-gi>

          <!-- Категория -->
          <n-form-item-gi :span="6" :md="12" label="Категория" path="category_id" v-if="categoryOptions.length > 0">
            <n-cascader
              v-model:value="form.category_id"
              :options="categoryOptions"
              placeholder="Выберите категорию"
              check-strategy="child"
              clearable
              filterable
              class="modern-select"
            />
          </n-form-item-gi>

          <!-- Цена -->
          <n-form-item-gi :span="6" :md="12" label="Цена" path="price">
            <n-input-number 
              v-model:value="form.price" 
              :min="0" 
              :step="0.10" 
              clearable
              class="modern-input-number"
              placeholder="0.00"
            >
              <template #suffix>
                <span class="currency">&euro;</span>
              </template>
            </n-input-number>
          </n-form-item-gi>

          <!-- Изменяемая цена -->
          <n-form-item-gi :span="6" :md="12" label="Цена может меняться" path="price_can_change">
            <n-switch 
              v-model:value="form.price_can_change" 
              :round="true"
              size="medium"
            >
              <template #checked>
                <span class="switch-label">Да</span>
              </template>
              <template #unchecked>
                <span class="switch-label">Нет</span>
              </template>
            </n-switch>
          </n-form-item-gi>

          <!-- Длительность -->
          <n-form-item-gi :span="6" :md="12" label="Минимальная длительность" path="duration_minutes_min">
            <n-input-number 
              v-model:value="form.duration_minutes_min" 
              :min="1" 
              clearable
              class="modern-input-number"
            >
              <template #suffix>
                <span class="unit">мин</span>
              </template>
            </n-input-number>
          </n-form-item-gi>
          
          <n-form-item-gi :span="6" :md="12" label="Длительность" path="duration_minutes">
            <n-input-number 
              v-model:value="form.duration_minutes" 
              :min="1" 
              clearable
              class="modern-input-number"
            >
              <template #suffix>
                <span class="unit">мин</span>
              </template>
            </n-input-number>
          </n-form-item-gi>

          <!-- Фиксированное время -->
          <n-form-item-gi :span="24" label="Фиксированное время" path="has_fixed_time">
            <n-switch 
              v-model:value="form.has_fixed_time" 
              :round="true"
              size="medium"
              @update:value="handleFixedTimeChange"
            >
              <template #checked>
                <span class="switch-label">Да</span>
              </template>
              <template #unchecked>
                <span class="switch-label">Нет</span>
              </template>
            </n-switch>
          </n-form-item-gi>

          <!-- Время начала/окончания -->
          <template v-if="form.has_fixed_time">
            <n-form-item-gi :span="6" :md="12" label="Время начала" path="time_from">
              <n-time-picker
                v-model:value="form.time_from"
                format="HH:mm"
                clearable
                class="modern-time-picker"
              />
            </n-form-item-gi>
            <n-form-item-gi :span="6" label="Время окончания" path="time_to">
              <n-time-picker
                v-model:value="form.time_to"
                format="HH:mm"
                clearable
                class="modern-time-picker"
              />
            </n-form-item-gi>
          </template>
        </n-grid>
      </section>

      <!-- Описания с современными табами -->
      <template v-for="(section, index) in descriptionSections" :key="index">
        <section class="form-section">
          <n-h3 class="section-title">
            <i :class="section.icon" class="mr-2"></i>
            {{ section.title }}
          </n-h3>
          
          <n-tabs type="line" animated class="translation-tabs">
            <n-tab-pane
              v-for="lang in availableLanguages"
              :key="`${lang.code}-${section.field}`"
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
                      'fa-check-circle text-success': isTranslationFilled(lang.code, section.field),
                      'fa-times-circle text-error': !isTranslationFilled(lang.code, section.field)
                    }"
                  ></i>
                </div>
              </template>
              <n-input
                v-model:value="getTranslation(lang.code)[section.field]"
                type="textarea"
                :placeholder="`Введите ${section.title.toLowerCase()} на ${lang.native_name}`"
                :autosize="{ minRows: section.minRows, maxRows: section.maxRows }"
                class="modern-textarea"
              />
            </n-tab-pane>
          </n-tabs>
        </section>
      </template>

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
            @click="handleSubmit"
            :loading="loading"
            class="action-button submit-button"
            size="large"
          >
            <template #icon>
              <i class="fas" :class="isEditMode ? 'fa-save' : 'fa-plus'"></i>
            </template>
            {{ isEditMode ? 'Сохранить изменения' : 'Создать услугу' }}
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
  NTimePicker,
  NFormItem,
  NTag
} from 'naive-ui';
import Swal from 'sweetalert2';

export default {
  name: 'ModernServiceForm',
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
    NTimePicker,
    NFormItem,
    NTag
  },
  data() {
    return {
      formRef: null,
      loading: false,
      form: {
        category_id: null,
        name: '',
        status: 1,
        price: null,
        price_can_change: false,
        duration_minutes: null,
        duration_minutes_min: null,
        has_fixed_time: false,
        time_from: null,
        time_to: null,
        eventColor: '#3b82f6',
        translations: [],
      },
      colorSwatches: [
        '#3b82f6', '#ef4444', '#10b981', '#f59e0b', 
        '#8b5cf6', '#ec4899', '#14b8a6', '#84cc16'
      ],
      descriptionSections: [
        {
          title: 'Краткое описание',
          field: 'short_description',
          icon: 'fas fa-align-left',
          minRows: 3,
          maxRows: 6
        },
        {
          title: 'Полное описание',
          field: 'full_description',
          icon: 'fas fa-file-alt',
          minRows: 5,
          maxRows: 10
        }
      ],
      rules: {
        category_id: [
          { 
            required: true, 
            message: 'Выберите категорию', 
            trigger: ['blur', 'change'],
            validator: (rule, value) => !!value
          }
        ],
        'translations.en.name': [
          { required: true, message: 'Название на английском языке обязательно', trigger: ['blur', 'change'] }
        ],
        price: [
          { 
            required: true, 
            message: 'Укажите цену услуги', 
            trigger: ['blur', 'change'],
            validator: (rule, value) => value !== null && !isNaN(value)
          }
        ],
        duration_minutes: [
          { 
            required: true, 
            message: 'Укажите длительность услуги', 
            trigger: ['blur', 'change'],
            validator: (rule, value) => value !== null && !isNaN(value) && value > 0
          }
        ]
      },
      validationErrors: {},
      showEnglishNameWarning: false 
    };
  },
  computed: {
    isEditMode() {
      return this.$route.name === 'admin.services.edit';
    },
    serviceId() {
      return this.$route.params.id;
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
    availableLanguages() {
      const languages = this.$store.getters['settings/availableLanguages'] || {};
      return Object.values(languages).filter(lang => lang.enabled);
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
          short_description: '',
          full_description: ''
        };
        this.form.translations.push(translation);
      }
      
      return translation;
    },
    loadServiceData() {
      if (!this.isEditMode) return;
      
      this.loading = true;
      this.$store.dispatch('services/getById', this.serviceId)
        .then(service => {
          if (service) {
            this.form = service.data || {};
            this.form.price = this.form.price !== null ? parseFloat(this.form.price) : null;
            this.form.duration_minutes = this.form.duration_minutes !== null ? parseInt(this.form.duration_minutes) : null;
            this.form.duration_minutes_min = this.form.duration_minutes_min !== null ? parseInt(this.form.duration_minutes_min) : null;
            
            if (!this.form.translations) {
              this.form.translations = [];
            }
            
            if(this.form.has_fixed_time) {
              this.form.has_fixed_time = true;
              this.form.time_from = new Date(`1970-01-01T${this.form.time_from}`);
              this.form.time_to = new Date(`1970-01-01T${this.form.time_to}`);
            } else {
              this.form.has_fixed_time = false;
              this.form.time_from = null;
              this.form.time_to = null;
            }
          }
        })
        .catch(error => {
          console.error('Error loading service:', error);
          this.showErrorNotification('Не удалось загрузить данные услуги');
          this.$router.push({ name: 'admin.services.list' });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    handleCancel() {
      this.$router.push({ name: 'admin.services.list' });
    },
    handleSubmit(e) {
      e.preventDefault();
      this.clearErrors();
      this.checkEngName();

      this.$refs.formRef.validate((errors) => {
        if (!errors && !this.showEnglishNameWarning) {
          this.saveService();
        } else {
          this.showErrorNotification('Пожалуйста, исправьте ошибки в форме');
          this.scrollToFirstError();
        }
      });
    },
    clearErrors() {
      this.validationErrors = {
        translations: {} 
      };
      this.showEnglishNameWarning = false;
    },
    saveService() {
      this.loading = true;
      const payload = { ...this.form };
      
      payload.translations = payload.translations.filter(trans => 
        trans.name || trans.short_description || trans.full_description
      );
      
      if (payload.has_fixed_time) {
        const timeFromDate = new Date(payload.time_from);
        const timeToDate = new Date(payload.time_to);
        payload.time_from = timeFromDate.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
        payload.time_to = timeToDate.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
      } else {
        payload.has_fixed_time = false;
        payload.time_from = null;
        payload.time_to = null;
      }
      
      const action = this.isEditMode 
        ? this.$store.dispatch('services/update', { id: this.serviceId, data: payload })
        : this.$store.dispatch('services/create', payload);
              
      action
        .then(() => {
          this.showSuccessNotification(
            this.isEditMode ? 'Услуга успешно обновлена' : 'Услуга успешно создана'
          );
          
          if (!this.isEditMode) {
            this.$router.push({ name: 'admin.services.list' });
          }
        })
        .catch(error => {
          console.error('Error saving service:', error);
          
          if (error.response?.status === 422) {
            this.handleValidationErrors(error.response.data.errors);
          } else {
            this.showErrorNotification(
              error.response?.data?.message || 'Не удалось сохранить услугу'
            );
          }
        })
        .finally(() => {
          this.loading = false;
        });
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
    handleFixedTimeChange(value) {
      this.form.has_fixed_time = value;
      
      if (!value) {
        this.form.time_from = null;
        this.form.time_to = null;
      } else {
        if (!this.form.time_from) {
          this.form.time_from = new Date(0, 0, 0, 9, 0);
        }
        if (!this.form.time_to) {
          this.form.time_to = new Date(0, 0, 0, 18, 0);
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
    confirmDeleteService() {
      Swal.fire({
        title: 'Вы уверены?',
        text: "Вы действительно хотите удалить эту услугу? Это действие нельзя отменить!",
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
          this.$store.dispatch('services/delete', this.serviceId)
            .then(() => {
              this.showSuccessNotification('Услуга успешно удалена');
              this.$router.push({ name: 'admin.services.list' });
            })
            .catch((error) => {
              this.showErrorNotification('Ошибка при удалении услуги');
            });
        }
      });
    }
  },
  mounted() {
    this.loadServiceData();
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