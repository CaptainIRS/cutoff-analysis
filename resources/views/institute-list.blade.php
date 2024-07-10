@extends('layouts.app')

@push('title')
    <title>
        View All Institutes Participating in JoSAA Counselling
    </title>
@endpush

@section('meta')
    <meta name="description" content="List of institutes participating in JoSAA">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">View All Institutes</h1>

    <p class="text-lg m-4 print:hidden">
        List of institutes participating in JoSAA.
    </p>
    <livewire:institute-list />
@endsection
