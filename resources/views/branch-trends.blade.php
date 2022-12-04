@extends('layouts.app')

@push('title', 'Analyse Branch-wise Cut-off Trends |')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs for courses in a particular branch of engineering over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, branch trends, indian institutes, course popularity, iits, nits, iiits">

    <meta property="og:title" content="Analyse Branch-wise Cut-off Trends - JoSAA Analysis">
    <meta property="og:description"
        content="Compare the cut-offs for courses in a particular branch of engineering over 10 years in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('branch-trends') }}">
    <meta property="og:type" content="website" />
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Analyse Branch-wise Cut-off Trends - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('branch-trends') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="Compare the cut-offs for courses in a particular branch of engineering over 10 years in the JoSAA seat allocation process.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">Analyse Branch-wise Cut-off Trends</h1>

    <p class="text-lg m-4 print:hidden">
        Compare the cut-offs for courses in a particular branch of engineering over 10 years in the JoSAA seat allocation
        process.
        <br>
        This helps understand the popularity and perception of a branch among engineering aspirants, and thus helps
        understand the demand for a particular branch during the counselling process.
    </p>
    <livewire:branch-trends />
@endsection
