@extends('layouts.public')
@section('title', 'Track Order')
@section('content')
<section class="section">
    <h2>Track order / garment tag</h2>
    <form method="get" action="{{ url('/track') }}" style="display:flex;gap:.5rem;max-width:480px">
        <input name="q" value="{{ $code }}" placeholder="Order or tag number" style="flex:1;padding:.7rem;border-radius:.6rem;border:1px solid #9bc5bf">
        <button class="btn">Track</button>
    </form>
    @if ($order)
        <div class="card-ish" style="margin-top:1rem">
            <strong>Order {{ $order->number }}</strong>
            <p>Status: {{ $order->status }} · Total: {{ number_format($order->grand_total/100, 2) }} {{ $order->currency }}</p>
        </div>
    @endif
    @if ($tag)
        <div class="card-ish" style="margin-top:1rem">
            <strong>Tag {{ $tag->tag_code }}</strong>
            <p>Status: {{ $tag->status }} · Rack: {{ $tag->rack_location ?? '—' }}</p>
        </div>
    @endif
</section>
@endsection
