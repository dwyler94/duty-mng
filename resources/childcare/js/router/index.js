import VueRouter from "vue-router";
import routes from "./routes";
import store from '../store';
import { showError } from "../helpers/error";

const router = new VueRouter({
    mode: 'history',
    routes,
    base: '/child',
    scrollBehavior: to => {
        if (to.hash) {
            return {selector: to.hash}
        } else {
            return {x: 0, y: 0}
        }
    },
});

export default router;
