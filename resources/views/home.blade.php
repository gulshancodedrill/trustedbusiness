<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Biztrus.to - Find Trusted Businesses Near You</title>
        <meta name="description" content="Real reviews. Verified businesses. No fake ratings. Find and review trusted local businesses on Biztrus.to.">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('biztrus.css') }}">

        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>

        <style>
            details > summary {
                list-style: none;
            }
            details > summary::-webkit-details-marker {
                display: none;
            }
            details[open] .faq-chevron {
                transform: rotate(180deg);
            }
            details[open] {
                /* Approximation of the React `shadow-md` on open */
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            }
            .faq-content {
                display: grid;
                grid-template-rows: 0fr;
                opacity: 0;
                transition: grid-template-rows 0.25s ease, opacity 0.25s ease;
            }
            .faq-content > div {
                overflow: hidden;
            }
            details[open] .faq-content {
                grid-template-rows: 1fr;
                opacity: 1;
            }

            /* Lightweight motion to mimic the React framer-motion feel */
            [data-reveal] {
                opacity: 0;
                transform: translateY(24px);
                transition: opacity 0.7s ease, transform 0.7s ease;
                will-change: opacity, transform;
            }
            [data-reveal].is-visible {
                opacity: 1;
                transform: translateY(0);
            }
        </style>
    </head>

    @php
        $trustBadges = [
            ['icon' => 'star', 'label' => '10,000+ Reviews'],
            ['icon' => 'check-circle', 'label' => 'Verified Businesses'],
            ['icon' => 'users', 'label' => 'Trusted by Thousands'],
        ];

        $categories = [
            ['icon' => 'utensils', 'label' => 'Restaurants', 'color' => 'text-accent', 'bg' => 'bg-accent/10'],
            ['icon' => 'shopping-bag', 'label' => 'Shopping', 'color' => 'text-primary', 'bg' => 'bg-primary/10'],
            ['icon' => 'wrench', 'label' => 'Home Services', 'color' => 'text-secondary', 'bg' => 'bg-secondary/10'],
            ['icon' => 'heart', 'label' => 'Health & Wellness', 'color' => 'text-destructive', 'bg' => 'bg-destructive/10'],
            ['icon' => 'graduation-cap', 'label' => 'Education', 'color' => 'text-primary', 'bg' => 'bg-primary/10'],
            ['icon' => 'car', 'label' => 'Automotive', 'color' => 'text-accent', 'bg' => 'bg-accent/10'],
            ['icon' => 'home', 'label' => 'Real Estate', 'color' => 'text-secondary', 'bg' => 'bg-secondary/10'],
            ['icon' => 'briefcase', 'label' => 'Professional', 'color' => 'text-primary', 'bg' => 'bg-primary/10'],
        ];

        $listings = [
            ['slug' => 'sunrise-cafe', 'name' => 'Sunrise Café', 'category' => 'Restaurant', 'rating' => 4.8, 'reviews' => 234, 'location' => 'New York, NY', 'badge' => 'Top Rated', 'badgeType' => 'accent', 'verified' => true],
            ['slug' => 'techfix-pro', 'name' => 'TechFix Pro', 'category' => 'Electronics Repair', 'rating' => 4.9, 'reviews' => 189, 'location' => 'San Francisco, CA', 'badge' => 'Featured', 'badgeType' => 'primary', 'verified' => true],
            ['slug' => 'green-thumb-gardens', 'name' => 'Green Thumb Gardens', 'category' => 'Landscaping', 'rating' => 4.7, 'reviews' => 156, 'location' => 'Austin, TX', 'badge' => 'Top Rated', 'badgeType' => 'accent', 'verified' => false],
            ['slug' => 'elite-dental-care', 'name' => 'Elite Dental Care', 'category' => 'Healthcare', 'rating' => 4.9, 'reviews' => 312, 'location' => 'Chicago, IL', 'badge' => 'Featured', 'badgeType' => 'primary', 'verified' => true],
            ['slug' => 'swift-movers', 'name' => 'Swift Movers', 'category' => 'Moving Services', 'rating' => 4.6, 'reviews' => 98, 'location' => 'Miami, FL', 'badge' => 'Top Rated', 'badgeType' => 'accent', 'verified' => false],
            ['slug' => 'bookworm-library', 'name' => 'BookWorm Library', 'category' => 'Education', 'rating' => 4.8, 'reviews' => 145, 'location' => 'Seattle, WA', 'badge' => 'Featured', 'badgeType' => 'primary', 'verified' => true],
        ];

        $logoColors = [
            'from-accent/20 to-accent/5 text-accent',
            'from-primary/20 to-primary/5 text-primary',
            'from-secondary/20 to-secondary/5 text-secondary',
            'from-primary/20 to-primary/5 text-primary',
            'from-accent/20 to-accent/5 text-accent',
            'from-secondary/20 to-secondary/5 text-secondary',
        ];

        $reviews = [
            ['name' => 'Sarah M.', 'business' => 'Sunrise Café', 'rating' => 5, 'text' => 'Amazing coffee and the friendliest staff! Became a regular after my first visit.', 'avatar' => 'S'],
            ['name' => 'James K.', 'business' => 'TechFix Pro', 'rating' => 5, 'text' => 'Fixed my laptop in under an hour. Fair price and great communication throughout.', 'avatar' => 'J'],
            ['name' => 'Maria L.', 'business' => 'Elite Dental Care', 'rating' => 4, 'text' => 'Very professional and modern facility. Dr. Chen made me feel comfortable right away.', 'avatar' => 'M'],
            ['name' => 'David R.', 'business' => 'Swift Movers', 'rating' => 5, 'text' => 'Moved our entire 3-bedroom house without a single scratch. Highly recommend!', 'avatar' => 'D'],
        ];

        $avatarColors = [
            'bg-primary/15 text-primary',
            'bg-secondary/15 text-secondary',
            'bg-accent/15 text-accent',
            'bg-primary/15 text-primary',
        ];

        $cities = [
            'New York',
            'Los Angeles',
            'Chicago',
            'Houston',
            'Phoenix',
            'San Francisco',
            'Seattle',
            'Miami',
            'Austin',
            'Denver',
            'Boston',
            'Atlanta',
        ];

        $faqs = [
            [
                'q' => 'Is it free to list my business?',
                'a' => 'Yes! Listing your business on Biztrus.to is completely free. You can create a profile, respond to reviews, and connect with customers at no cost.',
            ],
            [
                'q' => 'How are reviews verified?',
                'a' => 'We use a multi-step verification process to ensure reviews come from real customers. This includes email verification, purchase confirmation, and AI-powered fraud detection.',
            ],
            [
                'q' => 'Can I respond to reviews?',
                'a' => 'Absolutely. Business owners can respond to all reviews — both positive and negative. Engaging with your customers publicly builds trust and shows you care.',
            ],
            [
                'q' => 'How do I claim my business profile?',
                'a' => 'Search for your business on Biztrus.to, click \'Claim This Business,\' and follow the verification steps. You\'ll have full control of your profile within 24 hours.',
            ],
            [
                'q' => 'What makes Biztrus.to different?',
                'a' => 'Unlike other platforms, we focus exclusively on verified reviews with zero tolerance for fake ratings. Our transparent approach helps both consumers and businesses build genuine trust.',
            ],
        ];

        $userSteps = [
            ['icon' => 'search', 'title' => 'Search', 'desc' => 'Find businesses in your area by category or name.', 'num' => '1', 'accentColor' => 'bg-primary/10 text-primary'],
            ['icon' => 'star', 'title' => 'Read Reviews', 'desc' => 'Check verified reviews from real customers.', 'num' => '2', 'accentColor' => 'bg-primary/10 text-primary'],
            ['icon' => 'check-circle', 'title' => 'Choose & Connect', 'desc' => 'Pick the best match and reach out directly.', 'num' => '3', 'accentColor' => 'bg-primary/10 text-primary'],
        ];

        $bizSteps = [
            ['icon' => 'building', 'title' => 'Claim Your Profile', 'desc' => 'List your business for free in minutes.', 'num' => '1', 'accentColor' => 'bg-secondary/10 text-secondary'],
            ['icon' => 'message-square', 'title' => 'Get Reviews', 'desc' => 'Build trust with authentic customer feedback.', 'num' => '2', 'accentColor' => 'bg-secondary/10 text-secondary'],
            ['icon' => 'trending-up', 'title' => 'Grow', 'desc' => 'Attract new customers and grow your reputation.', 'num' => '3', 'accentColor' => 'bg-secondary/10 text-secondary'],
        ];

        $starPath = 'M12 2l3.09 6.26L22 9.27l-5 5.11L18.18 22 12 18.77 5.82 22 7 14.38 2 9.27l6.91-1.01L12 2z';
    @endphp

    <body class="bg-background text-foreground">
        <div class="min-h-screen">
            <x-site-header />

            <main>
                <!-- Hero -->
                <section data-reveal class="relative overflow-hidden bg-gradient-to-br from-primary via-primary to-primary/80 pt-32 pb-24 md:pt-44 md:pb-32">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="absolute -top-40 -right-40 h-[500px] w-[500px] rounded-full bg-secondary/15 blur-3xl"></div>
                        <div class="absolute -bottom-40 -left-40 h-[500px] w-[500px] rounded-full bg-accent/10 blur-3xl"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-primary-foreground/5 blur-3xl"></div>
                    </div>

                    <div class="container relative mx-auto px-4">
                        <div class="mx-auto max-w-3xl text-center">
                            <!-- Trust badges -->
                            <div class="mb-6 flex flex-wrap items-center justify-center gap-3">
                                @foreach ($trustBadges as $badge)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary-foreground/15 px-4 py-1.5 text-xs font-medium text-primary-foreground backdrop-blur-sm">
                                        <i data-lucide="{{ $badge['icon'] }}" class="h-3.5 w-3.5"></i>
                                        {{ $badge['label'] }}
                                    </span>
                                @endforeach
                            </div>

                            <h1 class="text-4xl font-extrabold tracking-tight text-primary-foreground md:text-6xl lg:text-7xl">
                                Find Trusted Businesses <span class="text-accent">Near You</span>
                            </h1>
                            <p class="mt-5 text-lg text-primary-foreground/80 md:text-xl">
                                Real reviews. Verified businesses. No fake ratings.
                            </p>

                            <!-- Search bar -->
                            <div class="mt-10 flex flex-col gap-3 rounded-2xl bg-card p-4 shadow-2xl md:flex-row md:items-center">
                                <div class="flex flex-1 items-center gap-3 rounded-xl bg-muted px-4 py-4">
                                    <i data-lucide="search" class="h-5 w-5 shrink-0 text-muted-foreground"></i>
                                    <input
                                        type="text"
                                        placeholder="Search business or service"
                                        class="w-full bg-transparent text-base text-foreground placeholder:text-muted-foreground focus:outline-none"
                                    >
                                </div>

                                <div class="flex flex-1 items-center gap-3 rounded-xl bg-muted px-4 py-4">
                                    <i data-lucide="map-pin" class="h-5 w-5 shrink-0 text-muted-foreground"></i>
                                    <input
                                        type="text"
                                        placeholder="Location"
                                        class="w-full bg-transparent text-base text-foreground placeholder:text-muted-foreground focus:outline-none"
                                    >
                                </div>

                                <button type="button" class="rounded-xl bg-primary px-8 py-4 font-semibold text-primary-foreground transition-all hover:opacity-90 hover:shadow-lg">
                                    Search
                                </button>
                            </div>

                            <div class="mt-6">
                                <button type="button" class="rounded-full bg-secondary px-8 py-3.5 font-semibold text-secondary-foreground shadow-lg transition-all hover:shadow-xl hover:brightness-110">
                                    List Your Business — It's Free
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Trust Stats -->
                <section data-reveal class="relative -mt-10 z-10 pb-10">
                    <div class="container mx-auto px-4">
                        <div class="grid grid-cols-2 gap-4 rounded-2xl bg-card p-6 shadow-xl md:grid-cols-4 md:gap-8 md:p-8">
                            @php
                                $stats = [
                                    ['icon' => 'star', 'value' => '10,000+', 'label' => 'Reviews', 'color' => 'text-accent'],
                                    ['icon' => 'building', 'value' => '5,000+', 'label' => 'Businesses', 'color' => 'text-primary'],
                                    ['icon' => 'map-pin', 'value' => '100+', 'label' => 'Cities', 'color' => 'text-secondary'],
                                    ['icon' => 'shield-check', 'value' => '100%', 'label' => 'Verified Listings', 'color' => 'text-primary'],
                                ];
                            @endphp

                            @foreach ($stats as $stat)
                                <div class="flex flex-col items-center gap-2 text-center">
                                    <i data-lucide="{{ $stat['icon'] }}" class="h-7 w-7 {{ $stat['color'] }}"></i>
                                    <span class="text-2xl font-extrabold text-foreground md:text-3xl">{{ $stat['value'] }}</span>
                                    <span class="text-sm text-muted-foreground">{{ $stat['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Featured Categories -->
                <section data-reveal class="bg-section-blue py-20">
                    <div class="container mx-auto px-4">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-foreground">Browse Categories</h2>
                            <p class="mt-2 text-muted-foreground">Explore businesses by category</p>
                        </div>

                        <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-8">
                            @foreach ($categories as $cat)
                                <div class="group flex flex-col items-center gap-3 rounded-2xl bg-card p-6 shadow-sm transition-shadow duration-300 hover:shadow-lg">
                                    <div class="rounded-xl {{ $cat['bg'] }} p-3.5 transition-transform duration-300 group-hover:scale-110">
                                        <i data-lucide="{{ $cat['icon'] }}" class="h-6 w-6 {{ $cat['color'] }}"></i>
                                    </div>
                                    <span class="text-sm font-medium text-foreground">{{ $cat['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Featured Listings -->
                <section data-reveal class="bg-section-light py-20">
                    <div class="container mx-auto px-4">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-foreground">Featured Businesses</h2>
                            <p class="mt-2 text-muted-foreground">Top-rated businesses trusted by your community</p>
                        </div>

                        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($listings as $listing)
                                @php
                                    $roundedRating = (int) round($listing['rating']);
                                @endphp

                                <div class="group overflow-hidden rounded-2xl border border-transparent bg-card shadow-sm transition-all duration-300 hover:border-primary/20 hover:shadow-xl">
                                    <div class="relative flex h-36 items-center justify-center bg-gradient-to-br from-primary/5 to-secondary/5">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br {{ $logoColors[$loop->index] }} shadow-sm">
                                            <span class="text-2xl font-black">{{ substr($listing['name'], 0, 1) }}</span>
                                        </div>

                                        <div class="absolute top-3 left-3 flex gap-1.5">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors {{ $listing['badgeType'] === 'accent' ? 'border-accent bg-accent text-accent-foreground' : 'border-secondary bg-secondary text-secondary-foreground' }}">
                                                @if ($listing['badgeType'] === 'accent')
                                                    <i data-lucide="award" class="mr-1 h-3 w-3"></i>
                                                @else
                                                    <i data-lucide="trending-up" class="mr-1 h-3 w-3"></i>
                                                @endif
                                                {{ $listing['badge'] }}
                                            </span>
                                            @if ($listing['verified'])
                                                <span class="inline-flex items-center rounded-full border border-primary bg-primary px-2.5 py-0.5 text-xs font-semibold text-primary-foreground">
                                                    <i data-lucide="shield-check" class="mr-1 h-3 w-3"></i>
                                                    Verified
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        <p class="text-xs font-medium uppercase tracking-wider text-primary">{{ $listing['category'] }}</p>
                                        <h3 class="mt-1 text-lg font-semibold text-foreground">{{ $listing['name'] }}</h3>

                                        <div class="mt-3 flex items-center gap-1">
                                            @for ($star = 1; $star <= 5; $star++)
                                                @php $filled = $star <= $roundedRating; @endphp
                                                <svg
                                                    viewBox="0 0 24 24"
                                                    class="h-5 w-5 {{ $filled ? 'text-accent' : 'text-border' }}"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    role="img"
                                                    aria-label="{{ $filled ? 'Filled star' : 'Empty star' }}"
                                                >
                                                    <path
                                                        d="{{ $starPath }}"
                                                        stroke="currentColor"
                                                        stroke-width="1.8"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        fill="{{ $filled ? 'currentColor' : 'none' }}"
                                                    />
                                                </svg>
                                            @endfor
                                            <span class="ml-1.5 text-lg font-bold text-foreground">{{ $listing['rating'] }}</span>
                                        </div>

                                        <p class="mt-1 text-sm text-muted-foreground">{{ $listing['reviews'] }} reviews</p>
                                        <div class="mt-3 flex items-center gap-1.5 text-sm text-muted-foreground">
                                            <i data-lucide="map-pin" class="h-3.5 w-3.5"></i>
                                            {{ $listing['location'] }}
                                        </div>

                                        <div class="mt-4 flex gap-2">
                                            <a href="{{ url('/business/' . $listing['slug']) }}" class="flex-1 rounded-xl border-2 border-primary bg-transparent py-2.5 text-center text-sm font-semibold text-primary transition-all duration-300 hover:bg-primary hover:text-primary-foreground">
                                                View Profile
                                            </a>
                                            <button type="button" class="flex-1 rounded-xl bg-secondary py-2.5 text-sm font-semibold text-secondary-foreground transition-all duration-300 hover:brightness-110">
                                                Get Quote
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- How It Works -->
                <section data-reveal class="bg-section-blue py-20">
                    <div class="container mx-auto px-4">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-foreground">How It Works</h2>
                            <p class="mt-2 text-muted-foreground">Simple for everyone</p>
                        </div>

                        <div class="mt-12 grid gap-8 md:grid-cols-2">
                            <div class="rounded-2xl bg-card p-8 shadow-sm">
                                <h3 class="mb-8 text-xl font-bold text-primary">For Users</h3>
                                <div class="flex flex-col">
                                    @foreach ($userSteps as $i => $step)
                                        <div class="relative flex gap-4">
                                            <div class="flex flex-col items-center">
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $step['accentColor'] }}">
                                                    <i data-lucide="{{ $step['icon'] }}" class="h-6 w-6"></i>
                                                </div>
                                                @if ($i < 2)
                                                    <div class="mt-2 h-full w-0.5 bg-border"></div>
                                                @endif
                                            </div>
                                            <div class="pb-8">
                                                <span class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Step {{ $step['num'] }}</span>
                                                <h4 class="mt-1 font-semibold text-foreground">{{ $step['title'] }}</h4>
                                                <p class="mt-1 text-sm text-muted-foreground">{{ $step['desc'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-2xl bg-card p-8 shadow-sm">
                                <h3 class="mb-8 text-xl font-bold text-secondary">For Businesses</h3>
                                <div class="flex flex-col">
                                    @foreach ($bizSteps as $i => $step)
                                        <div class="relative flex gap-4">
                                            <div class="flex flex-col items-center">
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $step['accentColor'] }}">
                                                    <i data-lucide="{{ $step['icon'] }}" class="h-6 w-6"></i>
                                                </div>
                                                @if ($i < 2)
                                                    <div class="mt-2 h-full w-0.5 bg-border"></div>
                                                @endif
                                            </div>
                                            <div class="pb-8">
                                                <span class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Step {{ $step['num'] }}</span>
                                                <h4 class="mt-1 font-semibold text-foreground">{{ $step['title'] }}</h4>
                                                <p class="mt-1 text-sm text-muted-foreground">{{ $step['desc'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Latest Reviews -->
                <section data-reveal class="bg-section-light py-20">
                    <div class="container mx-auto px-4">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-foreground">Latest Reviews</h2>
                            <p class="mt-2 text-muted-foreground">What people are saying</p>
                        </div>

                        <div class="mt-12 grid gap-6 sm:grid-cols-2">
                            @foreach ($reviews as $i => $review)
                                @php $roundedRating = (int) round($review['rating']); @endphp
                                <div class="relative rounded-2xl bg-card p-6 shadow-sm transition-shadow duration-300 hover:shadow-lg">
                                    <i data-lucide="quote" class="absolute top-5 right-5 h-8 w-8 text-primary/10"></i>

                                    <div class="flex items-center gap-1">
                                        @for ($star = 1; $star <= 5; $star++)
                                            @php $filled = $star <= $roundedRating; @endphp
                                            <svg
                                                viewBox="0 0 24 24"
                                                class="h-5 w-5 {{ $filled ? 'text-accent' : 'text-border' }}"
                                                xmlns="http://www.w3.org/2000/svg"
                                                role="img"
                                                aria-label="{{ $filled ? 'Filled star' : 'Empty star' }}"
                                            >
                                                <path
                                                    d="{{ $starPath }}"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    fill="{{ $filled ? 'currentColor' : 'none' }}"
                                                />
                                            </svg>
                                        @endfor
                                    </div>

                                    <p class="mt-4 text-foreground leading-relaxed">"{{ $review['text'] }}"</p>

                                    <div class="mt-5 flex items-center gap-3 border-t border-border pt-4">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full text-sm font-bold {{ $avatarColors[$i] }}">
                                            {{ $review['avatar'] }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-foreground">{{ $review['name'] }}</p>
                                            <p class="text-xs text-muted-foreground">on {{ $review['business'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- CTA -->
                <section data-reveal class="bg-background py-20">
                    <div class="container mx-auto px-4">
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="flex flex-col items-start gap-4 rounded-2xl bg-gradient-to-br from-primary to-primary/80 p-8 md:p-10">
                                <div class="rounded-xl bg-primary-foreground/15 p-3">
                                    <i data-lucide="building" class="h-8 w-8 text-primary-foreground"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-primary-foreground">List your business for free</h3>
                                <p class="text-primary-foreground/70">Reach thousands of potential customers looking for businesses like yours.</p>
                                <button type="button" class="mt-2 inline-flex items-center gap-2 rounded-xl bg-secondary px-6 py-3 font-semibold text-secondary-foreground shadow-lg transition-all hover:shadow-xl hover:brightness-110">
                                    Get Started
                                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                </button>
                            </div>

                            <div class="flex flex-col items-start gap-4 rounded-2xl bg-gradient-to-br from-secondary to-secondary/80 p-8 md:p-10">
                                <div class="rounded-xl bg-secondary-foreground/15 p-3">
                                    <i data-lucide="lightbulb" class="h-8 w-8 text-secondary-foreground"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-secondary-foreground">Suggest a business</h3>
                                <p class="text-secondary-foreground/70">Know a great business that should be on Biztrus.to? Let us know!</p>
                                <button type="button" class="mt-2 inline-flex items-center gap-2 rounded-xl bg-secondary-foreground px-6 py-3 font-semibold text-secondary transition-all hover:opacity-90">
                                    Suggest Now
                                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Top Locations -->
                <section data-reveal class="bg-section-light py-20">
                    <div class="container mx-auto px-4">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-foreground">Top Locations</h2>
                            <p class="mt-2 text-muted-foreground">Browse businesses in popular cities</p>
                        </div>

                        <div class="mt-10 flex flex-wrap justify-center gap-3">
                            @foreach ($cities as $city)
                                <a href="#" class="flex items-center gap-2 rounded-full bg-card px-5 py-3 text-sm font-medium text-foreground shadow-sm transition-shadow duration-300 hover:shadow-md hover:text-primary">
                                    <i data-lucide="map-pin" class="h-4 w-4 text-primary"></i>
                                    {{ $city }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- FAQ -->
                <section data-reveal class="bg-section-blue py-20">
                    <div class="container mx-auto px-4">
                        <div class="mx-auto max-w-2xl text-center">
                            <h2 class="text-3xl font-bold text-foreground">Frequently Asked Questions</h2>
                            <p class="mt-2 text-muted-foreground">Everything you need to know</p>
                        </div>

                        <div class="mx-auto mt-10 max-w-2xl">
                            <div class="space-y-3">
                                @foreach ($faqs as $i => $faq)
                                    <details data-faq-item class="rounded-xl bg-card px-6 shadow-sm transition-shadow duration-300" @if ($i === 0) open @endif>
                                        <summary class="flex cursor-pointer items-center justify-between gap-3 py-4 font-semibold text-foreground hover:no-underline">
                                            <span class="flex items-center gap-3">
                                                <i data-lucide="help-circle" class="h-5 w-5 shrink-0 text-primary"></i>
                                                {{ $faq['q'] }}
                                            </span>
                                            <i data-lucide="chevron-down" class="faq-chevron h-4 w-4 shrink-0 transition-transform duration-200"></i>
                                        </summary>
                                        <div class="faq-content">
                                            <div>
                                                <div class="pb-4 pt-0 text-muted-foreground pl-8">
                                                    {{ $faq['a'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <footer data-reveal class="border-t border-border bg-card py-14">
                    <div class="container mx-auto px-4">
                        <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-4">
                            <div>
                                <h4 class="text-lg font-bold text-primary">Biztrus.to</h4>
                                <p class="mt-2 text-sm text-muted-foreground">
                                    Real reviews. Verified businesses. Building trust in every community.
                                </p>
                                <div class="mt-4 flex items-center gap-2 text-sm text-muted-foreground">
                                    <i data-lucide="shield-check" class="h-4 w-4 text-secondary"></i>
                                    <span>Real reviews. Trusted businesses.</span>
                                </div>

                                <div class="mt-4 flex gap-3">
                                    @foreach (['Twitter', 'LinkedIn', 'Facebook'] as $social)
                                        <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-muted text-xs font-bold text-muted-foreground transition-colors hover:bg-primary hover:text-primary-foreground">
                                            {{ $social[0] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h5 class="font-semibold text-foreground">Company</h5>
                                <ul class="mt-3 space-y-2.5 text-sm text-muted-foreground">
                                    <li><a href="#" class="transition-colors hover:text-primary">About</a></li>
                                    <li><a href="#" class="transition-colors hover:text-primary">Contact</a></li>
                                    <li><a href="#" class="transition-colors hover:text-primary">Careers</a></li>
                                </ul>
                            </div>

                            <div>
                                <h5 class="font-semibold text-foreground">Resources</h5>
                                <ul class="mt-3 space-y-2.5 text-sm text-muted-foreground">
                                    <li><a href="#" class="transition-colors hover:text-primary">Categories</a></li>
                                    <li><a href="#" class="transition-colors hover:text-primary">Blog</a></li>
                                    <li><a href="#" class="transition-colors hover:text-primary">Help Center</a></li>
                                </ul>
                            </div>

                            <div>
                                <h5 class="font-semibold text-foreground">Legal</h5>
                                <ul class="mt-3 space-y-2.5 text-sm text-muted-foreground">
                                    <li><a href="#" class="transition-colors hover:text-primary">Privacy Policy</a></li>
                                    <li><a href="#" class="transition-colors hover:text-primary">Terms of Service</a></li>
                                    <li><a href="#" class="transition-colors hover:text-primary">Cookie Policy</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-10 border-t border-border pt-6 text-center text-sm text-muted-foreground">
                            © {{ now()->year }} Biztrus.to. All rights reserved.
                        </div>
                    </div>
                </footer>
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.lucide) window.lucide.createIcons();

                const btn = document.getElementById('mobileMenuBtn');
                const menu = document.getElementById('mobileMenu');
                const iconMenu = document.getElementById('iconMenu');
                const iconClose = document.getElementById('iconClose');

                if (btn && menu) {
                    btn.addEventListener('click', () => {
                        menu.classList.toggle('hidden');
                        if (iconMenu && iconClose) {
                            iconMenu.classList.toggle('hidden');
                            iconClose.classList.toggle('hidden');
                        }
                    });
                }

                // Match React accordion behavior: only one FAQ open at a time.
                const faqItems = Array.from(document.querySelectorAll('[data-faq-item]'));
                faqItems.forEach((item) => {
                    item.addEventListener('toggle', () => {
                        if (!item.open) return;
                        faqItems.forEach((other) => {
                            if (other !== item) other.open = false;
                        });
                    });
                });

                const revealed = document.querySelectorAll('[data-reveal]');
                const io = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            io.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12 });

                revealed.forEach((el, i) => {
                    el.style.transitionDelay = `${Math.min(i * 80, 420)}ms`;
                    io.observe(el);
                });
            });
        </script>
    </body>
</html>

