import api from '../../lib/axios';

export const posts = {
  namespaced: true,
  state: {
    lists: [],
    stats: {},
    page: 1,
    perPage: 18,
    totalPages: 1,
    searchQuery: '',
    sortBy: 'created_at',
    sortDirection: 'desc',
    statusFilter: 'all',
    dateFrom: '',
    dateTo: '',
    editPost: null,
  },
  getters: {
    lists: s => s.lists,
    stats: s => s.stats,
    page: s => s.page,
    perPage: s => s.perPage,
    totalPages: s => s.totalPages,
    searchQuery: s => s.searchQuery,
    sortBy: s => s.sortBy,
    sortDirection: s => s.sortDirection,
    statusFilter: s => s.statusFilter,
    dateFrom: s => s.dateFrom,
    dateTo: s => s.dateTo,
    editPost: s => s.editPost,
  },
  mutations: {
    lists(s, v){ s.lists = v; },
    stats(s, v){ s.stats = v; },
    page(s, v){ s.page = v; },
    perPage(s, v){ s.perPage = v; },
    totalPages(s, v){ s.totalPages = v; },
    setSearchQuery(s, v){ s.searchQuery = v; },
    setSortBy(s, v){ s.sortBy = v; },
    setSortDirection(s, v){ s.sortDirection = v; },
    setStatusFilter(s, v){ s.statusFilter = v; },
    setDateFrom(s, v){ s.dateFrom = v; },
    setDateTo(s, v){ s.dateTo = v; },
    setEditPost(s, v){ s.editPost = v; },
    resetEditPost(state) {
      state.editPost = null;
    },
  },
  actions: {
    lists({ commit, state }, payload = {}) {
      const params = {
        page: state.page,
        per_page: state.perPage,
        search: state.searchQuery,
        sort_by: state.sortBy,
        sort_direction: state.sortDirection,
        status: state.statusFilter,
        date_from: state.dateFrom,
        date_to: state.dateTo,
        ...payload
      };
      return api.get('/posts', { params }).then(res => {
        const p = res.data.posts;
        commit('lists', p.data);
        commit('page', p.current_page);
        commit('perPage', p.per_page);
        commit('totalPages', p.last_page);
        return res;
      });
    },
    stats({ commit }, payload) {
      return api.get('/posts/stats', { params: payload }).then(res => {
        commit('stats', res.data.stats); return res;
      });
    },
    show({ commit }, id) {
      return api.get(`/posts/${id}`).then(res => {
        commit('setEditPost', res.data.post); return res;
      });
    },
    store(_, { data }) {
      return api.post('/posts', data);
    },
    update(_, { id, data }) {
      return api.put(`/posts/${id}`, data);
    },
    delete(_, id) {
      return api.delete(`/posts/${id}`);
    },
    uploadImage(_, { formData, onProgress }) {
      return api.post('/posts/upload-image', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        onUploadProgress: e => {
          if (onProgress && e.total) {
            onProgress({ percent: Math.round((e.loaded * 100) / e.total) });
          }
        }
      });
    },

    // хелперы для фильтров/сортировки
    updateSearchQuery({ commit, dispatch }, query) {
      commit('setSearchQuery', query); return dispatch('lists', { page: 1 });
    },
    updateSort({ commit, dispatch }, { sortBy, sortDirection }) {
      commit('setSortBy', sortBy); commit('setSortDirection', sortDirection);
      return dispatch('lists', { page: 1 });
    },
    updateStatusFilter({ commit, dispatch }, status) {
      commit('setStatusFilter', status); return dispatch('lists', { page: 1 });
    },
    updateDateFilter({ commit, dispatch }, { dateFrom, dateTo }) {
      commit('setDateFrom', dateFrom); commit('setDateTo', dateTo);
      return dispatch('lists', { page: 1 });
    },
  },
};
