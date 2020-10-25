<template>
    <div class="main__background h-100 bg-light">
        <div class="main__top" :class="backgroundClassColour">
            <div class="main__content d-flex mt-4">
                <router-link v-if="currentUser" class="text-white" :to="{name: getRouteItem.route}">{{ getRouteItem.name }}</router-link>
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
            currentUser: authTypes.GET_CURRENT_USER,
        }),
        backgroundClassColour() {
            if (this.$route.path === '/dashboard') {
                return 'main__top__red';
            }

            return 'main__top__blue';
        },
        getRouteItem() {
            if (this.$route.path === '/dashboard') {
                return {
                    route: 'homepage',
                    name: 'Homepage',
                };
            }

            return {
                route: 'dashboard',
                name: 'Dashboard',
            };
        }
    }
}
</script>
