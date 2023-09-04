import Vue from 'vue'
import store from './store'
import router from './router'
import i18n from './plugins/i18n'
import App from './components/App'

import vuetify from './vuetify'
import './plugins'
import './components'

Vue.use(require('vue-moment'));

Vue.config.productionTip = false;

new Vue({
    i18n,
    vuetify,
    store,
    router,
    ...App
})