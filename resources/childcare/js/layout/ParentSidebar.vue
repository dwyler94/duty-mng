<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <router-link to="" class="brand-link text-center sidebar-logo">
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
                            'menu-open': '/parent/' + route.path === currentRoute
                        }"
                    >
                        <template v-if="(route.name == 'parent-contact-book' && isAfterAdmission()) || (route.name != 'parent-contact-book')">
                            <router-link v-if="isMobile()"
                                :to="'/parent/' + route.path"
                                class="nav-link"
                                data-widget="pushmenu"
                            >
                                <i :class="'nav-icon ' + route.meta.icon"></i>
                                <p>
                                    {{ route.meta.anchor }}
                                </p>
                            </router-link>
                            <router-link v-else
                                :to="'/parent/' + route.path"
                                class="nav-link"
                            >
                                <i :class="'nav-icon ' + route.meta.icon"></i>
                                <p>
                                    {{ route.meta.anchor }}
                                </p>
                            </router-link>
                        </template>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>
<script>
import moment from 'moment';
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
            session: state => state.session.info
        })
    },
    methods: {
        isMobile() {
            if (window.innerWidth < 768) {
                return true;
            }
            return false;
        },
        isAfterAdmission() {
            if (this.session.admissionDate) {
                if(moment().add(1, "days").format("YYYY-MM-DD") >= this.session.admissionDate) {
                    return true;
                }
            }
            return false;
        }
    },
    mounted() {
        const userRoutes = routes[1];
        this.routes = userRoutes.children.filter(item => item.meta.guards.includes(this.roleId) && item.meta.menu);
    }
};
</script>
