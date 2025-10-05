<template>
  <div class="rich-editor">
    <QuillEditor
      v-model:content="localContent"
      contentType="html"
      :options="editorOptions"
      @update:content="$emit('update:modelValue', $event)"
    />
  </div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

export default {
  name: 'RichEditor',
  components: { QuillEditor },
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Start writing...'
    },
    height: {
      type: Number,
      default: 400
    }
  },
  emits: ['update:modelValue'],
  data() {
    return {
      localContent: this.modelValue,
      editorOptions: {
        modules: {
          toolbar: {
            container: [
              [{ 'header': [1, 2, 3, false] }],
              ['bold', 'italic', 'underline', 'strike'],
              [{ 'color': [] }, { 'background': [] }],
              [{ 'align': [] }],
              ['blockquote', 'code-block'],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              ['link'], // Только ссылка, без image и video
              ['clean']
            ]
          }
        },
        theme: 'snow',
        placeholder: this.placeholder
      }
    }
  },
  watch: {
    modelValue(newVal) {
      if (newVal !== this.localContent) {
        this.localContent = newVal
      }
    }
  }
}
</script>

<style scoped>
.rich-editor {
  width: 100%;
}

:deep(.ql-editor) {
  min-height: v-bind(height + 'px');
  font-size: 16px;
  font-family: inherit;
}

:deep(.ql-toolbar.ql-snow) {
  border-top: 1px solid #ccc;
  border-left: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-bottom: none;
  border-radius: 8px 8px 0 0;
}

:deep(.ql-container.ql-snow) {
  border-bottom: 1px solid #ccc;
  border-left: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-top: none;
  border-radius: 0 0 8px 8px;
}

/* Стили для ссылок */
:deep(.ql-editor a) {
  color: #2563eb;
  text-decoration: underline;
}

:deep(.ql-editor a:hover) {
  color: #1d4ed8;
}
</style>