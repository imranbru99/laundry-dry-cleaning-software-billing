<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laundry & Dry-Cleaning Software')</title>
    <meta name="description" content="@yield('description', 'Modular multi-tenant laundry platform: tags, pickups, billing, AI, and more.')">
    <meta property="og:title" content="@yield('title', 'Laundry & Dry-Cleaning Software')">
    <meta property="og:description" content="@yield('description', 'Tags, pickups, billing — one platform for every laundry business type.')">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => 'Laundry and Dry-Cleaning Software: Tags, Pickups, Billing',
            'applicationCategory' => 'BusinessApplication',
            'offers' => ['@type' => 'Offer', 'price' => '29.00', 'priceCurrency' => 'USD'],
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>
    <style>
        :root {
            --ink: #0f2a2e;
            --foam: #e8f4f2;
            --aqua: #0d9488;
            --sun: #f4b860;
            --deep: #073b3a;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", "Noto Sans Bengali", "Noto Naskh Arabic", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 600px at 10% -10%, #b8e6df 0%, transparent 55%),
                radial-gradient(900px 500px at 100% 0%, #ffe2a8 0%, transparent 45%),
                linear-gradient(180deg, #f7fbfa 0%, #eef6f4 40%, #f8faf9 100%);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        .wrap { width: min(1120px, calc(100% - 2rem)); margin-inline: auto; }
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.1rem 0; gap: 1rem;
        }
        .brand { font-weight: 800; letter-spacing: -0.02em; font-size: 1.15rem; }
        .brand span { color: var(--aqua); }
        nav { display: flex; flex-wrap: wrap; gap: .85rem; font-size: .92rem; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: .7rem; padding: .7rem 1.1rem; font-weight: 700;
            background: var(--deep); color: #fff; border: 0; cursor: pointer;
        }
        .btn.secondary { background: transparent; color: var(--deep); border: 1px solid #9bc5bf; }
        .hero {
            display: grid; gap: 1.5rem; padding: 2.5rem 0 3rem;
            grid-template-columns: 1.1fr .9fr; align-items: center;
        }
        .hero h1 { font-size: clamp(2rem, 4vw, 3.2rem); line-height: 1.1; margin: 0 0 .8rem; letter-spacing: -0.03em; }
        .hero p { font-size: 1.05rem; line-height: 1.6; opacity: .85; }
        .visual {
            min-height: 320px; border-radius: 1.4rem;
            background:
                linear-gradient(145deg, rgba(13,148,136,.92), rgba(7,59,58,.95)),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 40 40"><circle cx="4" cy="4" r="1.4" fill="%23ffffff22"/></svg>');
            color: #fff; padding: 1.5rem; display: grid; align-content: end;
            box-shadow: 0 20px 50px rgba(7,59,58,.25);
        }
        .section { padding: 2.5rem 0; }
        .section h2 { margin: 0 0 .5rem; font-size: 1.7rem; letter-spacing: -0.02em; }
        .muted { opacity: .75; margin-top: 0; }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .card-ish {
            padding: 1.1rem; border-radius: 1rem;
            background: rgba(255,255,255,.72); border: 1px solid #d5e8e4;
            backdrop-filter: blur(6px);
        }
        table { width: 100%; border-collapse: collapse; background: rgba(255,255,255,.8); border-radius: 1rem; overflow: hidden; }
        th, td { padding: .85rem 1rem; text-align: left; border-bottom: 1px solid #dfeceb; }
        footer { padding: 2rem 0 3rem; opacity: .75; font-size: .9rem; }
        @media (max-width: 860px) {
            .hero { grid-template-columns: 1fr; }
            nav { display: none; }
        }
    </style>
    @stack('head')
</head>
<body>
    <div class="wrap">
        <header>
            <a class="brand" href="{{ url('/') }}">Sparkle<span>Suite</span></a>
            <nav aria-label="Primary">
                <a href="{{ url('/features') }}">Features</a>
                <a href="{{ url('/pricing') }}">Pricing</a>
                <a href="{{ url('/services') }}">Price list</a>
                <a href="{{ url('/track') }}">Track</a>
                <a href="{{ url('/book-pickup') }}">Book pickup</a>
                <a href="{{ url('/demo') }}">Live demo</a>
            </nav>
            <a class="btn" href="{{ url('/demo') }}">Try demo</a>
        </header>
        @yield('content')
        <footer>
            Laundry and Dry-Cleaning Software: Tags, Pickups, Billing — modular, multi-tenant, multilingual.
        </footer>
    </div>
</body>
</html>
