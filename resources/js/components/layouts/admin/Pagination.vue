<!-- resources/js/components/layouts/admin/Paginate.vue -->
<template>
    <div v-if="totalPages > 1" class="flex justify-center mt-8">
      <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/30 rounded-xl p-2">
        <!-- Кнопка "Предыдущая" -->
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="p-2 rounded-lg transition-all flex items-center justify-center"
          :class="{
            'bg-white/20 text-gray-800 cursor-pointer hover:bg-white/30': currentPage > 1,
            'text-gray-400 cursor-not-allowed': currentPage === 1
          }"
          title="Предыдущая страница"
        >
          <i class="fas fa-chevron-left text-xs"></i>
        </button>
  
        <!-- Первая страница с многоточием если нужно -->
        <button
          v-if="currentPage > 3 && totalPages > 5"
          @click="changePage(1)"
          class="w-8 h-8 rounded-lg transition-all flex items-center justify-center text-sm font-medium"
          :class="{
            'bg-blue-500 text-white': currentPage === 1,
            'bg-white/10 text-gray-700 hover:bg-white/20': currentPage !== 1
          }"
        >
          1
        </button>
        <span v-if="currentPage > 3 && totalPages > 5" class="text-gray-500 px-1">...</span>
  
        <!-- Страницы вокруг текущей -->
        <button
          v-for="pageNumber in visiblePages"
          :key="pageNumber"
          @click="changePage(pageNumber)"
          class="w-8 h-8 rounded-lg transition-all flex items-center justify-center text-sm font-medium"
          :class="{
            'bg-blue-500 text-white': currentPage === pageNumber,
            'bg-white/10 text-gray-700 hover:bg-white/20': currentPage !== pageNumber
          }"
        >
          {{ pageNumber }}
        </button>
  
        <!-- Многоточие и последняя страница если нужно -->
        <span v-if="currentPage < totalPages - 2 && totalPages > 5" class="text-gray-500 px-1">...</span>
        <button
          v-if="currentPage < totalPages - 2 && totalPages > 5"
          @click="changePage(totalPages)"
          class="w-8 h-8 rounded-lg transition-all flex items-center justify-center text-sm font-medium"
          :class="{
            'bg-blue-500 text-white': currentPage === totalPages,
            'bg-white/10 text-gray-700 hover:bg-white/20': currentPage !== totalPages
          }"
        >
          {{ totalPages }}
        </button>
  
        <!-- Кнопка "Следующая" -->
        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="p-2 rounded-lg transition-all flex items-center justify-center"
          :class="{
            'bg-white/20 text-gray-800 cursor-pointer hover:bg-white/30': currentPage < totalPages,
            'text-gray-400 cursor-not-allowed': currentPage === totalPages
          }"
          title="Следующая страница"
        >
          <i class="fas fa-chevron-right text-xs"></i>
        </button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Paginate',
    props: {
      totalPages: {
        type: Number,
        required: true
      },
      currentPage: {
        type: Number,
        required: true
      },
      visiblePages: {
        type: Array,
        required: true
      }
    },
    methods: {
      changePage(pageNumber) {
        this.$emit('page-changed', pageNumber);
      }
    }
  }
  </script>