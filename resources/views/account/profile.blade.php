@extends('layouts.account')

@section('title', 'Profile | ')

@section('account-content')
    <account-profile :user="{{json_encode(auth()->user())}}" inline-template>
        <div>
            <v-card>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs12 sm5 md4 lg3 text-xs-center text-sm-left>
                            <v-avatar size="150px">
                                <img src="http://placehold.it/200x200">
                            </v-avatar>
                        </v-flex>
                        <v-flex xs12 sm7 md8 lg9 text-xs-center text-sm-left>
                            <h2>{{auth()->user()->full_name}}</h2>
                            <div>{{auth()->user()->email}}</div>
                            <p>Member since {{auth()->user()->created_at->format('M jS, Y')}}</p>
                            <v-btn @click="openEditProfileModal" color="success"><v-icon left>edit</v-icon>Edit</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
            <v-dialog v-model="modals.profile" max-width="500px">
                <v-card>
                    <v-card-title>
                        <div class="headline">Update Profile</div>
                    </v-card-title>
                    <v-card-text>
                        <form id="profile-form" @submit.prevent="validateUpdateProfile" method="POST" action="/account/profile">
                            {{csrf_field()}}
                            <v-container grid-list-md>
                                <v-layout row>
                                    <v-flex>
                                        <v-text-field
                                                name="first_name"
                                                label="First Name"
                                                data-vv-as="first name"
                                                v-validate="'required'"
                                                :error-messages="errors.collect('first_name')"
                                                v-model="forms.profile.first_name">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex>
                                        <v-text-field
                                                name="last_name"
                                                label="Last Name"
                                                data-vv-as="last name"
                                                v-validate="'required'"
                                                :error-messages="errors.collect('last_name')"
                                                v-model="forms.profile.last_name">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-text-field
                                    name="email"
                                    label="Email"
                                    v-validate="'required|email'"
                                    :error-messages="errors.collect('email')"
                                    v-model="forms.profile.email">
                                </v-text-field>
                            </v-container>
                        </form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="errors.any()" :loading="http.profile" @click="validateUpdateProfile" color="success">Update</v-btn>
                        <v-btn @click="closeEditProfileModal">Cancel</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </account-profile>
@endsection