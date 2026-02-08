import api from '../../lib/axios';

export default {
  namespaced: true,
  state: {
    authStatus: false,
    authToken: null,
    authInfo: {},
  },
  getters: {
    authStatus: state => state.authStatus,
    authToken: state => state.authToken,
    authInfo: state => state.authInfo,
  },
  mutations: {
    setAuthStatus(state, status) {
      state.authStatus = status;
    },
    setAuthToken(state, token) {
      state.authToken = token;
    },
  },
  actions: {
    getAccessToken({ commit }) {
        const auth = JSON.parse(localStorage.getItem('auth'));
        if (auth && auth.access_token) {
            commit('setAuthToken', auth.access_token);
            commit('setAuthStatus', true);
        }
    },
    getAuthInfo({ commit }) {
      return new Promise((resolve, reject) => {
        api.post('/auth/me')
          .then(response => {
            
            const user = response.data;
            commit('setAuthInfo', user);
            resolve(user);
          })
          .catch(error => {
            console.error('Error fetching auth info:', error);
            commit('clearAuthData');
            reject(error);
          });
      });
    },
    login({ commit }, credentials) {
      return axios.post('/api/v1/auth/login', credentials)
        .then(response => {
          const data = {
            user: response.data.user,
            access_token: response.data.access_token,
          };
          localStorage.setItem('auth', JSON.stringify(data));
          commit('setAuthToken', data.access_token);
          commit('setAuthStatus', true);
          commit('setAuthInfo', data.user);
        });
    },
    logout({ commit }) {
      return new Promise((resolve, reject) => {
        api.post('/auth/logout')
          .then(() => {
            commit('setAuthStatus', false);
            commit('setAuthToken', null);
            commit('clearAuthData');
            localStorage.removeItem('auth');
            resolve();
          })
          .catch(error => {
            console.error('Logout error:', error);
            reject(error);
          });
      });
    },
  },
  mutations: {
    setAuthToken(state, token) {
      state.authToken = token;
    },
    setAuthStatus(state, status) {
      state.authStatus = status;
    },
    setAuthInfo(state, user) {
      state.authInfo = user;
    },
    clearAuthData(state) {
      state.authInfo = {};
      state.authMenu = [];
    },
  },
};
