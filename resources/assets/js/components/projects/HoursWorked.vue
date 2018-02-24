<template>
    <div>
        <slot v-if="http.fetching">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
        </slot>
        <span v-else>{{hours}}</span>
    </div>
</template>

<script>
    export default {
        props: ['project'],
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

                this.$http.get(`/ajax/projects/${this.project.id}/hours-worked`).then(res => {
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