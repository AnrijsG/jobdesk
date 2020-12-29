<template>
    <div>
        <h5>
            <strong>CV File</strong>
            <a @click="downloadCv" style="cursor: pointer">
                <i v-if="lastModifiedAt" class="material-icons">
                    save_alt
                </i>
            </a>
        </h5>

        <div>
            <p class="mb-0"><strong>Status:</strong>

                <span class="badge"
                      :class="lastModifiedAt ? 'badge-success' : 'badge-danger'"
                >
                    {{ lastModifiedAt ? 'Ok' : 'Not found' }}
                </span>
            </p>
        </div>

        <div v-if="lastModifiedAt">
            <strong>
                Last updated at:
            </strong>

            {{ lastModifiedAt }}
        </div>

        <div v-if="!lastModifiedAt">
            <div class="alert alert-danger d-flex mt-2" style="align-items: center;">
                <div class="mr-3">
                    <i class="material-icons" style="font-size: 36px;">
                        error_outline
                    </i>
                </div>
                <div>
                    You currently do not have a CV uploaded, please upload a CV to be able to apply for new positions
                </div>
            </div>
        </div>

        <div v-if="lastModifiedAt" class="d-inline-block mt-4">
            <a class="btn btn-purple"
               v-if="!shouldModifyCv"
               @click="shouldModifyCv = true"
            >
                Modify CV
            </a>
            <a v-if="!shouldModifyCv"
               class="btn btn-danger"
               @click="deleteCv"
            >
                Delete CV
            </a>
        </div>


        <p v-if="shouldModifyCv" class="mt-2">
            <strong>
                Replace CV:
            </strong>
        </p>

        <input v-if="!lastModifiedAt || shouldModifyCv"
               type="file"
               @change="selectFile"
               accept="application/pdf"
        >
    </div>
</template>

<script>
    export default {
        name: 'cv-data',
        data: () => ({
            cv: null,
            lastModifiedAt: null,
            shouldModifyCv: false,
        }),
        mounted() {
            this.getLastModifiedAt().then(response => this.lastModifiedAt = response.data);
        },
        methods: {
            selectFile(event) {
                this.cv = event.target.files[0];
            },
            async getLastModifiedAt() {
                return await axios.get('/api/get-personal-cv-modified-at');;
            },
            async downloadCv() {
                const downloadUrl = (await axios.get('/api/get-personal-cv-url')).data;

                const link = document.createElement('a');

                link.href = downloadUrl;
                link.setAttribute('download', 'cv.pdf');

                document.body.appendChild(link);

                link.click();
                link.remove();
            },
            async deleteCv() {
                await axios.post('/api/delete-cv');

                this.lastModifiedAt = null;
            },
        },
        watch: {
            cv(newValue) {
                if (!newValue) {
                    return;
                }

                const data = new FormData();
                data.append('cv', this.cv);

                axios.post("/api/upload-cv", data)
                    .then(() => this.getLastModifiedAt())
                    .then(response => this.lastModifiedAt = response.data);
            },
        },
    }
</script>
