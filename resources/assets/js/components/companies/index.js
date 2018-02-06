import Vue from 'vue';
import FormErrors from '../errors/FormErrors.vue';

Vue.component('companies', {
    components: {FormErrors},
    props: ['companies'],
    data() {
        return {
            http: {
                creatingCompany: false
            },
            mCompanies: this.companies,
            currentCompany: null,
            modals: {
                createModal: false
            },
            forms: {
                newCompany: {
                    name: null
                }
            },
            formErrors: {}
        };
    },
    created() {
        let d = this.mCompanies.filter(c => c.default);

        // if no default and we have companies
        // pick the first by default
        if(d.length < 1 && this.mCompanies.length) {
            this.currentCompany = this.mCompanies[0];
        } else {
            this.currentCompany = d[0];
        }
    },
    methods: {
        selectCompany(id) {
            this.mCompanies.map(c => {
                if(c.id === id) {
                    this.currentCompany = c;
                }
            });
        },
        validateCreateCompany() {
            this.$validate.validateAll('create').then(res => {
                if(res) {
                    this.createCompany();
                }
            });
        },
        createCompany() {
            let data = {};
            this.formErrors.create = {};
            this.http.creatingCompany = true;

            this.$http.post('/ajax/companies', data).then(res => {
                this.http.creatingCompany = false;
                this.mCompanies.push(res.data.data);
            }).catch(res => {
                this.http.creatingCompany = false;

                if(res.response) {
                    this.formErrors.create = res.response.data;
                }
            });
        },
        openCreateModal() {
            this.modals.createModal = true;
        },
        closeCreateModal() {
            this.modals.createModal = false;
        }
    }
});