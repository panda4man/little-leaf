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
                    <v-list-tile href="/account/profile" @if(request()->is('account/profile'))disabled @endif>
                        <v-list-tile-content>
                            <v-list-tile-title>Profile</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile href="/account/social-media" @if(request()->is('account/social-media'))disabled @endif>
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