import Vue from 'vue';

Vue.component('register', {
    props: ['formErrors'],
    data() {
        return {
            forms: {
                register: {
                    firstName: null,
                    lastName: null,
                    email: null,
                    password: null,
                    passwordConfirmation: null
                }
            }
        }
    },
    methods: {
        validateRegister() {
            this.$validate.validateAll().then(res => {
                if(res) {
                    this.doRegister();
                }
            });
        },
        doRegister() {
            $('#register-form').submit();
        }
    }
});