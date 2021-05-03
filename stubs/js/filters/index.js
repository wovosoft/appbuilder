import {startCase} from "bootstrap-vue/src/utils/string"
import dayjs from "dayjs";

export default {
    install(Vue) {
        Vue.filter('startCase', (value) => startCase(value));
        Vue.filter('dayjs', (value, format = 'DD-MM-YYYY') => {
            return dayjs(value).format(format);
        })
    }
}
