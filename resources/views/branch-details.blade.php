@extends('layouts.app')

@push('title', 'Branches |')

@section('meta')
    <meta name="description" content="Details of {{ $branch['id'] }} branch in JoSAA">
    <meta property="og:title" content="{{ $branch['id'] }} | JoSAA Analysis">
    <meta property="og:description" content="Details of {{ $branch['id'] }} branch in JoSAA">
    <meta property="og:url" content="{{ route('branch-details', ['branch' => $branch['id']]) }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="{{ $branch['id'] }} | JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('branch-details', ['branch' => $branch['id']]) }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description" content="Details of {{ $branch['id'] }} branch in JoSAA">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">{{ $branch['id'] }}</h1>

    <livewire:branch-details :branch="$branch" />
@endsection
