import Vue from 'vue'
import Vuex from 'vuex'
import * as storeTypes from './personal-advertisements.types';
import {AdvertisementModel} from "../../advertisement-list/models/advertisement.model";

Vue.use(Vuex)

const personalAdvertisements = {
    namespaced: true,
    state: {
        advertisements: [],
    },
    actions: {
        async [storeTypes.ACTION_GET_ENVIRONMENT_ADVERTISEMENTS](store) {
            const response = await axios.post('/api/personal-advertisements');
            const advertisements = response.data.map(item => AdvertisementModel.fromArray(item));

            store.commit(storeTypes.SET_ENVIRONMENT_ADVERTISEMENTS, advertisements);
        },
    },
    mutations: {
        [storeTypes.SET_ENVIRONMENT_ADVERTISEMENTS]: (state, advertisements) => state.advertisements = advertisements,
    },
    getters: {
        [storeTypes.GET_ENVIRONMENT_ADVERTISEMENTS]: (state) => state.advertisements,
    },
};

export default personalAdvertisements;
