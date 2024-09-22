<template>
    <div class="wrapper">
        <route-consumer  v-if="session"></route-consumer>
        <loading :active="!session || actionLoading" color="#007BFF"></loading>
    </div>
</template>
<script>
import { mapState } from 'vuex';
import LocalStorage from '../helpers/localStorage';
import Loading from 'vue-loading-overlay';
import { handleSignOut } from '../helpers/error';
import actionLoading from '../mixin/actionLoading';
import RouteConsumer from './RouteConsumer.vue';

export default {
    mixins: [actionLoading],
    components: {
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
