<template>
    <div v-if="advertisement" class="bg-white">
        <div class="row shadow p-4">
            <div class="col-8 col-xl-10">
                <h3><strong>{{ advertisement.title }}</strong></h3>
                <p class="m-0">
                    {{ advertisement.environment.companyName || '' }}
                </p>
            </div>
            <div class="col-4 col-xl-2">
                <p v-if="advertisement.salaryFrom && advertisement.salaryTo"
                   class="m-0"
                >
                    <strong>Salary:</strong> {{ advertisement.salaryFrom }}€ - {{ advertisement.salaryTo }}€ / month
                </p>

                <p v-if="advertisement.location"
                   class="m-0"
                >
                    <strong>Location:</strong> {{ advertisement.location }}
                </p>

                <p v-if="advertisement.expirationDate"
                   class="m-0"
                >
                    <strong>Ends on: </strong> {{ advertisement.expirationDate }}
                </p>
            </div>
        </div>

        <div class="pt-2 p-4">
            <p class="mb-1" v-for="text in advertisement.content.split('\n')">
                {{ text }}
            </p>

            <div v-if="advertisement.applyUrl" class="text-right mt-4">
                <a class="btn btn-purple w-25" :href="advertisement.applyUrl" rel="nofollow noopener" target="_blank">
                    Apply now
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import * as storeTypes from "../stores/advertisement.types";

export default {
    name: 'advertisement-view',
    computed: {
        ...mapGetters('advertisements', {
            /** @type {AdvertisementModel|*} */
            advertisement: storeTypes.GET_CURRENT_ADVERTISEMENT,
        }),
    }
}
</script>
