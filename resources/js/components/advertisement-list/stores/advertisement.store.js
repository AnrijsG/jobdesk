import Vue from 'vue'
import Vuex from 'vuex'
import * as storeTypes from './advertisement.types';
import {AdvertisementModel} from "../models/advertisement.model";

Vue.use(Vuex)

const advertisements = {
    namespaced: true,
    state: {
        advertisements: [],
    },
    actions: {
        async [storeTypes.ACTION_GET_ADVERTISEMENTS](store, searchFilterCategory = '') {
            try {
                const response = await axios.post('/api/get-advertisements', {category: searchFilterCategory});
                const advertisements = response.data.map(item => AdvertisementModel.fromArray(item));

                store.commit(storeTypes.SET_ADVERTISEMENTS, advertisements || []);
            } catch {}
        }
    },
    mutations: {
        [storeTypes.SET_ADVERTISEMENTS](state, advertisements) {
            state.advertisements = advertisements;
        }
    },
    getters: {
        [storeTypes.GET_ADVERTISEMENTS](state) {
            return state.advertisements;
        },
    },
};

export default advertisements;
