<template>
    <v-card class="company-card">
        <template v-if="company">
            <v-card-text>
                <v-layout row wrap>
                    <v-flex xs12 sm4 md3>
                        <img width="100%" :src="company.photo ? company.photo : 'http://placehold.it/200x200'">
                    </v-flex>
                    <v-flex xs12 sm8 md9>
                        <div class="pl-3">
                            <div class="name d-flex">
                                <div>
                                    {{ company.name }}
                                </div>
                                <v-spacer></v-spacer>
                                <div class="text-xs-right">
                                    <v-menu offset-y>
                                        <v-btn slot="activator" icon><v-icon>more_vert</v-icon></v-btn>
                                        <v-list>
                                            <v-list-tile @click="tryToEdit">
                                                <v-list-tile-content>
                                                    Edit
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile @click="tryToDestroy">
                                                <v-list-tile-content>
                                                    Delete
                                                </v-list-tile-content>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </div>
                            </div>
                            <div>{{ company.address }}</div>
                            <div>{{ company.city }}, {{ company.state }} {{ company.zip }}</div>
                            <div>{{ company.country }}</div>
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
