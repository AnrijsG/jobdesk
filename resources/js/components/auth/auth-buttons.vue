<template>
    <span id="header" class="w-100 d-flex" style="justify-content: flex-end">
        <span v-if="!currentUser">
            <a type="button" class="text-white" data-toggle="modal" data-target="#loginModal">Login</a>
            <a type="button" class="text-white" data-toggle="modal" data-target="#registerModal">Register</a>

            <login-modal @EVENT_USER_LOGGED_IN="onLogin"></login-modal>
            <register-modal></register-modal>
        </span>
        <span v-else>
            <a type="button" @click="onLogout" class="text-white">Logout</a>
        </span>
    </span>
</template>

<script>
    import * as storeTypes from './stores/auth.types';
    import {mapActions, mapGetters} from "vuex";
    import LoginModal from "../auth/components/login-modal";
    import RegisterModal from "../auth/components/register-modal";

    export default {
        name: 'auth-buttons',
        components: {RegisterModal, LoginModal},
        methods: {
            ...mapActions('auth', {
                getCurrentUser: storeTypes.ACTION_GET_CURRENT_USER,
            }),
            async onLogout() {
                try {
                    await axios.post('/auth/logout');

                    this.getCurrentUser();
                } catch {}
            },
            onLogin() {
                $('#loginModal').modal('hide');

                this.getCurrentUser();
            }
        },
        computed: {
            ...mapGetters('auth', {
                currentUser: storeTypes.GET_CURRENT_USER,
            }),
        }
    }
</script>
