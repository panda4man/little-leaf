<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">

    @yield('jsHead')
</head>
<body>
    <div id="app">
        <v-app v-cloak>
            @yield('body')
        </v-app>

        @foreach(['success', 'info', 'warning', 'error'] as $type)
            @if(session($type))
                <v-snackbar :top="true" :right="true" :timeout="2000" :{{$type}}="true" v-model="messages.{{$type}}">
                    {{session($type)}}
                    <v-btn light flat @click.native="messages.{{$type}} = false">Close</v-btn>
                </v-snackbar>
            @endif
        @endforeach
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('jsBody')
</body>
</html>
