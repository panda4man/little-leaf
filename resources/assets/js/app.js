import Vue from 'vue';
import './bootstrap';

const app = new Vue({
    el: '#app',
    data() {
        return {
            messages: {
                'success': true,
                'info': true,
                'warning': true,
                'error': true
            }
        }
    },
    methods: {
        logout() {
            $('#logout-form').submit();
        }
    }
});
