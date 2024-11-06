<!-- routes/folio/about.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>About Us</h1>
    <p>This is the about page, also using Laravel Folio.</p>
    <a href="{{ route('home') }}">Back to Home</a>
@endsection
