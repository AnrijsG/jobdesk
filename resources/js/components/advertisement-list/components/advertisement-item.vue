<template>
    <a type="button" class="w-100">
        <div class="card mb-4 shadow advertisement-item">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="d-inline">{{ advertisement.title }}</h5>

                    <span class="badge badge-success">{{ advertisement.environment.companyName }}</span>
                </div>
                <h6 class="card-subtitle mb-2 text-muted" style="text-decoration: none">

                    <p class="mb-0">{{ advertisement.category }}</p>
                </h6>
            </div>
        </div>
    </a>
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
