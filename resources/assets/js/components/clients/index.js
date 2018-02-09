import Vue from 'vue';

Vue.component('clients', {
    props: ['companies', 'clients'],
    data() {
        return {
            companyFilterIds: []
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
        }
    },
    computed: {
        mClients() {
            if(!this.companyFilterIds.length) {
                return this.clients;
            } else {
                return this.clients.filter(c => {
                    return this.companyFilterIds.indexOf(c.company.id) > -1;
                });
            }
        }
    }
});