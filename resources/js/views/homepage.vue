<template>
    <div>
        <div class="bg-white">
            <advertisement-list />
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
