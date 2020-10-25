<template>
    <div>
        <div class="modal fade" tabindex="-1" id="registerModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Register</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ValidationObserver v-slot="{ invalid }">
                            <ValidationProvider name="name" rules="required">
                                <div slot-scope="{ errors }">
                                    <div class="form-group">
                                        <label>Name:</label>
                                        <input v-model="name" class="form-control" type="text">
                                    </div>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                            </ValidationProvider>

                            <ValidationProvider name="email" rules="required|email">
                                <div slot-scope="{ errors }">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input v-model="email" class="form-control" type="text">
                                    </div>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                            </ValidationProvider>

                            <ValidationProvider name="email" rules="required|min:8">
                                <div slot-scope="{ errors }">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Password:</label>
                                            <input v-model="password" class="form-control" type="password">
                                        </div>
                                    </div>

                                <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                            </ValidationProvider>

                            <div class="form-group">
                                <label>Select role</label>
                                <select v-model="role" class="form-control">
                                    <option disabled value="">Select role</option>
                                    <option v-for="(role, index) in roles" :value="role" :key="index">{{ getRoleDisplayText(role) }}</option>
                                </select>
                            </div>

                            <ValidationProvider name="email" rules="required|min:8">
                                <div slot-scope="{ errors }">
                                    <div class="form-group" v-if="role === 'advertiser'">
                                        <label>Company name:</label>
                                        <input v-model="additionalData.companyName" class="form-control" type="text">
                                    </div>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                            </ValidationProvider>

                            <button class="btn btn-danger float-right" :disabled="invalid">Register</button>
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
    }),
    methods: {
        async onLogin() {
            try {
                await axios.post('/auth/register', {email: this.email, password: this.password, name: this.name, role: this.role});

                this.$emit('EVENT_USER_LOGGED_IN');
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
    },
}
</script>
