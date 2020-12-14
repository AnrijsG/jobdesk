<template>
    <div class="card p-4 shadow">
        <div v-if="isLoading">
            <h5 class="text-center">Personal advertisements</h5>
            <hr>
            <b-skeleton-table
                :rows="5"
                :columns="4"
                :table-props="{ bordered: false, striped: false }"
            ></b-skeleton-table>
        </div>
        <div v-else>
            <div v-if="advertisements.length">
                <h5 class="text-center">Personal advertisements</h5>
                <hr>
                <advertisements-table />
            </div>
            <div v-else>
                <h3 class="text-center">
            <span class="material-icons">
                history
            </span>
                </h3>
                <p class="text-center">
                    No advertisements found, use this panel to start creating
                </p>
            </div>
        </div>

        <create-advertisement-modal />

        <a @click="showModal"
           type="button"
           style="background-color: white"
           class="btn btn-outline-purple border-purple w-100 w-lg-auto">
            Create new
        </a>
    </div>
</template>

<script>
import AdvertisementsTable from "./components/advertisements-table";
import {mapActions, mapGetters, mapMutations} from "vuex";
import * as storeTypes from './stores/personal-advertisements.types';
import * as advertisementStoreTypes from '../advertisement-list/stores/advertisement.types';
import CreateAdvertisementModal from "./components/create-advertisement-modal";

export default {
    name: "personal-advertisements-editor",
    components: {CreateAdvertisementModal, AdvertisementsTable},
    data: () => ({
       isLoading: false,
    }),
    mounted() {
        this.loadAdvertisements();
        this.getCategories();
    },
    methods: {
        ...mapActions('personalAdvertisements', {
            getAdvertisements: storeTypes.ACTION_GET_ENVIRONMENT_ADVERTISEMENTS,
        }),
        ...mapActions('advertisements', {
            getCategories: advertisementStoreTypes.ACTION_GET_CATEGORIES,
        }),
        ...mapMutations('personalAdvertisements', {
            showModal: storeTypes.TOGGLE_CREATE_ADVERTISEMENT_MODAL,
        }),
        async loadAdvertisements() {
            this.isLoading = true;
            await this.getAdvertisements();

            this.isLoading = false;
        }
    },
    computed: {
        ...mapGetters('personalAdvertisements', {
            advertisements: storeTypes.GET_ENVIRONMENT_ADVERTISEMENTS,
        })
    },
}
</script>
