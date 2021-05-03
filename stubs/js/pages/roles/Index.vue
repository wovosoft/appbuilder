<template>
    <div>
        <the-table :datatable="datatable" :fields="fields">
            <template #header_buttons>
                <b-button-group size="sm">
                    <b-button variant="dark" @click="initAddForm">
                        <b-icon-plus/>
                    </b-button>
                </b-button-group>
            </template>
            <b-table
                ref="datatable"
                :no-provider-filtering="false"
                :api-url="baseUrl+'/backend/roles'"
                v-bind="{...attrs}">
                <template #cell(action)="row">
                    <b-button-group size="sm">
                        <b-button variant="dark"
                                  title="View"
                                  @click="currentItem=JSON.parse(JSON.stringify(row.item))"
                                  v-b-modal:view_modal>
                            <b-icon-eye/>
                        </b-button>
                        <b-button variant="warning"
                                  v-b-modal:edit_modal
                                  @click="currentItem=JSON.parse(JSON.stringify(row.item))"
                                  title="Edit">
                            <b-icon-pencil-square/>
                        </b-button>
                        <b-button variant="danger" title="Delete">
                            <b-icon-trash/>
                        </b-button>
                    </b-button-group>
                </template>
            </b-table>
        </the-table>
        <b-modal lazy id="edit_modal"
                 v-bind="{
                    bodyClass:'p-2',
                    headerBgVariant:'dark',
                    headerTextVariant:'light',
                    title:'Add/Edit Role',
                    hideFooter:true
                 }"
                 @hidden="currentItem=null">
            <b-form v-if="currentItem" @submit.prevent="handleSubmit">
                <b-form-group label="Name">
                    <b-input v-model="currentItem.name" placeholder="Role Name"/>
                </b-form-group>
                <b-form-group label="Permissions">
                    <b-form-checkbox-group
                        stacked
                        v-model="currentItem.permissions"
                        :options="permissions"
                        class="mb-3"
                        value-field="value"
                        text-field="text"
                    ></b-form-checkbox-group>
                </b-form-group>
                <b-button type="submit" variant="dark" block>SUBMIT</b-button>
            </b-form>
        </b-modal>
        <b-modal lazy id="view_modal"
                 v-bind="{
                    bodyClass:'p-2',
                    headerBgVariant:'dark',
                    headerTextVariant:'light',
                    title:'View Details'
                 }"
                 @hidden="currentItem=null">
            <b-table v-if="currentItem"
                     thead-class="d-none"
                     bordered
                     hover
                     striped
                     small
                     head-variant="dark"
                     :items="o2t(currentItem)">
                <template #cell(key)="row">
                    {{ row.item.key|startCase }}
                </template>
                <template #cell(value)="row">
                    <template v-if="['created_at','updated_at'].includes(row.item.key)">
                        {{ row.item.value|dayjs }}
                    </template>
                    <template v-else>
                        {{ row.item.value }}
                    </template>
                </template>
            </b-table>
        </b-modal>
    </div>
</template>
<script>
import TheTable from "../../components/TheTable";
import dt, {o2t} from "../../partials/datatable";

export default {
    components: {
        TheTable
    },
    mixins: [dt],
    computed: {
        baseUrl() {
            return baseUrl();
        },
        permissions() {
            return window.s('permissions');
        }
    },
    data() {
        return {
            currentItem: null,
            fields: [
                {key: 'id', sortable: true},
                {key: 'name', sortable: true},
                {key: 'permissions', sortable: true},
                {key: 'action', thClass: 'text-right', tdClass: 'text-right'},
            ]
        }
    },
    methods: {
        o2t,
        initAddForm() {
            this.$set(this, 'currentItem', {
                "name": "",
                "permissions": []
            });
            this.$bvModal.show('edit_modal');
        },
        handleSubmit() {
            axios
                .post(this.baseUrl + "/backend/roles/store", JSON.parse(JSON.stringify(this.currentItem)))
                .then(({data}) => {
                    console.log(data)
                    this.$bvToast.toast(data.message, {
                        title: data.title,
                        autoHideDelay: 3000,
                        appendToast: true,
                        variant: data.variant || 'success'
                    });
                    this.$refs.datatable.refresh();
                    this.$bvModal.hide('edit_modal');
                })
                .catch(({response}) => {
                    console.log(response.data)
                    this.$bvToast.toast(response.data.message, {
                        title: "Server Error",
                        variant: 'danger',
                        autoHideDelay: 3000,
                        appendToast: true
                    });
                })
        }
    }
}
</script>
