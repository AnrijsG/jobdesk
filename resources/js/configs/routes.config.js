import Dashboard from "../views/dashboard";
import Homepage from "../views/homepage";
import Advertisement from "../views/advertisement";
import {UserModel} from "../components/auth/models/user.model";
import {EnvironmentModel} from "../components/auth/models/environment.model";

async function requireLogin(to, from, next) {
    const response = await axios.post('/auth/get-user');
    const userData = response.data;

    if (!userData) {
        next({
            name: 'homepage',
        });
    } else {
        const user = UserModel.fromArray(userData);
        const userRole = user.environment.role;

        if (userRole === EnvironmentModel.ROLE_ADVERTISER || userRole === EnvironmentModel.ROLE_ADMIN) {
            next(true);
        } else {
            next({
                name: 'homepage',
            });
        }
    }
}

export default [
    {
        path: '*',
        redirect: {
            name: 'homepage'
        },
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        beforeEnter: requireLogin,
    },
    {
        path: '/',
        name: 'homepage',
        component: Homepage,
    },
    {
        path: '/advertisement/:advertisementId',
        name: 'advertisement',
        component: Advertisement,
        props: true,
    },
];
