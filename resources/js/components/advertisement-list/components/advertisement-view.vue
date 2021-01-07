<template>
    <div v-if="advertisement" class="bg-white">
        <div class="row shadow p-4">
            <div class="col-8 col-xl-10">
                <h3><strong>{{ advertisement.title }}</strong></h3>
                <p class="m-0">
                    {{ advertisement.environment.companyName || '' }}

                    <span v-if="advertisement.environment.logoUrl">
                        <img style="max-height: 50px;"
                             :src="advertisement.environment.logoUrl"
                             :alt="advertisement.environment.companyName + ' logo'"
                        >
                    </span>
                </p>
            </div>
            <div class="col-4 col-xl-2">
                <p v-if="advertisement.salaryFrom && advertisement.salaryTo"
                   class="m-0"
                >
                    <strong>Salary:</strong> {{ advertisement.salaryFrom }}€ - {{ advertisement.salaryTo }}€ / month
                </p>

                <p v-if="advertisement.location"
                   class="m-0"
                >
                    <strong>Location:</strong> {{ advertisement.location }}
                </p>

                <p v-if="advertisement.expirationDate"
                   class="m-0"
                >
                    <strong>Ends on: </strong> {{ advertisement.expirationDate }}
                </p>
            </div>
        </div>

        <div class="pt-2 p-4">
            <p class="mb-1" v-for="text in advertisement.content.split('\n')">
                {{ text }}
            </p>

            <div v-if="!shouldAllowInternalSubmission" class="text-right">
                <div v-if="advertisement.areInternalApplicationsEnabled && !currentUser">
                    <div class="alert alert-warning d-flex" style="align-items: center;">
                        <div class="mr-3">
                            <i class="material-icons" style="font-size: 36px;">
                                info
                            </i>
                        </div>
                        <div>
                            To apply for this position you must be a registered user.
                        </div>
                    </div>
                </div>

                <a v-if="advertisement.environment.companyWebsite"
                   class="btn btn-purple w-25"
                   :href="advertisement.environment.companyWebsite"
                   rel="nofollow noopener"
                   target="_blank"
                >
                    Visit advertiser
                </a>
            </div>
            <div v-if="shouldAllowInternalSubmission" class="w-100">
                <div class="text-right mt-2">
                    <a v-if="advertisement.environment.companyWebsite"
                       class="btn btn-warning w-25"
                       :href="advertisement.environment.companyWebsite"
                       rel="nofollow noopener"
                       target="_blank"
                    >
                        Visit advertiser
                    </a>
                </div>

                <hr class="my-4">

                <h5 class="mb-2">Cover letter</h5>
                <ValidationObserver v-slot="{ invalid, validated }">
                    <ValidationProvider name="cover letter" rules="required">
                        <div slot-scope="{ errors }">
                            <b-form-textarea
                                id="cover-letter-input"
                                v-model="coverLetterInput"
                                rows="8"
                            ></b-form-textarea>

                            <p v-if="errors[0]" class="text-danger">{{ errors[0] }}</p>
                        </div>
                    </ValidationProvider>

                    <br>

                    <div class="text-right">
                        <button class="btn btn-purple w-25" :disabled="invalid || !validated" @click="submitApplication">
                            Apply now
                        </button>
                    </div>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate';
import {mapGetters} from "vuex";
import * as storeTypes from "../stores/advertisement.types";
import * as authTypes from '../../auth/stores/auth.types';
import Swal from 'sweetalert2';

export default {
    name: 'advertisement-view',
    data: () => ({
        coverLetterInput: '',
        isLoading: false,
    }),
    methods: {
        async submitApplication() {
            this.isLoading = true;

            try {
                await axios.post('/api/submit-application', {
                    coverLetter: this.coverLetterInput,
                    advertisementId: this.advertisement.advertisementId,
                });
            } catch (e) {
                await Swal.fire({
                    'title': e.response.data.message,
                    'icon': 'error',
                });
                this.isLoading = false;

                return;
            }


            await Swal.fire('Application submitted successfully!');

            this.isLoading = false;
        },
    },
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    computed: {
        ...mapGetters('advertisements', {
            /** @type {AdvertisementModel|*} */
            advertisement: storeTypes.GET_CURRENT_ADVERTISEMENT,
        }),
        ...mapGetters('auth', {
            /** @type {UserModel|*} */
            currentUser: authTypes.GET_CURRENT_USER,
        }),
        shouldAllowInternalSubmission() {
            return this.currentUser?.environment.isApplier()
                && this.advertisement.areInternalApplicationsEnabled;
        }
    }
}
</script>
