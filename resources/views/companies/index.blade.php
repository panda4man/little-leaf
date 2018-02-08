@extends('layouts.main')

@section('title', 'Companies | ' . config('app.name'))

@section('content')
    <companies :states="{{json_encode(config('states'))}}" :companies="{{ json_encode($companies) }}" inline-template>
        <v-layout row wrap>
            <v-flex xs12 sm4 m4 lg3>
                <v-card>
                    <v-list>
                        <v-subheader style="justify-content: space-between">Companies <v-btn flat icon color="green" @click="openCreateCompanyModal"><v-icon>add_circle</v-icon></v-btn></v-subheader>
                        <v-list-tile avatar @click="selectCompany(company.id)" v-for="company in companies" :key="company.id">
                            <v-list-tile-avatar>
                                <img :src="company.photo ? company.photo : 'http://placehold.it/200x200'">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="company.name"></v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
            <v-flex xs12 sm8 m8 lg9>
                <v-layout row>
                    <v-flex xs12 sm12>
                        <company-card v-on:company-edit="openEditCompanyModal" :company="currentCompany"></company-card>
                    </v-flex>
                </v-layout>

                {{-- Show Clients --}}
                <v-layout row>
                    <v-flex xs12 sm12>
                        <v-card v-if="currentCompany">
                            <v-card-title class="pb-0" style="justify-content: space-between">
                                <h3 class="headline">
                                    Clients
                                </h3>
                                <v-btn flat icon color="green" @click="openCreateClientModal"><v-icon>add_circle</v-icon></v-btn>
                            </v-card-title>
                            <v-card-text>
                                <v-list v-if="currentCompany.clients && currentCompany.clients.length">
                                    <v-list-tile v-for="client in currentCompany.clients" :key="client.id">
                                        <v-list-tile-content>
                                            <v-list-tile-title v-text="client.name"></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                                <template v-else>
                                    <v-alert color="info" icon="info" value="true">
                                        You have not created any clients for @{{ currentCompany.name }} yet.
                                    </v-alert>
                                </template>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>

            {{-- Create Company Modal --}}
            <v-dialog v-model="modals.createCompany" max-width="500">
                <v-card>
                    <v-card-title>
                        <h3 class="headline">Create New Company</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <form @submit.prevent="validateCreateCompany" method="POST">
                                <form-errors v-if="formErrors && formErrors.createCompany" :form-errors="formErrors.create"></form-errors>
                                <v-text-field
                                        label="Company Name"
                                        name="name"
                                        v-model="forms.newCompany.name"
                                        data-vv-name="create-company.name"
                                        data-vv-as="company name"
                                        :error-messages="errors.collect('create-company.name')"
                                        v-validate="'required'">
                                </v-text-field>
                                <v-text-field
                                        label="Address"
                                        name="address"
                                        v-model="forms.newCompany.address"
                                        data-vv-name="create-company.address"
                                        data-vv-as="address"
                                        :error-messages="errors.collect('create-company.address')"
                                        v-validate="'required'"></v-text-field>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            label="City"
                                            name="city"
                                            v-model="forms.newCompany.city"
                                            data-vv-name="create-company.city"
                                            data-vv-as="city"
                                            :error-messages="errors.collect('create-company.city')"
                                            v-validate="'required'"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            label="Zip Code"
                                            name="zip"
                                            v-model="forms.newCompany.zip"
                                            data-vv-name="create-company.zip"
                                            data-vv-as="zip code"
                                            :error-messages="errors.collect('create-company.zip')"
                                            v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-select
                                            :items="formattedStates"
                                            v-model="forms.newCompany.state"
                                            label="State"
                                            single-line
                                            bottom
                                            data-vv-name="create-company.state"
                                            data-vv-as="state"
                                            :error-messages="errors.collect('create-company.state')"
                                            v-validate="'required'"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            label="Country"
                                            name="country"
                                            v-model="forms.newCompany.country"
                                            :error-messages="errors.collect('create-company.country')"
                                            data-vv-name="create-company.country"
                                            data-vv-as="country"
                                            v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm12>
                                        <input id="photo-input-create" type="file" name="photo" style="display: none">
                                        <img style="width:100%" id="create-company-preview">
                                        <v-layout row>
                                            <v-flex xs12 sm12>
                                                <v-btn @click="openSelectPhotoCreate" color="primary">Choose Photo</v-btn>
                                                <v-btn @click="clearSelectedPhotoCreate">Clear</v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="http.creatingCompany || errors.any()" :loading="http.creatingCompany" @click.stop="validateCreateCompany" color="primary">
                            Create
                        </v-btn>
                        <v-btn @click.stop="closeCreateCompanyModal">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            {{-- Edit Company Modal --}}
            <v-dialog v-model="modals.editCompany" max-width="500">
                <v-card>
                    <v-card-title>
                        <h3 class="headline">Edit Company</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <form @submit.prevent="validateUpdateCompany" method="POST">
                                <form-errors v-if="formErrors && formErrors.editCompany" :form-errors="formErrors.editCompany"></form-errors>
                                <v-text-field
                                        label="Company Name"
                                        name="name"
                                        v-model="forms.editCompany.name"
                                        data-vv-name="edit-company.name"
                                        data-vv-as="company name"
                                        :error-messages="errors.collect('edit-company.name')"
                                        v-validate="'required'">
                                </v-text-field>
                                <v-text-field
                                        label="Address"
                                        name="address"
                                        v-model="forms.editCompany.address"
                                        data-vv-name="edit-company.address"
                                        data-vv-as="address"
                                        :error-messages="errors.collect('edit-company.address')"
                                        v-validate="'required'"></v-text-field>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="City"
                                                name="city"
                                                v-model="forms.editCompany.city"
                                                data-vv-name="edit-company.city"
                                                data-vv-as="city"
                                                :error-messages="errors.collect('edit-company.city')"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="Zip Code"
                                                name="zip"
                                                v-model="forms.editCompany.zip"
                                                data-vv-name="edit-company.zip"
                                                data-vv-as="zip code"
                                                :error-messages="errors.collect('edit-company.zip')"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-select
                                                :items="formattedStates"
                                                v-model="forms.editCompany.state"
                                                label="State"
                                                single-line
                                                bottom
                                                data-vv-name="edit-company.state"
                                                data-vv-as="state"
                                                :error-messages="errors.collect('edit-company.state')"
                                                v-validate="'required'"></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                                label="Country"
                                                name="country"
                                                v-model="forms.editCompany.country"
                                                :error-messages="errors.collect('edit-company.country')"
                                                data-vv-name="edit-company.country"
                                                data-vv-as="country"
                                                v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm12>
                                        <input id="photo-input-edit" type="file" name="photo" style="display: none">
                                        <img style="width: 100%" id="edit-company-preview-real" :src="forms.editCompany.photo" v-if="forms.editCompany.photo">
                                        <img style="width:100%" id="edit-company-preview">

                                        <v-layout row>
                                            <v-flex xs12 sm12>
                                                <v-btn @click="openSelectPhotoEdit" color="primary">Choose Photo</v-btn>
                                                <v-btn @click="clearSelectedPhotoEdit">Clear</v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="http.updatingCompany || errors.any()" :loading="http.editingCompany" @click.stop="validateUpdateCompany" color="primary">
                            Update
                        </v-btn>
                        <v-btn @click.stop="closeEditCompanyModal">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

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
                                <v-text-field
                                        label="Company Name"
                                        name="name"
                                        v-model="forms.newClient.name"
                                        data-vv-name="create-client.name"
                                        data-vv-as="company name"
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
                        <v-btn :disabled="http.creatingClient || errors.any()" :loading="http.creatingClient" @click.stop="validateCreateClient" color="primary">
                            Create
                        </v-btn>
                        <v-btn @click.stop="closeCreateClientModal">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </companies>
@endsection