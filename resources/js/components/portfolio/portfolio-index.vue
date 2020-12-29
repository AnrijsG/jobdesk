<template>
    <div class="card p-4">
        <cv-upload />
    </div>
</template>

<script>
    import CvUpload from "./components/cv-data";
    export default {
        name: "index",
        components: {CvUpload}
    }
</script>
