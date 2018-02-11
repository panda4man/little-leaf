@extends('layouts.account')

@section('title', 'Profile | ' . config('app.name'))

@section('account-content')
    <v-card>
        <v-card-text>
            <v-layout row wrap>
                <v-flex xs12 sm4 md4 lg3>
                    <v-avatar size="150px">
                        <img src="http://placehold.it/200x200">
                    </v-avatar>
                </v-flex>
                <v-flex xs12 sm9 md8 lg9>
                    <h2>{{auth()->user()->full_name}}</h2>
                    <div>{{auth()->user()->email}}</div>
                    <p>Member since {{auth()->user()->created_at->format('M jS, Y')}}</p>
                </v-flex>
            </v-layout>
        </v-card-text>
    </v-card>
@endsection