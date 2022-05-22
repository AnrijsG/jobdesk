<template>
    <div class="main__background bg-light">
        <div class="main__top bg-white" style="position: sticky; top: 0; z-index: 100; flex: 1" id="header">
            <div class="main__content d-flex my-4" style="align-items: center">
                <router-link class="home-button" :to="{name: 'homepage'}">
                    <div>
                        <p @click="scrollToTop" class="d-inline text-dark m-0" style="font-size: 24px"><strong>Job</strong>Desk</p>
                    </div>
                </router-link>

                <auth-buttons></auth-buttons>
            </div>
            <hr class="m-0">
        </div>

        <router-view></router-view>

        <hr class="m-0">

        <div class="bg-white">
            <div class="main main__content py-4">
                <p class="text-right mb-0"><strong>JobDesk</strong> 2022</p>
            </div>
        </div>
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
        window.addEventListener('scroll', this.toggleHeaderShadow);
    },
    methods: {
        ...mapActions('auth', {
            getCurrentUser: authTypes.ACTION_GET_CURRENT_USER,
        }),
        toggleHeaderShadow() {
            if ($(window).scrollTop() !== 0) {
                $('#header').addClass('shadow');
                $('#welcomeText').addClass('main__top__welcome-text-hide')
            } else {
                $('#header').removeClass('shadow');
                $('#welcomeText').removeClass('main__top__welcome-text-hide')
            }
        },
        scrollToTop() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        },
    },
    computed: {
        ...mapGetters('auth', {
            /** @type {UserModel|*} */
            currentUser: authTypes.GET_CURRENT_USER,
        }),
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

<style lang="scss" scoped>
.home-button:hover {
    text-decoration: none;
}
</style>
