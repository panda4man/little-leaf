import Vue from 'vue';

Vue.component('login', {
    data() {
        return {
            forms: {
                login: {
                    email: null,
                    password: null,
                    rememberMe: false
                }
            }
        };
    }
});