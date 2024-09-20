@extends('layout')

@section('content')
    @livewire('workstations-table', key(str()->random(10)))
@endsection
