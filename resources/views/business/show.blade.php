<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $business->business_name }} - Biztrus.to</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
</head>
<body class="bg-background text-foreground">
    <x-site-header />
    <main class="container mx-auto max-w-4xl px-4 pb-16 pt-28">
        @if (session('status'))
            <div class="mb-5 rounded-xl border border-secondary/40 bg-secondary/10 px-4 py-3 text-sm font-medium text-secondary">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-3xl border border-border/70 bg-card p-6 shadow-lg">
            <h1 class="text-3xl font-black">{{ $business->business_name }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">{{ $business->city }}, {{ $business->state }}, {{ $business->country }}</p>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Owner</p>
                    <p class="font-semibold">{{ $business->owner_first_name }} {{ $business->owner_last_name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Contact</p>
                    <p class="font-semibold">{{ $business->business_contact_number }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Business Email</p>
                    <p class="font-semibold">{{ $business->business_email }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Website</p>
                    <p class="font-semibold">{{ $business->website ?: 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Description</p>
                    <p class="text-sm text-muted-foreground">{{ $business->business_description ?: 'N/A' }}</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
