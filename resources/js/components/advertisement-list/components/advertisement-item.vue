<template>
    <router-link type="button" class="w-100" :to="`/advertisement/${advertisement.advertisementId}`">
        <div class="card mb-4 shadow advertisement-item">
            <div class="card-body">
                <div class="card-title d-flex">
                    <h5 class="flex-grow-1">{{ advertisement.title }}</h5>

                    <div class="border-left pr-4"></div>
                    <div>
                        <span class="badge badge-success">{{ advertisement.environment.companyName }}</span><br>
                        <span><strong>Location:</strong> {{ advertisement.location }}</span>
                        <span v-if="advertisement.salaryFrom && advertisement.salaryTo">
                            <br>
                            <strong>Salary:</strong> {{ advertisement.salaryFrom }}€ - {{ advertisement.salaryTo }}€ / month
                        </span>
                    </div>
                </div>
                <h6 class="card-subtitle mb-2 text-muted" style="text-decoration: none">
                    {{ advertisement.category }}
                </h6>
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
    background-color: #f7fafc;
}
</style>
