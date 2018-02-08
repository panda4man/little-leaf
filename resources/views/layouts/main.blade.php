@extends('layouts.app')

@section('body')
    <v-toolbar dark color="primary" app>
        <v-toolbar-title>
            <a class="white--text" style="text-decoration: none" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </v-toolbar-title>
        @php
            $company = currentCompany();
        @endphp
        @if(currentCompanySet() && $company)
            <v-spacer></v-spacer>
            <v-btn flat>
                {{strtoupper($company->name)}}
            </v-btn>
        @endif
        <v-spacer></v-spacer>
        <v-toolbar-items class="hidden-sm-and-down">
            @guest
                <v-btn href="/login" flat>Login</v-btn>
                <v-btn href="/register" flat>Register</v-btn>
            @endguest
            @auth
                <v-btn href="/companies" flat>Companies</v-btn>
                <v-menu bottom left offset-y close-on-click
                        origin="bottom top"
                        transition="v-scale-transition">
                    <v-btn class="white--text" flat light slot="activator">
                        {{auth()->user()->first_name}}<v-icon light>arrow_drop_down</v-icon>
                    </v-btn>
                    <v-list subheader>
                        <v-list-tile href="/" ripple>
                            <v-list-tile-title>
                                <v-icon>person</v-icon>Profile
                            </v-list-tile-title>
                        </v-list-tile>
                        <v-divider></v-divider>
                        <v-subheader>Companies</v-subheader>
                        @foreach(auth()->user()->companies as $c)
                            <v-list-tile href="/companies/{{$c->id}}/select" ripple>
                                <v-list-tile-title>
                                    {{$c->name}}
                                </v-list-tile-title>
                            </v-list-tile>
                        @endforeach
                        <v-divider></v-divider>
                        <v-list-tile @click.native.prevent="logout" ripple>
                            <v-list-tile-title>
                                <v-icon>exit_to_app</v-icon>Logout
                            </v-list-tile-title>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            @endauth
        </v-toolbar-items>
    </v-toolbar>
    <v-content>
        <v-container grid-list-md @yield('contentWidthClass', '')>
            @yield('content')
        </v-container>
    </v-content>
@endsection