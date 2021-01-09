<template>
    <div class="border">
        <div class="border bg-light d-flex p-2">
            <div class="w-100">
                <strong>
                    User ID
                </strong>
            </div>
            <div class="w-100">
                <strong>
                    Name
                </strong>
            </div>
            <div class="w-100">
                <strong>
                    Email
                </strong>
            </div>
            <div class="w-100">
                <strong>
                    Actions
                </strong>
            </div>
        </div>
        <div style="max-height: 300px; overflow: hidden scroll">
            <div v-for="user in environmentUsers" class="d-flex p-2 border-bottom">
                <div class="w-100">
                    {{ user.userId }}
                </div>
                <div class="w-100">
                    {{ user.name }}

                    <span v-if="user.isEnvironmentOwner" class="badge badge-info">
                    Admin
                </span>
                </div>
                <div class="w-100">
                    {{ user.email }}
                </div>
                <div class="w-100">
                    <button @click="toggleOwnership(user.userId)" class="btn btn-purple m-1" v-if="user.userId !== currentUser.userId">
                        Toggle ownership
                    </button>
                    <button class="btn m-1"
                            :class="user.isActive ? 'btn-danger' : 'btn-success'"
                            v-if="!user.isEnvironmentOwner"
                            @click="toggleActive(user.userId)"
                    >
                        {{ user.isActive ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";
    import * as storeTypes from "../../auth/stores/auth.types";
    import Swal from "sweetalert2";

    export default {
        name: 'user-management',
        mounted() {
            this.getEnvironmentUsers();
        },
        methods: {
            ...mapActions('auth', {
                getEnvironmentUsers: storeTypes.ACTION_GET_ENVIRONMENT_USERS,
            }),
            async toggleActive(id) {
                try {
                    const response = await axios.post('/api/toggle-active', {userId: id});
                    if (!response) {
                        await Swal.fire({
                            'title': 'Something went wrong, please try again later',
                            'icon': 'error',
                        });

                        return;
                    }
                } catch (e) {
                    await Swal.fire({
                        'title': 'Something went wrong, please try again later',
                        'icon': 'error',
                    });

                    return;
                }

                await Swal.fire({
                    'title': 'User status changed',
                    'icon': 'success',
                });

                this.getEnvironmentUsers();
            },
            async toggleOwnership(id) {
                try {
                    const response = await axios.post('/api/toggle-ownership', {userId: id});
                    if (!response) {
                        await Swal.fire({
                            'title': 'Something went wrong, please try again later',
                            'icon': 'error',
                        });

                        return;
                    }
                } catch (e) {
                    await Swal.fire({
                        'title': 'Something went wrong, please try again later',
                        'icon': 'error',
                    });

                    return;
                }

                await Swal.fire({
                    'title': 'User status changed',
                    'icon': 'success',
                });

                this.getEnvironmentUsers();
            },
        },
        computed: {
            ...mapGetters('auth', {
                /** @type Array<UserModel>|* */
                environmentUsers: storeTypes.GET_ENVIRONMENT_USERS,
                currentUser: storeTypes.GET_CURRENT_USER,
            }),
        },
    }
</script>
