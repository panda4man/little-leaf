import Vue from 'vue';
import FormErrors from '../errors/FormErrors.vue';
import CompanyCard from './CompanyCard.vue';
import swal from 'sweetalert2';

Vue.component('companies', {
    components: {FormErrors, CompanyCard},
    props: ['companies', 'states'],
    data() {
        return {
            http: {
                creatingCompany: false,
                updatingCompany: false
            },
            mCompanies: this.companies,
            currentCompany: null,
            modals: {
                createCompany: false,
                editCompany: false
            },
            forms: {
                newCompany: {
                    name: '',
                    address: '',
                    city: '',
                    state: '',
                    country: '',
                    zip: '',
                    default: false
                },
                editCompany: {
                    name: '',
                    address: '',
                    city: '',
                    state: '',
                    country: '',
                    zip: '',
                    default: false,
                    photo: ''
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
    mounted() {
        $(document).ready(() => {
            $('#photo-input-create').change((e) => {
                this.readImageUrl(e.target, $('#create-company-preview'));
            });

            $('#photo-input-edit').change((e) => {
                this.readImageUrl(e.target, $('#edit-company-preview'));

                if(this.forms.editCompany.photo) {
                    $('#edit-company-preview-real').hide();
                }
            });
        });
    },
    methods: {
        selectCompany(id) {
            let f = false;

            this.mCompanies.map(c => {
                if(c.id === id) {
                    f = true;
                    this.currentCompany = c;
                }
            });

            if(!f) {
                swal('Uh oh', 'This company could not be found', 'info');
            }
        },
        validateCreateCompany() {
            let fields = Object.keys(this.forms.newCompany).map(k => {
                return `create-company.${k}`;
            });

            this.$validator.validateAll(fields).then(res => {
                if(res) {
                    this.createCompany();
                }
            });
        },
        validateUpdateCompany() {
            let fields = Object.keys(this.forms.newCompany).map(k => {
                return `edit-company.${k}`;
            });

            this.$validator.validateAll(fields).then(res => {
                if(res) {
                    this.updateCompany();
                }
            });
        },
        updateCompany() {
            let data = new FormData();
            this.formErrors.editCompany = {};
            this.http.updatingCompany = true;

            Object.keys(this.forms.editCompany).forEach(k => {
                if(k !== 'photo') {
                    let val = this.forms.editCompany[k];

                    if(k === 'default') {
                        val = val ? 1 : 0;
                    }

                    data.append(k, val);
                }
            });

            let photoInput = $('#photo-input-edit');

            // Add photo data
            if(photoInput[0].files && photoInput[0].files[0]) {
                data.append('photo', photoInput[0].files[0]);
            }

            data.append('_method', 'PUT');

            this.$http.post(`/ajax/companies/${this.currentCompany.hash_id}`, data).then(res => {
                this.http.updatingCompany = false;

                this.updateLocalCompany(res.data.data);
                this.closeEditCompanyModal();
            }).catch(res => {
                this.http.updatingCompany = false;

                if(res.response) {
                    console.log(res.response);
                    this.formErrors.editCompany = res.response.data.errors;
                }
            });
        },
        updateLocalCompany(company) {
            this.mCompanies = this.mCompanies.map(c => {
                if(c.id === company.id) {
                    this.currentCompany = company;
                    c = company;
                }

                return c;
            });
        },
        createCompany() {
            let data = new FormData();
            this.formErrors.createCompany = {};
            this.http.creatingCompany = true;

            Object.keys(this.forms.newCompany).forEach(k => {
                let val = this.forms.newCompany[k];

                if(k === 'default') {
                    val = val ? 1 : 0;
                }

                data.append(k, val);
            });

            let photoInput = $('#photo-input-create');

            // Add photo data
            if(photoInput[0].files && photoInput[0].files[0]) {
                data.append('photo', photoInput[0].files[0]);
            }

            this.$http.post('/ajax/companies', data).then(res => {
                this.http.creatingCompany = false;

                this.mCompanies.push(res.data.data);
                this.closeCreateCompanyModal();
            }).catch(res => {
                this.http.creatingCompany = false;

                if(res.response) {
                    console.log(res.response);
                    this.formErrors.createCompany = res.response.data;
                }
            });
        },
        openCreateCompanyModal() {
            this.modals.createCompany = true;
        },
        closeCreateCompanyModal() {
            this.modals.createCompany = false;

            Object.keys(this.forms.newCompany).forEach(k => {
                this.forms.newCompany[k] = '';
            });

            this.clearSelectedPhotoCreate();
            this.errors.clear();
        },
        openEditCompanyModal(id) {
            this.selectCompany(id);

            Object.keys(this.forms.editCompany).forEach(k => {
                this.forms.editCompany[k] = this.currentCompany[k];
            });

            this.modals.editCompany = true;
        },
        closeEditCompanyModal() {
            this.modals.editCompany = false;

            Object.keys(this.forms.editCompany).forEach(k => {
                this.forms.editCompany[k] = '';
            });

            this.clearSelectedPhotoEdit();
            this.errors.clear();
        },
        removeLocalCompany(id) {
            this.mCompanies = this.mCompanies.filter(c => {
                return c.id !== id;
            });
        },
        openSelectPhotoCreate() {
            $('#photo-input-create').trigger('click');
        },
        clearSelectedPhotoCreate() {
            $('#photo-input-create').val('');
            $('#create-company-preview').attr('src', '');
        },
        openSelectPhotoEdit() {
            $('#photo-input-edit').trigger('click');
        },
        clearSelectedPhotoEdit() {
            $('#photo-input-edit').val('');
            $('#edit-company-preview').attr('src', '');

            if(this.forms.editCompany.photo) {
                $('#edit-company-preview-real').show();
            }
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