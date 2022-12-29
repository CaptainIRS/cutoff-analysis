@extends('layouts.app')

@section('meta')
    <meta name="description" content="Details of {{ $institute['id'] }} participating in JoSAA">
@endsection

@section('content')
    <h1 class="text-3xl font-bold mx-4 my-10 print:hidden">{!! $institute['alias'] !!}</h1>

    <livewire:institute-details :institute="$institute" />
@endsection
