@extends('layouts.main')

@section('title', 'Companies | ' . config('app.name'))

@section('content')
    <companies :companies="{{ json_encode($companies) }}" inline-template>
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
            <v-dialog v-model="modals.createModal" max-width="600">
                <v-card>
                    <v-card-title>
                        Create New Company
                    </v-card-title>
                    <v-card-text>
                        <form method="POST" data-vv-scope="create">
                            <form-errors v-if="formErrors && formErrors.create" :errors="formErrors.create"></form-errors>
                            <v-text-field
                                label="Company Name"
                                name="name"
                                v-model="forms.newCompany.name"
                                :error-messages="errors.collect('name', 'create')"
                                v-validate="'required'">
                            </v-text-field>
                        </form>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn @click.stop="validateCreateCompany" color="primary">Create</v-btn>
                        <v-btn @click.stop="closeCreateModal" flat>Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </companies>
@endsection