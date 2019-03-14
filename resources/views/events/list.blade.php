@extends('layouts.normal-page')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            {{$wholePaginationElement}}
            {{--<events-pagination :data="{{$wholePaginationElement->toJson()}}"></events-pagination>--}}
        </div>

        <events-list class="row justify-content-center" :events="{{ $events }}"></events-list>
    </div>
@endsection
