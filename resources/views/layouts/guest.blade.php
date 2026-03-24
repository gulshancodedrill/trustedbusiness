<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
</head>
<body class="bg-background text-foreground antialiased">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-[430px] bg-gradient-to-br from-primary via-primary/90 to-secondary/85"></div>
        <div class="absolute inset-x-0 top-0 h-[430px] bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.22),_transparent_58%)]"></div>

        <nav class="relative z-20 border-b border-primary-foreground/10 bg-primary/70 backdrop-blur-md">
            <div class="container mx-auto flex items-center justify-between px-4 py-4">
                <a href="{{ url('/') }}" class="text-xl font-extrabold text-primary-foreground">Biztrus.to</a>
                <div class="flex items-center gap-5 text-sm font-medium">
                    <a href="{{ url('/') }}" class="text-primary-foreground/85 transition-colors hover:text-primary-foreground">Home</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-primary-foreground/85 transition-colors hover:text-primary-foreground">Register</a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="rounded-lg bg-secondary px-4 py-2 text-secondary-foreground transition-all hover:brightness-110">
                            Sign In
                        </a>
                    @endif
                </div>
            </div>
        </nav>

        <main class="relative z-10 px-4 py-12">
            <div class="mx-auto w-full max-w-xl">
                <div class="mb-6 text-center">
                    <h1 class="text-3xl font-extrabold tracking-tight text-primary-foreground sm:text-4xl">Welcome Back</h1>
                    <p class="mt-2 text-sm text-primary-foreground/80">Secure access to your Biztrus business account.</p>
                </div>

                <div class="rounded-2xl border border-border/60 bg-card/95 p-6 shadow-xl backdrop-blur sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
