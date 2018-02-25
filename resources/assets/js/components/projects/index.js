import Vue from 'vue';
import Project from '../../models/project';
import HoursWorked from '../projects/HoursWorked.vue';
import FormErrors from '../errors/FormErrors.vue';
import swal from 'sweetalert2';
import Promise from 'bluebird';

Vue.component('projects-list', {
    props: ['projects', 'clients'],
    components: {HoursWorked, FormErrors},
    data() {
        return {
            mProjects: this.projects,
            filter: {
                clientIds: [],
                status: {
                    completed: false,
                    notCompleted: false
                }
            },
            forms: {
                newProject: {
                    client_id: '',
                    name: '',
                    estimated_cost: '',
                    estimated_hours: '',
                    due_at: '',
                },
                editProject: {}
            },
            formErrors: {
                newProject: null,
                editProject: null
            },
            http: {
                creatingProject: false,
                updatingProject: false
            },
            table: {
                headers: [
                    {
                        text: '',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Client',
                        align: 'left',
                        sortable: 'true',
                        value: 'client.name'
                    },
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: 'true',
                        value: 'name'
                    }, {
                        text: 'Hours',
                        align: 'left',
                        sortable: true,
                        value: 'estimated_hours'
                    }, {
                        text: 'Worked',
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
            },
            modals: {
                createNewProject: false,
                editProject: false
            }
        }
    },
    methods: {
        openCreateProjectModal() {
            this.modals.createNewProject = true;
        },
        closeCreateProjectModal() {
            this.modals.createNewProject = false;

            Object.keys(this.forms.newProject).forEach(k => {
                this.forms.newProject[k] = '';
            });

            this.$nextTick(() => {
                this.errors.clear();
            });
        },
        openEditProjectModal(id) {
            let found = false;
            this.forms.editProject = {};

            this.mProjects.map(p => {
                if(p.id === id) {
                    found = true;

                    Object.keys(this.forms.newProject).forEach(k => {
                        if(p.hasOwnProperty(k)) {
                            this.$set(this.forms.editProject, k, p[k]);
                        }
                    });

                    this.forms.editProject.id = p.id;
                }
            });

            if(!found) {

            } else {
                this.modals.editProject = true;
            }
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
        validateCreateProject() {
            let keys = [];

            Object.keys(this.forms.newProject).forEach(k => {
                keys.push(`new-${k}`);
            });

            this.$validator.validateAll(keys).then(res => {
                if(res) {
                    this.createProject();
                }
            });
        },
        validateUpdateProject() {
            let keys = [];

            Object.keys(this.forms.newProject).forEach(k => {
                keys.push(`edit-${k}`);
            });

            this.$validator.validateAll(keys).then(res => {
                if(res) {
                    this.updateProject();
                }
            });
        },
        createProject() {
            this.formErrors.newProject = null;
            this.http.creatingProject = true;

            this.$http.post('/ajax/projects', this.forms.newProject).then(res => {
                this.http.creatingProject = false;
                this.closeCreateProjectModal();
                this.mProjects.push(res.data.data);
            }).catch(res => {
                this.http.creatingProject = false;

                if(res.response && res.response.data) {
                    if(res.response.data.errors) {
                        this.formErrors.newProject = res.response.data.errors;
                    }
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
        swapProject(project) {
            this.mProjects = this.mProjects.map(p => {
                if(p.id === project.id) {
                    p = project;
                }

                return p;
            });
        },
        removeLocalProject(id) {
            this.mProjects = this.mProjects.filter(p => {
                return p.id !== id;
            });
        },
        confirmDelete(id) {
            swal({
                title: 'Delete Project',
                type: 'warning',
                text: 'Are you sure you want to permanently delete this project?',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: (res) => {
                    return new Promise((resolve) => {
                        this.deleteProject(id).then(res2 => {
                            resolve();
                        }).catch(res2 => {
                            resolve();
                        });
                    });
                }
            }).then(res => {
                if(res.value) {
                    this.removeLocalProject(id);
                }
            })
        },
        deleteProject(id) {
            return this.$http.delete(`/ajax/projects/${id}`);
        }
    },
    computed: {
        hydratedProjects() {
            return Project.hydrate(this.mProjects);
        },
        filteredProjects() {
            return this.hydratedProjects.filter(p => {
                let passes = true;

                if(this.filter.clientIds.length) {
                    passes = this.filter.clientIds.indexOf(p.client.id) > -1;
                }

                if(!passes)
                    return passes;

                return passes;
            }).filter(p => {
                let passes = false;

                if(this.filter.status.completed && p.completed_at) {
                    passes = true;
                }

                if(this.filter.status.notCompleted && !p.completed_at) {
                    passes = true;
                }

                if(!this.filter.status.completed && !this.filter.status.notCompleted) {
                    passes = true;
                }

                return passes;
            });
        }
    }
});