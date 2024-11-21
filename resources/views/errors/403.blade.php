@extends('layouts.error-layout')

@section('title', '403 - Forbidden')

@section('content')
    <p class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-wider text-gray-300">403</p>
    <p class="text-xl md:text-2xl lg:text-3xl font-bold tracking-wider text-gray-500 mt-4">Access Denied</p>
    <p class="text-gray-500 mt-2 pb-2 border-b-2 text-center">
        Sorry, you do not have permission to access this page.
    </p>
@endsection
