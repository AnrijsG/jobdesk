import Dashboard from "../views/dashboard";
import Homepage from "../views/homepage";
import Advertisement from "../views/advertisement";
import Portfolio from "../views/portfolio";
import {UserModel} from "../components/auth/models/user.model";
import {EnvironmentModel} from "../components/auth/models/environment.model";

async function getUser() {
    const response = await axios.post('/auth/get-user');

    return response.data;
}

async function canAccessDashboard(to, from, next) {
    const userData = await getUser();
    if (!userData) {
        next({
            name: 'homepage',
        });

        return;
    }

    const user = UserModel.fromArray(userData);
    const userRole = user.environment.role;

    if (user.environment.isAdvertiser() || userRole === EnvironmentModel.ROLE_ADMIN) {
        next(true);
    } else {
        next({
            name: 'homepage',
        });
    }
}

async function canAccessPortfolio(to, from, next) {
    const userData = await getUser();
    if (!userData) {
        next({
            name: 'homepage',
        });

        return;
    }

    const user = UserModel.fromArray(userData);

    if (user.environment.isAdvertiser()) {
        next(true);
    } else {
        next({
            name: 'homepage',
        });
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
        beforeEnter: canAccessDashboard,
    },
    {
        path: '/portfolio',
        name: 'portfolio',
        component: Portfolio,
        beforeEnter: canAccessPortfolio,
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
