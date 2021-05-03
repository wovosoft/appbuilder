import Vue from 'vue'
import VueRouter from "vue-router";

Vue.use(VueRouter);

const Dashboard = () => import("../pages/dashboard/Index");
const Users = () => import("../pages/users/Index");
const Profile = () => import("../pages/users/Profile");
const Roles = () => import("../pages/roles/Index");

//ROUTES_PLACEHOLDER_USED_BY_GENERATOR

export default new VueRouter({
    mode: 'hash', // https://router.vuejs.org/api/#mode
    linkActiveClass: 'open active',
    scrollBehavior: () => ({y: 0}),
    base: '/',
    routes: [
        {
            path: '/',
            name: 'Dashboard',
            component: Dashboard,
        },
        {
            path: '/users',
            name: 'Users',
            component: Users,
        },
        {
            path: '/profile',
            name: 'Profile',
            component: Profile
        },
        {
            path: '/roles',
            name: 'Roles',
            component: Roles
        },
    ]
})
