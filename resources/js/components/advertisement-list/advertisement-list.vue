<template>
    <div>
        <div class="w-100" style="position: absolute; margin-top: -130px">
            <search-bar :initial-input-title="$router.currentRoute.query.title || ''" />
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
    import {mapActions, mapGetters, mapMutations} from "vuex";

    export default {
        name: 'advertisement-list',
        components: {AdvertisementItem, SearchBar},
        data: () => ({
            isPageBottomReached: false,
        }),
        mounted() {
            this.loadItems();

            window.addEventListener('scroll', this.setLeftToScroll);
        },
        methods: {
            ...mapActions('advertisements', {
                getAdvertisements: storeTypes.ACTION_GET_ADVERTISEMENTS,
                increaseLimit: storeTypes.ACTION_INCREASE_LIMIT,
            }),
            ...mapMutations('advertisements', {
                setSearchTitle: storeTypes.SET_SEARCH_TITLE,
                setSearchCategory: storeTypes.SET_SEARCH_CATEGORY,
                setSearchLimit: storeTypes.SET_SEARCH_LIMIT,
            }),
            setLeftToScroll() {
                this.isPageBottomReached = $(window).scrollTop() + $(window).height() === $(document).height();
            },
            loadItems() {
                this.setSearchLimit(10);

                const queryParams = this.$router.currentRoute.query;
                Object.keys(queryParams).map((key) => {
                    switch (key) {
                        case 'title':
                            this.setSearchTitle(queryParams.title);
                            break;
                        case 'category':
                            this.setSearchCategory(queryParams.category);
                            break;
                    }
                });

                this.getAdvertisements(this.searchItem);
            },
        },
        computed: {
            ...mapGetters('advertisements', {
                advertisements: storeTypes.GET_ADVERTISEMENTS,
                searchItem: storeTypes.GET_CURRENT_SEARCH_ITEM,
            }),
        },
        watch: {
            isPageBottomReached(value) {
                if (value) {
                    this.increaseLimit(10);
                }
            }
        }
    }
</script>
