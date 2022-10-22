@extends('layouts.app')

@push('title', 'Institutes |')

@section('meta')
    <meta name="description" content="List of institutes participating in JoSAA">
    <meta property="og:title" content="Institutes - JoSAA Analysis">
    <meta property="og:description" content="List of institutes participating in JoSAA">
    <meta property="og:url" content="{{ route('institute-list') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Institutes - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('institute-list') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description" content="List of institutes participating in JoSAA">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">Institutes</h1>

    <p class="text-lg m-4 print:hidden">
        List of institutes participating in JoSAA.
    </p>
    <livewire:institute-list />
@endsection
