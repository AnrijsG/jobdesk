<template>
    <div class="card p-4 shadow">
        <h5 class="text-center">Personal advertisements</h5>

        <hr>

        <advertisements-table />

        <a type="button"
           style="width: fit-content; background-color: #3c0207!important; place-self: flex-end" class="btn text-white shadow">
            Create new
        </a>
    </div>
</template>

<script>
import AdvertisementsTable from "./components/advertisements-table";
import {mapActions} from "vuex";
import * as storeTypes from './stores/personal-advertisements.types';

export default {
    name: "personal-advertisements-editor",
    components: {AdvertisementsTable},
    mounted() {
        this.getAdvertisements();
    },
    methods: {
        ...mapActions('personalAdvertisements', {
            getAdvertisements: storeTypes.ACTION_GET_ENVIRONMENT_ADVERTISEMENTS,
        }),
    }
}
</script>
