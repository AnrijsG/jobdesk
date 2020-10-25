import * as authTypes from './auth.types';
import {UserModel} from "../models/user.model";

const auth = {
    namespaced: true,
    state: {
        /** @type {UserModel|null} */
        currentUser: null,
    },
    actions: {
        async [authTypes.ACTION_GET_CURRENT_USER](store) {
            try {
                const user = await axios.post('/auth/get-user');
                if (!user.data) {
                    store.commit(authTypes.SET_CURRENT_USER, null);

                    return;
                }

                const currentUser = UserModel.fromArray(user.data);

                store.commit(authTypes.SET_CURRENT_USER, currentUser);
            } catch {}
        },
    },
    mutations: {
        [authTypes.SET_CURRENT_USER](state, currentUser) {
            state.currentUser = currentUser
        },
    },
    getters: {
        [authTypes.GET_CURRENT_USER](state) {
            return state.currentUser;
        },
    },
};

export default auth;
