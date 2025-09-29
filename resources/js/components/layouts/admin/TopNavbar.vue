<template>
  <header class="bg-white shadow-sm z-10">
    <div class="flex items-center justify-between px-6 py-3">
      <div class="flex items-center">
        <n-button text @click="$emit('toggle-sidebar')" class="md:hidden">
          <template #icon>
            <i class="fas fa-bars"></i>
          </template>
        </n-button>
        <Breadcrumbs />
      </div>
      
      <div class="flex items-center space-x-4 gap-4">
        <!-- Language dropdown -->
        <n-dropdown
          v-if="currentLanguage && languageOptions.length"
          trigger="click"
          :options="languageOptions"
          placement="bottom-end"
          @select="handleLanguageSelect"
        >
          <n-button text>
            <div class="flex items-center gap-2">
              <img 
                v-if="currentLanguage?.flag"
                :src="`/storage/${currentLanguage.flag}`" 
                class="w-5 h-5 object-cover rounded"
              />
              <span v-else class="w-5 h-5 flex items-center justify-center border rounded text-xs uppercase">
                {{ currentLanguage?.code?.substring(0, 2) || '??' }}
              </span>
              <span class="text-sm font-medium hidden md:inline">
                {{ currentLanguage?.name || 'Language' }}
              </span>
              <i class="fas fa-chevron-down text-xs text-gray-500"></i>
            </div>
          </n-button>
        </n-dropdown>
        
        <!-- Notifications dropdown -->
        <n-dropdown
          trigger="click"
          placement="bottom-end"
          :options="notificationOptions"
        >
          <n-badge :value="unreadNotifications" dot>
            <n-button text>
              <template #icon>
                <i class="fas fa-bell text-lg"></i>
              </template>
            </n-button>
          </n-badge>
        </n-dropdown>
        
        <!-- Show My Website link -->
        <n-button quaternary @click="goToWebsite">
          <div class="flex items-center gap-2">
            <i class="fas fa-globe text-lg"></i>
            <span class="hidden md:inline text-sm font-medium">Show my website</span>
          </div>
        </n-button>
        
        <!-- Profile dropdown -->
        <n-dropdown 
          trigger="click" 
          placement="bottom-end"
          :options="profileOptions"
        >
          <n-button text>
            <div class="flex items-center">
              <n-avatar 
                round 
                size="small" 
                src="https://i.pravatar.cc/150?img=3" 
              />
              <span class="ml-2 hidden md:inline font-medium">{{ authInfo.name }}</span>
              <i class="fas fa-chevron-down text-xs text-gray-500 ml-1"></i>
            </div>
          </n-button>
        </n-dropdown>
      </div>
    </div>
  </header>
</template>

<script>
import { NButton, NAvatar, NBadge, NDropdown } from 'naive-ui';
import Breadcrumbs from './Breadcrumbs.vue';
import { h } from 'vue';

export default {
  name: 'TopNavbar',
  components: {
    NButton,
    NAvatar,
    NBadge,
    NDropdown,
    Breadcrumbs
  },
  data() {
    return {
      currentLanguage: null,
      unreadNotifications: 3,
    };
  },
  computed: {
    authInfo() {
      return this.$store.getters['auth/authInfo'];
    },
    currentRouteName() {
      return this.$route.meta?.breadcrumb || this.$t('msg.menu.dashboard');
    },
    settings() {
      return this.$store.state('settings/lists') || {};
    },
    availableLanguages() {
      return this.$store.getters['settings/availableLanguages'] || {};
    },
    languageOptions() {
      if (!this.availableLanguages) return [];

      return Object.values(this.availableLanguages)
        .filter(lang => lang?.enabled)
        .map(lang => ({
          label: () => h('div', { class: 'flex items-center gap-2' }, [
            lang.flag 
              ? h('img', { 
                  src: `/storage/${lang.flag}`, 
                  class: 'w-5 h-5 object-cover rounded' 
                })
              : h('span', { class: 'w-5 h-5 flex items-center justify-center border rounded text-xs uppercase' }, 
                  lang.code?.substring(0, 2) || '??'),
            h('span', lang.name || lang.code)
          ]),
          key: lang.code,
          extra: lang.native_name
        }));
    },
    profileOptions() {
      return [
        {
          label: this.$t('msg.menu.profile'),
          key: 'profile',
          icon: () => h('i', { class: 'fas fa-user mr-2' })
        },
        {
          label: this.$t('msg.menu.settings'),
          key: 'settings',
          icon: () => h('i', { class: 'fas fa-cog mr-2' })
        },
        { type: 'divider' },
        {
          label: this.$t('msg.menu.logout'),
          key: 'logout',
          icon: () => h('i', { class: 'fas fa-sign-out-alt mr-2' }),
          props: {
            onClick: this.logout
          }
        }
      ];
    },
    notificationOptions() {
      return [
        {
          type: 'render',
          key: 'header',
          render: () => h('div', { class: 'px-4 py-2 font-semibold' }, this.$t('msg.menu.notifications'))
        },
        { type: 'divider' },
        {
          label: this.$t('msg.menu.newOrderReceived'),
          key: 'order',
          icon: () => h('i', { class: 'fas fa-shopping-cart text-blue-500 mr-2' }),
          extra: '5 min ago'
        },
        {
          label: this.$t('msg.menu.newMessageFromClient'),
          key: 'message',
          icon: () => h('i', { class: 'fas fa-envelope text-green-500 mr-2' }),
          extra: '1 hour ago'
        },
        {
          label: this.$t('msg.menu.systemUpdateAvailable'),
          key: 'update',
          icon: () => h('i', { class: 'fas fa-download text-yellow-500 mr-2' }),
          extra: '2 days ago'
        },
        { type: 'divider' },
        {
          label: this.$t('msg.menu.viewAllNotifications'),
          key: 'view-all',
          icon: () => h('i', { class: 'fas fa-bell mr-2' })
        }
      ];
    },
    // langv2() {
    //   const langv2 = this.$store.getters['settings/justForTest'] || {};
    //   console.log('langV2 navbar', langv2);
    //   return langv2;
    // },
  },
  watch: {
    availableLanguages: {
      immediate: true,
      handler(languages) {
        if (languages && Object.keys(languages).length) {
          this.setCurrentLanguage();
        }
      }
    }
  },
  methods: {
    getSettings() {
      this.$store.dispatch('settings/lists', {
        group: 'localization'
      });
    },
    setCurrentLanguage() {
      const savedLocale = localStorage.getItem('locale');
      const defaultLang = Object.values(this.availableLanguages).find(lang => lang?.default);
      const locale = savedLocale || defaultLang?.code || 'en';
      this.currentLanguage = this.availableLanguages[locale] || Object.values(this.availableLanguages)[0];

      if (this.currentLanguage) {
        this.$i18n.locale = locale;
        localStorage.setItem('locale', locale);
      }
    },
    handleLanguageSelect(key) {
      if (this.availableLanguages[key]) {
        this.currentLanguage = this.availableLanguages[key];
        this.$i18n.locale = key;
        localStorage.setItem('locale', key);
        window.location.reload();
      }
    },
    logout() {
      this.$store.dispatch('auth/logout').then(() => {
        this.$router.push({ name: 'authLogin' });
      }).catch(error => {
        console.error('Logout failed:', error);
      });
    },
    goToWebsite() {
      window.open('/', '_blank');
    }
  },
  mounted() {
    this.getSettings();
  }
};
</script>

<style scoped>
/* Анимация для иконки уведомлений */
.fa-bell {
  transition: transform 0.2s;
}
.fa-bell:hover {
  transform: scale(1.1);
}

/* Стили для выпадающих списков */
:deep(.n-dropdown-option) {
  padding: 8px 12px;
}
:deep(.n-dropdown-option__extra) {
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 2px;
}
</style>