@extends('layout')

@section('content')
    <div>
        <h2 class="text-2xl font-bold">Clienti</h2>

        @livewire('customer-table')
    </div>
@endsection
