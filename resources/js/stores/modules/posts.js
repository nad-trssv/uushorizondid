import { c } from 'naive-ui';
import api from '../../lib/axios';

export const posts = {
  namespaced: true,
  state: {
    lists: [],
    stats: {},
    page: 1,
    perPage: 10,
    totalPages: 1,
  },
  getters: {
    lists: state => state.lists,
    stats: state => state.stats,
    page: state => state.page,
    perPage: state => state.perPage,
    totalPages: state => state.totalPages,
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
  },
  actions: {
    lists({ commit }, payload) {
        return new Promise((resolve, reject) => {
            api.get('/posts', { params: payload })
                .then(response => {
                    commit('lists', response.data.posts.data);
                    commit('stats', response.data.stats);
                    commit('page', response.data.posts.current_page);
                    commit('perPage', response.data.posts.per_page);
                    commit('totalPages', response.data.posts.last_page);
                    resolve(response);
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                    reject(error);
                });
        });
    },
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
  },
}