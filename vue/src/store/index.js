import Vuex from "vuex";

import * as auth from "@/store/modules/Auth";


export default new Vuex.Store({
  strict: true,

  modules: {
    auth,
  },
});