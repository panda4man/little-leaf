@extends('layouts.main')

@section('title', 'Companies | ' . config('app.name'))

@section('content')
    <companies :companies="{{ json_encode($companies) }}" inline-template>
        <v-layout row>
            <v-flex xs12 sm4 m4 lg3>
                <v-card>
                    <v-list>
                        <v-list-tile avatar @click="selectCompany(c.id)" v-for="company in companies" :key="company.id">
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

                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </companies>
@endsection