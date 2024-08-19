@extends('layout')

@section('content')
<div class="">
    <h1 class="text-2xl font-bold">Home</h1>

    <div class="flex">
        <div class="rounded-md bg-red-500 p-10 mr-4">
            img
        </div>
        <div class="place-content-center">
            <span>11.125</span>
            <br>
            <span>totale auto in avorazione</span>
        </div>
    </div>
    @livewire('dynamic-table', [
    'headers' => ['ID' => 'id', 'Email' => 'email', 'Name' => 'name'],
    'rows' => $users,
    'model' => \App\Models\User::class,
    'actions' => ['show', 'delete']
    ])
    @role('admin')
    I am admin!
    @else
    I am not admin...
    @endrole


</div>
@endsection