<nav class="fixed top-0 z-50 w-full border-b border-primary-foreground/10 bg-primary/95 backdrop-blur-md">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <a href="/" class="text-xl font-extrabold text-primary-foreground">Biztrus.to</a>

        <div class="hidden items-center gap-6 md:flex">
            <a href="#" class="text-sm font-medium text-primary-foreground/80 transition-colors hover:text-primary-foreground">Categories</a>
            <a href="#" class="text-sm font-medium text-primary-foreground/80 transition-colors hover:text-primary-foreground">Write a Review</a>
            <a href="#" class="text-sm font-medium text-primary-foreground/80 transition-colors hover:text-primary-foreground">For Business</a>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg bg-secondary px-5 py-2 text-sm font-semibold text-secondary-foreground transition-all hover:brightness-110">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg bg-secondary px-5 py-2 text-sm font-semibold text-secondary-foreground transition-all hover:brightness-110">
                        Sign In
                    </a>
                @endauth
            @endif
        </div>

        <button id="mobileMenuBtn" class="md:hidden text-primary-foreground" type="button" aria-label="Toggle menu">
            <i data-lucide="menu" class="h-6 w-6" id="iconMenu"></i>
            <i data-lucide="x" class="h-6 w-6 hidden" id="iconClose"></i>
        </button>
    </div>

    <div id="mobileMenu" class="hidden border-t border-primary-foreground/10 bg-primary px-4 py-4 md:hidden">
        <div class="flex flex-col gap-3">
            <a href="#" class="text-sm font-medium text-primary-foreground/80">Categories</a>
            <a href="#" class="text-sm font-medium text-primary-foreground/80">Write a Review</a>
            <a href="#" class="text-sm font-medium text-primary-foreground/80">For Business</a>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="mt-2 rounded-lg bg-secondary px-5 py-2 text-center text-sm font-semibold text-secondary-foreground">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="mt-2 rounded-lg bg-secondary px-5 py-2 text-center text-sm font-semibold text-secondary-foreground">
                        Login
                    </a>
                @endauth
            @endif
        </div>
    </div>
</nav>

