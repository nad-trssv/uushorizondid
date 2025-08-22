<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Mobile Overlay -->
    <div 
      v-if="isMobile && !isSidebarCollapsed"
      class="fixed inset-0 z-40 lg:hidden"
      @click="toggleSidebar"
    ></div>
    
    <!-- Sidebar Component -->
    <Sidebar 
      :is-collapsed="isSidebarCollapsed"
      :is-mobile="isMobile"
      :toggle-sidebar="toggleSidebar"
      @toggle="toggleSidebar"
      class="z-50"
    />
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Navigation Component -->
      <TopNavbar 
        @toggle-sidebar="toggleSidebar"
      />
      
      <!-- Content Area with Transition -->
      <main class="flex-1 overflow-y-auto bg-gray-50 transition-all duration-300">
        <router-view v-slot="{ Component }">
          <transition
            name="fade"
            mode="out-in"
          >
            <div class="p-4 sm:p-6 max-w-7xl mx-auto w-full">
              <component :is="Component" />
            </div>
          </transition>
        </router-view>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import Sidebar from './admin/Sidebar.vue';
import TopNavbar from './admin/TopNavbar.vue';

export default {
  name: 'AdminLayout',
  components: {
    Sidebar,
    TopNavbar
  },
  setup() {
    const isSidebarCollapsed = ref(false);
    const isMobile = ref(false);
    
    // Проверяем состояние в localStorage при инициализации
    const checkLocalStorage = () => {
      const savedState = localStorage.getItem('sidebarCollapsed');
      if (savedState !== null) {
        isSidebarCollapsed.value = JSON.parse(savedState);
      }
    };
    
    // Определяем мобильное устройство
    const checkMobile = () => {
      isMobile.value = window.innerWidth < 1024;
      if (isMobile.value) {
        isSidebarCollapsed.value = true;
      }
    };
    
    const toggleSidebar = () => {
      isSidebarCollapsed.value = !isSidebarCollapsed.value;
      // Сохраняем состояние в localStorage
      localStorage.setItem('sidebarCollapsed', JSON.stringify(isSidebarCollapsed.value));
    };
    
    // Обработчик изменения размера окна
    const handleResize = () => {
      checkMobile();
      // На десктопе восстанавливаем сохраненное состояние
      if (!isMobile.value) {
        checkLocalStorage();
      }
    };
    
    onMounted(() => {
      checkMobile();
      checkLocalStorage();
      window.addEventListener('resize', handleResize);
    });
    
    onBeforeUnmount(() => {
      window.removeEventListener('resize', handleResize);
    });
    
    return {
      isSidebarCollapsed,
      isMobile,
      toggleSidebar
    };
  },
  mounted() {
    this.getAuthInfo();
  },
  methods: {
    getAuthInfo() {
      this.$store.dispatch('auth/getAuthInfo');
    }
  }
};
</script>

<style>
/* Fade transition for route changes */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Smooth transitions for sidebar */
.sidebar-transition {
  transition: transform 0.3s ease, width 0.3s ease;
}

/* Mobile sidebar styles */
@media (max-width: 1023px) {
  .sidebar-mobile {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    transform: translateX(-100%);
    z-index: 50;
  }
  
  .sidebar-mobile.open {
    transform: translateX(0);
  }
}
</style>