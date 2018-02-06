@extends('layouts.main')

@section('content')
    <password-email inline-template>
        <v-layout row align-center>
            <v-flex xs12 sm10 offset-sm1 md6 offset-md3 lg4 offset-lg4>
                @if (session('status'))
                    <v-alert color="info" icon="info" dismissible v-model="alert">
                        {{session('status')}}
                    </v-alert>
                @endif
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline">
                                Reset Password
                            </h3>
                        </div>
                    </v-card-title>
                    <v-card-text>
                        <form @submit="validateEmail" id="password-email-form" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}

                            <v-text-field
                                type="email"
                                label="Email Address"
                                v-model="form.email"
                                name="email"
                                v-validate="'required|email'"
                                :error-messages="errors.collect('email')">
                            </v-text-field>

                            <v-layout row>
                                <v-flex sm12>
                                    <v-btn :disabled="errors.any()" primary type="submit">Send Password Reset Link</v-btn>
                                </v-flex>
                            </v-layout>
                        </form>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </password-email>
@endsection
