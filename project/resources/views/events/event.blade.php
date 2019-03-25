@extends('layouts.normal-page')

@section('content')
    <div class="container">
        <div class="row mt-1 mb-2 h1">
            {{$event->name}}
        </div>

        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="row">

                    <div class="col-sm-5">
                        from: {{$event->starts_at}}</div>
                    <div class="col-sm-5">
                        to: {{$event->ends_at}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                registration open till: {{$event->closes_at}}
            </div>
        </div>

        <div class="row mb-2">
            {{ $event->description }}
        </div>

        <div class="row">
            <guests-controller :event="{{ json_encode($event) }}"
                               :guests="{{ json_encode($guests) }}"></guests-controller>
        </div>
    </div>
@endsection

