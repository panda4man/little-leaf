import Vue from 'vue';

Vue.component('account-profile', {
    props: ['user'],
    data() {
        return {
            http: {
                profile: false
            },
            modals: {
                profile: false
            },
            forms: {
                profile: {
                    first_name: this.user.first_name,
                    last_name: this.user.last_name,
                    email: this.user.email
                }
            }
        }
    },
    methods: {
        openEditProfileModal() {
            this.modals.profile = true;
        },
        closeEditProfileModal() {
            this.modals.profile = false;

            Object.keys(this.forms.profile).forEach(k => {
                this.forms.profile[k] = this.user[k];
            });

            this.errors.clear();
        },
        validateUpdateProfile() {
            this.$validator.validateAll().then(res => {
                if(res) {
                    this.updateProfile();
                }
            });
        },
        updateProfile() {
            this.http.profile = true;
            $('#profile-form').submit();
        }
    }
});