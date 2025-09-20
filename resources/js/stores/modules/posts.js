import api from '../../lib/axios';

export const posts = {
  namespaced: true,
  state: {
    lists: [],
    page: 1,
    pageSize: 20,
    totalPages: 1,
  },
  getters: {
    lists: state => state.lists,
    page: state => state.page,
    pageSize: state => state.pageSize,
    totalPages: state => state.totalPages,
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
  },
  actions: {
    lists({ commit }) {
        return new Promise((resolve, reject) => {
            api.get('/posts')
                .then(response => {
                  console.log('posts 1 -', response);
                    commit('lists', response.data.data);
                    commit('page', response.data.current_page);
                    commit('pageSize', response.data.perPage);
                    commit('totalPages', response.data.last_page);
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
    page(state, page) {
      state.page = page;
    },
    pageSize(state, pageSize) {
      state.pageSize = pageSize;
    },
    totalPages(state,  totalPages) {
      state.totalPages =  totalPages;
    },
  },
}