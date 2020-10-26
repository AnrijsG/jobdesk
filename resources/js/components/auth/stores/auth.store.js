import * as authTypes from './auth.types';
import {UserModel} from "../models/user.model";
import {EnvironmentModel} from "../models/environment.model";

const auth = {
    namespaced: true,
    state: {
        /** @type {UserModel|null} */
        currentUser: null,
        canAccessDashboard: false,
    },
    actions: {
        async [authTypes.ACTION_GET_CURRENT_USER](store) {
            const response = await axios.post('/auth/get-user');

            if (!response.data) {
                store.commit(authTypes.SET_CURRENT_USER, null);

                return;
            }

            const currentUser = UserModel.fromArray(response.data);
            store.commit(authTypes.SET_CURRENT_USER, currentUser);

            const userRole = currentUser.environment.role;
            const canAccessDashboard = userRole === EnvironmentModel.ROLE_ADVERTISER || userRole === EnvironmentModel.ROLE_ADMIN;

            store.commit(authTypes.SET_CAN_ACCESS_DASHBOARD, canAccessDashboard);
        },
        /**
         * @param store
         * @param credentials
         * @returns {Promise<void>}
         */
        async [authTypes.ACTION_LOGIN](store, credentials) {
            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('/auth/login', {email: credentials.email, password: credentials.password});

                store.dispatch(authTypes.ACTION_GET_CURRENT_USER);
            } catch(e) {
                console.error('Something went wrong');
            }
        },
    },
    mutations: {
        [authTypes.SET_CURRENT_USER](state, currentUser) {
            state.currentUser = currentUser
        },
        [authTypes.SET_CAN_ACCESS_DASHBOARD]: (state, canAccessDashboard) => state.canAccessDashboard = canAccessDashboard,
    },
    getters: {
        [authTypes.GET_CURRENT_USER]: (state) => state.currentUser,
        [authTypes.GET_CAN_ACCESS_DASHBOARD]: (state) => state.canAccessDashboard,
    },
};

export default auth;
