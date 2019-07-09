@extends('layouts.normal-page')

@section('content')
    <div class="row justify-content-center">
        {{ $wholePaginationElement }}
    </div>

    <events-list class="row justify-content-center" :events="{{ $events }}"></events-list>
@endsection
