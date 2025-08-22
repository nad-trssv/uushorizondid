import api from '../../lib/axios';

export const appointments = {
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
            api.get('/appointments')
                .then(response => {
                  console.log('Fetched appointments:', response.data);
                  
                    commit('lists', response.data);
                    resolve(response);
                })
                .catch(error => {
                    console.error('Error fetching appointments:', error);
                    reject(error);
                });
        });
    },
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
  },
}