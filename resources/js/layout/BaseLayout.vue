<template>
    <div class="wrapper">
        <template v-if="session">
            <topbar />
            <sidebar />
            <div class="content-wrapper">
                <route-consumer></route-consumer>
            </div>
        </template>
        <loading :active="!session || actionLoading" color="#007BFF"></loading>
    </div>
</template>
<script>
import Topbar from "./Topbar.vue";
import Sidebar from "./Sidebar.vue";
import { mapState } from 'vuex';
import LocalStorage from '../helpers/localStorage';
import Loading from 'vue-loading-overlay';
import { handleSignOut } from '../helpers/error';
import actionLoading from '../mixin/actionLoading';
import RouteConsumer from './RouteConsumer.vue';

export default {
    mixins: [actionLoading],
    components: {
        Topbar,
        Sidebar,
        Loading,
        RouteConsumer,
    },
    computed: mapState({
        session: state =>  state.session.info,
    }),
    mounted() {
        const token = LocalStorage.getToken();
        if (token && !this.session) {
            this.$store.dispatch('session/fetchSession');
        }
        if (!token) {
            handleSignOut();
            return;
        }
    }
};
</script>
