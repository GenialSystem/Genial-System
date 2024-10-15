@extends('layout')

@section('content')
    @livewire('main-order-table', ['isCustomer' => Auth::user()->roles->pluck('name')[0] === 'customer' ? true : false], key(str()->random(10)))
@endsection
