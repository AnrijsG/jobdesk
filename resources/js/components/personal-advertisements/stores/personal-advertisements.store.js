import Vue from 'vue'
import Vuex from 'vuex'
import * as storeTypes from './personal-advertisements.types';
import {AdvertisementModel} from "../../advertisement-list/models/advertisement.model";
import {AdvertisementQueryItemStructure} from "../../advertisement-list/structures/advertisement-query-item.structure";

Vue.use(Vuex)

const personalAdvertisements = {
    namespaced: true,
    state: {
        advertisements: [],
        showCreateModal: false,
    },
    actions: {
        async [storeTypes.ACTION_GET_ENVIRONMENT_ADVERTISEMENTS](store) {
            const searchItem = new AdvertisementQueryItemStructure();
            searchItem.withCurrentEnvironmentId = true;

            const response = await axios.post('/api/personal-advertisements', {searchItem});
            const advertisements = response.data.map(item => AdvertisementModel.fromArray(item));

            store.commit(storeTypes.SET_ENVIRONMENT_ADVERTISEMENTS, advertisements);
        },
        async [storeTypes.ACTION_SAVE_ADVERTISEMENT](store, advertisementData) {
            try {
                const response = await axios.post('/api/save-advertisement', {advertisementData})

                return !!response.data;
            } catch {
                return false;
            }
        }
    },
    mutations: {
        [storeTypes.SET_ENVIRONMENT_ADVERTISEMENTS]: (state, advertisements) => state.advertisements = advertisements,
        [storeTypes.TOGGLE_CREATE_ADVERTISEMENT_MODAL]: (state) => state.showCreateModal = !state.showCreateModal,
    },
    getters: {
        [storeTypes.GET_ENVIRONMENT_ADVERTISEMENTS]: (state) => state.advertisements,
        [storeTypes.GET_SHOW_MODAL]: (state) => state.showCreateModal,
    },
};

export default personalAdvertisements;
