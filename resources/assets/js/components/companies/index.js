import Vue from 'vue';
import FormErrors from '../errors/FormErrors.vue';

Vue.component('companies', {
    components: {FormErrors},
    props: ['companies', 'states'],
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
                    name: null,
                    address: null,
                    city: null,
                    state: null,
                    country: null,
                    zip: null
                }
            },
            imagePreview: null,
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
    mounted() {
        $(document).ready(() => {
            $('#photo-input-create').change((e) => {
                this.readImageUrl(e.target, $('#create-company-preview'));
            });
        });
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
            this.$validator.validateAll('create').then(res => {
                if(res) {
                    this.createCompany();
                }
            });
        },
        createCompany() {
            let data = new FormData();
            this.formErrors.create = {};
            this.http.creatingCompany = true;

            Object.keys(this.forms.newCompany).forEach(k => {
                data.append(k, this.forms.newCompany[k]);
            });

            let photoInput = $('#photo-input-create');

            if(photoInput[0].files) {
                data.append('photo', photoInput[0].files[0]);
            }

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
        },
        openSelectPhotoCreate() {
            $('#photo-input-create').trigger('click');
        },
        clearSelectedPhotoCreate() {
            $('#photo-input-create').val('');
            $('#create-company-preview').attr('src', '');
        },
        readImageUrl(inputElement, target) {
            if (inputElement.files && inputElement.files[0]) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    target.attr('src', e.target.result);
                };

                reader.readAsDataURL(inputElement.files[0]);
            }
        }
    },
    computed: {
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