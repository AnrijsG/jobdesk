<template>
    <div class="pb-4">
        <div class="w-100 py-4 bg-light" style="padding-left: 16px; padding-right: 16px;">
            <search-bar
                :initial-input-title="$router.currentRoute.query.title || ''"
                :initial-input-location="$router.currentRoute.query.location || ''"
                :initial-input-category="$router.currentRoute.query.category || ''"
            />
        </div>
        <hr class="m-0 mb-4">
        <div class="main main__content" style="position: relative">
            <div v-if="advertisements.length">
                <advertisement-item v-for="advertisement in advertisements" :key="advertisement.advertisementId" :advertisement="advertisement" />

                <div class="d-flex" v-if="!isInfiniteScrollEnabled && !isBottomReached">
                    <button class="btn btn-outline-purple w-100 mr-2" @click="increaseLimit(10)">Load more</button>
                    <button class="btn btn-outline-dark w-100" @click="initializeInfiniteScroll">Turn on infinite scrolling</button>
                </div>
            </div>
            <div v-else>
                <div class="row" style="align-items: center;">
                    <div class="border-right col text-center">
                        <i class="material-icons" style="font-size: 64px;">
                            search_off
                        </i>
                    </div>
                    <div class="col">
                        <p class="m-0">
                            <strong>Oops!</strong> <br>Looks like there are no results for your search
                        </p>
                    </div>
                </div>
            </div>
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
            isInfiniteScrollEnabled: false,
        }),
        mounted() {
            this.getCategories();
            this.loadItems();
        },
        methods: {
            ...mapActions('advertisements', {
                getAdvertisements: storeTypes.ACTION_GET_ADVERTISEMENTS,
                increaseLimit: storeTypes.ACTION_INCREASE_LIMIT,
                getCategories: storeTypes.ACTION_GET_CATEGORIES,
            }),
            ...mapMutations('advertisements', {
                setSearchTitle: storeTypes.SET_SEARCH_TITLE,
                setSearchCategory: storeTypes.SET_SEARCH_CATEGORY,
                setSearchLocation: storeTypes.SET_SEARCH_LOCATION,
                setSearchLimit: storeTypes.SET_SEARCH_LIMIT,
            }),
            setLeftToScroll() {
                this.isPageBottomReached = $(window).scrollTop() + $(window).height() === $(document).height();
            },
            initializeInfiniteScroll() {
                window.addEventListener('scroll', this.setLeftToScroll);
                this.isPageBottomReached = true;
                this.isInfiniteScrollEnabled = true;
            },
            loadItems() {
                this.setSearchLimit(10);

                if (Object.keys(this.queryParams).length === 0) {
                    this.setSearchTitle('');
                    this.setSearchCategory('');
                    this.setSearchLocation('');

                    this.getAdvertisements(this.searchItem);

                    return;
                }

                Object.keys(this.queryParams).map((key) => {
                    switch (key) {
                        case 'title':
                            this.setSearchTitle(this.queryParams.title);
                            break;
                        case 'category':
                            this.setSearchCategory(this.queryParams.category);
                            break;
                        case 'location':
                            this.setSearchLocation(this.queryParams.location);
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
                isBottomReached: storeTypes.GET_IS_BOTTOM_REACHED,
            }),
            queryParams() {
                return this.$route.query;
            }
        },
        watch: {
            isPageBottomReached(value) {
                if (value) {
                    this.increaseLimit(10);
                }
            },
            queryParams(value) {
                this.loadItems();
            },
        }
    }
</script>
