<template>
    <div>
        <div class="w-100" style="position: absolute; margin-top: -130px">
            <search-bar class="position: relative;" />
        </div>
        <div class="main main__content mt-4" style="position: relative">
            <advertisement-item v-for="advertisement in advertisements" :key="advertisement.advertisementId" :advertisement="advertisement" />
        </div>
    </div>
</template>

<script>
    import SearchBar from "./components/search-bar";
    import AdvertisementItem from "./components/advertisement-item";
    import * as storeTypes from './stores/advertisement.types';
    import {mapActions, mapGetters} from "vuex";
    import {AdvertisementQueryItemStructure} from "./structures/advertisement-query-item.structure";

    export default {
        name: 'advertisement-list',
        components: {AdvertisementItem, SearchBar},
        data: () => ({
            isPageBottomReached: false,
        }),
        mounted() {
            const searchItem = new AdvertisementQueryItemStructure();
            searchItem.limit = 10;

            window.addEventListener('scroll', this.setLeftToScroll);

            this.getAdvertisements(searchItem);
        },
        methods: {
            ...mapActions('advertisements', {
                getAdvertisements: storeTypes.ACTION_GET_ADVERTISEMENTS,
            }),
            setLeftToScroll() {
                this.isPageBottomReached = $(window).scrollTop() + $(window).height() === $(document).height();
            },
        },
        computed: {
            ...mapGetters('advertisements', {
                advertisements: storeTypes.GET_ADVERTISEMENTS,
            }),
        },
        watch: {
            isPageBottomReached() {
                this.getAdvertisements();
            }
        }
    }
</script>
