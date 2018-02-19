@extends('layouts.account')

@section('title', $client->name . ' | Clients')

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
                            :items="projects">
                        <template slot="items" slot-scope="props">
                            <td>@{{ props.item.name }}</td>
                            <td>@{{ props.item.estimated_hours }}</td>
                            <td>
                                <hours-worked :project="props.item">
                                </hours-worked>
                            </td>
                            <td>$@{{ props.item.estimated_cost }}</td>
                            <td>@{{ props.item.due_at }}</td>
                            <td>@{{ props.item.completed_at }}</td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </client-details>
@endsection