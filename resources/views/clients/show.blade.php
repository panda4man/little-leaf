@extends('layouts.account')

@section('title', $client->name . ' | Clients | ')

@section('breadcrumbs')
    <v-breadcrumbs divider="/">
        <v-breadcrumbs-item href="/account/clients">Clients</v-breadcrumbs-item>
        <v-breadcrumbs-item>{{$client->name}}</v-breadcrumbs-item>
    </v-breadcrumbs>
@endsection

@section('account-content')
    <client-details
            :client="{{json_encode($json)}}"
            :states="{{json_encode(config('states'))}}"
            :companies="{{json_encode($companies)}}" inline-template>
        <v-layout row>
            <v-flex xs12 sm12>
                <v-card>
                    <v-card-title class="layout d-flex">
                        <div class="headline">
                            @{{ mClient.name }}
                        </div>
                        <div class="text-xs-right text-sm-right">
                            <v-menu offset-y>
                                <v-btn slot="activator" icon><v-icon>more_vert</v-icon></v-btn>
                                <v-list>
                                    <v-list-tile @click="openEditClientModal">
                                        <v-list-tile-content>
                                            Edit
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile @click="confirmDeleteClient">
                                        <v-list-tile-content>
                                            Delete
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-menu>
                        </div>
                    </v-card-title>
                    <v-data-table
                            hide-actions
                            :headers="table.headers"
                            :items="mProjects">
                        <template slot="items" slot-scope="props">
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
                                <span v-if="props.item.due_at">@{{ props.item.due_at_moment.format('MMM Do, YYYY') }}</span>
                            </td>
                            <td>
                                <span v-if="props.item.completed_at">@{{ props.item.completed_at_moment.format('MMM Do, YYYY') }}</span>
                            </td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>

            {{-- Edit Client --}}
            <v-dialog v-model="modals.editClient" max-width="550">
                <v-card>
                    <v-card-title>
                        <h3 class="headline">Edit Client</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <form method="POST" @submit.prevent="validateUpdateClient" data-vv-scope="edit-client">
                                <form-errors v-if="formErrors && formErrors.editClient" :form-errors="formErrors.editClient"></form-errors>
                                <v-select
                                        :items="companies"
                                        v-model="forms.editClient.company_id"
                                        data-vv-name="edit-client.company_id"
                                        item-text="name"
                                        item-value="id"
                                        data-vv-as="company"
                                        v-validate="'required'"
                                        placeholder="Choose Company"
                                        :error-messages="errors.collect('edit-client.company_id')"
                                        label="Company"></v-select>
                                <v-text-field
                                        label="Client Name"
                                        name="name"
                                        v-model="forms.editClient.name"
                                        data-vv-name="edit-client.name"
                                        data-vv-as="client name"
                                        :error-messages="errors.collect('edit-client.name')"
                                        v-validate="'required'">
                                </v-text-field>
                                <v-text-field
                                        label="Address"
                                        name="address"
                                        v-model="forms.editClient.address"
                                        data-vv-name="edit-client.address"
                                        data-vv-as="address"
                                        :error-messages="errors.collect('edit-client.address')"
                                        v-validate="'required'"></v-text-field>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="City"
                                                name="city"
                                                v-model="forms.editClient.city"
                                                data-vv-name="edit-client.city"
                                                data-vv-as="city"
                                                :error-messages="errors.collect('edit-client.city')"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="Zip Code"
                                                name="zip"
                                                v-model="forms.editClient.zip"
                                                data-vv-name="edit-client.zip"
                                                data-vv-as="zip code"
                                                :error-messages="errors.collect('edit-client.zip')"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-select
                                                :items="formattedStates"
                                                v-model="forms.editClient.state"
                                                label="State"
                                                single-line
                                                bottom
                                                data-vv-name="edit-client.state"
                                                data-vv-as="state"
                                                :error-messages="errors.collect('edit-client.state')"
                                                v-validate="'required'"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="Country"
                                                name="country"
                                                v-model="forms.editClient.country"
                                                :error-messages="errors.collect('edit-client.country')"
                                                data-vv-name="edit-client.country"
                                                data-vv-as="country"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="http.updatingClient || errors.any()" :loading="http.updatingClient" @click.stop="validateUpdateClient" color="success">
                            Update
                        </v-btn>
                        <v-btn @click.stop="closeEditClientModal" flat>Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </client-details>
@endsection