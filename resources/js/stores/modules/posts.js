import { c } from 'naive-ui';
import api from '../../lib/axios';

export const posts = {
  namespaced: true,
  state: {
    lists: [],
    stats: {},
  },
  getters: {
    lists: state => state.lists,
    stats: state => state.stats,
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
    stats(state, stats) {
      state.stats = stats;
    }
  },
  actions: {
    lists({ commit }) {
        return new Promise((resolve, reject) => {
            api.get('/posts')
                .then(response => {
                  console.log('posts 1 -', response);
                    commit('lists', response.data.posts);
                    commit('stats', response.data.stats);
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
    }
  },
}