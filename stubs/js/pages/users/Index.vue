<template>
    <div>
        <the-table :datatable="datatable" :fields="fields">
            <template #header_buttons>
                <b-button-group size="sm">
                    <b-button variant="dark"
                              @click="currentItem={
                                  name:null,
                                  email:null,
                                  password:null
                              }"
                              v-b-modal:edit_modal>
                        <b-icon-plus/>
                    </b-button>
                </b-button-group>
            </template>
            <b-table
                ref="datatable"
                :no-provider-filtering="false"
                :api-url="baseUrl+'/backend/users'"
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
                    title:'Add/Edit User',
                    hideFooter:true
                 }"
                 @hidden="currentItem=null">
            <b-form v-if="currentItem" @submit.prevent="handleSubmit">
                <b-form-group label="Name">
                    <b-input v-model="currentItem.name" placeholder="User Name"/>
                </b-form-group>
                <b-form-group label="Email">
                    <b-input v-model="currentItem.email" placeholder="User Email Address"/>
                </b-form-group>
                <b-form-group label="Role">
                    <b-select
                        required
                        text-field="name"
                        value-field="id"
                        v-model="currentItem.role_id"
                        :options="roles"/>
                </b-form-group>
                <b-form-group label="Branch">
                    <b-select
                        required
                        text-field="branch_name"
                        value-field="id"
                        v-model="currentItem.branch_id"
                        :options="branches"/>
                </b-form-group>
                <b-form-group label="Password">
                    <b-input type="password" v-model="currentItem.password"/>
                </b-form-group>
                <!--                <b-form-group label="Confirm Password">-->
                <!--                    <b-input type="password" v-model="currentItem.password_confirmation"/>-->
                <!--                </b-form-group>-->
                <b-button type="submit" variant="dark" block>SUBMIT</b-button>
            </b-form>
<!--            <pre v-html="currentItem"></pre>-->
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
                     :items="o2t(currentItem,['email_verified_at'])">
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
        roles() {
            return window.s('roles');
        },
        branches() {
            return window.s('branches');
        },
    },
    data() {
        return {
            currentItem: null,
            fields: [
                {key: 'id', sortable: true},
                {key: 'name', sortable: true},
                {key: 'email', sortable: true},
                {
                    key: 'created_at', sortable: true,
                    formatter: v => this.$options.filters.dayjs(v)
                },
                {key: 'action', thClass: 'text-right', tdClass: 'text-right'},
            ]
        }
    },
    methods: {
        o2t,
        handleSubmit() {
            axios
                .post(this.baseUrl + "/backend/users/store", JSON.parse(JSON.stringify(this.currentItem)))
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
