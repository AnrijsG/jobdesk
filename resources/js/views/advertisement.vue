<template>
    <div class="main main__content mt-4 shadow p-4">
        <advertisement-view />
    </div>
</template>

<script>
import AdvertisementView from "../components/advertisement-list/components/advertisement-view";
import {mapActions} from "vuex";
import * as storeTypes from "../components/advertisement-list/stores/advertisement.types";
export default {
    name: 'advertisement',
    components: {AdvertisementView},
    mounted() {
        this.getCurrentAdvertisement(this.$attrs.advertisementId);
    },
    methods: {
        ...mapActions('advertisements', {
            getCurrentAdvertisement: storeTypes.ACTION_GET_CURRENT_ADVERTISEMENT,
        }),
    },
}
</script>
