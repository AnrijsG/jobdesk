<template>
    <div class="main main__content my-4">
        <div class="mb-4">
            <advertisement-replies />
        </div>
        <div class="row mb-4">
            <div class="col-12 col-xl">
                <personal-advertisements-editor />
            </div>
            <div class="col-12 col-xl">
                <div class="card p-4">
                    <environment-tools />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PersonalAdvertisementsEditor from "../components/personal-advertisements/personal-advertisements-editor";
import EnvironmentTools from "../components/environment-tools/environment-tools";
import AdvertisementReplies from "../components/advertisement-applications/advertisement-replies";
export default {
    name: 'dashboard',
    components: {AdvertisementReplies, EnvironmentTools, PersonalAdvertisementsEditor},
}
</script>

