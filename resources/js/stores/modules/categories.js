import api from '../../lib/axios';

export const categories = {
  namespaced: true,
  state: {
    lists: [],
  },
  getters: {
    lists: state => state.lists,
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
  },
  actions: {
    lists({ commit }) {
        return new Promise((resolve, reject) => {
            api.get('/categories')
                .then(response => {
                    commit('lists', response.data.data);
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
        api.get(`/categories/${id}`)
          .then(response => {
            commit('lists', response.data.data);
            resolve(response);
          })
          .catch(error => {
            console.error('Error fetching category by ID:', error);
            reject(error);
          });
      });
    },
    create({ commit }, category) {
      console.log('Creating category:', category.data);
      
      try {
        return new Promise((resolve, reject) => {
          api.post('/categories', category.data)
            .then(response => {
              commit('lists', response.data.data);
              resolve(response);
            })
            .catch(error => {
              console.error('Error creating category:', error);
              reject(error);
            });
        });
      } catch (error) {
        throw error;
      }
    },
    moveCategory({ commit }, { categoryId, targetId, position, parentId }) {
      try {
        return new Promise((resolve, reject) => {
          api.post('/categories/move', {
            categoryId,
            targetId,
            position,
            parentId
          })
          .then(response => {
            commit('lists', response.data.data);
            resolve(response);
          })
          .catch(error => {
            console.error('Error moving category:', error);
            reject(error);
          });
        });
      } catch (error) {
        throw error;
      }
    }
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
  },
}