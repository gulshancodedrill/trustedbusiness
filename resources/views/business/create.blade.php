<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>List Your Business - Biztrus.to</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-background text-foreground antialiased">
    <x-site-header />

    <main class="min-h-screen bg-gradient-to-b from-section-blue via-background to-background pb-16 pt-28">
        <div class="container mx-auto max-w-5xl px-4">
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-black tracking-tight sm:text-4xl">List Your Business</h1>
                <p class="mt-2 text-muted-foreground">Join trusted businesses and grow with real customer discovery.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-destructive/40 bg-destructive/10 px-5 py-4 text-sm text-destructive">
                    <p class="font-semibold">Please fix the following fields:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-3xl border border-border/60 bg-card p-5 shadow-xl sm:p-8">
                <div class="mb-8 grid grid-cols-3 gap-3 text-center">
                    <div class="step-pill" data-step-pill="1">
                        <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-primary-foreground">1</div>
                        <p class="text-xs font-semibold sm:text-sm">Member Details</p>
                    </div>
                    <div class="step-pill opacity-60" data-step-pill="2">
                        <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary text-sm font-bold text-primary">2</div>
                        <p class="text-xs font-semibold sm:text-sm">Business Details</p>
                    </div>
                    <div class="step-pill opacity-60" data-step-pill="3">
                        <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary text-sm font-bold text-primary">3</div>
                        <p class="text-xs font-semibold sm:text-sm">Done</p>
                    </div>
                </div>

                @php
                    $isEdit = isset($business) && $business;
                    $existingTags = $isEdit && is_array($business->tags) ? implode(', ', $business->tags) : '';
                @endphp
                <form id="businessForm" method="POST" action="{{ $isEdit ? route('businesses.update', $business) : route('businesses.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if ($isEdit)
                        @method('PATCH')
                    @endif

                    <section class="step-panel" data-step-panel="1">
                        <h2 class="mb-4 text-xl font-bold">Step 1: Member Details</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Owner First Name</label>
                                <input name="owner_first_name" value="{{ old('owner_first_name', $business->owner_first_name ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Owner Last Name</label>
                                <input name="owner_last_name" value="{{ old('owner_last_name', $business->owner_last_name ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold">Contact Number</label>
                                <input name="contact_number" value="{{ old('contact_number', $business->contact_number ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" class="next-step rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-primary-foreground hover:brightness-110">Continue</button>
                        </div>
                    </section>

                    <section class="step-panel hidden" data-step-panel="2">
                        <h2 class="mb-4 text-xl font-bold">Step 2: Business Details</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Business Name</label>
                                <input name="business_name" value="{{ old('business_name', $business->business_name ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Business Email</label>
                                <input type="email" name="business_email" value="{{ old('business_email', $business->business_email ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Business Contact Number</label>
                                <input name="business_contact_number" value="{{ old('business_contact_number', $business->business_contact_number ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Website</label>
                                <input type="url" name="website" value="{{ old('website', $business->website ?? '') }}" placeholder="https://example.com" class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold">Business Description</label>
                                <textarea name="business_description" rows="4" class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">{{ old('business_description', $business->business_description ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Business Logo</label>
                                <input type="file" name="business_logo" accept="image/*" class="w-full rounded-xl border border-border bg-background px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Cover Photo</label>
                                <input type="file" name="cover_photo" accept="image/*" class="w-full rounded-xl border border-border bg-background px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Country</label>
                                <input name="country" value="{{ old('country', $business->country ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">State</label>
                                <input name="state" value="{{ old('state', $business->state ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">City</label>
                                <input name="city" value="{{ old('city', $business->city ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Pincode</label>
                                <input name="pincode" value="{{ old('pincode', $business->pincode ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold">Address Line 1</label>
                                <input name="address_line_1" value="{{ old('address_line_1', $business->address_line_1 ?? '') }}" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Industry</label>
                                <select id="industrySelect" name="industry_id" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                                    <option value="">Select Industry</option>
                                    @foreach ($industries as $industry)
                                        <option value="{{ $industry->id }}" @selected(old('industry_id', $business->industry_id ?? null) == $industry->id)>{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Category</label>
                                <select id="categorySelect" name="category_id" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" data-industry-id="{{ $category->industry_id }}" @selected(old('category_id', $business->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-semibold">Service</label>
                                <select id="serviceSelect" name="service_id" required class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                                    <option value="">Select Service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" data-category-id="{{ $service->category_id }}" @selected(old('service_id', $business->service_id ?? null) == $service->id)>{{ $service->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold">Tags</label>
                                <input name="tags_input" value="{{ old('tags_input', $existingTags) }}" placeholder="e.g. affordable, family-friendly, same-day service" class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                                <p class="mt-1 text-xs text-muted-foreground">Use comma-separated values.</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold">How did you hear about us?</label>
                                <input name="hear_from" value="{{ old('hear_from', $business->hear_from ?? '') }}" maxlength="50" class="w-full rounded-xl border border-border bg-background px-4 py-2.5 text-sm">
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl border border-border/70 p-4">
                            <h3 class="mb-3 text-lg font-bold">Business Hours</h3>
                            @php($days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])
                            <div class="space-y-3">
                                @foreach ($days as $day)
                                    <div class="grid grid-cols-1 gap-3 rounded-xl bg-section-light p-3 md:grid-cols-4 md:items-center">
                                        <p class="font-semibold capitalize">{{ $day }}</p>
                                        <input type="time" name="{{ $day }}_open" value="{{ old($day.'_open') }}" class="rounded-lg border border-border bg-background px-3 py-2 text-sm">
                                        <input type="time" name="{{ $day }}_close" value="{{ old($day.'_close') }}" class="rounded-lg border border-border bg-background px-3 py-2 text-sm">
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox" name="{{ $day }}_closed" value="1" @checked(old($day.'_closed')) class="h-4 w-4 rounded border-border text-primary focus:ring-primary">
                                            Closed
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <button type="button" class="prev-step rounded-xl border border-border px-6 py-2.5 text-sm font-semibold">Back</button>
                            <button type="button" class="next-step rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-primary-foreground hover:brightness-110">Continue</button>
                        </div>
                    </section>

                    <section class="step-panel hidden text-center" data-step-panel="3">
                        <h2 class="text-3xl font-black text-secondary">Almost Done!</h2>
                        <p class="mt-3 text-muted-foreground">Review your details and submit to list your business.</p>
                        <label class="mx-auto mt-5 flex max-w-md items-center justify-center gap-2 text-sm">
                            <input id="termsCheckbox" type="checkbox" class="h-4 w-4 rounded border-border text-primary focus:ring-primary">
                            I agree to the <a href="#" class="text-primary underline">Terms &amp; Conditions</a>.
                        </label>
                        <div class="mt-8 flex items-center justify-center gap-3">
                            <button type="button" class="prev-step rounded-xl border border-border px-6 py-2.5 text-sm font-semibold">Back</button>
                            <button id="submitBusinessBtn" type="submit" disabled class="rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-primary-foreground disabled:cursor-not-allowed disabled:opacity-60">
                                {{ $isEdit ? 'Update Business' : 'Submit Business' }}
                            </button>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </main>

    <script>
        if (window.lucide) window.lucide.createIcons();

        const menuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const iconMenu = document.getElementById('iconMenu');
        const iconClose = document.getElementById('iconClose');
        if (menuBtn && mobileMenu && iconMenu && iconClose) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                iconMenu.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            });
        }

        const panels = Array.from(document.querySelectorAll('[data-step-panel]'));
        const pills = Array.from(document.querySelectorAll('[data-step-pill]'));
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        let step = 1;

        const syncSteps = () => {
            panels.forEach((panel, idx) => panel.classList.toggle('hidden', idx + 1 !== step));
            pills.forEach((pill, idx) => {
                pill.classList.toggle('opacity-60', idx + 1 > step);
            });
        };
        syncSteps();

        nextButtons.forEach((btn) => btn.addEventListener('click', () => {
            step = Math.min(step + 1, 3);
            syncSteps();
        }));
        prevButtons.forEach((btn) => btn.addEventListener('click', () => {
            step = Math.max(step - 1, 1);
            syncSteps();
        }));

        const industrySelect = document.getElementById('industrySelect');
        const categorySelect = document.getElementById('categorySelect');
        const serviceSelect = document.getElementById('serviceSelect');

        const filterCategories = () => {
            const industryId = industrySelect.value;
            for (const option of categorySelect.options) {
                if (!option.value) continue;
                option.hidden = industryId ? option.dataset.industryId !== industryId : false;
            }
            if (categorySelect.selectedOptions[0] && categorySelect.selectedOptions[0].hidden) categorySelect.value = '';
            filterServices();
        };

        const filterServices = () => {
            const categoryId = categorySelect.value;
            for (const option of serviceSelect.options) {
                if (!option.value) continue;
                option.hidden = categoryId ? option.dataset.categoryId !== categoryId : false;
            }
            if (serviceSelect.selectedOptions[0] && serviceSelect.selectedOptions[0].hidden) serviceSelect.value = '';
        };

        if (industrySelect && categorySelect && serviceSelect) {
            industrySelect.addEventListener('change', filterCategories);
            categorySelect.addEventListener('change', filterServices);
            filterCategories();
        }

        const termsCheckbox = document.getElementById('termsCheckbox');
        const submitBusinessBtn = document.getElementById('submitBusinessBtn');
        if (termsCheckbox && submitBusinessBtn) {
            termsCheckbox.addEventListener('change', () => {
                submitBusinessBtn.disabled = !termsCheckbox.checked;
            });
        }
    </script>
</body>
</html>
