import api, { apiErrorHandler } from "../global/api"

export default {
    namespaced: true,
    state: {
        info: null
    },
    getters: {
        session(state) {
            return state.info
        }
    },
    mutations: {
        setSession(state, payload) {
            state.info = payload
        }
    },
    actions: {
        fetchSession(context) {
            api.get('me')
                .then(response => {
                    context.commit('setSession', response);
                })
                .catch(e => {
                    apiErrorHandler(e);
                })
        }
    }
}
