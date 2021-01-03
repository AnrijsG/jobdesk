<template>
    <div class="modal fade" tabindex="-1" id="applicantsModal" data-backdrop="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    Applicants for advertisement #{{ advertisementId }}

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <strong>
                                Name
                            </strong>
                        </div>
                        <div class="col">
                            <strong>
                                Email
                            </strong>
                        </div>
                        <div class="col">
                            <strong>
                                Cover letter
                            </strong>
                        </div>
                        <div class="col">
                            <strong>
                                CV
                            </strong>
                        </div>
                    </div>

                    <div class="row" v-for="applicant in applicants" :key="applicant.advertisementReplyId">
                        <div class="col">
                            {{ applicant.userName }}
                        </div>
                        <div class="col">
                            {{ applicant.userEmail }}
                        </div>
                        <div class="col">
                            <a @click="downloadCoverLetter(applicant.coverLetter, applicant.userName)" style="cursor: pointer;">
                                <i class="material-icons">
                                    save_alt
                                </i>
                            </a>
                        </div>
                        <div class="col">
                            <a :href="applicant.cvDownloadUrl"
                               class="text-dark"
                               :download="'cv-' + applicant.userName"
                            >
                                <i class="material-icons">
                                    save_alt
                                </i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {AdvertisementReply} from "../structures/advertisement-reply.structure";
    import jsPDF from 'jspdf';
    import slugify from "slugify";

    export default {
        name: 'applicants-modal',
        props: {
            advertisementId: {
                type: Number,
                required: true,
            }
        },
        data: () => ({
            /** @type {Array<AdvertisementReply>} */
            applicants: [],
        }),
        methods: {
            async loadApplicants() {
                const response = await axios.post('/api/get-applicants', {
                    advertisementId: this.advertisementId,
                });

                return response.data || [];
            },
            /**
             * @param {String} coverLetter
             * @param {String} applicantName
             */
            downloadCoverLetter(coverLetter, applicantName) {
                let letter = new jsPDF();
                letter.setFont('Times New Roman');
                letter.setFontSize(11);

                letter.text(coverLetter, 10, 10);

                letter.save('coverLetter-'+ slugify(applicantName) + '.pdf');
            }
        },
        watch: {
            async advertisementId() {
                const applicants = await this.loadApplicants();

                this.applicants = applicants.map(applicant => AdvertisementReply.fromArray(applicant));
            },
        }
    }
</script>
