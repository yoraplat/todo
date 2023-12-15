// import { createApp } from 'vue'
import { createApp } from 'vue/dist/vue.esm-bundler';
import App from './App.vue'
import router from "./router";
import store from "./store";

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSquareCheck } from '@fortawesome/free-solid-svg-icons'
import { faSquareXmark } from '@fortawesome/free-solid-svg-icons'
import { faTrash } from '@fortawesome/free-solid-svg-icons'

library.add(faSquareCheck, faSquareXmark, faTrash)

createApp(App).use(router).component('font-awesome-icon', FontAwesomeIcon).use(store).mount('#app')
