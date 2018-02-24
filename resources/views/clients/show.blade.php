@extends('layouts.account')

@section('title', $client->name . ' | Clients | ')

@section('breadcrumbs')
    <v-breadcrumbs divider="/">
        <v-breadcrumbs-item href="/account/clients">Clients</v-breadcrumbs-item>
        <v-breadcrumbs-item>{{$client->name}}</v-breadcrumbs-item>
    </v-breadcrumbs>
@endsection

@section('account-content')
    <client-details :client="{{json_encode($json)}}" inline-template>
        <v-layout row>
            <v-flex xs12 sm12>
                <v-card>
                    <v-card-title>
                        <h3 class="headline">@{{ client.name }}</h3>
                    </v-card-title>
                    <v-data-table
                            hide-actions
                            :headers="table.headers"
                            :items="mProjects">
                        <template slot="items" slot-scope="props">
                            <td>@{{ props.item.name }}</td>
                            <td>@{{ props.item.estimated_hours }}</td>
                            <td>
                                <hours-worked :project="props.item">
                                </hours-worked>
                            </td>
                            <td>$@{{ props.item.estimated_cost }}</td>
                            <td>
                                <span v-if="props.item.due_at">@{{ props.item.due_at_moment.format('MMM Do, YYYY') }}</span>
                            </td>
                            <td>
                                <span v-if="props.item.completed_at">@{{ props.item.completed_at_moment.format('MMM Do, YYYY') }}</span>
                            </td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </client-details>
@endsection