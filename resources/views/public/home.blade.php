@extends('layouts.public')
@section('title', 'Laundry & Dry-Cleaning Software — Tags, Pickups, Billing')
@section('content')
<section class="hero">
    <div>
        <h1>Tags. Pickups. Billing.<br>One modular laundry OS.</h1>
        <p>Switch modules on or off per business — from a single counter to a 50-branch chain with central plant, B2B linen, AI tagging, and multilingual invoices.</p>
        <div style="display:flex;gap:.7rem;flex-wrap:wrap;margin-top:1.2rem">
            <a class="btn" href="{{ url('/demo') }}">One-click demo login</a>
            <a class="btn secondary" href="{{ url('/features') }}">Explore modules</a>
        </div>
    </div>
    <div class="visual">
        <strong style="font-size:1.3rem">Live order tracking</strong>
        <form action="{{ url('/track') }}" method="get" style="margin-top:1rem;display:flex;gap:.5rem">
            <input name="q" placeholder="Order or tag number" style="flex:1;border:0;border-radius:.6rem;padding:.7rem">
            <button class="btn" style="background:var(--sun);color:#111">Track</button>
        </form>
    </div>
</section>
<section class="section">
    <h2>Built for every laundry business</h2>
    <p class="muted">Dry-clean, wash-and-fold, hotel linen, uniforms, self-service, franchise chains.</p>
    <div class="grid" style="margin-top:1rem">
        @foreach (['Dry-cleaner','Wash & fold','Hotel / linen','Uniform rental','Launderette','Franchise chain'] as $type)
            <div class="card-ish"><strong>{{ $type }}</strong><p class="muted">Preset modules, editable later.</p></div>
        @endforeach
    </div>
</section>
<section class="section">
    <h2>Plans that unlock modules</h2>
    <div class="grid">
        @foreach ($plans as $plan)
            <div class="card-ish">
                <strong>{{ $plan->name }}</strong>
                <p>${{ number_format($plan->price_monthly/100, 2) }}/mo</p>
                <p class="muted">{{ $plan->description }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
