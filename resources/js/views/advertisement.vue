<template>
    <div class="bg-light py-4 mx-4">
        <div class="bg-white shadow main main__content">
            <b-skeleton-wrapper :loading="isLoading">
                <template #loading>
                    <div class="row mb-2 shadow p-4">
                        <div class="col-8 col-xl-10">
                            <b-skeleton class="mb-2" width="30%"></b-skeleton>
                            <b-skeleton class="mb-4" width="25%"></b-skeleton>
                        </div>
                        <div class="col-4 col-xl-2">
                            <b-skeleton class="mb-2" width="50%"></b-skeleton>
                            <b-skeleton class="mb-2" width="30%"></b-skeleton>
                            <b-skeleton class="mb-2" width="40%"></b-skeleton>
                        </div>
                    </div>
                    <div class="p-4">
                        <b-skeleton width="100%"></b-skeleton>
                        <b-skeleton width="98%"></b-skeleton>
                        <b-skeleton width="100%"></b-skeleton>
                        <b-skeleton width="93%"></b-skeleton>
                        <b-skeleton width="86%"></b-skeleton>
                        <b-skeleton width="100%"></b-skeleton>
                        <b-skeleton width="96%"></b-skeleton>
                    </div>
                </template>

                <advertisement-view />
            </b-skeleton-wrapper>
        </div>
    </div>
</template>

<script>
import AdvertisementView from "../components/advertisement-list/components/advertisement-view";
import {mapActions} from "vuex";
import * as storeTypes from "../components/advertisement-list/stores/advertisement.types";
export default {
    name: 'advertisement',
    components: {AdvertisementView},
    data: () => ({
        isLoading: false,
    }),
    mounted() {
        this.loadAdvertisement();
    },
    methods: {
        ...mapActions('advertisements', {
            getCurrentAdvertisement: storeTypes.ACTION_GET_CURRENT_ADVERTISEMENT,
        }),
        async loadAdvertisement() {
            this.isLoading = true;
            await this.getCurrentAdvertisement(this.$attrs.advertisementId);

            this.isLoading = false;
        },
    },
}
</script>
