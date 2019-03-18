@extends('layouts.normal-page')

@section('content')
    <div class="container">
        <events-table class="row justify-content-center"
                      :events="{{ $events }}"></events-table>
    </div>
@endsection
