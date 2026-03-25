<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Businesses - Biztrus.to</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
</head>
<body class="bg-background text-foreground">
    <x-site-header />
    <main class="container mx-auto px-4 pb-16 pt-28">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-black">Businesses</h1>
            <a href="{{ route('businesses.add') }}" class="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground">Add Business</a>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($businesses as $business)
                <a href="{{ route('business.detail', $business) }}" class="rounded-2xl border border-border bg-card p-5 shadow-sm transition hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">{{ $business->city }}, {{ $business->state }}</p>
                    <h2 class="mt-2 text-xl font-bold">{{ $business->business_name }}</h2>
                    <p class="mt-1 text-sm text-muted-foreground">{{ $business->business_email }}</p>
                </a>
            @empty
                <p class="text-muted-foreground">No businesses found yet.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $businesses->links() }}</div>
    </main>
</body>
</html>
