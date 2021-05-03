<template>
    <div>
        <the-navbar class="py-0" brand="BKB Office"></the-navbar>
        <b-container fluid class="mt-4">
            <router-view></router-view>
        </b-container>
    </div>
</template>

<script>
import TheNavbar from './TheNavbar'
import TheTable from "./TheTable";

export default {
    name: "TheContainer",
    components: {
        TheTable,
        TheNavbar
    }
}
</script>

<style scoped>

</style>
