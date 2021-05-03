<template>
    <b-card
        class="shadow"
        body-class="p-1"
        footer-class="pb-0">
        <template #header>
            <slot name="header">
                <b-row>
                    <b-col md="2" sm="12">
                        <b-input-group prepend="Per Page" size="sm">
                            <b-select
                                v-model="datatable.per_page"
                                :options="[5,10,15,20,25,30,40,50,100,150,200,500]"/>
                        </b-input-group>
                    </b-col>
                    <b-col md="4" sm="12">
                        <b-input-group size="sm">
                            <b-input
                                v-model="datatable.search"
                                :debounce="200"
                                type="search"
                                placeholder="Search..."/>
                        </b-input-group>
                    </b-col>
                    <b-col md="6" sm="12" class="text-right">
                        <slot name="header_buttons"></slot>
                        <b-dropdown size="sm"
                                    menu-class="p-0 mh-300px"
                                    variant="dark"
                                    style="list-style-type:none;"
                                    right>
                            <b-list-group-item tag="li" class="p-0" v-for="(op,op_key) in fields" :key="op_key">
                                <label class="d-block p-2 form-check" style="cursor: pointer;">
                                    <input v-model="op.visible" class="custom-checkbox" type="checkbox">
                                    {{ (op.label || op.key) |startCase }}
                                </label>
                            </b-list-group-item>
                        </b-dropdown>
                    </b-col>
                </b-row>
            </slot>
        </template>
        <slot></slot>
        <template #footer>
            <slot name="footer">
                <b-row>
                    <b-col md="6" sm="12">
                        Showing from {{ datatable.from }} to {{ datatable.to }} of {{ datatable.total }}.
                        Page : {{ datatable.current_page }}/{{ datatable.last_page }}
                    </b-col>
                    <b-col md="6" sm="12">
                        <b-pagination
                            align="right"
                            size="sm"
                            :per-page="datatable.per_page"
                            :total-rows="datatable.total"
                            v-model="datatable.current_page"/>
                    </b-col>
                </b-row>
            </slot>
        </template>
    </b-card>
</template>

<script>
import {visibleFields} from "../partials/datatable";

export default {
    name: "TheTable",
    props: {
        datatable: {
            type: Object,
            default: () => {
            }
        },
        fields: {
            type: Array | null,
            default: () => []
        }
    },
    computed: {
        visibleFields() {
            return visibleFields(this.fields);
        }
    },
    created() {
        this.fields.forEach(item => {
            this.$set(item, 'visible', item.visible !== false);
        });
    }
}
</script>

<style>
.mh-300px {
    max-height: 300px;
    overflow: auto;
}
</style>
