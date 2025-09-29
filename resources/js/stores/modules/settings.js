import api from '../../lib/axios';

export const settings = {
  namespaced: true,
  state: {
    lists: [],
    availableLanguages: [],
    languagesV2: [],
  },
  getters: {
    lists: state => state.lists,
    languagesV2: state => state.languagesV2,
    availableLanguages: state => {
      const localizationSettings = state.lists.find(item => item.group === 'localization');
      if (localizationSettings) {
        const availableLangSetting = localizationSettings.settings.find(s => s.key === 'available_languages');
        return availableLangSetting ? JSON.parse(availableLangSetting.value) : [];
      }
      return [];
    },
  },
  actions: {
    lists({ commit }, payload) {
        return new Promise((resolve, reject) => {
            api.get('/settings', { params: payload })
                .then(response => {
                    commit('lists', response.data.settings);
                    commit('languagesV2', response.data.languages);
                    
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
    languagesV2(state, languages) {
      state.languagesV2 = languages;
    },
    setAvailableLanguages(state, languages) {
      state.availableLanguages = languages;
    },
  },
}