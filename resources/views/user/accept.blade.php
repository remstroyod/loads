@extends('layouts.app')

@section('content')

    <h1>{{ $user->name }} {{ $user->surname }}</h1>

    @if (session('message'))
        <hr>
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <hr>
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

@endsection
