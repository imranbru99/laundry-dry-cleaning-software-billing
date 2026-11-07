<html><body>
<h1>Invoice {{ $invoice->number ?? '' }}</h1>
<p>{{ $customerName ?? '' }}</p>
<p>Total: {{ $total ?? '' }}</p>
</body></html>
