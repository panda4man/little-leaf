import Vue from 'vue';

Vue.component('clients', {
    props: ['companies', 'clients', 'states'],
    data() {
        return {
            http: {
                creatingClient: false
            },
            companyFilterIds: [],
            modals: {
                createClient: false
            },
            mClients: this.clients,
            forms: {
                newClient: {
                    company_id: '',
                    name: '',
                    address: '',
                    city: '',
                    zip: '',
                    state: '',
                    country: ''
                }
            },
            formErrors: {
                createClient: null
            }
        }
    },
    methods: {
        filterByCompany(companyId) {
            let i = this.companyFilterIds.indexOf(companyId);

            if(i > -1) {
                this.companyFilterIds.splice(i, 1);
            } else {
                this.companyFilterIds.push(companyId);
            }
        },
        companySelected(companyId) {
            return this.companyFilterIds.indexOf(companyId) > -1;
        },
        createClient() {
            let data = new FormData();
            this.http.creatingClient = true;

            Object.keys(this.forms.newClient).forEach(k => {
                data.append(k, this.forms.newClient[k]);
            });

            this.$http.post('/ajax/clients', data).then(res => {
                this.http.creatingClient = false;
                this.mClients.push(res.data.data);
                this.closeCreateClientModal();
            }).catch(res => {
                this.http.creatingClient = false;
            });
        },
        validateCreateClient() {
            let fields = Object.keys(this.forms.newClient).map(k => {
                return `create-client.${k}`;
            });

            this.$validator.validateAll(fields).then(res => {
                if(res) {
                    this.createClient();
                }
            });
        },
        openCreateClientModal() {
            this.modals.createClient = true;
        },
        closeCreateClientModal() {
            this.modals.createClient = false;
            this.http.creatingClient = false;

            Object.keys(this.forms.newClient).forEach((k) => {
                this.forms.newClient[k] = '';
            });

            this.errors.clear();
        }
    },
    computed: {
        filteredClients() {
            if(!this.companyFilterIds.length) {
                return this.mClients;
            } else {
                return this.mClients.filter(c => {
                    return this.companyFilterIds.indexOf(c.company.id) > -1;
                });
            }
        },
        formattedStates() {
            let states = [];

            Object.keys(this.states).forEach(k => {
                states.push({
                    text: this.states[k],
                    value: k
                });
            });

            return states;
        }
    }
});