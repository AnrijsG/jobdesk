<template>
    <div class="d-flex" style="place-content: center">
        <input v-model="inputTitle"
               @keydown.enter="search"
               placeholder="Search for jobs"
               class="form-control w-50 mr-2"
               style="padding: 22px"
               type="text"
               :disabled="isLoading"
        >
        <button class="btn btn-danger" @click="search" :disabled="isLoading">
            <span class="material-icons" v-if="!isLoading">
                search
            </span>
            <span v-else class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </span>
        </button>
    </div>
</template>

<script>
import {mapActions} from "vuex";
import * as storeTypes from '../stores/advertisement.types';
import {AdvertisementQueryItemStructure} from "../structures/advertisement-query-item.structure";

export default {
    name: 'search-bar',
    data () {
        return {
            inputTitle: '',
            inputCategory: '',
            isLoading: false,
        }
    },
    methods: {
        async search() {
            this.isLoading = true;

            await this.$router.push({name: 'homepage', query: {title: this.inputTitle}});
            const searchItem = new AdvertisementQueryItemStructure(this.inputTitle, this.inputCategory, 10);

            await this.getAdvertisements(searchItem);
            this.isLoading = false;
        },
        ...mapActions('advertisements', {
            getAdvertisements: storeTypes.ACTION_GET_ADVERTISEMENTS,
        }),
    }
}
</script>
