<template>
    <div class="card py-4 px-4">
        <h5 class="text-center">Advertisement applications</h5>
        <hr>

        <active-advertisement-list v-if="appliableAdvertisements.length" />

        <div v-if="!appliableAdvertisements.length">
            <h3 class="text-center">
                    <span class="material-icons">
                        history
                    </span>
            </h3>
            <p class="text-center">
                No advertisements with internal application found
            </p>
        </div>
    </div>
</template>

<script>
    import ActiveAdvertisementList from "./components/appliable-advertisement-list";
    import {mapActions, mapGetters} from "vuex";
    import * as storeTypes from './stores/appliable-advertisements.types';

    export default {
        name: "advertisement-replies",
        components: {ActiveAdvertisementList},
        mounted() {
            this.getActiveAdvertisements();
        },
        methods: {
            ...mapActions('appliableAdvertisements', {
                getActiveAdvertisements: storeTypes.ACTION_GET_APPLIABLE_ADVERTISEMENTS,
            }),
        },
        computed: {
            ...mapGetters('appliableAdvertisements', {
                appliableAdvertisements: storeTypes.GET_APPLIABLE_ADVERTISEMENTS,
            }),
        },
    }
</script>

