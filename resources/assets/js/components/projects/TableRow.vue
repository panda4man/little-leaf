<script>
    import HoursWorked from './HoursWorked.vue';

    export default {
        props: ['project'],
        render(h) {
            return [
                h('td', this.project.name),
                h('td', this.project.estimated_hours),
                h(HoursWorked, {
                    props: {
                        project: this.project
                    }
                }),
                h('td', this.project.estimated_cost),
                h('td', this.project.due_at ? this.project.due_at_moment.format('MMM Do, YYYY') : ''),
                h('td', this.project.completed_at ? this.project.completed_at_moment.format('MMM Do, YYYY') : '')
            ];
        }
    }
</script>
