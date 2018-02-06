@extends('layouts.main')

@section('content')
    <register :form-errors="{{$errors}}" inline-template>
        <v-layout row align-center>
            <v-flex xs12 sm10 offset-sm1 md8 offset-md2 lg6 offset-lg3>
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline">Register</h3>
                        </div>
                    </v-card-title>
                    <v-card-text>
                        <form @submit="validateRegister" id="register-form" method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <v-layout row wrap>
                                <v-flex xs12 sm6>
                                    <v-text-field
                                        label="First Name"
                                        name="first_name"
                                        data-vv-as="first name"
                                        :error-messages="errors.collect('first_name')"
                                        v-model="forms.register.firstName"
                                        v-validate="'required'">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field
                                            label="Last Name"
                                            name="last_name"
                                            data-vv-ass="last name"
                                            :error-messages="errors.collect('last_name')"
                                            v-model="forms.register.lastName"
                                            v-validate="'required'">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-text-field
                                type="email"
                                name="email"
                                label="Email"
                                :error-messages="errors.collect('email')"
                                v-model="forms.register.email"
                                v-validate="'required|email'">
                            </v-text-field>
                            <v-layout row>
                                <v-flex xs12 sm6>
                                    <v-text-field
                                        type="password"
                                        label="Password"
                                        name="password"
                                        :error-messages="errors.collect('password')"
                                        v-model="forms.register.password"
                                        v-validate="'required'">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field
                                        type="password"
                                        label="Confirm Password"
                                        name="password_confirmation"
                                        :error-messages="errors.collect('passwordConfirmation')"
                                        v-model="forms.register.passwordConfirmation"
                                        v-validate="'required'">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row>
                                <v-flex sm12>
                                    <v-btn :disabled="errors.any()" primary type="submit">Register</v-btn>
                                </v-flex>
                            </v-layout>
                        </form>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </register>
@endsection
