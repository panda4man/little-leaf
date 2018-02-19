@extends('layouts.account')

@section('title', 'Clients | ')

@section('account-content')
    <clients
            :states="{{json_encode(config('states'))}}"
            :clients="{{json_encode($clients)}}"
            :companies="{{json_encode($companies)}}" inline-template>
        <v-layout row>
            <v-flex xs12 sm12 md7 lg8>
                <v-card>
                    <v-list two-line subheader>
                        <v-subheader>Clients</v-subheader>
                        <v-list-tile v-for="client in filteredClients" :key="client.id" :href="'/account/clients/' + client.hash_id">
                            <v-list-tile-content>
                                <v-list-tile-title>@{{ client.name }} - @{{ client.city }}, @{{ client.state }}</v-list-tile-title>
                                <v-list-tile-sub-title>Projects: @{{ client.projects.length }} | Completed: @{{ client.projects.filter(c => c.completed_at).length }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
            <v-flex hidden-sm-and-down md5 lg4>
                <v-card>
                    <v-list avatar>
                        <v-subheader>Companies</v-subheader>
                        <v-list-tile v-for="c in companies" :key="c.id" @click="filterByCompany(c.id)">
                            <v-list-tile-avatar>
                                <img :src="c.photo ? c.photo : '//placehold.it/200x200'">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>@{{ c.name }}</v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action v-if="companySelected(c.id)">
                                <v-btn icon><v-icon>done</v-icon></v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>

            <v-btn fab fixed bottom right color="success" @click="openCreateClientModal">
                <v-icon>add</v-icon>
            </v-btn>

            {{-- Create Client Modal --}}
            <v-dialog v-model="modals.createClient" max-width="550">
                <v-card>
                    <v-card-title>
                        <h3 class="headline">Create New Client</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <form method="POST" data-vv-scope="create-client">
                                <form-errors v-if="formErrors && formErrors.createClient" :form-errors="formErrors.createClient"></form-errors>
                                <v-select
                                    :items="companies"
                                    v-model="forms.newClient.company_id"
                                    data-vv-name="create-client.company_id"
                                    item-text="name"
                                    item-value="id"
                                    data-vv-as="company"
                                    v-validate="'required'"
                                    placeholder="Choose Company"
                                    :error-messages="errors.collect('create-client.company_id')"
                                    label="Company"></v-select>
                                <v-text-field
                                        label="Client Name"
                                        name="name"
                                        v-model="forms.newClient.name"
                                        data-vv-name="create-client.name"
                                        data-vv-as="client name"
                                        :error-messages="errors.collect('create-client.name')"
                                        v-validate="'required'">
                                </v-text-field>
                                <v-text-field
                                        label="Address"
                                        name="address"
                                        v-model="forms.newClient.address"
                                        data-vv-name="create-client.address"
                                        data-vv-as="address"
                                        :error-messages="errors.collect('create-client.address')"
                                        v-validate="'required'"></v-text-field>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="City"
                                                name="city"
                                                v-model="forms.newClient.city"
                                                data-vv-name="create-client.city"
                                                data-vv-as="city"
                                                :error-messages="errors.collect('create-client.city')"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="Zip Code"
                                                name="zip"
                                                v-model="forms.newClient.zip"
                                                data-vv-name="create-client.zip"
                                                data-vv-as="zip code"
                                                :error-messages="errors.collect('create-client.zip')"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-select
                                                :items="formattedStates"
                                                v-model="forms.newClient.state"
                                                label="State"
                                                single-line
                                                bottom
                                                data-vv-name="create-client.state"
                                                data-vv-as="state"
                                                :error-messages="errors.collect('create-client.state')"
                                                v-validate="'required'"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="Country"
                                                name="country"
                                                v-model="forms.newClient.country"
                                                :error-messages="errors.collect('create-client.country')"
                                                data-vv-name="create-client.country"
                                                data-vv-as="country"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="http.creatingClient || errors.any()" :loading="http.creatingClient" @click.stop="validateCreateClient" color="success">
                            Create
                        </v-btn>
                        <v-btn @click.stop="closeCreateClientModal">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </clients>
@endsection