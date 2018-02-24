import Vue from 'vue';
import HoursWorked from '../projects/HoursWorked.vue';
import Project from '../../models/project';

Vue.component('client-details', {
    props: ['client'],
    components: {HoursWorked},
    data() {
        return {
            mClient: this.client,
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
    computed: {
        mProjects() {
            return Project.hydrate(this.mClient.projects);
        }
    }
});
