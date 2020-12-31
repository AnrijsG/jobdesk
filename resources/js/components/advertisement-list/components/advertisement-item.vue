<template>
    <router-link type="button" class="w-100" :to="`/advertisement/${advertisement.advertisementId}`">
        <div class="card mb-4 advertisement-item">
            <div class="card-body">
                <div class="card-title row">
                    <div class="col-8 col-xl-10 border-right">
                        <h5>{{ advertisement.title }}</h5>

                        <h6 class="mb-2" style="text-decoration: none">
                            {{ advertisement.category }}
                        </h6>
                    </div>

                    <div class="col-4 col-xl-2">
                        <span class="badge badge-warning">{{ advertisement.environment.companyName }}</span><br>

                        <div v-if="advertisement.environment.logoUrl">
                            <img style="max-height: 50px;"
                                 :src="advertisement.environment.logoUrl"
                                 :alt="advertisement.environment.companyName + ' logo'"
                            >

                            <br>
                        </div>

                        <span><strong>Location:</strong> {{ advertisement.location }}</span>
                        <span v-if="advertisement.salaryFrom && advertisement.salaryTo">
                            <br>
                            <strong>Salary:</strong> {{ advertisement.salaryFrom }}€ - {{ advertisement.salaryTo }}€ / month
                        </span>
                        <span>
                            <br>
                            <strong>Ends on: </strong> {{ advertisement.expirationDate }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
import {AdvertisementModel} from "../models/advertisement.model";

export default {
    name: 'advertisement-item',
    props: {
        advertisement: {
            type: AdvertisementModel,
            required: true,
        }
    }
}
</script>

<style lang="scss" scoped>
a {
    color: black;
}
a:hover {
    text-decoration: none;
    color: black;
}
.advertisement-item:hover {
    background-color: #5f42ff;
    color: white !important;
    box-shadow: 0 0.5rem 1rem #5f42ff !important;
    transition: 75ms ease-in-out;
}
</style>
