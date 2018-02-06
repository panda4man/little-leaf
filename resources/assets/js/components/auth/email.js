import Vue from 'vue';

Vue.component('password-email', {
    data() {
        return {
            form: {
                email: null
            }
        }
    },
    methods: {
        validateEmail() {
            this.$validate.validateAll().then(res => {
                if(res) {
                    this.doSendEmail();
                }
            });
        },
        doSendEmail() {
            $('#password-email-form').submit();
        }
    }
});