<template>
    <div class="main main__content">
        <div class="row">
            <div class="col-12 col-lg mt-4">
                <personal-advertisements-editor />
            </div>
            <div class="col-12 col-lg mt-4">
                <div class="card p-4 shadow">
                    Placeholder for environment info / users
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PersonalAdvertisementsEditor from "../components/personal-advertisements/personal-advertisements-editor";
export default {
    name: 'dashboard',
    components: {PersonalAdvertisementsEditor}
}
</script>

