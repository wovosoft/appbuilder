<template>
    <div>
        <b-container style="min-height: calc(100vh - 80px);">
            <b-jumbotron class="shadow-md">
                <b-button class="float-right"
                          v-b-modal:developer
                          size="sm"
                          variant="dark">
                    <b-icon-code/>
                </b-button>
                <div class="text-center mb-5">
                    <b-img-lazy
                        fluid
                        src="images/logo.png"
                    />
                </div>
                <h2 class="text-center mt-5">
                    Office Management System
                </h2>
                <p class="text-center">
                    Bangladesh Krishi Bank<br>
                    Divisional Office, Khulna.<br>
                    &copy; Copyright - {{ date }}
                </p>
            </b-jumbotron>
        </b-container>
        <b-modal id="developer"
                 body-class="text-center"
                 centered
                 hide-backdrop
                 body-bg-variant="dark"
                 body-text-variant="light"
                 hide-footer
                 size="sm"
                 title="Developed By"
                 header-bg-variant="dark"
                 header-text-variant="light">
            <a href="https://narayanadhikary.netlify.app" class="text-white" target="_blank">
                Narayan Chandra Adhikary
            </a> <br>
            <p class="text-secondary">
                Senior Officer (ICT),<br>
                Divisional Office, Khulna,<br>
                Bangladesh Krishi Bank
            </p>
            <b-button-group size="sm">
                <b-button href="mailto:narayanadhikary24@gmail.com">
                    <b-icon-envelope/>
                </b-button>
                <b-button href="tel:+8801764954227">
                    <b-icon-phone/>
                </b-button>
                <b-button href="https://fb.com/narayan.adhikary" target="_blank">
                    <b-icon-facebook/>
                </b-button>
                <b-button href="https://github.com/wovosoft" target="_blank">
                    <b-icon-github/>
                </b-button>
            </b-button-group>
        </b-modal>
    </div>
</template>
<script>
export default {
    data() {
        return {
            date: new Date().getFullYear()
        }
    }
}
</script>
