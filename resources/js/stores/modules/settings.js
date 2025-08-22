import api from '../../lib/axios';

export const settings = {
  namespaced: true,
  state: {
    lists: [],
    availableLanguages: [],
  },
  getters: {
    lists: state => state.lists,
    availableLanguages: state => {
      const localizationSettings = state.lists.find(item => item.group === 'localization');
      if (localizationSettings) {
        const availableLangSetting = localizationSettings.settings.find(s => s.key === 'available_languages');
        return availableLangSetting ? JSON.parse(availableLangSetting.value) : [];
      }
      return [];
    },
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
    setAvailableLanguages(state, languages) {
      state.availableLanguages = languages;
    },
  },
  actions: {
    lists({ commit }, payload) {
        return new Promise((resolve, reject) => {
            api.get('/settings', { params: payload })
                .then(response => {
                    commit('lists', response.data);
                    resolve(response);
                })
                .catch(error => {
                    console.error('Error fetching settings:', error);
                    reject(error);
                });
        });
    },
  },
  mutations: {
    lists(state, lists) {
      state.lists = lists;
    },
    setAvailableLanguages(state, languages) {
      state.availableLanguages = languages;
    },
  },
}