import api from '../../lib/axios';

export const services = {
  namespaced: true,
  state: {
    lists: [],
    page: 1,
    pageSize: 10,
    totalPages: 1,
    editService: {},
  },
  getters: {
    lists: state => state.lists,
    page: state => state.page,
    pageSize: state => state.pageSize,
    totalPages: state => state.totalPages,
    editService: state => state.editService,
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
    page(state, page) {
      state.page = page;
    },
    pageSize(state, pageSize) {
      state.pageSize = pageSize;
    },
    totalPages(state, totalPages) {
      state.totalPages = totalPages;
    },  
    editService(state, service) {
      state.editService = service;
    },
  },
  actions: {
    lists({ commit }, payload) {
        return new Promise((resolve, reject) => {
            api.get('/services', { params: payload })
                .then(response => {
                    commit('lists', response.data.paginated.data);
                    commit('page', response.data.paginated.current_page);
                    commit('pageSize', response.data.paginated.perPage);
                    commit('totalPages', response.data.paginated.last_page);
                    resolve(response);
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                    reject(error);
                });
        });
    },
    getById({ commit }, id) {
      return new Promise((resolve, reject) => {
        api.get(`/services/${id}`)
          .then(response => {
            commit('editService', response.data.data);
            resolve(response.data);
          })
          .catch(error => {
            console.error('Error fetching service by ID:', error);
            reject(error);
          });
      });
    },
    create({ commit }, payload) {
      return new Promise((resolve, reject) => {
        api.post('/services', payload)
          .then(response => {
            commit('ADD_SERVICE', response.data);
            resolve(response.data);
          })
          .catch(error => {
            console.error('Error creating service:', error);
            reject(error);
          });
      });
    },
    toggleStatus({ commit }, payload) {
      return new Promise((resolve, reject) => {
        api.put(`/services/${payload.id}/toggle-status`)
          .then(response => {
            commit('UPDATE_SERVICE', response.data);
            resolve(response.data);
          })
          .catch(error => {
            console.error('Error updating service:', error);
            reject(error);
          });
      });
    }, 
    update({ commit }, payload) {
      return new Promise((resolve, reject) => {
        api.put(`/services/${payload.id}`, payload.data)
          .then(response => {
            commit('UPDATE_SERVICE', response.data);
            resolve(response.data);
          })
          .catch(error => {
            console.error('Error updating service:', error);
            reject(error);
          });
      });
    },
    delete({ commit }, id) {
      return new Promise((resolve, reject) => {
        api.delete(`/services/${id}`)
          .then(response => {
            commit('UPDATE_SERVICE', response.data);
            resolve(response.data);
          })
          .catch(error => {
            console.error('Error deleting service:', error);
            reject(error);
          });
      });
    },
    addMaster({ commit }, payload) {
      return new Promise((resolve, reject) => {
        api.post(`/services/${payload.serviceId}/add-master`, payload)
          .then(response => {
            commit('UPDATE_SERVICE', response.data);
            resolve(response.data);
          })
          .catch(error => {
            console.error('Error adding master to service:', error);
            reject(error);
          });
      });
    },
    removeMaster({commit}, payload) {
      return new Promise((resolve, reject) => {
        api.post(`/services/${payload.serviceId}/remove-master`, payload)
          .then(response => {
            commit('UPDATE_SERVICE', response.data);
            resolve(response.data);
          })
          .catch(error => {
            console.error('Error adding master to service:', error);
            reject(error);
          });
      });
    }
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
    page(state, page) {
      state.page = page;
    },
    pageSize(state, pageSize) {
      state.pageSize = pageSize;
    },
    totalPages(state,  totalPages) {
      state.totalPages =  totalPages;
    },
    ADD_SERVICE(state, service) {
      state.lists.unshift(service);
    },
    UPDATE_SERVICE(state, updatedService) {
      const index = state.lists.findIndex(s => s.id === updatedService.id);
      if (index !== -1) {
        state.lists.splice(index, 1, updatedService);
      }
    },
    editService(state, service) {
      state.editService = service;
    },
  },
}