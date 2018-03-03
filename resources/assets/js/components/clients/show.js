import Vue from 'vue';
import HoursWorked from '../projects/HoursWorked.vue';
import FormErrors from '../errors/FormErrors.vue';
import Project from '../../models/project';
import swal from 'sweetalert2';
import Promise from "bluebird";

Vue.component('client-details', {
    props: ['client', 'states', 'companies'],
    components: {HoursWorked, FormErrors},
    data() {
        return {
            forms: {
                editClient: {
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
                editClient: {}
            },
            http: {
                updatingClient: false
            },
            mClient: this.client,
            modals: {
                editClient: false
            },
            table: {
                headers: [
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: 'true',
                        value: 'name'
                    }, {
                        text: 'Estimated Hours',
                        align: 'left',
                        sortable: true,
                        value: 'estimated_hours'
                    }, {
                        text: 'Hours Worked',
                        align: 'left',
                        sortable: true,
                        value: 'hours_worked'
                    }, {
                        text: 'Cost',
                        align: 'left',
                        sortable: true,
                        value: 'estimated_cost'
                    }, {
                        text: 'Due on',
                        align: 'left',
                        sortable: true,
                        value: 'due_at'
                    }, {
                        text: 'Completed on',
                        align: 'left',
                        sortable: true,
                        value: 'completed_at'
                    }
                ]
            }
        }
    },
    methods: {
        updateClient() {
            this.http.updatingClient = true;
            this.formErrors.editClient = {};

            this.$http.put(`/ajax/clients/${this.mClient.hash_id}`, this.forms.editClient).then(res => {
                this.http.updatingClient = false;
                this.updateLocalClient(res.data.data);
                this.closeEditClientModal();
            }).catch(res => {
                this.http.updatingClient = false;

                if(res.response && res.response.data) {
                    if(res.response.data.errors) {
                        this.formErrors.editClient = res.response.data.errors;
                    }
                }
            })
        },
        validateUpdateClient() {
            let fields = Object.keys(this.forms.editClient).map(k => {
                return `edit-client.${k}`;
            });

            this.$validator.validateAll(fields).then(res => {
                if(res) {
                    this.updateClient();
                }
            });
        },
        openEditClientModal() {
            this.modals.editClient = true;

            Object.keys(this.forms.editClient).forEach((k) => {
                if(this.mClient.hasOwnProperty(k)) {
                    this.forms.editClient[k] = this.mClient[k];
                }
            });
        },
        closeEditClientModal() {
            this.modals.editClient = false;
            this.http.updatingClient = false;

            Object.keys(this.forms.editClient).forEach((k) => {
                this.forms.editClient[k] = '';
            });

            this.$nextTick(() => {
                this.errors.clear();
            });
        },
        deleteClient(id) {
            return this.$http.delete(`/ajax/clients/${id}`);
        },
        updateLocalClient(newClient) {
            this.mClient = newClient;
        },
        confirmDeleteClient() {
            swal({
                title: 'Delete Client',
                text: 'Are you sure you want to delete this client?',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        this.deleteClient(this.mClient.hash_id).then(res2 => {
                            resolve();
                        }).catch(res2 => {
                            reject();
                        });
                    });
                }
            }).then(res => {
                if(res.value) {
                    window.location = '/account/clients';
                }
            }).catch(res => {
                //handle bad case
            });
        }
    },
    computed: {
        mProjects() {
            return Project.hydrate(this.mClient.projects);
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
