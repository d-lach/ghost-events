@extends('layouts.normal-page')

@section('content')
    <passport-clients></passport-clients>
    <passport-authorized-clients></passport-authorized-clients>
    <passport-personal-access-tokens></passport-personal-access-tokens>

    <div class="row justify-content-center">
        {{ $wholePaginationElement }}
    </div>

    <events-list class="row justify-content-center" :events="{{ $events }}"></events-list>
@endsection
