export function DtData() {
    return {
        search: '',
        current_page: 1,
        data: [],
        from: 1,
        to: 1,
        total: 0,
        per_page: 15,
        last_page: 1,
    }
}

export function visibleFields(fields) {
    return fields.filter(item => item.visible !== false);
}

export function o2t(item, omit = []) {
    return Object
        .keys(item)
        .filter(k => !omit.includes(k))
        .map(i => Object.assign({}, {key: i, value: item[i]}));
}

export function pluck2table(item, keys = []) {
    return Object
        .keys(item)
        .filter(k => keys.includes(k))
        .map(i => Object.assign({}, {key: i, value: item[i]}));
}

export function processApiUrl(ctx) {
    let url = new URL(ctx.apiUrl);
    let params = new URLSearchParams(url.search.slice(1));
    params.set('page', ctx.currentPage);
    return url.href.split('?')[0] + '?' + params.toString();
}

export default {
    data() {
        return {
            is_loading: false,
            datatable: DtData()
        }
    },
    computed: {
        visibleFields() {
            return visibleFields(this.fields);
        },
        attrs() {
            return {
                responsive: true,
                striped: true,
                bordered: true,
                hover: true,
                small: true,
                headVariant: 'dark',
                filter: this.datatable.search || '',
                perPage: this.datatable.per_page || 15,
                currentPage: this.datatable.current_page,
                items: this.getItems,
                fields: this.visibleFields
            }
        },
    },
    methods: {
        getItems(ctx) {
            this.is_loading = true;
            let appends = Object.assign({}, this.appends || {});
            return axios
                .post(processApiUrl(ctx), {...ctx, ...appends})
                .then(({data}) => {
                    // console.log(data)
                    this.$set(this, 'datatable', {search: this.datatable.search, ...data});
                    this.is_loading = false;
                    return data.data;
                })
                .catch(({response}) => {
                    this.$set(this, 'datatable', DtData());
                    this.is_loading = false;
                    console.log(response);
                });
        }
    }
}
