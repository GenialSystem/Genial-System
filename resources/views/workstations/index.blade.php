@extends('layout')

@section('content')
    @livewire('work-stations-table', key(str()->random(10)))
@endsection
