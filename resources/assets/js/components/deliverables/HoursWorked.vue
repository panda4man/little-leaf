<template>
    <div class="d-flex">
        <slot v-if="http.fetching">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
        </slot>
        <span v-else>{{hours}} {{suffix}}</span>
    </div>
</template>

<script>
    export default {
        props: {
            deliverable: Object,
            suffix: {
                type: String,
                default: 'hrs'
            }
        },
        data() {
            return {
                http: {
                    fetching: false
                },
                hours: 0
            };
        },
        mounted() {
            this.getHours();
        },
        methods: {
            getHours() {
                this.http.fetching = true;

                this.$http.get(`/ajax/deliverables/${this.deliverable.id}/hours-worked`).then(res => {
                    this.http.fetching = false;
                    this.hours = res.data.data.hours;
                }).catch(res => {
                    this.http.fetching = false;

                    if(res.response) {
                        console.log(res.response);
                    }
                });
            }
        }
    }
</script>