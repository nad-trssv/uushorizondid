import api from '../../lib/axios';

export const events = {
  namespaced: true,
  state: {
    lists: [],
    stats: {},
    page: 1,
    perPage: 18,
    totalPages: 1,
    searchQuery: '',
    sortBy: 'start_time',
    sortDirection: 'desc',
    statusFilter: '',
    dateFrom: '',
    dateTo: '',
    editEvent: null,
  },
  getters: {
    lists: state => state.lists,
    stats: state => state.stats,
    page: state => state.page,
    perPage: state => state.perPage,
    totalPages: state => state.totalPages,
    searchQuery: state => state.searchQuery,
    sortBy: state => state.sortBy,
    sortDirection: state => state.sortDirection,
    statusFilter: state => state.statusFilter,
    dateFrom: state => state.dateFrom,
    dateTo: state => state.dateTo,
    editEvent: state => state.editEvent,
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
    stats(state, stats) {
      state.stats = stats;
    },
    page(state, page) {
      state.page = page;
    },
    perPage(state, perPage) {
      state.perPage = perPage;
    },
    totalPages(state, totalPages) {
      state.totalPages = totalPages;
    },
    setSearchQuery(state, query) {
      state.searchQuery = query;
    },
    setSortBy(state, sortBy) {
      state.sortBy = sortBy;
    },
    setSortDirection(state, direction) {
      state.sortDirection = direction;
    },
    setStatusFilter(state, status) {
      state.statusFilter = status;
    },
    setDateFrom(state, date) {
      state.dateFrom = date;
    },
    setDateTo(state, date) {
      state.dateTo = date;
    },
    setEditEvent(state, event) {
      state.editEvent = event;
    }
  },
  actions: {
    lists({ commit, state }, payload) {
        const params = {
          page: state.page,
          perPage: state.perPage,
          search: state.searchQuery,
          sort_by: state.sortBy,
          sort_direction: state.sortDirection,
          status: state.statusFilter,
          date_from: state.dateFrom,
          date_to: state.dateTo,
          ...payload
        };

        return new Promise((resolve, reject) => {
            api.get('/events', { params })
                .then(response => {
                    commit('lists', response.data.events.data);
                    commit('page', response.data.events.current_page);
                    commit('perPage', response.data.events.per_page);
                    commit('totalPages', response.data.events.last_page);
                    resolve(response);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    reject(error);
                });
        });
    },
    updateSearchQuery({ commit, dispatch }, query) {
      commit('setSearchQuery', query);
      dispatch('lists', { page: 1 }); 
    },
    updateSort({ commit, dispatch }, { sortBy, sortDirection }) {
      commit('setSortBy', sortBy);
      commit('setSortDirection', sortDirection);
      dispatch('lists', { page: 1 });
    },
    updateStatusFilter({ commit, dispatch }, status) {
      commit('setStatusFilter', status);
      dispatch('lists', { page: 1 });
    },
    updateDateFilter({ commit, dispatch }, { dateFrom, dateTo }) {
      commit('setDateFrom', dateFrom);
      commit('setDateTo', dateTo);
      dispatch('lists', { page: 1 });
    },
    stats({ commit }, payload) {
      return new Promise((resolve, reject) => {
          api.get('/events/stats', { params: payload })
              .then(response => {
                  commit('stats', response.data.stats);
                  resolve(response);
              })
              .catch(error => {
                  console.error('Error fetching events stats:', error);
                  reject(error);
              });
      });
    },
    getById({ commit }, params) {
      return new Promise((resolve, reject) => {
          api.get(`/events/${params.id}`)
              .then(response => {
                commit('setEditEvent', response.data.event);
                resolve(response);
              })
              .catch(error => {
                  console.error('Error fetching event by ID:', error);
                  reject(error);
              });
      });
    },
    store({ commit }, data) {
      console.log('actions store', data.data);
      
      return new Promise((resolve, reject) => {
          api.post('/events', data.data)
              .then(response => {
                resolve(response);
              })
              .catch(error => {
                  console.error('Error creating event:', error);
                  reject(error);
              });
      });
    },
    update({ commit }, { id, data }) {
      return new Promise((resolve, reject) => {
          api.put(`/events/${id}`, data)
              .then(response => {
                resolve(response);
              })
              .catch(error => {
                  console.error('Error updating event:', error);
                  reject(error);
              });
      });
    },
    delete({ commit }, id) {
      return new Promise((resolve, reject) => {
          api.delete(`/events/${id}`)
              .then(response => {
                resolve(response);
              })
              .catch(error => {
                  console.error('Error deleting event:', error);
                  reject(error);
              });
      });
    },
  },
}