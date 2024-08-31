<!-- Archivo: resources/views/tweet.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Publicar un Tweet</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('twitter.tweet') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="tweet">Tweet</label>
            <textarea class="form-control" id="tweet" name="tweet" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publicar</button>
    </form>
</div>
@endsection
