@extends('layouts.app')

@push('title', 'Institutes |')

@section('meta')
    <meta name="description" content="Details of {{ $institute['id'] }} participating in JoSAA">
    <meta property="og:title" content="{{ $institute['id'] }} | JoSAA Analysis">
    <meta property="og:description" content="Details of {{ $institute['id'] }} participating in JoSAA">
    <meta property="og:url" content="{{ route('institute-details', ['institute' => $institute['id']]) }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="{{ $institute['id'] }} | JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('institute-details', ['institute' => $institute['id']]) }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description" content="Details of {{ $institute['id'] }} participating in JoSAA">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">{!! $institute['alias'] !!}</h1>

    <livewire:institute-details :institute="$institute" />
@endsection
