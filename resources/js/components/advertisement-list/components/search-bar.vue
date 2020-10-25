<template>
    <div class="d-flex" style="place-content: center">
        <input v-model="inputValue"
            placeholder="Search for jobs"
            class="form-control w-50 mr-2"
            type="text"
        >
        <button class="btn btn-danger" @click="search" :disabled="isLoading">
            <span class="material-icons">
                search
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
    mounted () {
        this.getAdvertisements();
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
