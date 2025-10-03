// src/store/modules/events.js
import api from '../../lib/axios';

export const events = {
  namespaced: true,

  state: {
    lists: [],
    stats: {},
    editEvent: null,
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0
    }
  },

  getters: {
    lists: (state) => state.lists,
    stats: (state) => state.stats,
    editEvent: (state) => state.editEvent,
    pagination: (state) => state.pagination,
    totalPages: (state) => state.pagination?.last_page || 1
  },

  mutations: {
    setLists(state, items) {
      state.lists = Array.isArray(items) ? items : [];
    },
    setStats(state, stats) {
      state.stats = stats || {};
    },
    setEditEvent(state, event) {
      state.editEvent = event || null;
    },
    setPagination(state, meta) {
      state.pagination = {
        current_page: meta?.current_page ?? 1,
        last_page: meta?.last_page ?? 1,
        per_page: meta?.per_page ?? 10,
        total: meta?.total ?? 0
      };
    }
  },

  actions: {
    // Список мероприятий
    lists({ commit }, params = {}) {
      return new Promise((resolve, reject) => {
        api
          .get('/events', { params })
          .then((response) => {
            // ожидаем { events: { data, current_page, last_page, per_page, total } }
            const payload = response?.data?.events || {};
            const items = payload?.data || [];
            commit('setLists', items);
            commit('setPagination', {
              current_page: payload.current_page,
              last_page: payload.last_page,
              per_page: payload.per_page,
              total: payload.total
            });
            resolve(response);
          })
          .catch((error) => {
            console.error('Error fetching events:', error);
            reject(error);
          });
      });
    },
    getAll({ commit }) {
      return new Promise((resolve, reject) => {
        api
          .get('/events/calendar')
          .then((response) => {
            const items = response?.data?.events || [];
            commit('setLists', items);
            resolve(response);
          })
          .catch((error) => {
            console.error('Error fetching all events:', error);
            reject(error);
          });
      });
    },
    // Статистика
    stats({ commit }) {
      return new Promise((resolve, reject) => {
        api
          .get('/events/stats')
          .then((response) => {
            commit('setStats', response?.data?.stats || {});
            resolve(response);
          })
          .catch((error) => {
            console.error('Error fetching events stats:', error);
            reject(error);
          });
      });
    },

    // Получить по ID
    getById({ commit }, { id }) {
      return new Promise((resolve, reject) => {
        api
          .get(`/events/${id}`)
          .then((response) => {
            commit('setEditEvent', response?.data?.event || null);
            resolve(response);
          })
          .catch((error) => {
            console.error('Error fetching event by ID:', error);
            reject(error);
          });
      });
    },

    // Создать
    store(context, { data }) {
      return new Promise((resolve, reject) => {
        api
          .post('/events', data)
          .then((response) => resolve(response))
          .catch((error) => {
            console.error('Error creating event:', error);
            reject(error);
          });
      });
    },

    // Обновить
    update(context, { id, data }) {
      return new Promise((resolve, reject) => {
        api
          .put(`/events/${id}`, data)
          .then((response) => resolve(response))
          .catch((error) => {
            console.error('Error updating event:', error);
            reject(error);
          });
      });
    },

    // Удалить
    delete(context, id) {
      return new Promise((resolve, reject) => {
        api
          .delete(`/events/${id}`)
          .then((response) => resolve(response))
          .catch((error) => {
            console.error('Error deleting event:', error);
            reject(error);
          });
      });
    },
    uploadImage(_, { formData, onProgress }) {
       return api.post('/events/upload-image', formData, {
         headers: { 'Content-Type': 'multipart/form-data' },
         onUploadProgress: (e) => {
           if (onProgress && e.total) {
             onProgress({ percent: Math.round((e.loaded * 100) / e.total) });
           }
         }
       });
    },
    // МУЛЬТИЗАГРУЗКА галереи
    uploadGallery({ }, { eventId, files, onProgress }) {
      const fd = new FormData();
      Array.from(files).forEach((f) => fd.append('files[]', f));
      return api.post(`/events/${eventId}/gallery`, fd, {
        headers: { 'Content-Type': 'multipart/form-data' },
        onUploadProgress: (e) => {
          if (onProgress && e.total) {
            onProgress({ percent: Math.round((e.loaded * 100) / e.total) });
          }
        }
      });
    },

    // Обновить alt
    updateGalleryAlt({ }, { eventId, galleryId, alt }) {
      return api.put(`/events/${eventId}/gallery/${galleryId}`, { alt });
    },

    // Удалить фото
    deleteGalleryItem({ }, { eventId, galleryId }) {
      return api.delete(`/events/${eventId}/gallery/${galleryId}`);
    },

  }
};
