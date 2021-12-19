import getters from "./getters";
import actions from "./actions";
import mutations from "./mutations";

const getDefaultState = () => {
    return {
        theme: {}
    }
}

const state = () => getDefaultState()

export default {
  state,
  getters,
  actions,
  mutations,
  modules: {
    sub: {
      actions: {
        logout() {
          this.reset()
        }
      }
    }
  }
};
