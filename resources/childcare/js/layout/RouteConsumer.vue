<template>
    <div v-if="allowed">
        <parent-topbar v-if="isParent"/>
        <topbar v-else />
        <parent-sidebar v-if="isParent"/>
        <sidebar v-else/>
        <div class="content-wrapper">
            <section class="content">
                <template v-if="allowed">
                    <router-view />
                </template>
            </section>
        </div>
    </div>
</template>
<script>
import ParentTopbar from "./ParentTopbar.vue";
import ParentSidebar from "./ParentSidebar.vue";
import Topbar from "./Topbar.vue";
import Sidebar from "./Sidebar.vue";
import { mapState } from 'vuex';
import { Guards } from '../global/consts';
import { handleSignOut, showError } from '../helpers/error';
import routes from "../router/routes";

export default {
    components: {
        Topbar,
        Sidebar,
        ParentTopbar,
        ParentSidebar
    },
    props: {
        isParent: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        ...mapState({
            roleId: state =>  state.session.info.roleId || Guards.PARENT
        }),
        matchedRoute: function() {
            const resolved = this.$router.resolve(this.$route.path)
            const routeName = resolved.route.name;
            const targetRoutes = this.roleId !== Guards.PARENT ? routes[0] : routes[1]
            return targetRoutes.children.find(({name}) => name === routeName);
        }
    },
    watch: {
        matchedRoute: function(value) {
            this.checkRoute(value);
        }
    },
    data() {
        return {
            allowed: false,
        }
    },
    created() {
        this.checkRoute(this.matchedRoute)
    },
    methods: {
        checkRoute(value) {
            if (!value || !value.meta.guards.includes(this.roleId)) {
                // showError(this.$t("You are not allowed"))
                this.allowed = false;
                handleSignOut();
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

