<template>
    <div class="main main__content my-4">
        <div class="row">
            <div class="col-12 col-lg">
                <personal-advertisements-editor />
            </div>
            <div class="col-12 col-lg">
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
export default {
    name: 'dashboard',
    components: {EnvironmentTools, PersonalAdvertisementsEditor}
}
</script>

