@extends('layouts.app')

@push('title')
    <title>
        View All Branches in JoSAA Counselling
    </title>
@endpush

@section('meta')
    <meta name="description" content="List of branches available in JoSAA counselling">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">View All Branches</h1>

    <p class="text-lg m-4 print:hidden">
        List of branches available in JoSAA counselling.
    </p>
    <livewire:branch-list />
@endsection
