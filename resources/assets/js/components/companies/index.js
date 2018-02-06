import Vue from 'vue';

Vue.component('companies', {
    props: ['companies'],
    data() {
        return {
            mCompanies: this.companies,
            currentCompany: null
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
    method: {
        selectCompany(id) {
            this.mCompanies.map(c => {
                if(c.id === id) {
                    this.currentCompany = c;
                }
            });
        }
    }
});