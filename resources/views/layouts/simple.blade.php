@extends('layouts.app')

@section('body')
    <v-content>
        <v-container fluid>
            @yield('content')
        </v-container>
    </v-content>
@endsection