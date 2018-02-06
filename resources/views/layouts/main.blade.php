@extends('layouts.app')

@section('body')
    <v-toolbar dark color="primary" app>
        <v-toolbar-title class="white--text">{{config('app.name')}}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
            @guest
                <v-btn href="/login" flat>Login</v-btn>
                <v-btn href="/register" flat>Register</v-btn>
            @endguest
            @auth
                <v-btn @click.native="logout" flat>Logout</v-btn>
            @endauth
        </v-toolbar-items>
    </v-toolbar>
    <v-content>
        <v-container fluid>
            @foreach(['success', 'info', 'warning', 'danger'] as $type)
                @if(session($type))
                    
                @endif
            @endforeach

            @yield('content')
        </v-container>
    </v-content>
@endsection