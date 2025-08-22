<template>
    <div
      class="child-category"
      draggable="true"
      @dragstart="startDrag($event, category, parent)"
      @dragover.prevent="onDragOver($event, category, parent)"
      @dragleave="onDragLeave($event)"
      @drop.prevent="onDrop($event, category, parent)"
    >
      <!-- Категория -->
      <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow hover:shadow-md transition">
        <div class="flex items-center gap-3">
          <i class="fas fa-grip-vertical text-gray-400 cursor-move"></i>
          <div>
            <h4 class="font-semibold text-gray-800"> {{ category.id }} {{ category.name }}</h4>
            <p class="text-sm text-gray-500">{{ category.description }}</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <button 
            v-if="category.children && category.children.length"
            @click.stop="toggleChildren(category.id)" 
            class="p-2 text-blue-500 hover:text-blue-700"
          >
            <i :class="['fas', expandedCategories.includes(category.id) ? 'fa-minus' : 'fa-plus']"></i>
          </button>
          <router-link :to="{ name: 'admin.categories.edit', params: {id: category.id} }" class="p-2 text-green-500 hover:text-green-700"><i class="fas fa-pen"></i></router-link>
          <button class="p-2 text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
        </div>
      </div>
  
      <!-- Рекурсивный рендер подкатегорий -->
      <div 
        v-if="expandedCategories.includes(category.id) && category.children && category.children.length" 
        class="mt-2 space-y-2 pl-12"
      >
        <CategoryItem
          v-for="child in category.children"
          :key="child.id"
          :category="child"
          :parent="category"
          :expanded-categories="expandedCategories"
          @toggle="toggleChildren"
          @start-drag="startDrag"
          @drag-over="onDragOver"
          @drag-leave="onDragLeave"
          @drop="onDrop"
        />
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'CategoryItem',
    props: {
      category: Object,
      parent: Object,
      expandedCategories: Array
    },
    methods: {
      toggleChildren(id) {
        this.$emit('toggle', id);
      },
      startDrag(e, item, parent) {
        e.stopPropagation();
        this.$emit('start-drag', e, item, parent);
      },
      onDragOver(e, item, parent) {
        e.stopPropagation();
        this.$emit('drag-over', e, item, parent);
      },
      onDragLeave(e) {
        e.stopPropagation();
        this.$emit('drag-leave', e);
      },
      onDrop(e, item, parent) {
        e.stopPropagation();
        this.$emit('drop', e, item, parent);
      }
    }
  };
  </script>
  
  <style scoped>
  .child-category {
    transition: all 0.2s ease;
  }
  
  .child-category[draggable="true"]:hover {
    cursor: grab;
  }
  
  .child-category:active {
    cursor: grabbing;
  }
  </style>