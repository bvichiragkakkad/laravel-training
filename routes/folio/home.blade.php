<!-- routes/folio/home.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Welcome to Laravel Folio!</h1>
    <p>This is the home page created with Laravel Folio.</p>
    <a href="{{ route('about') }}">Go to About Page</a>
@endsection
