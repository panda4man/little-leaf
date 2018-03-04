import Vue from 'vue';
import HoursWorked from '../projects/HoursWorked.vue';
import DHoursWorked from '../deliverables/HoursWorked.vue';
import FormErrors from '../errors/FormErrors.vue';
import Project from '../../models/project';
import Deliverable from "../../models/deliverable";
import Promise from "bluebird";
import swal from "sweetalert2";

Vue.component('project-details', {
    components: {HoursWorked, DHoursWorked, FormErrors},
    props: ['project', 'clients'],
    data() {
        return {
            http: {
                updatingProject: false,
                updatingDeliverable: false,
                creatingDeliverable: false,
                completedProject: false
            },
            mProject: this.project,
            mDeliverables: [],
            forms: {
                editProject: {

                },
                newDeliverable: {
                    name: '',
                    description: '',
                    estimated_hours: '',
                    estimated_cost: '',
                    due_at: ''
                },
                editDeliverable: {
                    project_id: '',
                    name: '',
                    description: '',
                    estimated_hours: '',
                    estimated_cost: '',
                    due_at: ''
                }
            },
            formErrors: {
                editProject: {},
                createDeliverable: {},
                editDeliverable: {}
            },
            modals: {
                editProject: false,
                createDeliverable: false,
                editDeliverable: false
            },
            tables: {
                deliverables: {
                    headers: [
                        {
                            text: 'Name',
                            sortable: true,
                            value: 'name'
                        }, {
                            text: 'Hours',
                            sortable: true,
                            value: 'estimated_hours'
                        }, {
                            text: 'Cost',
                            sortable: true,
                            value: 'estimated_cost'
                        }, {
                            text: 'Worked',
                            sortable: false,
                            value: 'hours_worked'
                        },{
                            text: 'Due',
                            sortable: true,
                            value: 'due_at'
                        }, {
                            text: 'Completed',
                            sortable: true,
                            value: 'completed_at'
                        }
                    ]
                }
            }
        }
    },
    created() {
        // Dynamically set deliverables if exists
        if(this.mProject.deliverables) {
            this.mDeliverables = [...this.mProject.deliverables];
        }
    },
    methods: {
        openEditProjectModal() {
            this.forms.editProject = {};

            Object.keys(this.mProject).forEach(k => {
                this.$set(this.forms.editProject, k, this.mProject[k]);
            });

            this.forms.editProject.id = this.mProject.id;

            this.modals.editProject = true;
        },
        openCreateDeliverableModal() {
            Object.keys(this.forms.newDeliverable).forEach(k => {
                this.forms.newDeliverable[k] = '';
            });

            this.forms.newDeliverable.project_id = this.mProject.id;
            this.modals.createDeliverable = true;
        },
        openEditDeliverableModal(deliverable) {
            this.modals.editDeliverable = true;

            Object.keys(this.forms.editDeliverable).forEach(k => {
                if(deliverable.hasOwnProperty(k)) {
                    this.forms.editDeliverable[k] = deliverable[k];
                }
            });

            this.forms.editDeliverable.id = deliverable.id;
        },
        closeEditDeliverableModal() {
            this.modals.editDeliverable = false;

            this.$nextTick(() => {
                this.errors.clear();
            });
        },
        closeEditProjectModal() {
            this.modals.editProject = false;

            this.$nextTick(() => {
                this.errors.clear();
            });
        },
        closeCreateDeliverableModal() {
            this.modals.createDeliverable = false;

            this.$nextTick(() => {
                this.errors.clear();
            });
        },
        closeEditDeliverableModal() {
            this.modals.editDeliverable = false;

            this.$nextTick(() => {
                this.errors.clear();
            });
        },
        validateUpdateProject() {
            let keys = [];

            Object.keys(this.forms.editProject).forEach(k => {
                keys.push(`edit-${k}`);
            });

            this.$validator.validateAll(keys).then(res => {
                if(res) {
                    this.updateProject();
                }
            });
        },
        validateCreateDeliverable() {
            let keys = [];

            Object.keys(this.forms.newDeliverable).forEach(k => {
                keys.push(`new-deliverable-${k}`);
            });

            this.$validator.validateAll(keys).then(res => {
                if(res) {
                    this.createDeliverable();
                }
            });
        },
        validateEditDeliverable() {
            let keys = [];

            Object.keys(this.forms.editDeliverable).forEach(k => {
                keys.push(`edit-deliverable-${k}`);
            });

            this.$validator.validateAll(keys).then(res => {
                if(res) {
                    this.updateDeliverable(this.forms.editDeliverable.id);
                }
            });
        },
        updateProject() {
            this.formErrors.editProject = null;
            this.http.updatingProject = true;

            this.$http.put(`/ajax/projects/${this.forms.editProject.id}`, this.forms.editProject).then(res => {
                this.http.updatingProject = false;
                this.closeEditProjectModal();
                this.swapProject(res.data.data);
            }).catch(res => {
                this.http.updatingProject = false;

                if(res.response && res.response.data) {
                    if(res.response.data.errors) {
                        this.formErrors.editProject = res.response.data.errors;
                    }
                }
            });
        },
        createDeliverable() {
            let data = {};
            this.http.creatingDeliverable = true;

            Object.keys(this.forms.newDeliverable).forEach(k => {
                data[k] = this.forms.newDeliverable[k];
            });

            this.$http.post('/ajax/deliverables', data).then(res => {
                this.http.creatingDeliverable = false;
                this.mDeliverables = [...this.mDeliverables, res.data.data];
                this.closeCreateDeliverableModal();
            }).catch(res => {
                this.http.creatingDeliverable = false;

                if(res.response && res.response.data) {
                    if(res.response.data.errors) {
                        this.formErrors.createDeliverable = res.response.data.errors;
                    }
                }
            });
        },
        updateDeliverable(id) {
            let data = {};
            this.http.updatingDeliverable = true;

            Object.keys(this.forms.editDeliverable).forEach(k => {
                data[k] = this.forms.editDeliverable[k];
            });

            this.$http.put(`/ajax/deliverables/${id}`, data).then(res => {
                this.http.updatingDeliverable = false;
                this.mDeliverables = this.mDeliverables.map(d => {
                    if(res.data.data.id === d.id) {
                        d = res.data.data;
                    }

                    return d;
                });
                this.closeEditDeliverableModal();
            }).catch(res => {
                this.http.updatingDeliverable = false;

                if(res.response && res.response.data) {
                    if(res.response.data.errors) {
                        this.formErrors.editDeliverable = res.response.data.errors;
                    }
                }
            });
        },
        swapProject(project) {
            this.mProject = project;
        },
        confirmDeleteProject() {
            swal({
                title: 'Delete Project',
                type: 'warning',
                text: 'Are you sure you want to permanently delete this project?',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: (res) => {
                    return new Promise((resolve) => {
                        this.deleteProject(this.mProject.id).then(res2 => {
                            resolve();
                        }).catch(res2 => {
                            resolve();
                        });
                    });
                }
            }).then(res => {
                if(res.value) {
                    window.location = '/projects';
                }
            })
        },
        confirmDeleteDeliverable(deliverable) {
            swal({
                title: 'Delete Deliverable',
                type: 'warning',
                text: 'Are you sure you want to permanently delete this deliverable?',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: (res) => {
                    return new Promise((resolve, reject) => {
                        this.deleteDeliverable(deliverable.id).then(res2 => {
                            resolve();
                        }).catch(res2 => {
                            reject();
                        });
                    });
                }
            }).then(res => {
                if(res.value) {
                    this.removeLocalDeliverable(deliverable.id);
                }
            }).catch(res => {
                swal('Uh oh', 'An error occurred when deleting your deliverable.', 'error');
            });
        },
        removeLocalDeliverable(id) {
            this.mDeliverables = this.mDeliverables.filter(d => {
                return d.id !== id;
            });
        },
        deleteProject(id) {
            return this.$http.delete(`/ajax/projects/${id}`);
        },
        deleteDeliverable(id) {
            return this.$http.delete(`/ajax/deliverables/${id}`);
        },
        clearDueAt(form) {
            form.due_at = '';
        },
        tryMarkComplete() {
            let can = this.checkMarkComplete();

            if(can) {
                this.completeProject();
            } else {
                swal({
                    title: 'Are you sure?',
                    text: 'You haven\'t completed all of your deliverables yet.',
                    type: 'warning',
                    showCancelButton: true
                }).then(res => {
                    if(res.value) {
                        this.completeProject();
                    }
                });
            }
        },
        checkMarkComplete() {
            let passCount = this.mDeliverables.filter(d => {
                return d.completed_at;
            });

            return passCount.length > 0;
        },
        completeProject() {
            this.http.completingProject = true;

            this.$http.post().then(res => {
                this.http.completingProject = false;
            }).catch(res => {
                this.http.completingProject = false;
            });
        },
        completeDeliverable(deliverable) {
            this.$http.post(`/ajax/deliverables/${deliverable.id}/complete`).then(res => {
                //TODO
            }).catch(res => {
                //TODO
            });
        }
    },
    computed: {
        hydratedProject() {
            return Project.hydrate(this.mProject);
        },
        hydratedDeliverables() {
            return Deliverable.hydrate(this.mDeliverables);
        }
    }
});