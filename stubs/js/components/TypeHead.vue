<template>
    <b-dropdown
        :toggle-class="toggleClass"
        class="typehead-dd"
        @shown="dropdownOpened"
        v-bind="{...$props,...$attrs,...init}"
        :menu-class="['dropdown-menu-scrollable',menuClass].join(' ')">
        <b-input
            class="ml-2 mr-2"
            style="width: calc(100% - 14px)"
            v-model="query"
            :placeholder="searchPlaceholder"
            :size="searchSize"
            type="search"/>
        <b-dd-item
            @click="itemSelected(item)"
            v-for="(item,item_key) in searchedItems"
            :key="item_key">
            {{ textField ? item[textField] : item }}
        </b-dd-item>
    </b-dropdown>
</template>

<script>
export default {
    name: "VSelect",
    props: {
        fullWidth: {
            type: Boolean,
            default: false
        },
        initPlaceholder: {
            type: String,
            default: () => "Not Selected"
        },
        variant: {
            type: String,
            default: 'outline-dark'
        },
        block: {
            type: Boolean,
            default: true
        },
        menuClass: {
            type: String,
            default: null
        },
        searchSize: {
            type: String,
            default: 'sm'
        },
        searchPlaceholder: {
            type: String,
            default: 'Search Items...'
        },
        options: {
            type: Array | Function,
            default: () => []
        },
        valueField: {
            type: String | Function,
            default: null
        },
        textField: {
            type: String | Function,
            default: null,
        },
        value: {
            default: null
        },
        clearSearchOnSelect: {
            type: Boolean,
            default: false
        },
        limit: {
            type: Number,
            default: null
        },
        toggleClass: {
            type: String | Array,
            default: 'dd-toggle-border-color'
        }
    },
    computed: {
        searchedItems() {
            if (Array.isArray(this.options)) {
                let items = this.options.filter(i => (this.textField ? i[this.textField] : i).toLocaleLowerCase().includes((this.query || "").toLocaleLowerCase()));
                if (this.limit) {
                    return items.slice(0, this.limit);
                }
                return items;
            } else if (typeof this.options === 'function') {
                let items = this.options(this.query);
                if (this.limit) {
                    return items.slice(0, this.limit);
                }
                return items;
            }
            return [];
        },
        selectedItem() {
            if (!this.valueField) {
                return this.value;
            }
            return this.options.find(i => i[this.valueField] === this.value);
        }
    },
    watch: {
        value(val) {
            this.setPlaceholder(val);
        }
    },
    mounted() {
        this.init.text = this.initPlaceholder;
        this.setPlaceholder(this.selectedItem);
    },
    data() {
        return {
            init: {
                text: 'Select Element',
            },
            query: null,
        }
    },
    methods: {
        setPlaceholder(val) {
            if (val) {
                if (this.textField) {
                    if (typeof this.textField === 'function') {
                        this.init.text = this.textField(this.selectedItem);
                    } else {
                        this.init.text = this.selectedItem[this.textField];
                    }
                } else {
                    this.init.text = this.selectedItem;
                }
            } else {
                this.init.text = this.initPlaceholder;
            }
        },
        dropdownOpened() {
            this.$el.querySelector("[type='search']").focus();
        },

        itemSelected(item) {
            if (this.valueField) {
                if (typeof this.valueField === 'function') {
                    this.$emit('input', this.valueField(item));
                } else {
                    this.$emit('input', item[this.valueField]);
                }
            } else {
                this.$emit('input', item);
            }
            if (this.clearSearchOnSelect) {
                this.query = null;
            }
        }
    }
}
</script>

<style lang="scss">
.typehead-dd {
    .dropdown-menu-scrollable {
        max-height: 350px;
        overflow-y: scroll !important;
        overflow-x: auto;
        width: 100%;
    }

    .dropdown-toggle {
        text-align: left;
    }

    .dropdown-toggle::after {
        right: 10px;
        position: absolute;
        margin-top: 6px;
    }

    .dd-toggle-border-color {
        border: 1px solid #ced4da;
    }
}

</style>
