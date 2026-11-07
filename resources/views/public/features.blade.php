@extends('layouts.public')
@section('title', 'Features & Modules')
@section('content')
<section class="section">
    <h2>Every capability is a module</h2>
    <p class="muted">Turn modules ON/OFF per business. Data is preserved when off; navigation, API and public pages are blocked.</p>
    <div class="grid" style="margin-top:1.2rem">
        @foreach ($modules as $key => $meta)
            <div class="card-ish">
                <strong>{{ $meta['name'] }}</strong>
                <p class="muted">Key: {{ $key }} @if($meta['core'] ?? false) · Always on @endif</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
