@extends('layouts.public')
@section('title', 'Live Demo')
@section('content')
<section class="section">
    <h2>Live demo — one-click role login</h2>
    <p class="muted">Password for all demo users: <strong>password</strong>. Demo data resets nightly in production demos.</p>
    <div class="grid" style="margin-top:1rem">
        @foreach ($users as $user)
            <a class="card-ish" href="{{ url('/demo/login/'.$user->email) }}">
                <strong>{{ $user->name }}</strong>
                <p class="muted">{{ $user->email }}</p>
            </a>
        @endforeach
    </div>
</section>
@endsection
