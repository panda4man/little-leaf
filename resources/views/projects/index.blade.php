@extends('layouts.main')

@section('title', 'Projects | ')

@section('content')
    <projects-list :projects="{{ json_encode($projects) }}" :clients="{{ json_encode($clients) }}" inline-template>
        <v-layout row wrap>
            <v-flex hidden-xs-only sm4 lg3>
                <v-card>
                    <v-list dense subheader>
                        <v-subheader>Clients</v-subheader>
                        <v-list-tile v-for="c in clients" :key="c.id">
                            <v-checkbox hide-details :label="c.name" :value="c.id" v-model="filter.clientIds"></v-checkbox>
                        </v-list-tile>
                        <v-subheader>Status</v-subheader>
                        <v-list-tile>
                            <v-checkbox hide-details label="Completed" v-model="filter.status.completed"></v-checkbox>
                        </v-list-tile>
                        <v-list-tile>
                            <v-checkbox hide-details label="Not Completed" v-model="filter.status.notCompleted"></v-checkbox>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
            <v-flex sm8 lg9>
                <v-card>
                    <v-data-table
                            hide-actions
                            :headers="table.headers"
                            :items="filteredProjects">
                        <template slot="items" slot-scope="props">
                            <tr @click="projectDetails(props.item)">
                                <td>@{{ props.item.client.name }}</td>
                                <td>@{{ props.item.name }}</td>
                                <td>@{{ props.item.estimated_hours }}</td>
                                <td>
                                    <hours-worked :project="props.item">
                                    </hours-worked>
                                </td>
                                <td>
                                <span v-if="props.item.estimated_cost">
                                    $@{{ props.item.estimated_cost }}
                                </span>
                                </td>
                                <td>
                                    <span v-if="props.item.due_at">@{{ props.item.due_at_moment.format('M/DD/YY') }}</span>
                                </td>
                                <td>
                                    <span v-if="props.item.completed_at">@{{ props.item.completed_at_moment.format('M/DD/YY') }}</span>
                                </td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
            <v-btn fab fixed bottom right icon color="success" @click="openCreateProjectModal"><v-icon>add</v-icon></v-btn>
            <v-dialog v-model="modals.createNewProject" max-width="550">
                <v-card tile>
                    <v-card-title>
                        <div class="headline">
                            Create New Project
                        </div>
                    </v-card-title>
                    <form @submit.prevent="validateCreateProject">
                        <v-card-text>
                            <v-container grid-list-md>
                                <form-errors v-if="formErrors.newProject" :form-errors="formErrors.newProject"></form-errors>
                                <v-select
                                        no-data-text="Select client"
                                        v-validate="'required'"
                                        label="Client"
                                        name="client_id"
                                        data-vv-as="client"
                                        data-vv-name="new-client_id"
                                        :error-messages="errors.collect('new-client_id')"
                                        v-model="forms.newProject.client_id"
                                        :items="clients"
                                        item-text="name"
                                        item-value="id"></v-select>
                                <v-text-field v-validate="'required'" data-vv-as="name" data-vv-name="new-name" name="name" :error-messages="errors.collect('new-name')" label="Project Name" v-model="forms.newProject.name"></v-text-field>
                                <v-layout row wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Hours" v-model="forms.newProject.estimated_hours"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Cost" v-model="forms.newProject.estimated_cost"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <div class="d-flex" style="justify-content: space-between">
                                    <div style="font-size: 1.3em">Due At</div>
                                    <div style="text-align: right">
                                        <v-btn @click="forms.newProject.due_at = ''" flat>Clear</v-btn>
                                    </div>
                                </div>
                                <v-date-picker
                                        header-color="green lighten-2"
                                        color="green"
                                        full-width
                                        landscape
                                        class="mt-3"
                                        v-model="forms.newProject.due_at"
                                ></v-date-picker>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="http.creatingProject || errors.any()" :loading="http.creatingProject" type="submit" color="success">Create</v-btn>
                            <v-btn :disabled="http.creatingProject" color="success" @click="closeCreateProjectModal" flat>Cancel</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
            </v-dialog>
        </v-layout>
    </projects-list>
@endsection