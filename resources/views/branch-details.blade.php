@extends('layouts.app')

@section('meta')
    <meta name="description" content="Details of {{ $branch['id'] }} branch in JoSAA">
@endsection

@section('content')
    <h1 class="text-3xl font-bold mx-4 my-10 print:hidden">{{ $branch['name'] }}</h1>

    <livewire:branch-details :branch="$branch" />
@endsection
