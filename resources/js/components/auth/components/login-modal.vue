<template>
    <div>
        <div class="modal fade" tabindex="-1" id="loginModal">
            <div class="modal-dialog">
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
                                        <input v-model="email" class="form-control" type="text">
                                    </div>

                                    <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                            </ValidationProvider>
                            <div class="form-group">
                                <label>Password:</label>
                                <input v-model="password" class="form-control" type="password">
                            </div>

                            <button @click="onLogin" class="btn btn-danger float-right" :disabled="invalid">Login</button>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate';

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
                await axios.post('/auth/login', {email: this.email, password: this.password});

                this.$emit('EVENT_USER_LOGGED_IN');
            } catch {}
        },
    },
}
</script>
