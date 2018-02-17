@extends('layouts.app')

@section('body')
    <v-toolbar color="green lighten-3" flat app>
        <v-toolbar-side-icon>
            <img width="40" src="/img/little-leaf.svg">
        </v-toolbar-side-icon>
        <v-toolbar-title class="white--text">
             {{config('app.name')}}
        </v-toolbar-title>
        @php
            $company = currentCompany();
        @endphp
        @if(currentCompanySet() && $company)
            <v-spacer></v-spacer>
            <v-btn color="white" flat>
                {{strtoupper($company->name)}}
            </v-btn>
        @endif
        <v-spacer></v-spacer>
        <v-toolbar-items class="hidden-sm-and-down">
            @guest
                <v-btn href="/login" color="white" flat>Login</v-btn>
                <v-btn href="/register" color="white" flat>Register</v-btn>
            @endguest
            @auth
                <v-btn href="/work" color="white" flat><v-icon left>laptop_mac</v-icon>Work</v-btn>
                <v-btn href="/projects" color="white" flat><v-icon left>work</v-icon>Projects</v-btn>
                <v-btn href="/tasks" color="white" flat><v-icon left>list</v-icon>Tasks</v-btn>
                <v-menu bottom left offset-y close-on-click
                        origin="bottom top"
                        transition="v-scale-transition">
                    <v-btn flat color="white" slot="activator">
                        {{auth()->user()->first_name}}<v-icon>arrow_drop_down</v-icon>
                    </v-btn>
                    <v-list subheader>
                        <v-list-tile href="/account/profile" ripple>
                            <v-list-tile-title>
                                Settings
                            </v-list-tile-title>
                        </v-list-tile>
                        <v-divider></v-divider>
                        <v-subheader>Companies</v-subheader>
                        @foreach(auth()->user()->companies as $c)
                            <v-list-tile href="{{route('set-company', $c)}}" ripple>
                                <v-list-tile-title>
                                    @if(isCurrentCompany($c))<v-icon>star</v-icon>@endif{{$c->name}}
                                </v-list-tile-title>
                            </v-list-tile>
                        @endforeach
                        <v-divider></v-divider>
                        <v-list-tile href="/" @click.native.prevent="logout" ripple>
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