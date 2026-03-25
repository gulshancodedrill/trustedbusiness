<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Business - Biztrus.to</title>
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
</head>
<body class="bg-background text-foreground">
    <x-site-header />
    <main class="container mx-auto max-w-3xl px-4 pb-16 pt-28">
        <div class="rounded-2xl border border-border bg-card p-6 shadow-sm">
            <h1 class="text-2xl font-black">Edit Business</h1>
            <p class="mt-2 text-sm text-muted-foreground">
                To keep this delivery focused, edit flow is not fully wired yet. You can create a new entry from the business form.
            </p>
            <a href="{{ route('businesses.create') }}" class="mt-4 inline-flex rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground">
                Open Business Form
            </a>
        </div>
    </main>
</body>
</html>
