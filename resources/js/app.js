require('./bootstrap');

import * as VueGoogleMaps from 'vue2-google-maps'
import GmapCluster from 'vue2-google-maps/dist/components/cluster';

window.Vue = require('vue');

window.Vue.use(VueGoogleMaps, {
    load: {
        key: process.env.MIX_GOOGLE_API_KEY
    }
});

Vue.component('GmapCluster', GmapCluster);

// automatically register components inside components/core
const files = require.context('./components/core', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

const app = new Vue({
    el: '#app'
});