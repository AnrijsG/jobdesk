<template>
    <div style="max-width: 1500px;" class="d-flex m-auto">
        <div class="flex-grow-1">
            <div class="d-flex mb-2">
                <input v-model="inputTitle"
                       @keydown.enter="search"
                       placeholder="Job title"
                       class="form-control mr-2"
                       style="padding: 22px;"
                       type="text"
                       :disabled="isLoading"
                >
                <input v-model="inputLocation"
                       @keydown.enter="search"
                       placeholder="Location"
                       class="form-control mr-2"
                       style="padding: 22px;"
                       type="text"
                       :disabled="isLoading"
                >
            </div>

            <b-form-select :options="categories"
                           v-model="inputCategory"
                           id="advertisementCategory"
                           class="form-control mr-2"
                           style="max-width: -webkit-fill-available;"
            >
                <template #first>
                    <b-form-select-option :value="''">-- Select a category --</b-form-select-option>
                </template>
            </b-form-select>
        </div>

        <div class="d-inline">
            <button class="btn h-100 text-white" @click="search" :disabled="isLoading" style="background-color: #5f42ff">
                <span class="material-icons" v-if="!isLoading">
                    search
                </span>
                <span v-else class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </span>
            </button>
        </div>
    </div>

</template>

<script>
import {mapActions, mapGetters} from "vuex";
import * as storeTypes from '../stores/advertisement.types';
import {AdvertisementQueryItemStructure} from "../structures/advertisement-query-item.structure";
import * as advertisementStoreTypes from "../stores/advertisement.types";

export default {
    name: 'search-bar',
    props: {
        initialInputTitle: {
            type: String,
            required: false,
            default: '',
        },
        initialInputLocation: {
            type: String,
            required: false,
            default: '',
        },
        initialInputCategory: {
            type: String,
            required: false,
            default: '',
        },
    },
    data: () => ({
        inputTitle: '',
        inputLocation: '',
        inputCategory: '',
        isLoading: false,
    }),
    mounted() {
        this.inputTitle = this.initialInputTitle;
        this.inputLocation = this.initialInputLocation;
        this.inputCategory = this.initialInputCategory;
    },
    methods: {
        async search() {
            this.isLoading = true;

            try {
                await this.$router.push({
                    name: 'homepage',
                    query: {
                        title: this.inputTitle,
                        location: this.inputLocation,
                        category: this.inputCategory,
                    }
                });
            } catch {
                this.isLoading = false;

                return;
            }

            const searchItem = new AdvertisementQueryItemStructure(this.inputTitle, this.inputCategory, this.inputLocation, 10);

            await this.getAdvertisements(searchItem);
            this.isLoading = false;
        },
        ...mapActions('advertisements', {
            getAdvertisements: storeTypes.ACTION_GET_ADVERTISEMENTS,
        }),
    },
    computed: {
        ...mapGetters('advertisements', {
            categories: advertisementStoreTypes.GET_CATEGORIES,
        }),
    }
}
</script>
