import Vue from 'vue';
import HoursWorked from '../projects/HoursWorked.vue';
import DHoursWorked from '../deliverables/HoursWorked.vue';
import Project from '../../models/project';
import Promise from "bluebird";
import FormErrors from '../errors/FormErrors.vue';
import swal from "sweetalert2";
import Deliverable from "../../models/deliverable";

Vue.component('project-details', {
    components: {HoursWorked, DHoursWorked, FormErrors},
    props: ['project', 'clients'],
    data() {
        return {
            http: {
                updatingProject: false,
                creatingDeliverable: false
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
                }
            },
            formErrors: {
                editProject: {},
                createDeliverable: {}
            },
            modals: {
                editProject: false,
                createDeliverable: false
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
        closeEditProjectModal() {
            this.modals.editProject = false;

            Object.keys(this.forms.editProject).forEach(k => {
                this.forms.editProject[k] = '';
            });

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
        deleteProject(id) {
            return this.$http.delete(`/ajax/projects/${id}`);
        },
        clearDueAt(form) {
            form.due_at = '';
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