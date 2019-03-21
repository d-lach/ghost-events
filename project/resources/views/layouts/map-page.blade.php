@extends('layouts.base-page')

@section('headers')
    <link href="{{ asset('css/map.css') }}" rel="stylesheet">
@endsection

@section('page')


    <div id="app" class="container-fluid d-flex h-100 flex-column">
        @include('layouts.main-bar')
        <main class="h-100 bg-light flex-grow-1 d-flex ">
            @yield('content')
        </main>
    </div>

@endsection
