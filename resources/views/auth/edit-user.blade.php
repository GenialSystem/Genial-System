@extends('layout')

@section('content')
    @livewire('edit-user-profile', ['user' => $user])
@endsection
