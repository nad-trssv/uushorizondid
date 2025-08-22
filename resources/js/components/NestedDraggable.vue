<template>
    <div class="nested-categories">
      <draggable
        v-model="localCategories"
        group="categories"
        item-key="id"
        handle=".drag-handle"
        @change="handleDragChange"
      >
        <template #item="{ element }">
          <n-card class="category-card" hoverable>
            <template #header>
              <div class="category-header">
                <div class="drag-handle" style="cursor: move;">
                  <i class="fas fa-arrows-alt mr-2"></i>
                </div>
                <h3 class="category-title">
                  <i class="fas fa-folder text-yellow-500 mr-2"></i>
                  {{ element.name }}
                </h3>
                <div class="category-actions">
                  <n-button quaternary circle @click="$emit('edit', element)">
                    <i class="fas fa-pencil-alt text-blue-500"></i>
                  </n-button>
                  <n-button quaternary circle @click="$emit('delete', element)">
                    <i class="fas fa-trash-alt text-red-500"></i>
                  </n-button>
                  <n-button quaternary circle @click="$emit('add-subcategory', element)">
                    <i class="fas fa-plus-circle text-green-500"></i>
                  </n-button>
                </div>
              </div>
            </template>
  
            <div v-if="element.children && element.children.length" class="subcategories-container">
              <nested-draggable
                :categories="element.children"
                @change="$emit('change', $event)"
                @edit="$emit('edit-subcategory', $event)"
                @delete="$emit('delete-subcategory', $event)"
                @add-subcategory="$emit('add-subcategory', $event)"
              />
            </div>
          </n-card>
        </template>
      </draggable>
    </div>
  </template>
  
  <script>
  import draggable from 'vuedraggable';
  
  export default {
    name: 'NestedDraggable',
    components: { draggable },
    props: {
      categories: {
        type: Array,
        required: true
      }
    },
    data() {
      return {
        localCategories: [...this.categories]
      };
    },
    watch: {
      categories(newVal) {
        this.localCategories = [...newVal];
      }
    },
    methods: {
      handleDragChange(evt) {
        if (evt.moved) {
          this.$emit('change', {
            movedNode: evt.moved.element,
            targetNode: this.findParent(evt.moved.newIndex),
            placement: 'inside' // или 'before', 'after' в зависимости от позиции
          });
        }
      },
      findParent(index) {
        // Логика определения родительской категории
        // Это упрощенная версия, нужно доработать
        return this.localCategories[index + 1] || null;
      }
    }
  };
  </script>
  
  <style scoped>
  .nested-categories {
    margin-left: 20px;
  }
  
  .category-card {
    margin-bottom: 10px;
  }
  
  .category-header {
    display: flex;
    align-items: center;
  }
  
  .category-title {
    flex-grow: 1;
    margin: 0;
  }
  
  .category-actions {
    display: flex;
    gap: 5px;
  }
  
  .subcategories-container {
    margin-left: 30px;
    margin-top: 10px;
  }
  </style>