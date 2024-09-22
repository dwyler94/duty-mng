<template>
    <section class="content">
        <template v-if="allowed">
            <router-view />
        </template>
    </section>
</template>
<script>
import { mapState } from 'vuex';
import { handleSignOut, showError } from '../helpers/error';
import routes from "../router/routes";

export default {
    components: {},
    computed: {
        ...mapState({
            session: state =>  state.session.info
        }),
        matchedRoute: function() {
            const resolved = this.$router.resolve(this.$route.path)
            const routeName = resolved.route.name;
            return routes[0].children.find(({name}) => name === routeName);
        }
    },
    watch: {
        matchedRoute: function(value) {
            this.checkRoute(value);
        }
    },
    data() {
        return {
            allowed: false
        }
    },
    mounted() {
        this.checkRoute(this.matchedRoute)
    },
    methods: {
        checkRoute(value) {
            if (!value) return;
            if (!value.meta.guards) return;
            if (!value.meta.guards.includes(this.session.roleId)) {
                showError(this.$t("You are not allowed"))
                this.allowed = false;
                // setTimeout(() => {
                //     handleSignOut();
                // }, 5000);
            } else {
                this.allowed = true;
            }
        }
    }
}
</script>

