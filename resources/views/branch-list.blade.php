@extends('layouts.app')

@push('title', 'View All Branches in JoSAA Counselling')

@section('meta')
    <meta name="description" content="List of branches available in JoSAA counselling">
    <meta property="og:title" content="View All Branches - JoSAA Analysis">
    <meta property="og:description" content="List of branches available in JoSAA counselling">
    <meta property="og:url" content="{{ route('branch-list') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="View All Branches - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('branch-list') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description" content="List of branches available in JoSAA counselling">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">View All Branches</h1>

    <p class="text-lg m-4 print:hidden">
        List of branches available in JoSAA counselling.
    </p>
    <livewire:branch-list />
@endsection
