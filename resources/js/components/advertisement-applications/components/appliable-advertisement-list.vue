<template>
    <div>
        <div class="row mb-2">
            <div class="col-2">
                <strong>Advertisement ID</strong>
            </div>
            <div class="col-6">
                <strong>Job Title</strong>
            </div>
            <div class="col-2">
                <strong>Advertisement expiration date</strong>
            </div>
            <div class="col-2">

            </div>
        </div>

        <div style="max-height: 250px; overflow-y: scroll; overflow-x: hidden;">
            <div v-for="advertisement in appliableAdvertisements"
                 :key="advertisement.advertisementId"
                 class="row mb-2"
            >
                <div class="col-2">
                    {{ advertisement.advertisementId }}
                </div>
                <div class="col-6">
                    {{ advertisement.title }}
                </div>
                <div class="col-2">
                    {{ advertisement.expirationDate }}
                </div>
                <div class="col-2 text-right">
                    <button @click="currentAdvertisementId = advertisement.advertisementId"
                            class="btn btn-purple"
                            data-toggle="modal"
                            data-target="#applicantsModal"
                    >
                        Show candidates
                    </button>
                </div>
            </div>
        </div>

        <applicants-modal :advertisement-id="currentAdvertisementId" />
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import * as storeTypes from '../stores/appliable-advertisements.types';
    import ApplicantsModal from "./applicants-modal";

    export default {
        name: 'appliable-advertisement-list',
        components: {ApplicantsModal},
        data: () => ({
            currentAdvertisementId: -1,
        }),
        computed: {
            ...mapGetters('appliableAdvertisements', {
                appliableAdvertisements: storeTypes.GET_APPLIABLE_ADVERTISEMENTS,
            }),
        },
    }
</script>
