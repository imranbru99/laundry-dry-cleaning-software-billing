@extends('layouts.public')
@section('title', 'Book a Pickup')
@section('content')
<section class="section">
    <h2>Book a pickup</h2>
    <p class="muted">Public booking for {{ $tenant->name }}. Guest checkout + OTP supported in production flows.</p>
    <form class="card-ish" style="max-width:520px;display:grid;gap:.7rem" method="post" action="{{ url('/book-pickup') }}">
        @csrf
        <input name="name" placeholder="Your name" required style="padding:.7rem;border-radius:.6rem;border:1px solid #9bc5bf">
        <input name="phone" placeholder="Phone" required style="padding:.7rem;border-radius:.6rem;border:1px solid #9bc5bf">
        <input name="address" placeholder="Pickup address" required style="padding:.7rem;border-radius:.6rem;border:1px solid #9bc5bf">
        <input type="datetime-local" name="scheduled_at" required style="padding:.7rem;border-radius:.6rem;border:1px solid #9bc5bf">
        <button class="btn" type="submit">Request pickup</button>
    </form>
</section>
@endsection
