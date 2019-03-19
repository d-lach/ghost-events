require('./bootstrap');

import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster';
import BootstrapVue from 'bootstrap-vue';
import Formatting from '~/Utilities/Formatting';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

window.Vue = require('vue');

window.Vue.use(VueGoogleMaps, {
    load: {
        key: process.env.MIX_GOOGLE_API_KEY
    }
});

Vue.filter('standardDate', Formatting.relativeDate);
Vue.filter('text', Formatting.sentenceCase);
Vue.filter('name', Formatting.titleCase);

Vue.use(BootstrapVue);
Vue.component('GmapCluster', GmapCluster);

// automatically register components inside components/core
const files = require.context('./components/core', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
/*

window.$getUserId = () => {
    if (!window.$userId)
        window.$userId = parseInt(document.querySelector("meta[name='user-id']").getAttribute('content'));
    return window.$userId;
};
*/

const app = new Vue({
    el: '#app'
});

