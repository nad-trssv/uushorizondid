<template>
  <aside 
    class="sidebar-transition flex flex-col bg-white shadow-xl"
    :class="{
      'w-64': !isCollapsed,
      'w-20': isCollapsed,
      'sidebar-mobile': isMobile,
      'open': isMobile && !isCollapsed
    }"
  >
    <!-- Logo Section -->
    <div class="p-5 flex items-center justify-center border-b border-gray-100">
      <div v-if="!isCollapsed" class="text-2xl font-bold text-indigo-600">UC Approach</div>
      <i v-else class="fas fa-cube text-2xl text-indigo-600"></i>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 flex flex-col justify-between overflow-y-auto py-4 px-2">
      <ul class="space-y-1">
        <li v-for="item in menuOptions" :key="item.key">
          <!-- Single Menu Item -->
          <template v-if="!item.children">
            <router-link 
              :to="{ name: item.key }"
              @click="handleClick"
              class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all"
              :class="{ 
                'justify-center': isCollapsed,
                'bg-indigo-50 text-indigo-600 font-medium': isActive(item.key)
              }"
            >
              <i :class="[item.iconClass, 'text-lg', isCollapsed ? '' : 'mr-3']"></i>
              <span v-if="!isCollapsed" class="text-sm">{{ item.label }}</span>
              <span 
                v-if="isActive(item.key) && !isCollapsed"
                class="ml-auto w-2 h-2 bg-indigo-600 rounded-full"
              ></span>
            </router-link>
          </template>
          
          <!-- Menu Item with Children -->
          <template v-else>
            <div 
              class="flex items-center px-4 py-3 rounded-lg cursor-pointer text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all relative"
              :class="{ 
                'justify-center': isCollapsed,
                'bg-indigo-50 text-indigo-600 font-medium': isActiveParent(item)
              }"
              @click="toggleSubMenu(item.key)"
            >
              <i :class="[item.iconClass, 'text-lg', isCollapsed ? '' : 'mr-3']"></i>
              <span v-if="!isCollapsed" class="text-sm flex-1">{{ item.label }}</span>
              
              <!-- Индикатор подменю для свернутого состояния -->
              <div 
                v-if="isCollapsed && (subMenuOpen[item.key] || isActiveParent(item))"
                class="absolute right-1 top-1/2 transform -translate-y-1/2 w-1.5 h-1.5 bg-indigo-500 rounded-full"
              ></div>
              
              <i 
                v-if="!isCollapsed"
                :class="['fas text-xs transition-transform duration-200', subMenuOpen[item.key] ? 'fa-chevron-up text-indigo-600' : 'fa-chevron-down text-gray-400']"
              ></i>
            </div>
    
            <transition v-if="isCollapsed" name="slide-fade">
              <div 
                v-show="subMenuOpen[item.key]"
                class="ml-2 mt-1 bg-white rounded-lg shadow-md overflow-hidden fixed z-50"
                style="min-width: 200px;"
              >
                <ul class="py-1">
                  <li v-for="child in item.children" :key="child.key">
                    <router-link 
                      :to="{ name: child.key }"
                      @click="handleClick"
                      class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all"
                      :class="{ 'bg-indigo-50 text-indigo-600 font-medium': isActive(child.key) }"
                    >
                      <span class="w-1 h-1 bg-gray-400 rounded-full mr-3"></span>
                      <span>{{ child.label }}</span>
                    </router-link>
                  </li>
                </ul>
              </div>
            </transition>

            <transition v-else name="slide" 
              @enter="onEnter"
              @after-enter="onAfterEnter"
              @leave="onLeave"
            >
              <ul 
                v-show="subMenuOpen[item.key]"
                class="pl-2 mt-1 space-y-1 overflow-hidden"
              >
                <li v-for="child in item.children" :key="child.key">
                  <router-link 
                    :to="{ name: child.key }"
                    @click="handleClick"
                    class="flex items-center px-4 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all"
                    :class="{ 'bg-indigo-50 text-indigo-600 font-medium': isActive(child.key) }"
                  >
                    <span class="w-1 h-1 bg-gray-400 rounded-full mr-3"></span>
                    <span>{{ child.label }}</span>
                    <span 
                      v-if="isActive(child.key)"
                      class="ml-auto w-2 h-2 bg-indigo-600 rounded-full"
                    ></span>
                  </router-link>
                </li>
              </ul>
            </transition>
          </template>
        </li>
      </ul>
      <div 
        class="mt-4 mx-2 p-3 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-indigo-100"
        :class="{ 'hidden': isCollapsed }"
      >
        <div class="text-xs font-semibold text-indigo-800 mb-1">PREMIUM</div>
        <h4 class="text-sm font-bold text-gray-800 mb-1">{{ $t('msg.premium_features') }}</h4>
        <p class="text-xs text-gray-600 mb-2">{{ $t('msg.upgrade_description') }}</p>
        <button 
          @click="navigateToPremium"
          class="w-full py-1.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xs font-medium rounded-md hover:shadow-md transition-all cursor-pointer"
        >
          {{ $t('msg.upgrade_button') }}
        </button>
      </div>
    </nav>
    
    <!-- Footer Section -->
    <div class="p-4 border-t border-gray-100 bg-gray-50">
      <!-- Primary Action Button -->
      <button 
        @click="navigateToBooking"
        class="w-full mb-3 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white rounded-lg transition-all hover:shadow-md flex items-center justify-center cursor-pointer"
        :class="{ 'px-2': isCollapsed, 'px-4': !isCollapsed }"
      >
        <i class="fas fa-calendar-plus" :class="{ 'mr-0': isCollapsed, 'mr-2': !isCollapsed }"></i>
        <span v-if="!isCollapsed" class="text-sm font-medium">{{ $t('msg.new_booking') }}</span>
      </button>
      
      <!-- Secondary Action Button -->
      <button 
        @click="navigateToNewService"
        class="w-full mb-4 py-2.5 border border-indigo-500 text-indigo-600 bg-white rounded-lg transition-all hover:bg-indigo-50 hover:shadow-sm flex items-center justify-center cursor-pointer"
        :class="{ 'px-2': isCollapsed, 'px-4': !isCollapsed }"
      >
        <i class="fas fa-plus" :class="{ 'mr-0': isCollapsed, 'mr-2': !isCollapsed }"></i>
        <span v-if="!isCollapsed" class="text-sm font-medium">{{ $t('msg.new_service') }}</span>
      </button>
      
      <!-- User Profile -->
      <div class="flex items-center" :class="{ 'justify-center': isCollapsed, 'justify-between': !isCollapsed }">
        <div class="flex items-center">
            <n-avatar round size="small" src="https://i.pravatar.cc/150?img=3" class="ring-2 ring-indigo-200 aspect-square" />
          <div v-if="!isCollapsed && authInfo" class="ml-3">
            <div class="text-sm font-medium text-gray-800">{{ authInfo?.name }} |
              <span class="text-gray-500 text-xs">Role: {{ authInfo?.role?.name }}</span>
            </div>
            <div v-if="authInfo.role" class="text-xs text-indigo-500">{{ authInfo?.email }}</div>
          </div>
        </div>
        <button 
          v-if="!isCollapsed"
          @click="logout"
          class="text-gray-500 hover:text-indigo-600 transition-colors p-1 rounded-full hover:bg-indigo-50"
          title="Выйти"
        >
          <i class="fas fa-sign-out-alt text-sm"></i>
        </button>
      </div>
    </div>
  </aside>
</template>

<script>
import { NAvatar } from 'naive-ui';

export default {
  name: 'Sidebar',
  components: {
    NAvatar
  },
  props: {
    isCollapsed: Boolean,
    isMobile: Boolean,
    toggleSidebar: Function
  },
  data() {
    return {
      subMenuOpen: {}
    };
  },
  computed: {
    authInfo() {
      return this.$store.getters['auth/authInfo'];
    },
    menuOptions() {
      return [
        {
          label: this.$t('msg.menu.dashboard'),
          key: 'admin.dashboard',
          iconClass: 'fas fa-chart-pie text-indigo-500',
        },
        {
          label: this.$t('msg.menu.calendar'),
          key: 'admin.calendar.index',
          iconClass: 'fas fa-calendar-alt text-indigo-500',
        },
        {
          label: this.$t('msg.menu.services'),
          key: 'admin.services.parent',
          iconClass: 'fas fa-boxes text-indigo-500',
          children: [
            {
              label: this.$t('msg.menu.services'),
              key: 'admin.services.list',  
            },
            {
              label: this.$t('msg.menu.service_create'),
              key: 'admin.services.create',
            }
          ],
        },
        {
          label: this.$t('msg.menu.categories'),
          key: 'admin.categories.parent',
          iconClass: 'fas fa-tags text-indigo-500',
          children: [
            {
              label: this.$t('msg.menu.categories'),
              key: 'admin.categories.list',
            },
            {
              label: this.$t('msg.menu.category_create'),
              key: 'admin.categories.create',
            }
          ],
        },
        {
          label: this.$t('msg.menu.blog'),
          key: 'admin.blog.parent',
          iconClass: 'fas fa-newspaper text-indigo-500',
          children: [
            {
              label: this.$t('msg.menu.blog_stats'),
              key: 'admin.blog.stats',
            },
            {
              label: this.$t('msg.menu.blog_list'),
              key: 'admin.blog.list',
            }
          ],
        },
      ];
    }
  },
  created() {
    this.initializeSubMenus();
  },
  watch: {
    menuOptions: {
      handler() {
        this.initializeSubMenus();
      },
      immediate: true
    },
    '$route'() {
      if (!this.isCollapsed) {
        this.initializeSubMenus();
      }
    },
    isCollapsed(newVal) {
      if (newVal) {
        this.closeAllSubMenus();
      } else {
        this.initializeSubMenus();
      }
    }
  },
  methods: {
    initializeSubMenus() {
      if (!this.menuOptions) return;
      
      const newSubMenuOpen = {};
      this.menuOptions.forEach(item => {
        if (item.children && item.key) {
          newSubMenuOpen[item.key] = this.isCollapsed ? false : this.isActiveParent(item);
        }
      });
      this.subMenuOpen = newSubMenuOpen;
    },
    isActive(routeName) {
      return this.$route.name === routeName;
    },
    isActiveParent(item) {
      if (!item.children) return false;
      return item.children.some(child => {
        return this.$route.name === child.key || 
              (child.children && this.isActiveParent(child));
      });
    },
    toggleSubMenu(key) {
      this.subMenuOpen[key] = !this.subMenuOpen[key];
      
      if (!this.isCollapsed) {
        Object.keys(this.subMenuOpen).forEach(k => {
          if (k !== key) {
            this.subMenuOpen[k] = false;
          }
        });
      }
    },
    closeAllSubMenus() {
      Object.keys(this.subMenuOpen).forEach(key => {
        this.subMenuOpen[key] = false;
      });
    },
    logout() {
      this.$store.dispatch('auth/logout').then(() => {
        this.$router.push({ name: 'authLogin' });
      });
    },
    handleClick() {
      this.closeAllSubMenus();
      if (this.isMobile) this.toggleSidebar();
    },
    navigateToBooking() {
      this.closeAllSubMenus();
      this.$router.push({ name: 'booking.create' });
    },
    navigateToNewService() {
      this.closeAllSubMenus();
      this.$router.push({ name: 'admin.services.create' });
    },
    navigateToPremium() {
      this.closeAllSubMenus();
      alert('Premium features coming soon!');
    },
    onEnter(el) {
      el.style.height = '0';
      setTimeout(() => el.style.height = `${el.scrollHeight}px`, 10);
    },
    onAfterEnter(el) {
      el.style.height = 'auto';
    },
    onLeave(el) {
      el.style.height = `${el.scrollHeight}px`;
      setTimeout(() => el.style.height = '0', 10);
    }
  }
};
</script>

<style>
.slide-enter-active,
.slide-leave-active {
  transition: height 0.25s ease;
  overflow: hidden;
}

.sidebar-transition {
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar-mobile {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 50;
  transform: translateX(-100%);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 0 0 rgba(0,0,0,0);
}

.sidebar-mobile.open {
  transform: translateX(0);
  box-shadow: 4px 0 15px rgba(0,0,0,0.1);
}

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}
.slide-fade-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>