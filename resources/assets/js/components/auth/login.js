import Vue from 'vue';

Vue.component('login', {
    props: ['formErrors'],
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
    },
    methods: {
        validateLogin() {
            this.$validator.validateAll().then(res => {
                if(res) {
                    this.doLogin();
                }
            });
        },
        doLogin() {
            $('#login-form').submit();
        }
    }
});