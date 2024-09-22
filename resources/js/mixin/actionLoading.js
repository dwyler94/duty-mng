import { mapState } from "vuex";

export default {
    computed: {
        ...mapState({
            actionLoading: state => state.actionLoader.isLoading
        })
    },
    methods: {
        setActionLoading() {
            this.$store.commit('actionLoader/setLoading');
        },
        unsetActionLoading() {
            this.$store.commit('actionLoader/unsetLoading');
        }
    }
}
