@extends('layouts.main')

@section('content')
    <v-layout row>
        @json($work)
    </v-layout>
@endsection