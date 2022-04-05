export default {
    initTheme({ commit }) {

        function setColorScheme(scheme) {
            const cachedTheme = localStorage.theme ? localStorage.theme : false;
            if (cachedTheme)
                commit('SET_THEME', cachedTheme)
            else if (scheme)
                commit('SET_THEME', 'dark')
            else
                commit('SET_THEME', 'light')
        }

        function getPreferredColorScheme() {
            if (window.matchMedia) {
                if(window.matchMedia('(prefers-color-scheme: dark)').matches){
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }

        if(window.matchMedia){
            var colorSchemeQuery = window.matchMedia('(prefers-color-scheme: dark)');
            colorSchemeQuery.addEventListener('change', setColorScheme(getPreferredColorScheme()));
        }
    },
    toggleTheme({ commit }) {

        switch (localStorage.theme) {
            case 'light':
                commit('SET_THEME', 'dark')
                break;

            default:
                commit('SET_THEME', 'light')
                break;
        }
    }
  };
