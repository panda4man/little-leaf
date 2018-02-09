@extends('layouts.main')

@section('title', 'Clients | ' . config('app.name'))

@section('content')
    <clients :clients="{{json_encode($clients)}}" :companies="{{json_encode($companies)}}" inline-template>
        <v-layout row>
            <v-flex hidden-xs-only sm4 m4 lg3>
                <v-card>
                    <v-list>
                        <v-subheader>Filter by Company</v-subheader>
                        <v-list-tile avatar @click="filterByCompany(company.id)" v-for="company in companies" :key="company.id">
                            <v-list-tile-avatar>
                                <img :src="company.photo ? company.photo : 'http://placehold.it/200x200'">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="company.name"></v-list-tile-title>
                            </v-list-tile-content>
                            <v-list-tile-action v-if="companySelected(company.id)">
                                <v-btn icon ripple>
                                    <v-icon>done</v-icon>
                                </v-btn>
                            </v-list-tile-action>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
            <v-flex xs12 sm8 m8 lg9>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md6 lg4 v-for="c in mClients" :key="c.id">
                        <v-card>
                            <v-card-text>
                                <h3 class="headline">@{{ c.name }}</h3>
                                <div>Projects: @{{c.projects.length}}</div>
                                <div>Completed: @{{ c.projects.filter(p => c.completed_at).length }}</div>
                            </v-card-text>
                            <v-card-actions style="justify-content: center">
                                <v-btn color="primary" :href="'/clients/' + c.hash_id">Details</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </clients>
@endsection