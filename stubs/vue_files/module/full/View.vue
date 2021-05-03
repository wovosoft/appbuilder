<template>
    <b-modal lazy id="view_modal"
             @hidden="$emit('hidden',true)"
             v-bind="{
                    size:'xl',
                    headerClass:'p-2',
                    footerClass:'p-2',
                    buttonSize:'sm',
                    bodyClass:'p-2',
                    headerBgVariant:'dark',
                    headerTextVariant:'light',
                    title:'View Details'
                 }">
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
</template>

<script>
import {o2t} from "../../partials/datatable";

export default {
    props: {
        currentItem: {
            type: Object,
            required: true
        }
    },
    methods: {
        o2t
    }
}
</script>
