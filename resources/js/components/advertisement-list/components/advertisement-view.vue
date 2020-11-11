<template>
    <div v-if="advertisement">
        <h3>{{ advertisement.title }}</h3>
        <p class="text-muted">{{ advertisement.environment.companyName || '' }}</p>

        <div class="pt-2">
            <p class="mb-1" v-for="text in advertisement.content.split('\n')">
                {{ text }}
            </p>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import * as storeTypes from "../stores/advertisement.types";

export default {
    name: 'advertisement-view',
    computed: {
        ...mapGetters('advertisements', {
            /** @type {AdvertisementModel|*} */
            advertisement: storeTypes.GET_CURRENT_ADVERTISEMENT,
        }),
    }
}
</script>
