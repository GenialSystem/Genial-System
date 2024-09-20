@extends('layout')

@section('content')
    @livewire('main-order-table', key(str()->random(10)))
@endsection
