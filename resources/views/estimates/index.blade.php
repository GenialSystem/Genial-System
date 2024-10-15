@extends('layout')

@section('content')
    @livewire('estimates-table', ['isCustomer' => Auth::user()->roles->pluck('name')[0] === 'customer' ? true : false])
@endsection
