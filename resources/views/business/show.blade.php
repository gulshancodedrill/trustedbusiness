<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TechFix Pro Reviews — Biztrus.to</title>
        <meta name="description" content="Read 189 verified reviews for TechFix Pro in San Francisco, CA. Electronics Repair rated 4.9/5.">
        <link rel="canonical" href="https://biztrus.to/business/techfix-pro">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>

        <style>
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
            details > summary {
                list-style: none;
            }
            details > summary::-webkit-details-marker {
                display: none;
            }
            details[open] .faq-chevron {
                transform: rotate(180deg);
            }
        </style>
    </head>
    <body class="bg-background text-foreground">
        @php
            $biz = [
                'slug' => (string) ($business->id ?? 'business'),
                'name' => $business->business_name,
                'category' => $business->category?->name ?? '',
                'rating' => $avgRating ?? 0,
                'reviewCount' => $reviewCount ?? 0,
                'city' => $business->city,
                'state' => $business->state,
                'address' => $business->address_line_1,
                'phone' => $business->business_contact_number ?? $business->contact_number,
                'website' => $business->website,
                'description' => $business->business_description,
                'services' => $business->service?->name ? [$business->service->name] : [],
                'responseTime' => 'Under 30 min',
                'verified' => true,
                'topRated' => false,
                'ratingBreakdown' => $ratingBreakdown ?? [0, 0, 0, 0, 0],
            ];

            $similar = [
                ['slug' => 'sunrise-cafe', 'name' => 'Sunrise Café', 'category' => 'Restaurant', 'rating' => 4.8, 'reviewCount' => 234, 'city' => 'New York', 'state' => 'NY'],
                ['slug' => 'green-thumb-gardens', 'name' => 'Green Thumb Gardens', 'category' => 'Landscaping', 'rating' => 4.7, 'reviewCount' => 156, 'city' => 'Austin', 'state' => 'TX'],
                ['slug' => 'elite-dental-care', 'name' => 'Elite Dental Care', 'category' => 'Healthcare', 'rating' => 4.9, 'reviewCount' => 312, 'city' => 'Chicago', 'state' => 'IL'],
            ];

            $faqs = [
                ['q' => 'Is TechFix Pro verified?', 'a' => 'Yes, TechFix Pro is a verified business on Biztrus.to. We have confirmed their identity and business information.'],
                ['q' => 'How are reviews moderated?', 'a' => 'All reviews go through our moderation system. We check for fake reviews, spam, and policy violations to ensure authentic feedback.'],
                ['q' => 'Can I contact this business?', 'a' => 'Yes, you can call TechFix Pro directly, visit their website, or request a quote through Biztrus.to.'],
                ['q' => 'How do I leave a review?', 'a' => 'Click the "Write a Review" button below the ratings. You will need a free account to submit your review.'],
            ];

            $starPath = 'M12 2l3.09 6.26L22 9.27l-5 5.11L18.18 22 12 18.77 5.82 22 7 14.38 2 9.27l6.91-1.01L12 2z';
            $totalRatings = array_sum($biz['ratingBreakdown']);
        @endphp

        <script type="application/ld+json">
            {
                "\u0040context": "https://schema.org",
                "\u0040type": "LocalBusiness",
                "name": "TechFix Pro",
                "description": "TechFix Pro specializes in fast, reliable electronics repair for phones, laptops, tablets, and gaming consoles. Our certified technicians use genuine parts and offer a 90-day warranty on all repairs.",
                "address": {
                    "\u0040type": "PostalAddress",
                    "addressLocality": "San Francisco",
                    "addressRegion": "CA",
                    "streetAddress": "456 Market St, San Francisco, CA 94105"
                },
                "telephone": "(415) 555-0198",
                "url": "https://techfixpro.example.com",
                "aggregateRating": {
                    "\u0040type": "AggregateRating",
                    "ratingValue": 4.9,
                    "reviewCount": 189,
                    "bestRating": 5
                }
            }
        </script>

        <div class="min-h-screen bg-background">
            <x-site-header />

            <main>
                <section data-reveal class="bg-gradient-to-br from-primary via-primary to-primary/80 pt-28 pb-16 md:pt-36 md:pb-20">
                    <div class="container mx-auto px-4">
                        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                            <div class="flex items-start gap-5">
                                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-primary-foreground/15 text-3xl font-black text-primary-foreground backdrop-blur-sm md:h-24 md:w-24">T</div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h1 class="text-3xl font-extrabold text-primary-foreground md:text-4xl">{{ $biz['name'] }}</h1>
                                        <span class="inline-flex items-center gap-1 rounded-full border border-secondary bg-secondary px-2.5 py-0.5 text-xs font-semibold text-secondary-foreground">
                                            <i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Verified
                                        </span>
                                    </div>
                                    <div class="mt-2 flex flex-wrap items-center gap-3 text-primary-foreground/80">
                                        <span class="text-sm font-medium">{{ $biz['category'] }}</span>
                                        <span class="text-primary-foreground/40">•</span>
                                        <span class="inline-flex items-center gap-1 text-sm"><i data-lucide="map-pin" class="h-3.5 w-3.5"></i>{{ $biz['city'] }}, {{ $biz['state'] }}</span>
                                    </div>
                                    <div class="mt-3 flex items-center gap-1">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <svg viewBox="0 0 24 24" class="h-6 w-6 {{ $s <= round($biz['rating']) ? 'text-accent' : 'text-border' }}" xmlns="http://www.w3.org/2000/svg">
                                                <path d="{{ $starPath }}" stroke="currentColor" stroke-width="1.6" fill="{{ $s <= round($biz['rating']) ? 'currentColor' : 'none' }}"></path>
                                            </svg>
                                        @endfor
                                        <span class="ml-1.5 text-2xl font-bold text-foreground">{{ $biz['rating'] }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-primary-foreground/70">{{ $biz['reviewCount'] }} reviews</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="tel:{{ $biz['phone'] }}" class="inline-flex items-center gap-2 rounded-xl bg-secondary px-5 py-3 font-semibold text-secondary-foreground shadow-lg transition-all hover:shadow-xl"><i data-lucide="phone" class="h-4 w-4"></i>Call Now</a>
                                <a href="{{ $biz['website'] }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-primary-foreground/15 px-5 py-3 font-semibold text-primary-foreground backdrop-blur-sm transition-all hover:bg-primary-foreground/25"><i data-lucide="globe" class="h-4 w-4"></i>Visit Website</a>
                                <button type="button" class="inline-flex items-center gap-2 rounded-xl bg-accent px-5 py-3 font-semibold text-accent-foreground shadow-lg transition-all hover:shadow-xl"><i data-lucide="message-square" class="h-4 w-4"></i>Get Quote</button>
                            </div>
                        </div>
                    </div>
                </section>

                <section data-reveal class="border-b border-border bg-card py-4">
                    <div class="container mx-auto flex flex-wrap items-center justify-center gap-6 px-4 md:gap-10">
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground"><i data-lucide="star" class="h-4 w-4 text-accent"></i>{{ $biz['reviewCount'] }} Reviews</div>
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground"><i data-lucide="shield-check" class="h-4 w-4 text-secondary"></i>Verified Business</div>
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground"><i data-lucide="clock" class="h-4 w-4 text-primary"></i>Responds {{ $biz['responseTime'] }}</div>
                        <div class="flex items-center gap-2 text-sm font-medium text-muted-foreground"><i data-lucide="award" class="h-4 w-4 text-accent"></i>Top Rated</div>
                    </div>
                </section>

                <div class="container mx-auto px-4 py-12">
                    <div class="grid gap-8 lg:grid-cols-3">
                        <div class="lg:col-span-2 space-y-10">
                            <section data-reveal>
                                <h2 class="text-2xl font-bold text-foreground">About {{ $biz['name'] }}</h2>
                                <p class="mt-4 leading-relaxed text-muted-foreground">{{ $biz['description'] }}</p>
                                <h3 class="mt-6 text-lg font-semibold text-foreground">Services</h3>
                                <ul class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($biz['services'] as $service)
                                        <li class="rounded-full bg-muted px-4 py-1.5 text-sm font-medium text-foreground">{{ $service }}</li>
                                    @endforeach
                                </ul>
                            </section>

                            <section data-reveal>
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <h2 class="text-2xl font-bold text-foreground">Reviews</h2>
                                </div>
                                <div class="mt-6 rounded-2xl bg-muted/50 p-6">
                                    <div class="flex items-center gap-6">
                                        <div class="text-center">
                                            <span class="text-5xl font-extrabold text-foreground">{{ $biz['rating'] }}</span>
                                            <div class="mt-1 flex items-center gap-0.5">
                                                @for ($s = 1; $s <= 5; $s++)
                                                    <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $s <= round($biz['rating']) ? 'text-accent' : 'text-border' }}" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="{{ $starPath }}" stroke="currentColor" stroke-width="1.6" fill="{{ $s <= round($biz['rating']) ? 'currentColor' : 'none' }}"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <p class="mt-1 text-xs text-muted-foreground">{{ $biz['reviewCount'] }} reviews</p>
                                        </div>
                                        <div class="flex-1 space-y-2">
                                            @foreach ($biz['ratingBreakdown'] as $i => $count)
                                                <div class="flex items-center gap-3">
                                                    <span class="w-14 text-xs text-muted-foreground">{{ 5 - $i }} stars</span>
                                                    <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-border">
                                                        <div class="h-full rounded-full bg-accent {{ $i === 0 ? 'w-[85%]' : ($i === 1 ? 'w-[11%]' : ($i === 2 ? 'w-[3%]' : 'w-[1%]')) }}"></div>
                                                    </div>
                                                    <span class="w-8 text-right text-xs text-muted-foreground">{{ $count }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 rounded-2xl bg-muted/50 p-6 border border-border/60" id="write-review">
                                    @auth
                                        <form method="POST" action="{{ route('business.reviews.store', $business) }}" class="space-y-4">
                                            @csrf
                                            <h3 class="text-lg font-bold text-foreground">Write a Review</h3>

                                            <div class="space-y-2">
                                                <label class="text-sm font-medium text-foreground">Rating</label>
                                                <div id="rating-stars" class="flex items-center gap-1">
                                                    @for ($s = 1; $s <= 5; $s++)
                                                        <label
                                                            class="rating-star-label cursor-pointer text-border transition-colors hover:text-accent"
                                                            data-value="{{ $s }}"
                                                        >
                                                            <input
                                                                type="radio"
                                                                name="rating"
                                                                value="{{ $s }}"
                                                                class="rating-input"
                                                                style="position:absolute;opacity:0;pointer-events:none;"
                                                                required
                                                            >
                                                            <i data-lucide="star" class="h-6 w-6" aria-hidden="true"></i>
                                                        </label>
                                                    @endfor
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <label for="comment" class="text-sm font-medium text-foreground">Comment</label>
                                                <textarea
                                                    id="comment"
                                                    name="comment"
                                                    rows="4"
                                                    class="w-full rounded-xl border border-border bg-card px-4 py-3 text-sm text-foreground"
                                                    placeholder="Share your experience..."
                                                    required
                                                ></textarea>
                                            </div>

                                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-accent px-6 py-3 text-sm font-semibold text-accent-foreground shadow transition-all hover:brightness-110">
                                                <i data-lucide="send" class="h-4 w-4"></i>
                                                Submit Review
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-sm text-muted-foreground">
                                            Sign in to write a review.
                                        </p>
                                    @endauth
                                </div>

                                <div class="mt-6 space-y-4">
                                    @forelse ($reviews as $review)
                                        <article class="rounded-2xl border border-border bg-card p-5 transition-shadow hover:shadow-md">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
                                                        {{ strtoupper(substr((string) ($review->user->name ?? ''), 0, 1)) ?: '?' }}
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-foreground">{{ $review->user->name }}</p>
                                                        <p class="text-xs text-muted-foreground">
                                                            {{ optional($review->created_at)->format('M j, Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3 flex items-center gap-0.5">
                                                @for ($s = 1; $s <= 5; $s++)
                                                    <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $s <= $review->rating ? 'text-accent' : 'text-border' }}" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="{{ $starPath }}" stroke="currentColor" stroke-width="1.6" fill="{{ $s <= $review->rating ? 'currentColor' : 'none' }}"></path>
                                                    </svg>
                                                @endfor
                                            </div>

                                            <p class="mt-3 text-sm leading-relaxed text-muted-foreground">{{ $review->comment }}</p>

                                            <div class="mt-4 flex items-center gap-3">
                                                @auth
                                                    <form method="POST" action="{{ route('reviews.vote', $review) }}" class="inline-flex">
                                                        @csrf
                                                        <input type="hidden" name="vote" value="like">
                                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-secondary/20 px-4 py-2 text-sm font-semibold text-secondary-foreground transition-all hover:brightness-110">
                                                            <i data-lucide="thumbs-up" class="h-4 w-4"></i>
                                                            <span>{{ (int) ($review->like_count ?? 0) }}</span>
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('reviews.vote', $review) }}" class="inline-flex">
                                                        @csrf
                                                        <input type="hidden" name="vote" value="dislike">
                                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-secondary/20 px-4 py-2 text-sm font-semibold text-secondary-foreground transition-all hover:brightness-110">
                                                            <i data-lucide="thumbs-down" class="h-4 w-4"></i>
                                                            <span>{{ (int) ($review->dislike_count ?? 0) }}</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="inline-flex items-center gap-2 rounded-xl bg-secondary/10 px-4 py-2 text-sm font-semibold text-muted-foreground">
                                                        <i data-lucide="thumbs-up" class="h-4 w-4"></i>
                                                        <span>{{ (int) ($review->like_count ?? 0) }}</span>
                                                    </div>
                                                    <div class="inline-flex items-center gap-2 rounded-xl bg-secondary/10 px-4 py-2 text-sm font-semibold text-muted-foreground">
                                                        <i data-lucide="thumbs-down" class="h-4 w-4"></i>
                                                        <span>{{ (int) ($review->dislike_count ?? 0) }}</span>
                                                    </div>
                                                @endauth
                                            </div>
                                        </article>
                                    @empty
                                        <p class="text-sm text-muted-foreground">No reviews yet. Be the first to leave one.</p>
                                    @endforelse
                                </div>
                            </section>

                            <section data-reveal class="rounded-2xl border border-primary/20 bg-gradient-to-br from-primary/5 to-secondary/5 p-6">
                                <div class="flex items-center gap-2"><i data-lucide="sparkles" class="h-5 w-5 text-primary"></i><h2 class="text-lg font-bold text-foreground">AI Review Summary</h2></div>
                                <p class="mt-3 leading-relaxed text-muted-foreground">Based on 189 reviews, customers describe TechFix Pro as overwhelmingly positive. Reviewers frequently praise service quality, professionalism, and speed. The business maintains a strong 4.9/5 rating, with most reviewers giving 5 stars.</p>
                            </section>

                            <section data-reveal>
                                <h2 class="text-2xl font-bold text-foreground">Photos</h2>
                                <div class="mt-4 grid grid-cols-3 gap-3">
                                    @for ($i = 0; $i < 6; $i++)
                                        <div class="flex aspect-square items-center justify-center rounded-xl bg-muted transition-shadow hover:shadow-md"><i data-lucide="image" class="h-8 w-8 text-muted-foreground/40"></i></div>
                                    @endfor
                                </div>
                            </section>
                        </div>

                        <aside class="space-y-8">
                            <section data-reveal class="rounded-2xl border border-border bg-card p-6">
                                <h2 class="text-lg font-bold text-foreground">Location & Contact</h2>
                                <div class="mt-4 flex h-40 items-center justify-center rounded-xl bg-muted"><i data-lucide="map-pin" class="h-8 w-8 text-muted-foreground/40"></i></div>
                                <div class="mt-4 space-y-3">
                                    <div class="flex items-start gap-3 text-sm"><i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-primary"></i><span class="text-muted-foreground">{{ $biz['address'] }}</span></div>
                                    <div class="flex items-center gap-3 text-sm"><i data-lucide="phone" class="h-4 w-4 shrink-0 text-secondary"></i><a href="tel:{{ $biz['phone'] }}" class="text-primary hover:underline">{{ $biz['phone'] }}</a></div>
                                    <div class="flex items-center gap-3 text-sm"><i data-lucide="globe" class="h-4 w-4 shrink-0 text-primary"></i><a href="{{ $biz['website'] }}" class="truncate text-primary hover:underline">{{ $biz['website'] }}</a></div>
                                    <div class="flex items-center gap-3 text-sm"><i data-lucide="clock" class="h-4 w-4 shrink-0 text-accent"></i><span class="text-muted-foreground">Responds {{ $biz['responseTime'] }}</span></div>
                                </div>
                            </section>

                            <section data-reveal class="rounded-2xl border border-border bg-card p-6">
                                <h2 class="text-lg font-bold text-foreground">FAQ</h2>
                                <div class="mt-4 space-y-2">
                                    @foreach ($faqs as $idx => $faq)
                                        <details class="rounded-xl border border-border px-4 py-2">
                                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-left font-medium">
                                                <span class="flex items-center gap-2"><i data-lucide="help-circle" class="h-4 w-4 shrink-0 text-primary"></i>{{ $faq['q'] }}</span>
                                                <i data-lucide="chevron-down" class="faq-chevron h-4 w-4 transition-transform"></i>
                                            </summary>
                                            <p class="pt-2 text-sm text-muted-foreground">{{ $faq['a'] }}</p>
                                        </details>
                                    @endforeach
                                </div>
                            </section>
                        </aside>
                    </div>
                </div>

                <section data-reveal class="bg-section-light py-16">
                    <div class="container mx-auto px-4">
                        <h2 class="text-2xl font-bold text-foreground">Similar Businesses</h2>
                        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($similar as $s)
                                <a href="#" class="block rounded-2xl border border-transparent bg-card p-5 shadow-sm transition-all hover:border-primary/20 hover:shadow-lg">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-xl font-bold text-primary">{{ $s['name'][0] }}</div>
                                        <div>
                                            <h3 class="font-semibold text-foreground">{{ $s['name'] }}</h3>
                                            <p class="text-xs text-muted-foreground">{{ $s['category'] }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg viewBox="0 0 24 24" class="h-4 w-4 {{ $i <= round($s['rating']) ? 'text-accent' : 'text-border' }}" xmlns="http://www.w3.org/2000/svg">
                                                <path d="{{ $starPath }}" stroke="currentColor" stroke-width="1.6" fill="{{ $i <= round($s['rating']) ? 'currentColor' : 'none' }}"></path>
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-sm font-bold text-foreground">{{ $s['rating'] }}</span>
                                        <span class="ml-1 text-xs text-muted-foreground">({{ $s['reviewCount'] }})</span>
                                    </div>
                                    <div class="mt-2 flex items-center gap-1 text-xs text-muted-foreground"><i data-lucide="map-pin" class="h-3 w-3"></i>{{ $s['city'] }}, {{ $s['state'] }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            </main>

            <footer data-reveal class="border-t border-border bg-card py-14">
                <div class="container mx-auto px-4">
                    <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-4">
                        <div>
                            <h4 class="text-lg font-bold text-primary">Biztrus.to</h4>
                            <p class="mt-2 text-sm text-muted-foreground">Real reviews. Verified businesses. Building trust in every community.</p>
                            <div class="mt-4 flex items-center gap-2 text-sm text-muted-foreground"><i data-lucide="shield-check" class="h-4 w-4 text-secondary"></i><span>Real reviews. Trusted businesses.</span></div>
                        </div>
                        <div><h5 class="font-semibold text-foreground">Company</h5><ul class="mt-3 space-y-2.5 text-sm text-muted-foreground"><li><a href="#" class="transition-colors hover:text-primary">About</a></li><li><a href="#" class="transition-colors hover:text-primary">Contact</a></li><li><a href="#" class="transition-colors hover:text-primary">Careers</a></li></ul></div>
                        <div><h5 class="font-semibold text-foreground">Resources</h5><ul class="mt-3 space-y-2.5 text-sm text-muted-foreground"><li><a href="#" class="transition-colors hover:text-primary">Categories</a></li><li><a href="#" class="transition-colors hover:text-primary">Blog</a></li><li><a href="#" class="transition-colors hover:text-primary">Help Center</a></li></ul></div>
                        <div><h5 class="font-semibold text-foreground">Legal</h5><ul class="mt-3 space-y-2.5 text-sm text-muted-foreground"><li><a href="#" class="transition-colors hover:text-primary">Privacy Policy</a></li><li><a href="#" class="transition-colors hover:text-primary">Terms of Service</a></li><li><a href="#" class="transition-colors hover:text-primary">Cookie Policy</a></li></ul></div>
                    </div>
                    <div class="mt-10 border-t border-border pt-6 text-center text-sm text-muted-foreground">© {{ now()->year }} Biztrus.to. All rights reserved.</div>
                </div>
            </footer>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.lucide) window.lucide.createIcons();

                const ratingStarsContainer = document.getElementById('rating-stars');
                if (ratingStarsContainer) {
                    const labels = ratingStarsContainer.querySelectorAll('.rating-star-label');
                    const inputs = ratingStarsContainer.querySelectorAll('.rating-input');

                    const renderStars = () => {
                        const checked = ratingStarsContainer.querySelector('.rating-input:checked');
                        const selectedValue = checked ? parseInt(checked.value, 10) : 0;

                        labels.forEach((label) => {
                            const v = parseInt(label.getAttribute('data-value'), 10) || 0;
                            const active = selectedValue > 0 && v <= selectedValue;
                            if (active) {
                                label.classList.remove('text-border');
                                label.classList.add('text-accent');
                            } else {
                                label.classList.remove('text-accent');
                                label.classList.add('text-border');
                            }
                        });
                    };

                    inputs.forEach((input) => input.addEventListener('change', renderStars));
                    renderStars();
                }

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

