<template>
    <div>
        <pre v-html="currentItem"></pre>
    </div>
</template>
<script>
export default {
    mounted() {
        this.getItem();
    },
    data() {
        return {
            currentItem: null,
        }
    },
    methods: {
        getItem() {
            axios.post(this.$parent.baseUrl + "/profile")
                .then(res => {
                    console.log(res.data);
                    this.$set(this, 'currentItem', res.data);
                })
                .catch(err => {
                    this.$set(this, 'currentItem', null);
                    console.log(err.response.data);
                });
        }
    }
}
</script>
