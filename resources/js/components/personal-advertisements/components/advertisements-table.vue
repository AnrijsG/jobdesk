<template>
    <div>
        <b-table select-mode="single"
                 selectable
                 sticky-header
                 :items="getTableRows"
                 head-variant="light"
                 @row-clicked="(payload) => showModal(payload)"
        />
    </div>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";
import * as storeTypes from '../stores/personal-advertisements.types';

export default {
    name: 'advertisements-table',
    methods: {
        ...mapMutations('personalAdvertisements', {
            showModal: storeTypes.TOGGLE_CREATE_ADVERTISEMENT_MODAL,
        }),
    },
    computed: {
        ...mapGetters('personalAdvertisements', {
            advertisements: storeTypes.GET_ENVIRONMENT_ADVERTISEMENTS,
        }),
        getTableRows() {
            return this.advertisements.map(item => (
                {
                    "ID": item.advertisementId,
                    'Job Title': item.title,
                    category: item.category,
                    'Expiration Date': item.expirationDate,
                }
            ));
        },
    },
}
</script>
