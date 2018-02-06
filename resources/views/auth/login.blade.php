@extends('layouts.main')

@section('content')
    <login inline-template>
        <v-layout row align-center>
            <v-flex xs12 sm10 offset-sm1 md8 offset-md2 lg4 offset-lg4>
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline">Login</h3>
                        </div>
                    </v-card-title>
                    <v-card-text>
                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}

                            <v-text-field
                                name="email"
                                v-model="forms.login.email"
                                label="Email"
                                v-validate="'required'">
                            </v-text-field>

                            <v-text-field
                                    name="password"
                                    type="password"
                                    v-model="forms.login.password"
                                    label="Password"
                                    v-validate="'required'">
                            </v-text-field>

                            <v-checkbox name="remember" label="Remember Me" v-model="forms.login.rememberMe"></v-checkbox>

                            <v-layout row>
                                <v-flex sm12>
                                    <v-btn primary type="submit">Login</v-btn>
                                    <v-btn href="{{ route('password.request') }}">Forgot Your Password?</v-btn>
                                </v-flex>
                            </v-layout>
                        </form>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </login>
@endsection
