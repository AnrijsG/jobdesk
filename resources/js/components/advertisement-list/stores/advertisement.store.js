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
        /** @type {AdvertisementQueryItemStructure} */
        advertisementQueryItem: new AdvertisementQueryItemStructure,
        advertisements: [],
        categories: [],
        isBottomReached: false,
    },
    actions: {
        async [storeTypes.ACTION_GET_ADVERTISEMENTS](store, searchItem = new AdvertisementQueryItemStructure()) {
            const response = await axios.post('/api/get-advertisements', {searchItem: searchItem});

            const advertisements = response.data.map(item => AdvertisementModel.fromArray(item));
            if (!advertisements.length || advertisements.length < searchItem.limit) {
                store.commit(storeTypes.SET_IS_BOTTOM_REACHED, true);
            } else {
                store.commit(storeTypes.SET_IS_BOTTOM_REACHED, false);
            }

            store.commit(storeTypes.SET_ADVERTISEMENTS, advertisements || []);
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
        },
        [storeTypes.ACTION_INCREASE_LIMIT]({state, commit}, limit) {
            commit(storeTypes.SET_SEARCH_LIMIT, limit);
            commit(storeTypes.SET_SEARCH_OFFSET, state.advertisementQueryItem.offset + limit);

            axios.post('/api/get-advertisements', {searchItem: state.advertisementQueryItem}).then(response => {
                const advertisements = response.data.map(item => AdvertisementModel.fromArray(item)) || [];
                if (!advertisements.length || advertisements.length < limit) {
                    commit(storeTypes.SET_IS_BOTTOM_REACHED, true);
                } else {
                    commit(storeTypes.SET_IS_BOTTOM_REACHED, false);
                }

                commit(storeTypes.ADD_ADVERTISEMENTS, advertisements || []);
            });
        }
    },
    mutations: {
        [storeTypes.SET_ADVERTISEMENTS]: (state, advertisements) => state.advertisements = advertisements,
        [storeTypes.SET_CATEGORIES]: (state, categories) => state.categories = categories,
        [storeTypes.SET_CURRENT_ADVERTISEMENT]: (state, currentAdvertisement) => state.currentAdvertisement = currentAdvertisement,
        [storeTypes.SET_SEARCH_TITLE]: (state, title) => state.advertisementQueryItem.title = title,
        [storeTypes.SET_SEARCH_CATEGORY]: (state, category) => state.advertisementQueryItem.category = category,
        [storeTypes.SET_SEARCH_LOCATION]: (state, location) => state.advertisementQueryItem.location = location,
        [storeTypes.SET_SEARCH_LIMIT]: (state, limit) => state.advertisementQueryItem.limit = limit,
        [storeTypes.SET_SEARCH_OFFSET]: (state, offset) => state.advertisementQueryItem.offset = offset,
        [storeTypes.ADD_ADVERTISEMENTS]: (state, advertisements) => state.advertisements.push(...advertisements),
        [storeTypes.SET_IS_BOTTOM_REACHED]: (state, isBottomReached) => state.isBottomReached = isBottomReached,
    },
    getters: {
        [storeTypes.GET_ADVERTISEMENTS]: (state) => state.advertisements,
        [storeTypes.GET_CATEGORIES]: (state) => state.categories,
        [storeTypes.GET_CURRENT_ADVERTISEMENT]: (state) => state.currentAdvertisement,
        [storeTypes.GET_CURRENT_SEARCH_ITEM]: (state) => state.advertisementQueryItem,
        [storeTypes.GET_IS_BOTTOM_REACHED]: (state) => state.isBottomReached,
    },
};

export default advertisements;
