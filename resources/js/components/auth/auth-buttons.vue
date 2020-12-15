<template>
    <div id="header" class="w-100 d-flex" style="justify-content: flex-end">
        <div class="m-0" v-if="!currentUser">
            <p class="d-inline m-0">
                <a type="button" class="btn btn-outline-purple mr-2" data-toggle="modal" data-target="#loginModal">
                    Login
                </a>
            </p>
            <p class="d-inline m-0">
                <a type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#registerModal">
                    Register
                </a>
            </p>

            <login-modal></login-modal>
            <register-modal></register-modal>
        </div>
        <div v-else>
            <p class="m-0 mb-2 text-dark" id="welcomeText"><strong>Welcome, </strong>{{ currentUser.name }}!</p>

            <b-dropdown text="Account" right block variant="outline-purple" class="float-right">
                <b-dropdown-item v-if="canAccessDashboard && $route.name !== 'dashboard'" href="#">
                    <router-link class="text-dark dropdown-selection" :to="{name: 'dashboard'}">
                        <p class="m-0">Dashboard</p>
                    </router-link>
                </b-dropdown-item>
                <b-dropdown-item href="#">
                    <p class="m-0">
                        <a type="button" @click="onLogout" class="w-100 text-dark dropdown-selection">
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
<style lang="scss" scoped>
.dropdown-selection:active {
    text-decoration: none;
    color: white !important;
}

.dropdown-selection:hover {
    text-decoration: none;
}
</style>
