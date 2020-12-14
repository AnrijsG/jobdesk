<template>
    <div>
        <div class="modal fade" tabindex="-1" id="registerModal" data-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Register</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ValidationObserver v-slot="{ invalid, validated }">
                            <ValidationProvider rules="required" v-slot="{errors}">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input v-model="name" class="form-control" type="text">
                                </div>

                                <p class="text-danger">{{ errors[0] }}</p>
                            </ValidationProvider>

                            <ValidationProvider rules="required|email" v-slot="{errors}">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input v-model="email" class="form-control" type="text">
                                </div>

                                <p class="text-danger">{{ errors[0] }}</p>
                            </ValidationProvider>

                            <ValidationProvider :bails="false" rules="required|min:8" v-slot="{errors}">
                                <div class="form-group">
                                    <label>Password:</label>
                                    <input v-model="password" class="form-control" name="password" type="password">
                                </div>

                                <p class="text-danger">{{ errors[0] }}</p>
                            </ValidationProvider>

                            <p>Are you a member of a registered company?</p>
                            <div class="form-group">
                                <label class="mr-2">
                                    <input v-model="isExistingEnvironment" type="radio" name="isExistingEnvironment" :value="true">
                                    Yes
                                </label>
                                <label>
                                    <input v-model="isExistingEnvironment" type="radio" name="isExistingEnvironment" :value="false">
                                    No
                                </label>
                            </div>

                            <ValidationProvider v-if="!isExistingEnvironment" rules="required" v-slot="{errors}">
                                <div class="form-group">
                                    <label>Select role</label>
                                    <select v-model="role" class="form-control">
                                        <option disabled value="" disabled>Select role</option>
                                        <option v-for="(role, index) in roles" :value="role" :key="index">
                                            {{ getRoleDisplayText(role) }}
                                        </option>
                                    </select>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                            </ValidationProvider>

                            <span v-if="shouldDisplayRoleDropdown">
                                <ValidationProvider v-if="role === 'advertiser'" rules="required" v-slot="{errors}">
                                    <div class="form-group">
                                        <label>Company name:</label>
                                        <input v-model="additionalData.companyName" class="form-control" type="text">
                                    </div>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </ValidationProvider>
                            </span>

                            <span v-if="isExistingEnvironment">
                                <ValidationProvider rules="required" v-slot="{errors}">
                                    <div class="form-group">
                                        <label>Registration Hash:</label>
                                        <input v-model="additionalData.registrationHash" class="form-control" type="text">
                                    </div>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </ValidationProvider>
                            </span>

                            <button class="btn btn-danger float-right" :disabled="invalid || !validated" @click="onRegister">Register</button>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate';
import {EnvironmentModel} from "../models/environment.model";
import {mapActions} from "vuex";
import * as authTypes from '../stores/auth.types';

export default {
    name: 'register-modal',
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data: () => ({
        name: '',
        email: '',
        password: '',
        role: '',
        additionalData: {},
        roles: EnvironmentModel.PUBLIC_ROLES,
        isExistingEnvironment: false,
    }),
    methods: {
        async onRegister() {
            try {
                await axios.get('/sanctum/csrf-cookie');
                await axios.post('/auth/register', {email: this.email, password: this.password, name: this.name, role: this.role, additionalData: this.additionalData});

                this.getUser();
            } catch {}
        },
        getRoleDisplayText(role) {
            switch (role) {
                case EnvironmentModel.ROLE_ADVERTISER:
                    return 'Job Advertiser';
                case EnvironmentModel.ROLE_APPLIER:
                    return 'Job Applier';
            }
        },
        ...mapActions('auth', {
            getUser: authTypes.ACTION_GET_CURRENT_USER,
        }),
    },
    computed: {
        shouldDisplayRoleDropdown() {
            return this.role === 'advertiser' && !this.isExistingEnvironment;
        },
    },
}
</script>
