<template>
    <div>
        <div class="bg-light">
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
