<template>
    <div class="d-flex" style="place-content: center">
        <input v-model="inputValue"
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

export default {
    name: 'search-bar',
    data () {
        return {
            inputValue: '',
            isLoading: false,
        }
    },
    methods: {
        async search() {
            this.isLoading = true;
            await this.getAdvertisements(this.inputValue);
            this.isLoading = false;
        },
        ...mapActions('advertisements', {
            getAdvertisements: storeTypes.ACTION_GET_ADVERTISEMENTS,
        }),
    }
}
</script>
