<template>
    <div>
        <div class="w-100" style="position: absolute; margin-top: -130px">
            <search-bar class="position: relative;" />
        </div>
        <div class="main main__content mt-4" style="position: relative">
            <advertisement-item v-for="(advertisement, index) in advertisements" :key="index" :advertisement="advertisement" />
        </div>
    </div>
</template>

<script>
    import SearchBar from "./components/search-bar";
    import AdvertisementItem from "./components/advertisement-item";
    import * as storeTypes from './stores/advertisement.types';
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: 'advertisement-list',
        components: {AdvertisementItem, SearchBar},
        mounted() {
            this.getAdvertisements();
        },
        methods: {
            ...mapActions('advertisements', {
                getAdvertisements: storeTypes.ACTION_GET_ADVERTISEMENTS,
            }),
        },
        computed: {
            ...mapGetters('advertisements', {
                advertisements: storeTypes.GET_ADVERTISEMENTS,
            }),
        },
    }
</script>
