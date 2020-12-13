<template>
    <div v-if="currentUser">
        <h5 class="text-center">Environment Tools</h5>
        <hr>

        <div class="mb-3"><strong>Company:</strong> {{ currentUser.environment.companyName }}</div>

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

            <button @click="resetRegistrationHash" class="btn btn-danger mr-2">
                <span class="material-icons">
                    autorenew
                </span>
            </button>
        </div>
        <div v-else class="mb-2">
            <div class="alert alert-info d-flex" style="align-items: center;">
                <div class="mr-3">
                    <i class="material-icons" style="font-size: 36px;">
                        help_outline
                    </i>
                </div>
                <div>
                    You currently do not have a registration hash generated, if you wish to invite new users to your environment, click <a href="#" @click="resetRegistrationHash"><strong>here.</strong></a>
                </div>
            </div>
        </div>

        <user-management />
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
    import * as storeTypes from "../auth/stores/auth.types";
    import UserManagement from "./components/user-management";

    export default {
        name: 'environment-tools',
        components: {UserManagement},
        computed: {
            ...mapGetters('auth', {
                /** @type {UserModel|*} */
                currentUser: storeTypes.GET_CURRENT_USER,
                /** @type {String|*} */
                registrationHash: storeTypes.GET_REGISTRATION_HASH,
            }),
        },
        mounted() {
            this.getRegistrationHash();
        },
        methods: {
            ...mapActions('auth', {
                resetRegistrationHash: storeTypes.ACTION_RESET_REGISTRATION_HASH,
                getRegistrationHash: storeTypes.ACTION_GET_REGISTRATION_HASH,
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
                })
            },
        },
    }
</script>
