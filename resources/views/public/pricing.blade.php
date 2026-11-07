@extends('layouts.public')
@section('title', 'Pricing & Plans')
@section('content')
<section class="section">
    <h2>Choose a plan, then toggle modules</h2>
    <table>
        <thead><tr><th>Plan</th><th>Monthly</th><th>Yearly</th><th>Modules</th></tr></thead>
        <tbody>
        @foreach ($plans as $plan)
            <tr>
                <td>{{ $plan->name }}</td>
                <td>${{ number_format($plan->price_monthly/100, 2) }}</td>
                <td>${{ number_format($plan->price_yearly/100, 2) }}</td>
                <td>{{ count($plan->modules ?? []) }} available</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endsection
