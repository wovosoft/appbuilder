<template>
    <div>
        <the-table :datatable="datatable" :fields="fields" title="Data List">
            <template #header_buttons>
                <b-button-group size="sm">
                    <!--                    <b-button variant="primary" @click="show_filter=!show_filter">-->
                    <!--                        <b-icon :icon="show_filter?'chevron-down':'chevron-up'"/>-->
                    <!--                    </b-button>-->
                    <b-button variant="dark" @click="initAddForm">
                        <b-icon-plus/>
                    </b-button>
                </b-button-group>
            </template>
            <b-table
                ref="datatable"
                sort-desc
                sort-by="id"
                :no-provider-filtering="false"
                :api-url="apiUrl"
                v-bind="{...attrs}">
                <template #cell(action)="row">
                    <b-button-group size="sm">
                        <b-button
                            variant="dark"
                            title="View"
                            @click="()=>{
                                currentItem=JSON.parse(JSON.stringify(row.item));
                                show_view=true;
                            }"
                            v-b-modal:view_modal>
                            <b-icon-eye/>
                        </b-button>
                        <b-button
                            variant="warning"
                            @click="()=>{
                                currentItem=JSON.parse(JSON.stringify(row.item));
                                show_add=true;
                            }"
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
        <item-add
            :visible="show_add"
            @success="()=>{
                $refs.datatable.refresh();
                currentItem=null;
            }"
            @hidden="()=>{
                show_add=false;
                currentItem=null
            }"
            v-if="currentItem && show_add"
            :item="currentItem"/>
        <item-view
            @hidden="()=>{
                show_view=false;
                currentItem=null
            }"
            v-if="currentItem && show_view"
            :current-item="currentItem"/>
    </div>
</template>

<script>
import TheTable from "../../components/TheTable";
import dt from "../../partials/datatable";
import ItemAdd from "./Add"
import ItemView from "./View"
// import VSelect from "../../components/VSelect";
import initAddForm from "./initAddForm";

export default {
    components: {
        TheTable,
        ItemAdd,
        ItemView,
        // VSelect
    },
    mixins: [dt],
    props: {
        apiUrl: {
            type: String,
            default() {
                return this.$root.baseUrl + "API_URL";
            }
        },
        trashUrl: {
            type: String,
            default() {
                return this.$root.baseUrl + "TRASH_URL";
            }
        }
    },
    data() {
        return {
            show_filter: false,
            show_add: false,
            show_view: false,
            currentItem: null,
            appends: APPENDS,
            fields: FIELDS
        }
    },
    methods: {
        resetFilter() {
            this.$set(this, 'appends', {});
            this.$refs.datatable.refresh();
        },
        initAddForm() {
            this.$set(this, 'currentItem', JSON.parse(JSON.stringify(initAddForm)));
            this.show_add = true;
        }
    }
}
</script>
