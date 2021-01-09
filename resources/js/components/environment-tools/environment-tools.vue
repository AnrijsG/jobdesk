<template>
    <div v-if="currentUser">
        <h5 class="text-center">Company overview</h5>
        <hr>

        <div class="mb-3">
            <div><strong>Company:</strong> {{ currentUser.environment.companyName }}</div>

            <div>
                <p class="m-0">
                    <strong>
                        Logo:
                    </strong>

                    <span class="text-muted" v-if="!currentUser.environment.logoUrl && !isShowUploadClicked">
                        <i>
                            You currently do not have a logo uploaded, click <a @click="isShowUploadClicked = true" style="cursor: pointer"><strong>here</strong></a> to upload one.
                        </i>
                    </span>
                </p>

                <div v-if="currentUser.environment.logoUrl" class="mb-2">
                    <img style="max-width: 200px"
                         :src="currentUser.environment.logoUrl"
                         :alt="currentUser.environment.companyName + ' logo'"
                    >

                    <br>

                    <a class="btn btn-warning d-inline-block"
                       v-if="!isShowUploadClicked"
                       style="cursor: pointer"
                       @click="isShowUploadClicked = true"
                    >
                        Replace logo
                    </a>
                    <a class="btn btn-danger d-inline-block"
                       style="cursor: pointer"
                       @click="deleteLogo"
                    >
                        Delete logo
                    </a>
                </div>

                <input v-if="this.isShowUploadClicked"
                       type="file"
                       @change="selectFile"
                       accept="image/jpeg, image/png"
                >
            </div>
        </div>

        <div class="mb-2 d-flex" style="align-items: center;">
            <p class="m-0 mr-2">
                <strong>Company website:</strong>
            </p>

            <input type="text" class="form-control mr-2" v-model="companyWebsite">

            <button @click="setCompanyWebsite" class="btn btn-success mr-2">
                <span class="material-icons">
                    save
                </span>
            </button>

            <button @click="setCompanyWebsite($event,true)" class="btn btn-danger">
                <span class="material-icons">
                    delete
                </span>
            </button>
        </div>

        <div v-if="registrationHash" class="d-flex mb-3" style="align-items: center">
            <span class="mr-2"><strong>Registration hash:</strong></span>
            <input id="registerHash"
                   class="form-control mr-2"
                   type="text"
                   readonly="readonly"
                   :value="registrationHash"
            />

            <button @click="copyHash" class="btn btn-success mr-2">
                <span class="material-icons">
                    content_copy
                </span>
            </button>

            <button @click="onResetRegistrationHash" class="btn btn-warning mr-2">
                <span class="material-icons">
                    autorenew
                </span>
            </button>

            <button @click="onDeleteRegistrationHash" class="btn btn-danger">
                <span class="material-icons">
                    delete
                </span>
            </button>
        </div>
        <div v-else class="mb-2">
            <div class="text-white alert alert-purple d-flex" style="align-items: center;">
                <div class="mr-3">
                    <i class="material-icons" style="font-size: 36px;">
                        help_outline
                    </i>
                </div>
                <div>
                    You currently do not have a registration hash generated, if you wish to invite new users to your environment, click <a href="#" class="text-white" @click="resetRegistrationHash"><strong>here.</strong></a>
                </div>
            </div>
        </div>

        <user-management v-if="currentUser.isEnvironmentOwner" />
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
    import * as storeTypes from "../auth/stores/auth.types";
    import UserManagement from "./components/user-management";

    export default {
        name: 'environment-tools',
        components: {UserManagement},
        data: () => ({
            logoFile: null,
            isShowUploadClicked: false,
            companyWebsite: '',
        }),
        computed: {
            ...mapGetters('auth', {
                /** @type {UserModel|*} */
                currentUser: storeTypes.GET_CURRENT_USER,
                /** @type {String|*} */
                registrationHash: storeTypes.GET_REGISTRATION_HASH,
            })
        },
        mounted() {
            this.getRegistrationHash();

            if (this.currentUser) {
                this.companyWebsite = this.currentUser.environment.companyWebsite;
            }
        },
        methods: {
            ...mapActions('auth', {
                resetRegistrationHash: storeTypes.ACTION_RESET_REGISTRATION_HASH,
                getRegistrationHash: storeTypes.ACTION_GET_REGISTRATION_HASH,
                deleteRegistrationHash: storeTypes.ACTION_DELETE_REGISTRATION_HASH,
                getCurrentUser: storeTypes.ACTION_GET_CURRENT_USER,
            }),
            copyHash() {
                const registerHashInputField = document.getElementById('registerHash');
                registerHashInputField.select();
                registerHashInputField.setSelectionRange(0, 99999);

                document.execCommand('copy');

                this.$bvToast.toast('Registration hash successfully copied to clipboard!', {
                    title: 'Notification',
                    variant: 'secondary',
                    solid: true
                });
            },
            onResetRegistrationHash() {
                this.resetRegistrationHash();

                this.$bvToast.toast('Registration hash successfully reset!', {
                    title: 'Notification',
                    variant: 'secondary',
                    solid: true
                });
            },
            onDeleteRegistrationHash() {
                this.deleteRegistrationHash();

                this.$bvToast.toast('Registration hash successfully deleted!', {
                    title: 'Notification',
                    variant: 'secondary',
                    solid: true
                });
            },
            selectFile(event) {
                this.logoFile = event.target.files[0];
            },
            async deleteLogo() {
                try {
                    await axios.post('/api/delete-logo');
                } catch {
                    this.throwErrorNotification();

                    return;
                }

                this.getCurrentUser();

                this.$bvToast.toast('Logo successfully deleted', {
                    title: 'Notification',
                    variant: 'secondary',
                    solid: true
                });
            },
            async setCompanyWebsite(e, shouldDelete = false) {
                try {
                    let newCompanyWebsite = this.companyWebsite;
                    if (shouldDelete) {
                        newCompanyWebsite = '';
                    }
                    await axios.post('/api/set-company-website', {
                        'companyWebsite': newCompanyWebsite,
                    });

                    this.getCurrentUser();

                    this.companyWebsite = this.currentUser.environment.companyWebsite;

                    this.$bvToast.toast('Company website successfully changed', {
                        title: 'Notification',
                        variant: 'secondary',
                        solid: true
                    });
                } catch {
                    this.throwErrorNotification();
                }
            },
            throwErrorNotification() {
                this.$bvToast.toast('There was an error with your request, please try again later', {
                    title: 'Notification',
                    variant: 'danger',
                    solid: true
                });
            }
        },
        watch: {
            async logoFile(newValue) {
                if (!newValue) {
                    return;
                }

                const data = new FormData();
                data.append('logoFile', this.logoFile);

                try {
                    await axios.post("/api/upload-logo", data);
                } catch {
                    this.throwErrorNotification();

                    return;
                }

                this.getCurrentUser();

                this.isShowUploadClicked = false;

                this.$bvToast.toast('Logo successfully uploaded', {
                    title: 'Notification',
                    variant: 'secondary',
                    solid: true
                });
            },
            currentUser(newValue) {
                this.companyWebsite = newValue.environment.companyWebsite;
            }
        },
    }
</script>
