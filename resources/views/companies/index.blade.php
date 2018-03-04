@extends('layouts.account')

@section('title', 'Companies | ')

@section('account-content')
    <companies
            :states="{{ json_encode(config('states')) }}"
            :companies="{{ json_encode($companies) }}" inline-template>
        <v-layout row wrap>
            <v-flex xs12>
                <v-layout column>
                    <template v-if="mCompanies && mCompanies.length">
                        <v-flex v-for="c in mCompanies" :key="c.id">
                            <company-card
                                    v-on:remove="removeLocalCompany"
                                    v-on:company-edit="openEditCompanyModal"
                                    :company="c">
                            </company-card>
                        </v-flex>
                    </template>
                    <template v-else>
                        <v-flex>
                            <v-alert type="info" :value="true">
                                You haven't created any companies yet. Click the green button below to get started.
                            </v-alert>
                        </v-flex>
                    </template>
                    <v-btn color="success" fixed fab bottom right @click="openCreateCompanyModal"><v-icon>add</v-icon></v-btn>
                </v-layout>

                {{-- Create Company Modal --}}
                <v-dialog v-model="modals.createCompany" max-width="500">
                    <v-card>
                        <v-card-title>
                            <h3 class="headline">Create New Company</h3>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <form @submit.prevent="validateCreateCompany" method="POST">
                                    <form-errors v-if="formErrors && formErrors.createCompany" :form-errors="formErrors.create"></form-errors>
                                    <v-text-field
                                            label="Company Name"
                                            name="name"
                                            v-model="forms.newCompany.name"
                                            data-vv-name="create-company.name"
                                            data-vv-as="company name"
                                            :error-messages="errors.collect('create-company.name')"
                                            v-validate="'required'">
                                    </v-text-field>
                                    <v-text-field
                                            label="Address"
                                            name="address"
                                            v-model="forms.newCompany.address"
                                            data-vv-name="create-company.address"
                                            data-vv-as="address"
                                            :error-messages="errors.collect('create-company.address')"
                                            v-validate="'required'"></v-text-field>
                                    <v-layout row>
                                        <v-flex xs12 sm6>
                                            <v-text-field
                                                label="City"
                                                name="city"
                                                v-model="forms.newCompany.city"
                                                data-vv-name="create-company.city"
                                                data-vv-as="city"
                                                :error-messages="errors.collect('create-company.city')"
                                                v-validate="'required'"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field
                                                label="Zip Code"
                                                name="zip"
                                                v-model="forms.newCompany.zip"
                                                data-vv-name="create-company.zip"
                                                data-vv-as="zip code"
                                                :error-messages="errors.collect('create-company.zip')"
                                                v-validate="'required'"></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row>
                                        <v-flex xs12 sm6>
                                            <v-select
                                                :items="formattedStates"
                                                v-model="forms.newCompany.state"
                                                label="State"
                                                single-line
                                                bottom
                                                data-vv-name="create-company.state"
                                                data-vv-as="state"
                                                :error-messages="errors.collect('create-company.state')"
                                                v-validate="'required'"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field
                                                label="Country"
                                                name="country"
                                                v-model="forms.newCompany.country"
                                                :error-messages="errors.collect('create-company.country')"
                                                data-vv-name="create-company.country"
                                                data-vv-as="country"
                                                v-validate="'required'"></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row>
                                        <v-flex xs12 sm12>
                                            <input id="photo-input-create" type="file" name="photo" style="display: none">
                                            <img style="width:100%" id="create-company-preview">
                                            <v-layout row>
                                                <v-flex xs12 sm12>
                                                    <v-btn @click="openSelectPhotoCreate" color="success">Choose Photo</v-btn>
                                                    <v-btn @click="clearSelectedPhotoCreate" flat>Clear</v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </form>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="http.creatingCompany || errors.any()" :loading="http.creatingCompany" @click.stop="validateCreateCompany" color="success">
                                Create
                            </v-btn>
                            <v-btn @click.stop="closeCreateCompanyModal" flat>Cancel</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                {{-- Edit Company Modal --}}
                <v-dialog v-model="modals.editCompany" max-width="500">
                    <v-card>
                        <v-card-title>
                            <h3 class="headline">Edit Company</h3>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <form @submit.prevent="validateUpdateCompany" method="POST">
                                    <form-errors v-if="formErrors && formErrors.editCompany" :form-errors="formErrors.editCompany"></form-errors>
                                    <v-text-field
                                            label="Company Name"
                                            name="name"
                                            v-model="forms.editCompany.name"
                                            data-vv-name="edit-company.name"
                                            data-vv-as="company name"
                                            :error-messages="errors.collect('edit-company.name')"
                                            v-validate="'required'">
                                    </v-text-field>
                                    <v-text-field
                                            label="Address"
                                            name="address"
                                            v-model="forms.editCompany.address"
                                            data-vv-name="edit-company.address"
                                            data-vv-as="address"
                                            :error-messages="errors.collect('edit-company.address')"
                                            v-validate="'required'"></v-text-field>
                                    <v-layout row>
                                        <v-flex xs12 sm6>
                                            <v-text-field
                                                    label="City"
                                                    name="city"
                                                    v-model="forms.editCompany.city"
                                                    data-vv-name="edit-company.city"
                                                    data-vv-as="city"
                                                    :error-messages="errors.collect('edit-company.city')"
                                                    v-validate="'required'"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field
                                                    label="Zip Code"
                                                    name="zip"
                                                    v-model="forms.editCompany.zip"
                                                    data-vv-name="edit-company.zip"
                                                    data-vv-as="zip code"
                                                    :error-messages="errors.collect('edit-company.zip')"
                                                    v-validate="'required'"></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row>
                                        <v-flex xs12 sm6>
                                            <v-select
                                                    :items="formattedStates"
                                                    v-model="forms.editCompany.state"
                                                    label="State"
                                                    single-line
                                                    bottom
                                                    data-vv-name="edit-company.state"
                                                    data-vv-as="state"
                                                    :error-messages="errors.collect('edit-company.state')"
                                                    v-validate="'required'"></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field
                                                    label="Country"
                                                    name="country"
                                                    v-model="forms.editCompany.country"
                                                    :error-messages="errors.collect('edit-company.country')"
                                                    data-vv-name="edit-company.country"
                                                    data-vv-as="country"
                                                    v-validate="'required'"></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout row>
                                        <v-flex xs12 sm12>
                                            <input id="photo-input-edit" type="file" name="photo" style="display: none">
                                            <img style="width: 100%" id="edit-company-preview-real" :src="forms.editCompany.photo" v-if="forms.editCompany.photo">
                                            <img style="width:100%" id="edit-company-preview">

                                            <v-layout row>
                                                <v-flex xs12 sm12>
                                                    <v-btn @click="openSelectPhotoEdit" color="success">Choose Photo</v-btn>
                                                    <v-btn @click="clearSelectedPhotoEdit">Clear</v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </form>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn :disabled="http.updatingCompany || errors.any()" :loading="http.updatingCompany" @click.stop="validateUpdateCompany" color="success">
                                Update
                            </v-btn>
                            <v-btn @click.stop="closeEditCompanyModal">Cancel</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-flex>
        </v-layout>
    </companies>
@endsection