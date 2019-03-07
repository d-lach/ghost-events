@extends('layouts.base-page')

@section('page')


    <div id="app">
        @include('layouts.main-bar')
        <main>
            <div class="col-md-12">
                @yield('content')
            </div>
        </main>
    </div>

@endsection