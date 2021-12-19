export default {
    SET_THEME(state, theme) {
        state.theme = theme;
        localStorage.theme = theme;
    }
};
