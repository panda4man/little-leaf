@extends('layouts.main')

@section('title', 'Work Month |')

@section('content')
    <v-layout row>
        @json($work)
    </v-layout>
@endsection