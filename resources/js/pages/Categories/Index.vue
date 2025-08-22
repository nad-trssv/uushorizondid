<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Список категорий</h1>
      <router-link    
        :to="{ name: 'admin.categories.create' }" 
        class="bg-white/10 backdrop-blur-md text-gray-700 border border-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg hover:shadow-xl hover:bg-white/20 flex items-center gap-2 w-full sm:w-auto justify-center hover:scale-105 active:scale-95"
      >
        <i class="fas fa-plus"></i>
        <span>Создать категорию</span>
      </router-link>
    </div>

    <!-- Список категорий -->
    <div v-if="loading" class="flex justify-center py-8">
      <i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i>
    </div>

    <div v-else class="space-y-4">
      <!-- Drop zone для перемещения в самый верх -->
      <div 
        class="drop-zone-root"
        @dragover.prevent="onDragOverRoot"
        @dragleave="onDragLeaveRoot"
        @drop.prevent="onDropRoot"
        :class="{ 'drop-target-root': isDraggingOverRoot }"
      >
        Переместить в корень
      </div>

      <!-- Родительские категории -->
      <div 
        v-for="(category, index) in categories"
        :key="category.id"
      >
        <!-- Drop zone перед категорией -->
        <div 
          class="drop-zone-between"
          @dragover.prevent="onDragOverBetween($event, index)"
          @dragleave="onDragLeaveBetween"
          @drop.prevent="onDropBetween($event, index)"
          :class="{ 'drop-target-between': isDraggingOverBetween === index }"
        ></div>

        <!-- Категория -->
        <div 
          class="parent-category"
          draggable="true"
          @dragstart="startDrag($event, category, null)"
          @dragover.prevent="onDragOver($event, category, null)"
          @dragleave="onDragLeave($event)"
          @drop.prevent="onDrop($event, category, null)"
        >
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
              <router-link :to="{ name: 'admin.categories.edit', params: { id: category.id } }" class="p-2 text-green-500 hover:text-green-700"><i class="fas fa-pen"></i></router-link>
              <button class="p-2 text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
            </div>
          </div>
          
          <!-- Вложенные категории -->
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
      </div>

      <!-- Drop zone в самом конце -->
      <div 
        class="drop-zone-root"
        @dragover.prevent="onDragOverRoot"
        @dragleave="onDragLeaveRoot"
        @drop.prevent="onDropRoot"
        :class="{ 'drop-target-root': isDraggingOverRoot }"
      >
        Переместить в корень
      </div>
    </div>
  </div>
</template>
<script>
import CategoryItem from './CategoryItem.vue';

export default {
  name: 'Categories',
  components: {
    CategoryItem
  },
  data() {
    return {
      loading: false,
      expandedCategories: [],
      dragItem: null,
      dragParent: null,
      isDraggingOverRoot: false,
      isDraggingOverBetween: null
    };
  },
  computed: {
    categories() {
      return this.$store.getters['categories/lists'];
    }
  },
  methods: {
    getCategories() {
      this.loading = true;
      this.$store.dispatch('categories/lists')
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    toggleChildren(categoryId) {
      const index = this.expandedCategories.indexOf(categoryId);
      if (index === -1) {
        this.expandedCategories.push(categoryId);
      } else {
        this.expandedCategories.splice(index, 1);
      }
    },
    
    startDrag(event, item, parent = null) {
      event.stopPropagation();
      event.dataTransfer.setData('text/plain', JSON.stringify(item));
      this.dragItem = item;
      this.dragParent = parent;
      event.currentTarget.classList.add('dragging');
      event.dataTransfer.effectAllowed = 'move';
    },
    
    onDragOver(event, targetItem, targetParent = null) {
      event.stopPropagation();
      event.preventDefault();
      
      if (!this.dragItem || !targetItem) return;
      
      const isInvalidDrop = this.dragParent === null && 
                          targetParent !== null && 
                          targetParent.id === this.dragItem.id;
      
      if (isInvalidDrop) {
        event.currentTarget.classList.add('drop-invalid');
        event.dataTransfer.dropEffect = 'none';
      } else {
        event.currentTarget.classList.add('drop-target');
        event.dataTransfer.dropEffect = 'move';
      }
    },
    
    onDragLeave(event) {
      event.stopPropagation();
      event.currentTarget.classList.remove('drop-target', 'drop-invalid');
    },
    
    onDrop(event, targetItem, targetParent = null) {
      event.stopPropagation();
      event.preventDefault();
      
      if (!this.dragItem || !targetItem) {
        event.currentTarget.classList.remove('drop-target', 'drop-invalid');
        return;
      }
      
      const isInvalidDrop = this.dragParent === null && 
                          targetParent !== null && 
                          targetParent.id === this.dragItem.id;
      
      if (isInvalidDrop) {
        event.currentTarget.classList.remove('drop-invalid');
        return;
      }
      
      event.currentTarget.classList.remove('drop-target');
      
      let toParentId = 'root';
      
      if (targetParent) {
        toParentId = targetParent.id;
      } else if (targetItem && targetItem.id) {
        toParentId = targetItem.id;
      }
      
      this.$store.dispatch('categories/moveCategory', {
        categoryId: this.dragItem.id,
        targetId: targetItem.id,
        position: 'change'
      }).then(() => {
        this.getCategories();
      }).catch(error => {
        console.error('Ошибка при перемещении категории:', error);
      });
      
      this.dragItem = null;
      this.dragParent = null;
    },

    onDragOverRoot(event) {
      event.preventDefault();
      this.isDraggingOverRoot = true;
      event.dataTransfer.dropEffect = 'move';
    },

    onDragLeaveRoot() {
      this.isDraggingOverRoot = false;
    },

    onDropRoot(event) {
      event.preventDefault();
      this.isDraggingOverRoot = false;

      if (!this.dragItem) return;

      this.$store.dispatch('categories/moveCategory', {
        categoryId: this.dragItem.id,
        targetId: null,
        position: 'root'
      }).then(() => {
        this.getCategories();
      }).catch(error => {
        console.error('Ошибка при перемещении категории в корень:', error);
      });

      this.dragItem = null;
      this.dragParent = null;
    },

    onDragOverBetween(event, index) {
      event.preventDefault();
      this.isDraggingOverBetween = index;
      event.dataTransfer.dropEffect = 'move';
    },

    onDragLeaveBetween() {
      this.isDraggingOverBetween = null;
    },

    onDropBetween(event, index) {
      event.preventDefault();
      this.isDraggingOverBetween = null;

      if (!this.dragItem) return;
      const targetCategory = this.categories[index];
      
      if (this.dragParent === null) {
        this.$store.dispatch('categories/moveCategory', {
          categoryId: this.dragItem.id,
          targetId: targetCategory.id,
          position: 'before',
          parentId: null 
        }).then(() => {
          this.getCategories();
        }).catch(error => {
          console.error('Ошибка при перемещении категории:', error);
        });
      } else {
        this.$store.dispatch('categories/moveCategory', {
          categoryId: this.dragItem.id,
          targetId: targetCategory.id,
          position: 'before'
        }).then(() => {
          this.getCategories();
        }).catch(error => {
          console.error('Ошибка при перемещении категории:', error);
        });
      }

      this.dragItem = null;
      this.dragParent = null;
    }
  },
  mounted() {
    this.getCategories();
  }
};
</script>
<style scoped>
.parent-category {
  transition: all 0.2s ease;
  position: relative;
}

.parent-category[draggable="true"]:hover {
  cursor: grab;
}

.parent-category:active {
  cursor: grabbing;
}

.dragging {
  opacity: 0.5;
  transform: scale(0.95);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.drop-target {
  position: relative;
  background-color: #dbeafe !important;
  transform: scale(1.02);
  transition: all 0.2s ease;
  z-index: 10;
}

.drop-target::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border: 2px dashed #3b82f6;
  border-radius: 0.5rem;
  pointer-events: none;
}

.drop-invalid {
  position: relative;
  background-color: #fee2e2 !important;
  cursor: not-allowed;
}

.drop-invalid::after {
  content: '⛔ Нельзя переместить в себя';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #ef4444;
  color: white;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 12px;
  white-space: nowrap;
  z-index: 20;
}

.drop-invalid::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border: 2px dashed #ef4444;
  border-radius: 0.5rem;
  pointer-events: none;
}

/* Стили для drop zones */
.drop-zone-root {
  padding: 12px;
  border: 2px dashed transparent;
  border-radius: 8px;
  text-align: center;
  color: #6b7280;
  transition: all 0.2s ease;
  margin: 4px 0;
}

.drop-target-root {
  border-color: #3b82f6;
  background-color: #dbeafe;
  color: #1e40af;
}

.drop-zone-between {
  height: 20px;
  margin: 4px 0;
  border: 2px dashed transparent;
  border-radius: 4px;
  transition: all 0.2s ease;
}

.drop-target-between {
  border-color: #3b82f6;
  background-color: #dbeafe;
}
</style>