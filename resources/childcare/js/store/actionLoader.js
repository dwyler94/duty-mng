export default {
    namespaced: true,
    state: {
        isLoading: false
    },
    getters: {
        isLoading(state) {
            return state.isLoading;
        }
    },
    mutations: {
        setLoading(state) {
            state.isLoading = true;
        },
        unsetLoading(state) {
            state.isLoading = false;
        }
    }
}
