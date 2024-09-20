@extends('layout')

@section('content')
@livewire('invoices-table', ['role' => $role])
@endsection