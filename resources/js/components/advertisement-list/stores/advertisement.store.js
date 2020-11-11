import Vue from 'vue'
import Vuex from 'vuex'
import * as storeTypes from './advertisement.types';
import {AdvertisementModel} from "../models/advertisement.model";
import {AdvertisementQueryItemStructure} from "../structures/advertisement-query-item.structure";

Vue.use(Vuex)

const advertisements = {
    namespaced: true,
    state: {
        /** @type {AdvertisementModel|null} */
        currentAdvertisement: null,
        advertisements: [],
        categories: [],
    },
    actions: {
        [storeTypes.ACTION_GET_ADVERTISEMENTS](store, searchItem = new AdvertisementQueryItemStructure()) {
            return axios.post('/api/get-advertisements', {searchItem: searchItem}).then(response => {
                const advertisements = response.data.map(item => AdvertisementModel.fromArray(item));

                store.commit(storeTypes.SET_ADVERTISEMENTS, advertisements || []);
            });
        },
        async [storeTypes.ACTION_GET_CATEGORIES](store) {
            try {
                const response = await axios.get('/api/get-categories');

                store.commit(storeTypes.SET_CATEGORIES, response.data || []);
            } catch {}
        },
        async [storeTypes.ACTION_GET_CURRENT_ADVERTISEMENT]({commit, dispatch, state}, advertisementId) {
            if (!state.advertisements.length) {
                await dispatch(storeTypes.ACTION_GET_ADVERTISEMENTS);
            }

            const currentAdvertisement = state.advertisements.find(item => item.advertisementId.toString() === advertisementId);

            commit(storeTypes.SET_CURRENT_ADVERTISEMENT, currentAdvertisement);
        }
    },
    mutations: {
        [storeTypes.SET_ADVERTISEMENTS]: (state, advertisements) => state.advertisements = advertisements,
        [storeTypes.SET_CATEGORIES]: (state, categories) => state.categories = categories,
        [storeTypes.SET_CURRENT_ADVERTISEMENT]: (state, currentAdvertisement) => state.currentAdvertisement = currentAdvertisement,
    },
    getters: {
        [storeTypes.GET_ADVERTISEMENTS]: (state) => state.advertisements,
        [storeTypes.GET_CATEGORIES]: (state) => state.categories,
        [storeTypes.GET_CURRENT_ADVERTISEMENT]: (state) => state.currentAdvertisement,
    },
};

export default advertisements;
