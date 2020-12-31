import * as authTypes from './auth.types';
import {UserModel} from "../models/user.model";
import {EnvironmentModel} from "../models/environment.model";

const auth = {
    namespaced: true,
    state: {
        /** @type {UserModel|null} */
        currentUser: null,
        /** @type {Array<UserModel>} */
        environmentUsers: [],
        canAccessDashboard: false,
        registrationHash: '',
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
        async [authTypes.ACTION_GET_ENVIRONMENT_USERS]({commit}) {
            const response = await axios.post('/auth/get-environment-users');

            commit(authTypes.SET_ENVIRONMENT_USERS, response.data);
        },
        async [authTypes.ACTION_RESET_REGISTRATION_HASH]({dispatch}) {
            await axios.post('/auth/reset-registration-hash');

            dispatch(authTypes.ACTION_GET_REGISTRATION_HASH);
        },
        async [authTypes.ACTION_GET_REGISTRATION_HASH]({commit}) {
            const response = await axios.post('/auth/get-registration-hash');

            commit(authTypes.SET_REGISTRATION_HASH, response.data);
        },
        async [authTypes.ACTION_DELETE_REGISTRATION_HASH]({dispatch}) {
            await axios.post('/auth/delete-registration-hash');

            dispatch(authTypes.ACTION_GET_REGISTRATION_HASH);
        },
        /**
         * @param store
         * @param credentials
         * @returns {Promise<void>}
         */
        async [authTypes.ACTION_LOGIN](store, credentials) {
            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/auth/login', {email: credentials.email, password: credentials.password});

            store.dispatch(authTypes.ACTION_GET_CURRENT_USER);
        },
    },
    mutations: {
        [authTypes.SET_CURRENT_USER](state, currentUser) {
            state.currentUser = currentUser
        },
        [authTypes.SET_CAN_ACCESS_DASHBOARD]: (state, canAccessDashboard) => state.canAccessDashboard = canAccessDashboard,
        [authTypes.SET_ENVIRONMENT_USERS]: (state, environmentUsers) => state.environmentUsers = environmentUsers,
        [authTypes.SET_REGISTRATION_HASH]: (state, registrationHash) => state.registrationHash = registrationHash,
    },
    getters: {
        [authTypes.GET_CURRENT_USER]: (state) => state.currentUser,
        [authTypes.GET_CAN_ACCESS_DASHBOARD]: (state) => state.canAccessDashboard,
        [authTypes.GET_ENVIRONMENT_USERS]: (state) => state.environmentUsers,
        [authTypes.GET_REGISTRATION_HASH]: (state) => state.registrationHash,
    },
};

export default auth;
