@extends('layouts.account')

@section('title', $client->name . ' | Clients')

@section('account-content')
    <client-details :client="{{json_encode($json)}}" inline-template>
        <v-layout row>
            <v-flex xs12 sm4 md4 lg3>
                <v-card>
                    <v-card-title>
                        <div class="headline">{{$client->name}}</div>
                    </v-card-title>
                    <v-card-text>

                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 sm8 md8 lg9>
                <v-data-table
                        hide-actions
                        :headers="table.headers"
                        :items="projects"
                        class="elevation-1">
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
            </v-flex>
        </v-layout>
    </client-details>
@endsection