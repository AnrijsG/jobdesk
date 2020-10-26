<template>
    <div>
        <b-table select-mode="single" selectable sticky-header :items="getTableRows" head-variant="light"></b-table>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import * as storeTypes from '../stores/personal-advertisements.types';

export default {
    name: 'advertisements-table',
    computed: {
        ...mapGetters('personalAdvertisements', {
            advertisements: storeTypes.GET_ENVIRONMENT_ADVERTISEMENTS,
        }),
        getTableRows() {
            return this.advertisements.map(item => (
                {
                    'Job Title': item.title,
                    category: item.category,
                    'Expiration Date': item.expirationDate,
                }
            ));
        }
    },
}
</script>
