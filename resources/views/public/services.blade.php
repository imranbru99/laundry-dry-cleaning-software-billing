@extends('layouts.public')
@section('title', 'Services & Price List')
@section('content')
<section class="section">
    <h2>Demo catalog</h2>
    <div class="grid">
        @foreach ($items as $item)
            <div class="card-ish"><strong>{{ $item->name }}</strong><p class="muted">{{ $item->unit }}</p></div>
        @endforeach
    </div>
</section>
@endsection
