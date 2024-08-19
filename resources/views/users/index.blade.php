@extends('layout')

@section('content')
<div class="">
    <h1 class="text-2xl font-bold">{{$role == 'mechanic' ? 'Tecnici' : 'Clienti'}}</h1>
    @livewire('dynamic-table', [
    'headers' => ['ID' => 'id', 'Email' => 'email', 'Name' => 'name'],
    'role' => $role,
    'model' => \App\Models\User::class,
    'actions' => ['show', 'delete']
    ])

</div>
@endsection