@extends('layouts.main')

@section('title', $project->name . ' | ')

@section('breadcrumbs')
    <v-breadcrumbs divider="/">
        <v-breadcrumbs-item href="/projects">Projects</v-breadcrumbs-item>
        <v-breadcrumbs-item>{{$project->name}}</v-breadcrumbs-item>
    </v-breadcrumbs>
@endsection

@section('content')
    <project-details :project="{{ json_encode($json['project']) }}" :clients="{{ json_encode($json['clients']) }}" inline-template>
        <v-layout row wrap>
            <v-flex sm5 md4 lg3>
                <v-card class="project-summary">
                    <v-card-title class="layout d-flex">
                        <div>
                            <div class="headline">
                                @{{ hydratedProject.name }}
                            </div>
                            <div v-if="mProject.due_at">
                                @{{ hydratedProject.due_at_moment.format('ddd, MMM Do YYYY') }}
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <v-menu offset-y>
                                <v-btn slot="activator" icon><v-icon>more_vert</v-icon></v-btn>
                                <v-list>
                                    <v-list-tile @click="openEditProjectModal">
                                        <v-list-tile-content>
                                            Edit
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile @click="confirmDeleteProject">
                                        <v-list-tile-content>
                                            Delete
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-menu>
                        </div>
                    </v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex class="big-stat" sm6>
                                <v-layout column>
                                    <v-flex class="stat-value text-xs-center">
                                        <hours-worked :project="hydratedProject" suffix=""></hours-worked>
                                    </v-flex>
                                    <v-flex class="text-xs-center">
                                        <div class="stat-text">Hours Worked</div>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <v-flex class="big-stat" sm6>
                                <v-layout column>
                                    <v-flex class="stat-value text-xs-center">@{{ mDeliverables.length }}</v-flex>
                                    <v-flex class="text-xs-center">
                                        <div class="stat-text">Deliverables</div>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn v-if="!mProject.completed_at" @click="tryMarkComplete" color="green" flat>Mark Complete</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
            {{-- Deliverables --}}
            <v-flex sm7 md8 lg9>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md6 :key="d.id" v-for="d in hydratedDeliverables">
                        <v-card :class="{'green lighten-5': d.completed_at}">
                            <v-card-title class="layout d-flex">
                                <div>
                                    <div class="headline">
                                        @{{ d.name }}
                                    </div>
                                    <div v-if="d.due_at">
                                        @{{ d.due_at_moment.format('ddd, MMM Do YYYY') }}
                                    </div>
                                </div>
                                <div class="text-xs-right">
                                    <v-menu offset-y>
                                        <v-btn slot="activator" icon><v-icon>more_vert</v-icon></v-btn>
                                        <v-list>
                                            <v-list-tile @click="openEditDeliverableModal(d)">
                                                <v-list-tile-content>
                                                    Edit
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile @click="confirmDeleteDeliverable(d)">
                                                <v-list-tile-content>
                                                    Delete
                                                </v-list-tile-content>
                                            </v-list-tile>
                                        </v-list>
                                    </v-menu>
                                </div>
                            </v-card-title>
                            <v-card-text>
                                <div>
                                    <span>$@{{d.estimated_cost}}</span>
                                    <span>@{{ d.estimated_hours }}</span>
                                </div>
                                <div v-if="d.description">
                                    @{{ d.description }}
                                </div>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="success" @click="completeProject(d)" flat>Mark Complete</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-btn color="success" fixed dark icon fab bottom right @click="openCreateDeliverableModal"><v-icon>add</v-icon></v-btn>

            {{-- Edit Project Modal --}}
            <v-dialog v-model="modals.editProject" max-width="550">
                <v-card tile>
                    <v-card-title>
                        <div class="headline">
                            Edit Project
                        </div>
                    </v-card-title>
                    <form @submit.prevent="validateUpdateProject">
                        <v-card-text>
                            <v-container grid-list-md>
                                <form-errors v-if="formErrors.editProject" :form-errors="formErrors.editProject"></form-errors>
                                <v-select
                                        no-data-text="Select client"
                                        v-validate="'required'"
                                        label="Client"
                                        name="client_id"
                                        data-vv-as="client"
                                        data-vv-name="edit-client_id"
                                        :error-messages="errors.collect('edit-client_id')"
                                        v-model="forms.editProject.client_id"
                                        :items="clients"
                                        item-text="name"
                                        item-value="id"></v-select>
                                <v-text-field
                                        v-validate="'required'"
                                        name="name"
                                        data-vv-as="name"
                                        :error-messages="errors.collect('edit-name')"
                                        data-vv-name="edit-name"
                                        label="Project Name"
                                        v-model="forms.editProject.name"></v-text-field>
                                <v-layout row wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Hours" v-model="forms.editProject.estimated_hours"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Cost" v-model="forms.editProject.estimated_cost"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <div class="d-flex" style="justify-content: space-between">
                                    <div style="font-size: 1.3em">Due At</div>
                                    <div style="text-align: right">
                                        <v-btn @click="clearDueAt(forms.editProject)" flat>Clear</v-btn>
                                    </div>
                                </div>
                                <v-date-picker
                                        header-color="green lighten-2"
                                        color="green"
                                        full-width
                                        landscape
                                        class="mt-3"
                                        v-model="forms.editProject.due_at"
                                ></v-date-picker>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="http.updatingProject || errors.any()" :loading="http.updatingProject" type="submit" color="success">Update</v-btn>
                            <v-btn :disabled="http.updatingProject" color="success" @click="closeEditProjectModal" flat>Cancel</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
            </v-dialog>

            {{-- Create Deliverable Modal --}}
            <v-dialog v-model="modals.createDeliverable" max-width="550">
                <v-card>
                    <v-card-title>
                        <div class="headline">Create Deliverable</div>
                    </v-card-title>
                    <form @submit.prevent="validateCreateDeliverable">
                        <v-card-text>
                            <v-container grid-list-md>
                                <form-errors v-if="formErrors.createDeliverable" :form-errors="formErrors.createDeliverable"></form-errors>
                                <v-text-field
                                        v-validate="'required'"
                                        name="name"
                                        data-vv-as="name"
                                        :error-messages="errors.collect('new-deliverable-name')"
                                        data-vv-name="new-deliverable-name"
                                        label="Project Name"
                                        v-model="forms.newDeliverable.name"></v-text-field>
                                <v-layout row wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Hours" v-model="forms.newDeliverable.estimated_hours"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Cost" v-model="forms.newDeliverable.estimated_cost"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-text-field
                                        label="Description"
                                        v-validate="'required'"
                                        data-vv-as="Description"
                                        data-vv-name="new-deliverable-description"
                                        :error-messages="errors.collect('new-deliverable-description')"
                                        v-model="forms.newDeliverable.description"
                                        multi-line></v-text-field>
                                <div class="d-flex" style="justify-content: space-between">
                                    <div style="font-size: 1.3em">Due At</div>
                                    <div style="text-align: right">
                                        <v-btn @click="clearDueAt(forms.newDeliverable)" flat>Clear</v-btn>
                                    </div>
                                </div>
                                <v-date-picker
                                        header-color="green lighten-2"
                                        color="green"
                                        full-width
                                        landscape
                                        class="mt-3"
                                        v-model="forms.newDeliverable.due_at"
                                ></v-date-picker>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="http.creatingDeliverable || errors.any()" :loading="http.creatingDeliverable" type="submit" color="success">Create</v-btn>
                            <v-btn :disabled="http.creatingDeliverable" color="success" @click="closeCreateDeliverableModal" flat>Cancel</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
            </v-dialog>

            {{-- Edit Deliverable --}}
            <v-dialog v-model="modals.editDeliverable" max-width="550">
                <v-card>
                    <v-card-title>
                        <div class="headline">Create Deliverable</div>
                    </v-card-title>
                    <form @submit.prevent="validateEditDeliverable">
                        <v-card-text>
                            <v-container grid-list-md>
                                <form-errors v-if="formErrors.editDeliverable" :form-errors="formErrors.editDeliverable"></form-errors>
                                <v-text-field
                                        v-validate="'required'"
                                        name="name"
                                        data-vv-as="name"
                                        :error-messages="errors.collect('edit-deliverable-name')"
                                        data-vv-name="edit-deliverable-name"
                                        label="Project Name"
                                        v-model="forms.editDeliverable.name"></v-text-field>
                                <v-layout row wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Hours" v-model="forms.editDeliverable.estimated_hours"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field label="Estimated Cost" v-model="forms.editDeliverable.estimated_cost"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-text-field
                                        label="Description"
                                        v-validate="'required'"
                                        data-vv-as="Description"
                                        data-vv-name="edit-deliverable-description"
                                        :error-messages="errors.collect('edit-deliverable-description')"
                                        v-model="forms.editDeliverable.description"
                                        multi-line></v-text-field>
                                <div class="d-flex" style="justify-content: space-between">
                                    <div style="font-size: 1.3em">Due At</div>
                                    <div style="text-align: right">
                                        <v-btn @click="clearDueAt(forms.editDeliverable)" flat>Clear</v-btn>
                                    </div>
                                </div>
                                <v-date-picker
                                        header-color="green lighten-2"
                                        color="green"
                                        full-width
                                        landscape
                                        class="mt-3"
                                        v-model="forms.editDeliverable.due_at"
                                ></v-date-picker>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="http.updatingDeliverable || errors.any()" :loading="http.updatingDeliverable" type="submit" color="success">Update</v-btn>
                            <v-btn :disabled="http.updatingDeliverable" color="success" @click="closeEditDeliverableModal" flat>Cancel</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
            </v-dialog>
        </v-layout>
    </project-details>
@endsection