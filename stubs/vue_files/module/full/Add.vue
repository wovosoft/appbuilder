<template>
    <b-modal
        lazy
        @hidden="$emit('hidden',true)"
        :visible="visible"
        v-bind="{
            headerClass:'p-2',
            footerClass:'p-2',
            buttonSize:'sm',
            size:'xl',
            bodyClass:'p-2',
            headerBgVariant:'dark',
            headerTextVariant:'light',
            title:'ADD_EDIT_DETAILS_TITLE',
            hideFooter:true
        }">
        <template #default="{hide}">
            <b-form @submit.prevent="handleSubmit(hide)">
                FORM_FIELDS
                <!--                                <pre v-html="currentItem"></pre>-->
                <b-button
                    size="sm"
                    type="submit"
                    variant="dark"
                    block>
                    SUBMIT
                </b-button>
            </b-form>
        </template>
    </b-modal>
</template>

<script>
import VSelect from "../../components/VSelect";
import j2fd from "../../partials/jsonToFormData";
import initAddForm from "./initAddForm"


export default {
    components: {
        VSelect,
    },
    props: {
        item: {
            type: Object,
            required: true
        },
        visible: {
            type: Boolean,
            default: false
        }
    },
    beforeMount() {
        this.$set(this, 'currentItem', Object.assign(
            {},
            JSON.parse(JSON.stringify(initAddForm)),
            JSON.parse(JSON.stringify(this.item))
        ));
    },
    data() {
        return {
            currentItem: null
        }
    },
    methods: {
        handleSubmit(hide) {
            axios
                .post(this.$root.baseUrl + "STORE_URL", j2fd(this.currentItem))
                .then(({data}) => {
                    this.$root.msgBox(data);
                    this.$emit('success', data);
                    hide();
                })
                .catch(({response}) => {
                    console.log(response)
                    this.$root.msgBox(response.data);
                    this.$emit('failed', response.data);
                })
        }
    }
}
</script>
