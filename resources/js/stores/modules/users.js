import api from '../../lib/axios';

export const users = {
  namespaced: true,
  state: {
    masters: [],
  },
  getters: {
    masters: state => state.masters,
  },
  mutations: {
    masters(state, masters) {
      state.masters = masters;
    },
  },
  actions: {
    getMasters({ commit }) {
        return new Promise((resolve, reject) => {
            api.get('/users/masters')
                .then(response => {
                    commit('masters', response.data);
                    resolve(response);
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                    reject(error);
                });
        });
    },
  },
  mutations: {
    masters(state, masters) {
      state.masters = masters;
    },
  },
}