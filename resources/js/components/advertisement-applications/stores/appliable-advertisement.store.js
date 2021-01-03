import Vue from "vue";
import Vuex from "vuex";
import * as storeTypes from './appliable-advertisements.types';
import {AdvertisementModel} from "../../advertisement-list/models/advertisement.model";

Vue.use(Vuex)

const appliableAdvertisements = {
    namespaced: true,
    state: {
        /** @type {Array<AdvertisementModel>|*} */
        appliableAdvertisements: [],
    },
    actions: {
        async [storeTypes.ACTION_GET_APPLIABLE_ADVERTISEMENTS]({commit}) {
            const response = await axios.post('/api/get-appliable-advertisements');
            if (!response.data) {
                return;
            }

            const appliableAdvertisements = response.data.map(advertisementItem => AdvertisementModel.fromArray(advertisementItem));

            commit(storeTypes.SET_APPLIABLE_ADVERTISEMENTS, appliableAdvertisements);
        },
    },
    getters: {
        [storeTypes.GET_APPLIABLE_ADVERTISEMENTS]: (state) => state.appliableAdvertisements,
    },
    mutations: {
        [storeTypes.SET_APPLIABLE_ADVERTISEMENTS]: (state, appliableAdvertisements) => state.appliableAdvertisements = appliableAdvertisements,
    },
}

export default appliableAdvertisements;
