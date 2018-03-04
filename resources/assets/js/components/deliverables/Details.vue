<template>
    <v-card class="delivery-summary" :class="{'green lighten-5': mDeliverable.completed_at}">
        <v-card-title class="layout d-flex">
            <div>
                <div class="headline">
                    {{ mDeliverable.name }}
                </div>
                <div v-if="mDeliverable.due_at">
                    {{ mDeliverable.due_at_moment.format('ddd, MMM Do YYYY') }}
                </div>
            </div>
            <div class="text-xs-right">
                <v-menu offset-y>
                    <v-btn slot="activator" icon><v-icon>more_vert</v-icon></v-btn>
                    <v-list>
                        <v-list-tile @click="edit">
                            <v-list-tile-content>
                                Edit
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile @click="destroy">
                            <v-list-tile-content>
                                Delete
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </div>
        </v-card-title>
        <v-card-text>
            <v-layout class="big-stat">
                <v-flex>
                    <v-layout column>
                        <v-flex class="text-xs-center">
                            <span class="stat-value">${{mDeliverable.estimated_cost}}</span>
                        </v-flex>
                        <v-flex class="text-xs-center">
                            <span class="stat-text">~ Cost</span>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex>
                    <v-layout column>
                        <v-flex class="text-xs-center">
                            <span class="stat-value">{{mDeliverable.estimated_hours}}</span>
                        </v-flex>
                        <v-flex class="text-xs-center">
                            <span class="stat-text">~ Hours</span>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex>
                    <v-layout column>
                        <v-flex class="text-xs-center">
                            <div class="stat-value">
                                <hours-worked suffix="" :deliverable="mDeliverable"></hours-worked>
                            </div>
                        </v-flex>
                        <v-flex class="text-xs-center">
                            <span class="stat-text">Hours</span>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
            <div class="mt-3" v-if="mDeliverable.description">
                {{ mDeliverable.description }}
            </div>
        </v-card-text>
        <v-card-actions>
            <v-btn :loading="http.completing" :disabled="http.completing" v-show="!mDeliverable.completed_at" color="success" @click="complete" flat>Mark Complete</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    import HoursWorked from './HoursWorked';
    import swal from 'sweetalert2';

    export default {
        props: ['deliverable'],
        components: {HoursWorked},
        data() {
            return {
                http: {
                    completing: false
                }
            }
        },
        methods: {
            edit() {
                this.$emit('edit', this.mDeliverable.id);
            },
            destroy() {
                this.$emit('delete', this.mDeliverable.id);
            },
            complete() {
                this.http.completing = true;

                this.$http.post(`/ajax/deliverables/${this.mDeliverable.id}/complete`).then(res => {
                    this.http.completing = false;

                    this.$emit('update', this.mDeliverable.id);
                }).catch(res => {
                    this.http.completing = false;

                    swal('Uh oh', 'Something went wrong!', 'error');
                });
            }
        },
        computed: {
            mDeliverable() {
                return this.deliverable;
            }
        }
    }
</script>