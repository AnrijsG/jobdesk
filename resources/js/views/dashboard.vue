<template>
    <div class="main main__content">
        <div class="row">
            <div class="col-12 col-lg mt-4">
                <personal-advertisements-editor />
            </div>
            <div class="col-12 col-lg mt-4">
                <div class="card p-4 shadow">
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

