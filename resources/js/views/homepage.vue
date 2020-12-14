<template>
    <div>
        <advertisement-list class="bg-white" />

        <hr class="m-0">

        <div class="main main__content bg-light py-4">
            <p class="text-right mb-0"><strong>JobDesk</strong> 2020</p>
        </div>
    </div>
</template>

<script>
import AdvertisementList from "../components/advertisement-list/advertisement-list";
export default {
    name: 'homepage',
    components: {AdvertisementList},
}
</script>
