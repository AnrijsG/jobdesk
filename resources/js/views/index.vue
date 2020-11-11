<template>
    <div class="main__background h-100 bg-light">
        <div class="main__top" :class="backgroundClassColour">
            <div class="main__content d-flex mt-4">
                <router-link v-if="($route.name !== 'homepage')" class="text-white mr-2" :to="{name: 'homepage'}">
                    <span class="material-icons">
                        first_page
                    </span>
                </router-link>
                <router-link v-if="canAccessDashboard && $route.name !== 'dashboard'" class="text-white" :to="{name: 'dashboard'}">Dashboard</router-link>
                <auth-buttons></auth-buttons>
            </div>
        </div>

        <router-view></router-view>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import * as authTypes from "../components/auth/stores/auth.types";
import AuthButtons from "../components/auth/auth-buttons";
import {EnvironmentModel} from "../components/auth/models/environment.model";

export default {
    name: 'index',
    components: {AuthButtons},
    mounted() {
        this.getCurrentUser();
    },
    methods: {
        ...mapActions('auth', {
            getCurrentUser: authTypes.ACTION_GET_CURRENT_USER,
        }),
    },
    computed: {
        ...mapGetters('auth', {
            /** @type {UserModel|*} */
            currentUser: authTypes.GET_CURRENT_USER,
        }),
        backgroundClassColour() {
            if (this.$route.path === '/dashboard') {
                return 'main__top__red';
            }

            if (this.$route.name === 'advertisement') {
                return 'main__top__purple';
            }

            return 'main__top__blue';
        },
        /**
         * @return {Boolean}
         */
        canAccessDashboard() {
            const userRole = this.currentUser?.environment.role;
            if (!userRole) {
                return false;
            }

            return userRole === EnvironmentModel.ROLE_ADVERTISER || userRole === EnvironmentModel.ROLE_ADMIN;
        },
    }
}
</script>
