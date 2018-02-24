@extends('layouts.main')

@section('content')
    <v-layout row wrap>
        <v-flex xs12 sm4 md4 lg3>
            <v-card>
                <v-list>
                    <v-list-tile avatar>
                        <v-list-tile-avatar>
                            <img src="http://placehold.it/100x100">
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{auth()->user()->full_name}}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile href="/account/profile" {{urlActive('account/profile')}}>
                        <v-list-tile-avatar>
                            <v-icon>perm_identity</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Profile</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile href="/account/companies" {{urlActive('account/companies')}}>
                        <v-list-tile-avatar>
                            <v-icon left>store</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Companies</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile href="/account/clients" {{urlActive('account/clients*')}}>
                        <v-list-tile-avatar>
                            <v-icon left>supervisor_account</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Clients</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile href="/account/social-media" {{urlActive('account/social-media')}}>
                        <v-list-tile-avatar>
                            <v-icon>phone_iphone</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Social Media</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-flex>
        <v-flex xs12 sm8 md8 lg9>
            @yield('account-content')
        </v-flex>
    </v-layout>
@endsection