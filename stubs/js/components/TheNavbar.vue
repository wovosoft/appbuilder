<template>
    <b-navbar toggleable="lg" type="dark" variant="dark" tag="header" class="shadow">
        <b-container>
            <b-navbar-brand :to="{name: 'Dashboard'}" exact exact-active-class="active">
                {{ brand }}
            </b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item-dd text="HRM">
<!--                        <b-dd-item :to="{name:'Employees'}">-->
<!--                            Employees-->
<!--                        </b-dd-item>-->
                    </b-nav-item-dd>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dd right>
                        <!-- Using 'button-content' slot -->
                        <template #button-content>
                            <em>{{ user.name }}</em>
                        </template>
                        <b-dd-item :to="{name:'Profile'}">Profile</b-dd-item>
                        <b-dd-item href="#" @click="logout">
                            Sign Out
                        </b-dd-item>
                        <b-dd-divider/>
                        <b-dropdown-item :to="{name:'Users'}" exact exact-active-class="active">
                            Users
                        </b-dropdown-item>
                        <b-dropdown-item :to="{name:'Roles'}" exact exact-active-class="active">
                            Roles
                        </b-dropdown-item>
                        <b-dd-divider/>
                    </b-nav-item-dd>
                </b-navbar-nav>
            </b-collapse>
        </b-container>
    </b-navbar>
</template>
<script>
export default {
    props: {
        brand: {
            type: String,
            default: () => "BKB LIMS"
        }
    },
    computed: {
        user() {
            return s('user');
        }
    },
    methods: {
        logout() {
            document.getElementById('logout_form').submit();
        }
    }
}
</script>
