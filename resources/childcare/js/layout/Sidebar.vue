<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <router-link to="/" class="brand-link text-center sidebar-logo">
            <span class="brand-text font-weight-bolder">Eeeeeさぽ</span>
        </router-link>
        <div class="sidebar">
            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    role="menu"
                    data-accordion="false"
                    v-if="routes.length > 0"
                >
                    <li
                        class="nav-item"
                        v-for="(route, index) in routes"
                        :key="index"
                        :class="{
                            'menu-open': '/' + route.path === currentRoute
                        }"
                    >
                        <router-link
                            :to="'/' + route.path"
                            class="nav-link"
                        >
                            <i :class="'nav-icon ' + route.meta.icon"></i>
                            <p>
                                {{ route.meta.anchor }}
                            </p>
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>
<script>
import { mapState } from 'vuex';
import { Guards } from '../global/consts';
import routes from "../router/routes";

export default {
    data() {
        return {
            routes: [],
        };
    },
    computed: {
        currentRoute() {
            const route = this.$route.path;
            return route;
        },
        ...mapState({
            roleId: state =>  state.session.info.roleId || Guards.PARENT,
            office: state => state.session.info.office
        })
    },
    methods: {
    },
    mounted() {
        const userRoutes = routes[0];
        this.routes = userRoutes.children.filter((item) => {
            if(item.meta.businessTypeBranch && this.roleId != Guards.ADMIN && this.office.businessTypeId != 1){
                return false;
            }
                return item.meta.guards.includes(this.roleId) && item.meta.menu;
        });
    }
};
</script>
