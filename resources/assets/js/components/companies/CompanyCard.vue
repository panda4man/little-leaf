<template>
    <v-card>
        <template v-if="company">
            <v-card-text>
                <v-layout row>
                    <v-flex xs12 sm4 md3>
                        <img width="100%" :src="company.photo ? company.photo : 'http://placehold.it/200x200'">
                    </v-flex>
                    <v-flex xs12 sm8 md9>
                        <div class="pl-3">
                            <h1>{{ company.name }}</h1>
                            <h3>{{ company.address }}</h3>
                            <h3>{{ company.city }}, {{ company.state }} {{ company.zip }}</h3>
                            <h3>{{ company.country }}</h3>
                        </div>
                        <div>
                            <v-btn @click="tryToEdit" color="success">
                                Edit<v-icon right>edit</v-icon>
                            </v-btn>
                            <v-btn @click="tryToDestroy" color="warning">
                                Delete<v-icon right>delete</v-icon>
                            </v-btn>
                        </div>
                    </v-flex>
                </v-layout>
            </v-card-text>
        </template>
        <template v-else>
            <v-alert color="warning" icon="info" dismissible v-model="alert">
                You haven't created any companies yet.
            </v-alert>
        </template>
    </v-card>
</template>

<script>
    export default {
        props: ['company'],
        methods: {
            tryToEdit() {
                this.$emit('company-edit', this.company.id);
            },
            tryToDestroy() {
                this.$swal({
                    title: 'Delete Company',
                    text: 'Are you sure you want to delete this company? We will hold off permanently deleting for 30 days.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'
                }).then(res => {
                    if(res.value) {
                        // TODO: delete
                    }
                });
            }
        }
    }
</script>