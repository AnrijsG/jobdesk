<template>
    <div>
        <div class="modal fade" tabindex="-1" id="loginModal" data-backdrop="false">
            <div class="modal-dialog" style="z-index: 1051">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Login</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ValidationObserver v-slot="{ invalid }">
                            <ValidationProvider name="email" rules="required|email">
                                <div slot-scope="{ errors }">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input @keydown.enter="onLogin" v-model="email" class="form-control" type="text">
                                    </div>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                            </ValidationProvider>
                            <div class="form-group">
                                <label>Password:</label>
                                <input @keydown.enter="onLogin" v-model="password" class="form-control" type="password">
                            </div>

                            <button @click="onLogin" class="btn btn-purple float-right" :disabled="invalid || !canSubmit">
                                Login
                            </button>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate';
import {mapActions, mapGetters} from "vuex";
import * as storeTypes from '../stores/auth.types';

export default {
    name: 'login-modal',
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data: () => ({
        email: '',
        password: '',
        canSubmit: true,
    }),
    methods: {
        async onLogin() {
            try {
                this.canSubmit = false;
                await this.login({email: this.email, password: this.password});

                $('#loginModal').modal('hide');
            } catch {}

            this.canSubmit = true;
        },
        ...mapActions('auth', {
            login: storeTypes.ACTION_LOGIN,
        }),
    },
}
</script>
