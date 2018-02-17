import Vue from 'vue';
import moment from 'moment';
import HoursWorked from '../projects/HoursWorked.vue';

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
    methods: {

    },
    computed: {
        projects() {
            return this.mClient.projects.map(p => {
                p.hours_worked = 0;
                p.due_at = p.due_at ? moment.utc(p.due_at) : null;
                p.completed_at = p.completed_at ? moment.utc(p.completed_at) : null;

                if(p.due_at) {
                    p.due_at = p.due_at.format('m/d/y');
                }

                if(p.completed_at) {
                    p.completed_at = p.completed_at.format('m/d/y');
                }

                return p;
            });
        }
    }
});