<template>
    <div id="header" class="w-100 d-flex" style="justify-content: flex-end">
        <div class="m-0" v-if="!currentUser">
            <p class="d-inline m-0">
                <a type="button" class="text-dark mr-2" data-toggle="modal" data-target="#loginModal">
                    Login
                </a>
            </p>
            <p class="d-inline m-0">
                <a type="button" class="text-dark" data-toggle="modal" data-target="#registerModal">
                    Register
                </a>
            </p>

            <login-modal></login-modal>
            <register-modal></register-modal>
        </div>
        <div v-else>
            <p class="m-0 mb-2 text-dark">Welcome, {{ currentUser.name }}!</p>

            <b-dropdown text="Account" right block variant="outline-purple" class="float-right">
                <b-dropdown-item v-if="canAccessDashboard && $route.name !== 'dashboard'" href="#">
                    <router-link class="text-dark" :to="{name: 'dashboard'}">
                        <p class="m-0">Dashboard</p>
                    </router-link>
                </b-dropdown-item>
                <b-dropdown-item href="#">
                    <p class="m-0">
                        <a type="button" @click="onLogout" class="w-100 text-dark">
                            Logout
                        </a>
                    </p>
                </b-dropdown-item>
            </b-dropdown>
        </div>
    </div>
</template>

<script>
import * as storeTypes from './stores/auth.types';
import {mapActions, mapGetters} from "vuex";
import LoginModal from "../auth/components/login-modal";
import RegisterModal from "../auth/components/register-modal";
import {EnvironmentModel} from "./models/environment.model";

export default {
    name: 'auth-buttons',
    components: {RegisterModal, LoginModal},
    methods: {
        ...mapActions('auth', {
            /** @type {UserModel|*} */
            getCurrentUser: storeTypes.ACTION_GET_CURRENT_USER,
        }),
        async onLogout() {
            try {
                await axios.post('/auth/logout');

                this.getCurrentUser();

                await this.$router.push('/');
            } catch {
            }
        },
    },
    computed: {
        ...mapGetters('auth', {
            currentUser: storeTypes.GET_CURRENT_USER,
        }),
        canAccessDashboard() {
            const userRole = this.currentUser?.environment.role;
            if (!userRole) {
                return false;
            }

            return userRole === EnvironmentModel.ROLE_ADVERTISER || userRole === EnvironmentModel.ROLE_ADMIN;
        },
    },
}
</script>
<style lang="scss">
.purple {
    color: rgb(95, 66, 255);
}

.btn-outline-purple {
    color: rgb(95, 66, 255);
    border-color: rgb(95, 66, 255);
}

.btn-outline-purple:hover {
    color: white;
    background-color: rgb(95, 66, 255);
    border-color: rgb(95, 66, 255);
    box-shadow: 0 0.25rem 0.5rem #5f42ff !important;
}
</style>
