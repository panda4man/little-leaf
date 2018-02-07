@extends('layouts.main')

@section('title', 'Companies | ' . config('app.name'))

@section('content')
    <companies :states="{{json_encode(config('states'))}}" :companies="{{ json_encode($companies) }}" inline-template>
        <v-layout row>
            <v-flex xs12 sm4 m4 lg3>
                <v-card>
                    <v-list>
                        <v-subheader style="justify-content: space-between">Companies <v-btn flat icon color="green" @click="openCreateModal"><v-icon>add_circle</v-icon></v-btn></v-subheader>
                        <v-list-tile avatar @click="selectCompany(company.id)" v-for="company in companies" :key="company.id">
                            <v-list-tile-avatar>
                                <img src="http://placehold.it/200x200">
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
                        <v-card>
                            <template v-if="currentCompany">
                                <v-card-title>
                                    <h3 class="headline">@{{currentCompany.name}}</h3>
                                </v-card-title>
                                <v-card-text>

                                </v-card-text>
                            </template>
                            <template v-else>
                                <v-alert color="warning" icon="info" dismissible v-model="alert">
                                    You haven't created any companies yet.
                                </v-alert>
                            </template>
                        </v-card>
                    </v-flex>
                </v-layout>

                {{-- Show Clients --}}
                <v-layout row>
                    <v-flex xs12 sm12>
                        <v-card v-if="currentCompany">
                            <v-card-title>
                                <h3 class="headline">Clients</h3>
                            </v-card-title>
                            <v-card-text>
                                <v-list>
                                    <v-list-tile v-for="client in currentCompany.clients" :key="client.id">
                                        <v-list-tile-content>
                                            <v-list-tile-title v-text="client.name"></v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-dialog v-model="modals.createModal" max-width="500">
                <v-card>
                    <v-card-title>
                        <h3 class="headline">Create New Company</h3>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <form method="POST" data-vv-scope="create">
                                <form-errors v-if="formErrors && formErrors.create" :form-errors="formErrors.create"></form-errors>
                                <v-text-field
                                        label="Company Name"
                                        name="name"
                                        v-model="forms.newCompany.name"
                                        :error-messages="errors.collect('name', 'create')"
                                        v-validate="'required'">
                                </v-text-field>
                                <v-text-field
                                        label="Address"
                                        name="address"
                                        v-model="forms.newCompany.address"
                                        :error-messages="errors.collect('address', 'create')"
                                        v-validate="'required'"></v-text-field>
                                <v-layout row>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            label="City"
                                            name="city"
                                            v-model="forms.newCompany.city"
                                            :error-messages="errors.collect('city', 'create')"
                                            v-validate="'required'"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            label="Zip Code"
                                            name="zip"
                                            v-model="forms.newCompany.zip"
                                            :error-messages="errors.collect('zip', 'create')"
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
                                            bottom></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field
                                            label="Country"
                                            name="country"
                                            v-model="forms.newCompany.country"
                                            :error-messages="errors.collect('country', 'create')"
                                            v-validate="'required'"></v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex xs12 sm12>
                                        <input id="photo-input-create" type="file" name="photo" style="display: none">
                                        <img style="width:100%" id="create-company-preview" :src="imagePreview">
                                        <v-btn @click="openSelectPhotoCreate" color="primary">Choose Photo</v-btn>
                                        <v-btn @click="clearSelectedPhotoCreate" flat>Clear</v-btn>
                                    </v-flex>
                                </v-layout>
                            </form>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="http.creatingCompany" :loading="http.creatingCompany" @click.stop="validateCreateCompany" color="primary">
                            Create
                        </v-btn>
                        <v-btn @click.stop="closeCreateModal" flat>Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </companies>
@endsection