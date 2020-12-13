<template>
    <div>
        <b-table :items="getEnvironmentUsersTableData"
                 sticky-header
                 head-variant="light"
        />
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import * as storeTypes from "../../auth/stores/auth.types";

    export default {
        name: 'user-management',
        mounted() {
            this.getEnvironmentUsers();
        },
        methods: {
            ...mapActions('auth', {
                getEnvironmentUsers: storeTypes.ACTION_GET_ENVIRONMENT_USERS,
            }),
        },
        computed: {
            ...mapGetters('auth', {
                /** @type Array<UserModel>|* */
                environmentUsers: storeTypes.GET_ENVIRONMENT_USERS,
            }),
            getEnvironmentUsersTableData() {
                return this.environmentUsers.map(user => (
                    {
                        'ID': user.userId,
                        'Name': user.name,
                        'Email': user.email,
                    }
                ));
            },
        },
    }
</script>
