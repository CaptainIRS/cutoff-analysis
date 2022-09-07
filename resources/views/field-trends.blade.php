@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs for courses in a particular field of engineering over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, field trends, indian institutes, course popularity, iits, nits, iiits">

    <meta property="og:title" content="Field Trends - JoSAA Analysis">
    <meta property="og:description"
        content="Compare the cut-offs for courses in a particular field of engineering over 10 years in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('field-trends') }}">
    <meta property="og:type" content="website" />
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Field Trends - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('field-trends') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="Compare the cut-offs for courses in a particular field of engineering over 10 years in the JoSAA seat allocation process.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">

    <title>Field Trends | {{ config('app.name') }}</title>
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4">Field Trends</h1>

    <h2 class="text-lg m-4">
        Compare the cut-offs for courses in a particular field of engineering over 10 years in the JoSAA seat allocation
        process.
        <br>
        This helps understand the popularity and perception of a field among engineering aspirants, and thus helps
        understand the demand for a particular field during the counselling process.
    </h2>
    <livewire:field-trends />
@endsection
