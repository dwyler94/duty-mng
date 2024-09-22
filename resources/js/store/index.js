import Vue from 'vue';
import Vuex from 'vuex';
import session from './session';
import constants from './constants';
import actionLoader from './actionLoader';

Vue.use(Vuex);

export default new Vuex.Store({
    namespaced: true,
    modules: {
        session,
        constants,
        actionLoader
    }
})
