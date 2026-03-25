<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Business - Biztrus.to</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-background text-foreground antialiased">
    <x-site-header />

    <main class="min-h-screen">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div id="addBusinessOverlay" class="absolute inset-0 bg-foreground/50 backdrop-blur-sm"></div>

            <div class="relative w-full max-w-2xl rounded-2xl bg-card p-6 shadow-2xl md:p-8">
                <a href="/" class="absolute right-4 top-4 rounded-full p-1 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>

                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-foreground">Get Started with Biztrus.to</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Choose how you'd like to contribute</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <a
                        href="{{ route('businesses.create') }}"
                        class="group flex flex-col items-center gap-4 rounded-xl border-2 border-border bg-card p-6 text-center transition-all duration-200 hover:border-secondary hover:shadow-lg"
                    >
                        <div class="rounded-xl bg-secondary/10 p-4 transition-colors group-hover:bg-secondary/20">
                            <i data-lucide="building" class="h-8 w-8 text-secondary"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-foreground">Your business?</h3>
                            <p class="mt-1 text-sm text-muted-foreground">List your business for free and get discovered online</p>
                        </div>
                        <span class="rounded-lg bg-secondary px-5 py-2.5 text-sm font-semibold text-secondary-foreground transition-all group-hover:scale-105 group-hover:shadow-md">
                            List Your Business
                        </span>
                    </a>

                    <a
                        href="{{ route('businesses.suggest') }}"
                        class="group flex flex-col items-center gap-4 rounded-xl border-2 border-border bg-card p-6 text-center transition-all duration-200 hover:border-accent hover:shadow-lg"
                    >
                        <div class="rounded-xl bg-accent/10 p-4 transition-colors group-hover:bg-accent/20">
                            <i data-lucide="lightbulb" class="h-8 w-8 text-accent"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-foreground">Someone else's business?</h3>
                            <p class="mt-1 text-sm text-muted-foreground">Help others discover great businesses around you</p>
                        </div>
                        <span class="rounded-lg bg-accent px-5 py-2.5 text-sm font-semibold text-accent-foreground transition-all group-hover:scale-105 group-hover:shadow-md">
                            Suggest Business
                        </span>
                    </a>
                </div>

                <div class="relative my-5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-border"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-card px-3 text-xs font-medium text-muted-foreground">OR</span>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-2 text-muted-foreground">
                    <i data-lucide="clock" class="h-4 w-4"></i>
                    <span class="text-xs">Takes less than 2 minutes</span>
                </div>
            </div>
        </div>
    </main>

    <script>
        if (window.lucide) window.lucide.createIcons();

        const overlay = document.getElementById('addBusinessOverlay');
        if (overlay) {
            overlay.addEventListener('click', () => {
                window.location.href = '/';
            });
        }
    </script>
</body>
</html>

