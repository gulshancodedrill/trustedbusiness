<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>List Your Business - Biztrus.to</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-background text-foreground antialiased">
    <x-site-header />

    <main class="min-h-screen bg-background">
        @php
            $initialStep = 0;
            $keys = collect($errors->keys());
            $hasServiceFieldErrors = $keys->filter(fn ($k) => str_starts_with((string) $k, 'services.'))->isNotEmpty();
            $hasTimingFieldErrors = $keys->filter(fn ($k) => str_ends_with((string) $k, '_open') || str_ends_with((string) $k, '_close'))->isNotEmpty();

            if ($keys->contains('industry_id') || $keys->contains('category_id') || $hasServiceFieldErrors) {
                $initialStep = 1;
            } elseif ($keys->contains('address')) {
                $initialStep = 2;
            } elseif ($keys->contains('business_email') || $keys->contains('business_contact_number') || $keys->contains('contact_person')) {
                $initialStep = 3;
            } elseif ($hasTimingFieldErrors) {
                $initialStep = 4;
            }
            $oldServices = old('services', []);
            if (! is_array($oldServices)) {
                $oldServices = [];
            }
        @endphp

        <div class="pt-28">
            <div id="listFormConfig" class="hidden" data-initial-step="{{ (int) $initialStep }}"></div>
            <div
                id="listFormOptions"
                class="hidden"
                data-categories-map='{{ json_encode($categoriesByIndustryId ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}'
                data-services-by-category-id='{{ json_encode($servicesByCategoryId ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}'
                data-old-services='{{ json_encode(array_values($oldServices ?? []), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}'
            ></div>
            <form id="businessListForm" method="POST" action="{{ route('businesses.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mx-auto w-full max-w-2xl px-4 py-6">
                    <button id="backBtn" type="button" data-back-url="{{ route('businesses.add') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i> Back
                    </button>

                    <div class="w-full">
                        <div class="mb-2 flex items-center justify-between">
                            <span id="stepProgressText" class="text-sm font-medium text-foreground">Step 1 of 6</span>
                            <span id="stepProgressLabel" class="text-sm text-muted-foreground">Basics</span>
                        </div>
                        <div class="relative h-2 w-full overflow-hidden rounded-full bg-muted">
                            <div id="stepProgressBar" class="h-full rounded-full bg-secondary transition-all duration-500 ease-out" style="width:0%"></div>
                        </div>
                        <div id="stepDots" class="mt-3 hidden gap-1 md:flex">
                            <div data-dot-step="0" class="flex flex-1 items-center gap-1.5">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-primary text-primary-foreground">1</div>
                                <span class="truncate text-xs font-medium text-foreground">Basics</span>
                            </div>
                            <div data-dot-step="1" class="flex flex-1 items-center gap-1.5">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground">2</div>
                                <span class="truncate text-xs text-muted-foreground">Category</span>
                            </div>
                            <div data-dot-step="2" class="flex flex-1 items-center gap-1.5">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground">3</div>
                                <span class="truncate text-xs text-muted-foreground">Location</span>
                            </div>
                            <div data-dot-step="3" class="flex flex-1 items-center gap-1.5">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground">4</div>
                                <span class="truncate text-xs text-muted-foreground">Contact</span>
                            </div>
                            <div data-dot-step="4" class="flex flex-1 items-center gap-1.5">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground">5</div>
                                <span class="truncate text-xs text-muted-foreground">Timing</span>
                            </div>
                            <div data-dot-step="5" class="flex flex-1 items-center gap-1.5">
                                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground">6</div>
                                <span class="truncate text-xs text-muted-foreground">Review</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 min-h-[380px]">
                        {{-- Step 0: Basics --}}
                        <section class="step-panel" data-step-panel="0">
                            <h3 class="mb-6 text-xl font-bold text-foreground">Business Basics</h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Business Name *</label>
                                    <div class="relative">
                                        <i data-lucide="building" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                        <input id="businessNameInput" name="business_name" value="{{ old('business_name') }}" placeholder="e.g. Acme Solutions" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="business_name"></p>
                                    @error('business_name')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Logo</label>
                                    <div class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-border bg-muted/50 p-8 transition-colors hover:border-primary/40">
                                        <div id="logoUploadPlaceholder" class="text-center">
                                            <i data-lucide="upload" class="mx-auto h-8 w-8 text-muted-foreground"></i>
                                            <p class="mt-2 text-sm text-muted-foreground">Drag & drop or click to upload</p>
                                            <p class="text-xs text-muted-foreground">PNG, JPG up to 2MB</p>
                                        </div>

                                        <div id="logoUploadPreviewWrap" class="hidden w-full text-center">
                                            <img id="logoPreviewImg" alt="Logo preview" class="mx-auto max-h-36 w-auto rounded-lg border border-border bg-background">
                                            <p id="logoPreviewFileName" class="mt-2 text-xs text-muted-foreground"></p>
                                        </div>

                                        <input type="file" name="business_logo" accept="image/*" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" aria-label="Business logo">
                                    </div>
                                    @error('business_logo')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Cover Photo</label>
                                    <div class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-border bg-muted/50 p-8 transition-colors hover:border-primary/40">
                                        <div id="coverUploadPlaceholder" class="text-center">
                                            <i data-lucide="image" class="mx-auto h-8 w-8 text-muted-foreground"></i>
                                            <p class="mt-2 text-sm text-muted-foreground">Drag & drop or click to upload</p>
                                            <p class="text-xs text-muted-foreground">JPG, PNG up to 5MB</p>
                                        </div>

                                        <div id="coverUploadPreviewWrap" class="hidden w-full text-center">
                                            <img id="coverPreviewImg" alt="Cover preview" class="mx-auto max-h-36 w-auto rounded-lg border border-border bg-background">
                                            <p id="coverPreviewFileName" class="mt-2 text-xs text-muted-foreground"></p>
                                        </div>

                                        <input type="file" name="cover_photo" accept="image/*" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" aria-label="Cover photo">
                                    </div>
                                    @error('cover_photo')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Website (optional)</label>
                                    <div class="relative">
                                        <i data-lucide="globe" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                        <input name="website" value="{{ old('website') }}" placeholder="https://www.example.com" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    @error('website')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Business Description</label>
                                    <textarea
                                        name="business_description"
                                        rows="4"
                                        placeholder="Describe your business..."
                                        class="w-full rounded-xl border border-input bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                                    >{{ old('business_description') }}</textarea>
                                    @error('business_description')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{-- Step 1: Category --}}
                        <section class="step-panel hidden" data-step-panel="1">
                            <h3 class="mb-6 text-xl font-bold text-foreground">Category & Services</h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Industry *</label>
                                    <select id="industrySelect" name="industry_id" class="w-full rounded-xl border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                        <option value="">Select industry...</option>
                                        @foreach ($industries as $industry)
                                            <option value="{{ $industry->id }}" @selected((string) old('industry_id') === (string) $industry->id)>{{ $industry->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="industry_id"></p>
                                    @error('industry_id')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                @php
                                    $currentIndustryId = old('industry_id');
                                    $categoryOptions = $currentIndustryId ? ($categoriesByIndustryId[$currentIndustryId] ?? []) : [];
                                    $selectedCategoryId = old('category_id');
                                    if (! $selectedCategoryId && count($categoryOptions) > 0) {
                                        $selectedCategoryId = $categoryOptions[0]['id'] ?? null;
                                    }
                                    $servicesForSelectedCategory = $selectedCategoryId ? ($servicesByCategoryId[$selectedCategoryId] ?? []) : [];
                                    $oldServiceIds = array_map(static fn ($v) => (string) $v, is_array($oldServices ?? []) ? $oldServices : []);
                                @endphp

                                <div id="categoryWrapper" class="{{ old('industry_id') ? '' : 'hidden' }}">
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-foreground">Category</label>
                                        <select id="categorySelect" name="category_id" class="w-full rounded-xl border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                            <option value="">Select category...</option>
                                            @foreach ($categoryOptions as $cat)
                                                <option value="{{ $cat['id'] }}" @selected((string) old('category_id') === (string) $cat['id'] || (! old('category_id') && (string) $selectedCategoryId === (string) $cat['id']))>{{ $cat['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Services</label>
                                    <div class="flex flex-wrap gap-2" id="servicesChips">
                                        @foreach ($servicesForSelectedCategory as $service)
                                            @php
                                                $serviceId = $service['id'];
                                                $serviceName = $service['name'];
                                                $checked = in_array((string) $serviceId, $oldServiceIds, true);
                                                $id = 'service_' . $serviceId;
                                            @endphp
                                            <input
                                                type="checkbox"
                                                id="{{ $id }}"
                                                name="services[]"
                                                value="{{ $serviceId }}"
                                                class="hidden"
                                                data-service-checkbox
                                                data-service-name="{{ $serviceName }}"
                                                @checked($checked)
                                            >
                                            <label
                                                for="{{ $id }}"
                                                data-service-chip
                                                class="select-none rounded-full border px-3.5 py-1.5 text-xs font-medium transition-all {{ $checked ? 'border-secondary bg-secondary/10 text-secondary' : 'border-border bg-card text-muted-foreground hover:border-primary/30' }}"
                                            >
                                                {{ $serviceName }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- Step 2: Location --}}
                        <section class="step-panel hidden" data-step-panel="2">
                            <h3 class="mb-6 text-xl font-bold text-foreground">Location</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Search Address *</label>
                                    <div class="relative">
                                        <i data-lucide="map-pin" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                        <input id="addressInput" name="address" value="{{ old('address') }}" placeholder="Start typing your address..." class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="address"></p>
                                    @error('address')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex h-44 items-center justify-center rounded-xl border border-border bg-muted/50">
                                    <div class="text-center text-muted-foreground">
                                        <i data-lucide="map-pin" class="mx-auto h-8 w-8 opacity-40"></i>
                                        <p class="mt-1 text-xs">Map preview will appear here</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-foreground">City</label>
                                        <input name="city" value="{{ old('city') }}" class="w-full rounded-lg border border-input bg-background px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-foreground">State</label>
                                        <input name="state" value="{{ old('state') }}" class="w-full rounded-lg border border-input bg-background px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-foreground">Country</label>
                                        <input name="country" value="{{ old('country') }}" class="w-full rounded-lg border border-input bg-background px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-medium text-foreground">Pincode</label>
                                        <input name="pincode" value="{{ old('pincode') }}" class="w-full rounded-lg border border-input bg-background px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                </div>

                                <label class="flex items-center gap-2 text-sm text-muted-foreground">
                                    <input type="checkbox" name="hide_address" value="1" class="rounded border-border" @checked(old('hide_address'))>
                                    Hide exact address (show only area)
                                </label>
                            </div>
                        </section>

                        {{-- Step 3: Contact --}}
                        <section class="step-panel hidden" data-step-panel="3">
                            <h3 class="mb-6 text-xl font-bold text-foreground">Contact Details</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Business Email *</label>
                                    <div class="relative">
                                        <i data-lucide="mail" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                        <input id="emailInput" name="business_email" type="email" value="{{ old('business_email') }}" placeholder="hello@business.com" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="business_email"></p>
                                    @error('business_email')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Business Phone *</label>
                                    <div class="relative">
                                        <i data-lucide="phone" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                        <input id="phoneInput" name="business_contact_number" value="{{ old('business_contact_number') }}" placeholder="+1 (555) 123-4567" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="business_contact_number"></p>
                                    @error('business_contact_number')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-foreground">Contact Person</label>
                                    <div class="relative">
                                        <i data-lucide="user" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                        <input name="contact_person" value="{{ old('contact_person') }}" placeholder="John Doe" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    @error('contact_person')
                                        <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{-- Step 4: Timing --}}
                        <section class="step-panel hidden" data-step-panel="4">
                            <h3 class="mb-6 text-xl font-bold text-foreground">Business Timing</h3>

                            <div class="space-y-4">
                                @php($days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'])
                                @foreach ($days as $day)
                                    <div class="grid grid-cols-1 gap-3 rounded-xl bg-section-light p-3 md:grid-cols-4 md:items-center md:gap-4">
                                        <p class="font-semibold capitalize text-sm">{{ $day }}</p>
                                        <label class="flex items-center gap-2 text-xs font-medium text-muted-foreground md:col-span-1">
                                            <input
                                                type="checkbox"
                                                name="{{ $day }}_closed"
                                                value="1"
                                                id="{{ $day }}_closed"
                                                class="h-4 w-4 rounded border-border text-primary focus:ring-primary"
                                                @checked(old($day.'_closed'))
                                            >
                                            Closed
                                        </label>
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs font-medium text-muted-foreground">Opening</label>
                                            <input
                                                type="time"
                                                id="{{ $day }}_open"
                                                name="{{ $day }}_open"
                                                value="{{ old($day.'_open') }}"
                                                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                                @if(old($day.'_closed')) disabled @endif
                                            >
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <label class="text-xs font-medium text-muted-foreground">Closing</label>
                                            <input
                                                type="time"
                                                id="{{ $day }}_close"
                                                name="{{ $day }}_close"
                                                value="{{ old($day.'_close') }}"
                                                class="w-full rounded-lg border border-border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                                                @if(old($day.'_closed')) disabled @endif
                                            >
                                        </div>
                                        <div class="text-xs md:col-span-1">
                                            @error($day.'_open')
                                                <p class="text-xs text-destructive">{{ $message }}</p>
                                            @enderror
                                            @error($day.'_close')
                                                <p class="text-xs text-destructive">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        {{-- Step 5: Review --}}
                        <section class="step-panel hidden text-center" data-step-panel="5">
                            <h3 class="mb-6 text-xl font-bold text-foreground">Review & Submit</h3>

                            <div class="space-y-4">
                                <div class="rounded-xl border-2 border-secondary/40 bg-secondary/5 p-5 transition-colors">
                                    @auth
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-secondary/10">
                                                <i data-lucide="check-circle" class="h-5 w-5 text-secondary"></i>
                                            </div>
                                            <div class="text-left">
                                                <p class="text-sm font-semibold text-foreground">Signed in as {{ auth()->user()->name ?: auth()->user()->email }}</p>
                                                <p class="text-xs text-muted-foreground">You can claim this listing later.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-left">
                                            <p class="text-sm font-bold text-foreground">Link to Your Account</p>
                                            <p class="mt-1 text-xs text-muted-foreground">You can skip this and claim later.</p>
                                        </div>
                                    @endauth

                                    <div class="mt-3">
                                        <button type="button" class="w-full text-center text-xs text-muted-foreground underline-offset-2 hover:underline">
                                            Skip for now — you can claim this listing later
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-start justify-between rounded-xl border border-border bg-card p-4">
                                        <div class="text-left">
                                            <h4 class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Business Basics</h4>
                                            <p id="reviewBusinessName" class="font-medium text-foreground">{{ old('business_name') ?: '—' }}</p>
                                            <p id="reviewWebsite" class="text-sm text-muted-foreground">{{ old('website') ?: '' }}</p>
                                            <p id="reviewDescription" class="mt-1 text-sm text-muted-foreground">{{ old('business_description') ?: '' }}</p>
                                        </div>
                                        <button type="button" data-edit-step="0" class="shrink-0 text-xs font-medium text-primary transition-colors hover:text-primary/80">Edit</button>
                                    </div>

                                    <div class="flex items-start justify-between rounded-xl border border-border bg-card p-4">
                                        <div class="text-left">
                                            <h4 class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Category</h4>
                                            <p id="reviewIndustryCategory" class="text-foreground">
                                                {{ old('industry') ?: '—' }} @if(old('category')) / {{ old('category') }} @endif
                                            </p>
                                            <div id="reviewServices" class="mt-1 flex flex-wrap gap-1">
                                                @foreach (($oldServices ?? []) as $svc)
                                                    @php($svc = is_string($svc) ? $svc : null)
                                                    @if ($svc)
                                                        <span class="rounded-full bg-secondary/10 px-2 py-0.5 text-xs text-secondary">{{ $svc }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        <button type="button" data-edit-step="1" class="shrink-0 text-xs font-medium text-primary transition-colors hover:text-primary/80">Edit</button>
                                    </div>

                                    <div class="flex items-start justify-between rounded-xl border border-border bg-card p-4">
                                        <div class="text-left">
                                            <h4 class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Location</h4>
                                            <p id="reviewAddress" class="text-foreground">{{ old('address') ?: '—' }}</p>
                                            <p id="reviewLocationLine" class="text-sm text-muted-foreground">
                                                {{ collect([old('city'), old('state'), old('country')])->filter()->implode(', ') ?: '' }}
                                            </p>
                                        </div>
                                        <button type="button" data-edit-step="2" class="shrink-0 text-xs font-medium text-primary transition-colors hover:text-primary/80">Edit</button>
                                    </div>

                                    <div class="flex items-start justify-between rounded-xl border border-border bg-card p-4">
                                        <div class="text-left">
                                            <h4 class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Contact</h4>
                                            <p id="reviewEmail" class="text-foreground">{{ old('business_email') ?: '—' }}</p>
                                            <p id="reviewPhone" class="text-sm text-muted-foreground">{{ old('business_contact_number') ?: '' }}</p>
                                        </div>
                                        <button type="button" data-edit-step="3" class="shrink-0 text-xs font-medium text-primary transition-colors hover:text-primary/80">Edit</button>
                                    </div>

                                    <div class="flex items-start justify-between rounded-xl border border-border bg-card p-4">
                                        <div class="text-left">
                                            <h4 class="mb-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Timing</h4>
                                            <p id="reviewTiming" class="text-foreground">—</p>
                                        </div>
                                        <button type="button" data-edit-step="4" class="shrink-0 text-xs font-medium text-primary transition-colors hover:text-primary/80">Edit</button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-between border-t border-border pt-5">
                                <button type="button" data-prev-step="true" class="inline-flex items-center gap-1.5 rounded-lg border border-border px-5 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-muted">
                                    <i data-lucide="arrow-left" class="h-4 w-4"></i> Back
                                </button>

                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-secondary px-8 py-2.5 text-sm font-bold text-secondary-foreground shadow-lg transition-all hover:scale-105 hover:shadow-xl">
                                    <i data-lucide="check-circle" class="h-4 w-4"></i> Submit Business
                                </button>
                            </div>
                        </section>

                        {{-- Bottom Navigation (Steps 0-3) --}}
                        <div id="stepNavRow" class="mt-8 flex items-center justify-between border-t border-border pt-5">
                            <button id="stepBackBtn" type="button" data-back-url="{{ route('businesses.add') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-border px-5 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-muted">
                                <i data-lucide="arrow-left" class="h-4 w-4"></i> <span id="stepBackBtnLabel">Cancel</span>
                            </button>
                            <button id="stepNextBtn" type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-primary-foreground transition-all hover:opacity-90">
                                Next <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        if (window.lucide) window.lucide.createIcons();

        const bindImagePreview = (inputSelector, placeholderId, previewWrapId, imgId, fileNameId) => {
            const input = document.querySelector(inputSelector);
            const placeholderEl = document.getElementById(placeholderId);
            const previewWrapEl = document.getElementById(previewWrapId);
            const imgEl = document.getElementById(imgId);
            const fileNameEl = document.getElementById(fileNameId);

            if (!input || !placeholderEl || !previewWrapEl || !imgEl || !fileNameEl) return;

            let currentUrl = null;
            const reset = () => {
                if (currentUrl) URL.revokeObjectURL(currentUrl);
                currentUrl = null;
                imgEl.removeAttribute('src');
                fileNameEl.textContent = '';
                previewWrapEl.classList.add('hidden');
                placeholderEl.classList.remove('hidden');
            };

            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (!file) {
                    reset();
                    return;
                }

                if (currentUrl) URL.revokeObjectURL(currentUrl);
                currentUrl = URL.createObjectURL(file);
                imgEl.src = currentUrl;
                fileNameEl.textContent = file.name;
                placeholderEl.classList.add('hidden');
                previewWrapEl.classList.remove('hidden');
            });

            // Ensure initial state.
            reset();
        };

        bindImagePreview(
            'input[name="business_logo"]',
            'logoUploadPlaceholder',
            'logoUploadPreviewWrap',
            'logoPreviewImg',
            'logoPreviewFileName'
        );
        bindImagePreview(
            'input[name="cover_photo"]',
            'coverUploadPlaceholder',
            'coverUploadPreviewWrap',
            'coverPreviewImg',
            'coverPreviewFileName'
        );

        // Timing: when a day is marked as closed, disable (and effectively skip) open/close inputs.
        const timingDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        timingDays.forEach((day) => {
            const closedCheckbox = document.getElementById(`${day}_closed`);
            const openInput = document.getElementById(`${day}_open`);
            const closeInput = document.getElementById(`${day}_close`);

            if (!closedCheckbox || !openInput || !closeInput) return;

            const sync = () => {
                const isClosed = closedCheckbox.checked;
                openInput.disabled = isClosed;
                closeInput.disabled = isClosed;

                if (isClosed) {
                    // Prevent accidental partial submission values.
                    openInput.value = '';
                    closeInput.value = '';
                }
            };

            closedCheckbox.addEventListener('change', sync);
            sync();
        });

        // Options are injected via HTML data-* to keep Blade out of JS parsing.
        const listFormOptions = document.getElementById('listFormOptions');
        const categoriesMap = listFormOptions
            ? JSON.parse(listFormOptions.dataset.categoriesMap || '{}')
            : {};
        const servicesByCategoryId = listFormOptions
            ? JSON.parse(listFormOptions.dataset.servicesByCategoryId || '{}')
            : {};
        const oldServiceIds = listFormOptions
            ? JSON.parse(listFormOptions.dataset.oldServices || '[]').map((v) => String(v))
            : [];
        const industrySelect = document.getElementById('industrySelect');
        const categoryWrapper = document.getElementById('categoryWrapper');
        const categorySelect = document.getElementById('categorySelect');

        const renderCategoryOptions = (industryValue) => {
            if (!categoryWrapper || !categorySelect) return;

            const cats = categoriesMap[industryValue] || [];
            const currentCategoryId = categorySelect.value || '';

            categorySelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Select category...';
            categorySelect.appendChild(placeholder);

            cats.forEach((c) => {
                const opt = document.createElement('option');
                opt.value = String(c.id ?? '');
                opt.textContent = c.name ?? '';
                categorySelect.appendChild(opt);
            });

            // Preserve current value if still valid.
            if (cats.some((c) => String(c.id ?? '') === currentCategoryId)) {
                categorySelect.value = currentCategoryId;
            } else {
                categorySelect.value = '';
            }
        };

        const syncCategoriesUI = () => {
            if (!industrySelect || !categoryWrapper) return;
            const v = industrySelect.value;
            categoryWrapper.classList.toggle('hidden', !v);
            if (v) {
                renderCategoryOptions(v);
            } else if (categorySelect) {
                categorySelect.innerHTML = '<option value="">Select category...</option>';
                categorySelect.value = '';
            }
        };

        if (industrySelect) {
            industrySelect.addEventListener('change', () => {
                syncCategoriesUI();
                renderServicesChips(categorySelect?.value || '', new Set());
            });
            syncCategoriesUI();
        }

        const selectedChipClass = 'select-none rounded-full border px-3.5 py-1.5 text-xs font-medium transition-all border-secondary bg-secondary/10 text-secondary';
        const unselectedChipClass = 'select-none rounded-full border px-3.5 py-1.5 text-xs font-medium transition-all border-border bg-card text-muted-foreground hover:border-primary/30';

        const syncChipForCheckbox = (checkbox) => {
            if (!checkbox) return;
            const id = checkbox.getAttribute('id');
            const chip = document.querySelector(`label[for="${id}"][data-service-chip]`);
            if (!chip) return;
            chip.className = checkbox.checked ? selectedChipClass : unselectedChipClass;
        };

        const renderServicesChips = (categoryId, selectedIdsSet) => {
            const servicesChipsEl = document.getElementById('servicesChips');
            if (!servicesChipsEl) return;

            servicesChipsEl.innerHTML = '';
            if (!categoryId) return;

            const services = servicesByCategoryId[categoryId] || [];

            services.forEach((svc) => {
                const serviceId = String(svc.id ?? '');
                const serviceName = svc.name ?? '';
                const checked = selectedIdsSet.has(serviceId);
                const inputId = `service_${serviceId}`;

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = inputId;
                checkbox.name = 'services[]';
                checkbox.value = serviceId;
                checkbox.className = 'hidden';
                checkbox.dataset.serviceCheckbox = '1';
                checkbox.dataset.serviceName = serviceName;
                checkbox.checked = checked;

                const label = document.createElement('label');
                label.setAttribute('for', inputId);
                label.dataset.serviceChip = '1';
                label.className = checked ? selectedChipClass : unselectedChipClass;
                label.textContent = serviceName;

                servicesChipsEl.appendChild(checkbox);
                servicesChipsEl.appendChild(label);
            });
        };

        // Event delegation for chips.
        const servicesChipsEl = document.getElementById('servicesChips');
        servicesChipsEl?.addEventListener('change', (e) => {
            const target = e.target;
            if (!target || !target.matches('input[data-service-checkbox]')) return;
            syncChipForCheckbox(target);
        });

        // Render initial services chips based on selected category + old selections.
        const initialCategoryId = categorySelect?.value || '';
        renderServicesChips(initialCategoryId, new Set(oldServiceIds));
        if (categorySelect) {
            categorySelect.addEventListener('change', () => {
                renderServicesChips(categorySelect.value || '', new Set());
            });
        }

        // Step navigation.
        const panels = Array.from(document.querySelectorAll('.step-panel[data-step-panel]'));
        let step = Number(document.getElementById('listFormConfig')?.dataset.initialStep || 0);

        const labels = ['Basics', 'Category', 'Location', 'Contact', 'Timing', 'Review'];

        const syncSteps = () => {
            panels.forEach((panel) => {
                const panelStep = Number(panel.dataset.stepPanel);
                const isActive = panelStep === step;

                // Keep Step 0 (logo upload) present in the DOM for reliable submission.
                const keepStep0VisibleForUpload = step !== 0 && panelStep === 0;
                if (keepStep0VisibleForUpload) {
                    panel.classList.remove('hidden');
                    panel.classList.add('opacity-0', 'pointer-events-none');
                    // Avoid collapsing with `h-0`/`overflow-hidden` (some browsers can lose file input payloads).
                    // Instead, remove from flow while keeping the inputs mounted.
                    panel.classList.remove('h-0', 'overflow-hidden');
                    // Keep Step 0 from affecting layout, but keep it in the DOM so the file input payload is preserved.
                    panel.style.position = 'absolute';
                    panel.style.left = '0';
                    panel.style.right = '0';
                    panel.style.top = '0';
                    panel.style.width = '100%';
                    panel.style.height = '';
                    panel.style.overflow = '';
                    panel.style.zIndex = '-1';

                    // Ensure file inputs themselves cannot capture clicks.
                    panel.querySelectorAll('input[type="file"]').forEach((i) => {
                        i.style.pointerEvents = 'none';
                    });
                } else {
                    panel.classList.remove('opacity-0', 'pointer-events-none');
                    panel.classList.remove('h-0', 'overflow-hidden');
                    panel.style.position = '';
                    panel.style.left = '';
                    panel.style.right = '';
                    panel.style.top = '';
                    panel.style.width = '';
                    panel.style.height = '';
                    panel.style.overflow = '';
                    panel.style.zIndex = '';
                    panel.classList.toggle('hidden', !isActive);

                    panel.querySelectorAll('input[type="file"]').forEach((i) => {
                        i.style.pointerEvents = '';
                    });
                }
            });

            const bar = document.getElementById('stepProgressBar');
            const text = document.getElementById('stepProgressText');
            const label = document.getElementById('stepProgressLabel');
            if (bar) bar.style.width = `${(step / 5) * 100}%`;
            if (text) text.textContent = `Step ${step + 1} of 6`;
            if (label) label.textContent = labels[step] || '';

            // Dots (md+)
            const dotContainers = document.querySelectorAll('#stepDots [data-dot-step]');
            dotContainers.forEach((dot) => {
                const i = Number(dot.dataset.dotStep);
                const badge = dot.querySelector('div[data-dot-badge]');
                // We used the first child `div` in markup as the badge.
                const badgeDiv = dot.querySelector('div');
                const textSpan = dot.querySelector('span');

                if (!badgeDiv || !textSpan) return;

                const isDone = i < step;
                const isCurrent = i === step;

                if (isDone) {
                    badgeDiv.className = 'flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-secondary text-secondary-foreground';
                    badgeDiv.textContent = '✓';
                    textSpan.className = 'truncate text-xs font-medium text-foreground';
                } else if (isCurrent) {
                    badgeDiv.className = 'flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-primary text-primary-foreground';
                    badgeDiv.textContent = String(i + 1);
                    textSpan.className = 'truncate text-xs font-medium text-foreground';
                } else {
                    badgeDiv.className = 'flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground';
                    badgeDiv.textContent = String(i + 1);
                    textSpan.className = 'truncate text-xs text-muted-foreground';
                }
            });

            // Bottom nav (single row).
            const stepNavRow = document.getElementById('stepNavRow');
            if (stepNavRow) stepNavRow.classList.toggle('hidden', step === 5);

            const stepBackBtnLabel = document.getElementById('stepBackBtnLabel');
            if (stepBackBtnLabel) stepBackBtnLabel.textContent = step === 0 ? 'Cancel' : 'Back';
        };

        const clearClientErrors = () => {
            document.querySelectorAll('[data-client-error-for]').forEach((el) => {
                el.textContent = '';
                el.classList.add('hidden');
            });
        };

        const setClientError = (field, message) => {
            const el = document.querySelector(`[data-client-error-for="${field}"]`);
            if (!el) return;
            el.textContent = message;
            el.classList.remove('hidden');
        };

        // Mirrors React step-level validation: blocks "Next" until required fields are valid.
        const validateStep = (s) => {
            clearClientErrors();

            if (s === 0) {
                const name = (document.getElementById('businessNameInput')?.value || '').trim();
                if (!name) {
                    setClientError('business_name', 'Business name is required');
                    return false;
                }
            }

            if (s === 1) {
                const ind = (industrySelect?.value || '').trim();
                if (!ind) {
                    setClientError('industry_id', 'Select an industry');
                    return false;
                }
            }

            if (s === 2) {
                const addr = (document.getElementById('addressInput')?.value || '').trim();
                if (!addr) {
                    setClientError('address', 'Address is required');
                    return false;
                }
            }

            if (s === 3) {
                const emailInput = document.getElementById('emailInput');
                const email = (emailInput?.value || '').trim();
                const phone = (document.getElementById('phoneInput')?.value || '').trim();

                if (!email) {
                    setClientError('business_email', 'Email is required');
                    return false;
                }

                if (emailInput && emailInput.checkValidity() === false) {
                    setClientError('business_email', 'Invalid email');
                    return false;
                }
                if (!phone) {
                    setClientError('business_contact_number', 'Phone is required');
                    return false;
                }
            }

            return true;
        };

        const nextStep = () => {
            if (step >= 5) return;
            if (!validateStep(step)) return;
            step = Math.min(step + 1, 5);
            syncSteps();
            if (step === 5) syncReview();
        };

        const prevStep = () => {
            step = Math.max(step - 1, 0);
            syncSteps();
        };

        document.getElementById('stepNextBtn')?.addEventListener('click', nextStep);
        document.getElementById('stepBackBtn')?.addEventListener('click', () => {
            if (step === 0) {
                const url = document.getElementById('stepBackBtn')?.dataset.backUrl;
                window.location = url || '/';
                return;
            }

            prevStep();
        });

        // Make sure the upload inputs are in a "normal" state right before submit.
        // Some browsers can fail to include file inputs when they were previously positioned far off-screen.
        const businessListForm = document.getElementById('businessListForm');
        businessListForm?.addEventListener('submit', () => {
            const step0Panel = document.querySelector('.step-panel[data-step-panel="0"]');
            if (!step0Panel) return;

            step0Panel.classList.remove('opacity-0', 'pointer-events-none');
            step0Panel.classList.remove('hidden');

            step0Panel.style.position = '';
            step0Panel.style.left = '';
            step0Panel.style.right = '';
            step0Panel.style.top = '';
            step0Panel.style.width = '';
            step0Panel.style.height = '';
            step0Panel.style.overflow = '';
                step0Panel.style.zIndex = '';

            // Re-enable pointer events on the file inputs for this submit.
            step0Panel.querySelectorAll('input[type="file"]').forEach((i) => {
                i.style.pointerEvents = '';
            });
        });

        document.getElementById('backBtn')?.addEventListener('click', () => {
            const url = document.getElementById('backBtn')?.dataset.backUrl;
            window.location = url || '/';
        });

        // Review "Edit" buttons.
        document.querySelectorAll('[data-edit-step]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const target = Number(btn.dataset.editStep);
                step = target;
                syncSteps();
            });
        });

        // Review back button.
        document.querySelectorAll('[data-prev-step="true"]').forEach((btn) => {
            btn.addEventListener('click', () => {
                step = 4;
                syncSteps();
            });
        });

        const syncReview = () => {
            const reviewBusinessName = document.getElementById('reviewBusinessName');
            const reviewWebsite = document.getElementById('reviewWebsite');
            const reviewDescription = document.getElementById('reviewDescription');
            const reviewIndustryCategory = document.getElementById('reviewIndustryCategory');
            const reviewAddress = document.getElementById('reviewAddress');
            const reviewLocationLine = document.getElementById('reviewLocationLine');
            const reviewEmail = document.getElementById('reviewEmail');
            const reviewPhone = document.getElementById('reviewPhone');
            const reviewServices = document.getElementById('reviewServices');
            const reviewTiming = document.getElementById('reviewTiming');

            const businessName = document.getElementById('businessNameInput')?.value.trim() || '';
            const website = document.querySelector('input[name="website"]')?.value.trim() || '';
            const industryValue = industrySelect?.selectedOptions?.[0]?.textContent || '';
            const categoryValue = categorySelect?.selectedOptions?.[0]?.textContent || '';
            const address = document.getElementById('addressInput')?.value.trim() || '';
            const city = document.querySelector('input[name="city"]')?.value.trim() || '';
            const state = document.querySelector('input[name="state"]')?.value.trim() || '';
            const country = document.querySelector('input[name="country"]')?.value.trim() || '';
            const pincode = document.querySelector('input[name="pincode"]')?.value.trim() || '';
            const email = document.getElementById('emailInput')?.value.trim() || '';
            const phone = document.getElementById('phoneInput')?.value.trim() || '';

            if (reviewBusinessName) reviewBusinessName.textContent = businessName || '—';
            if (reviewWebsite) reviewWebsite.textContent = website || '';
            if (reviewDescription) reviewDescription.textContent = (document.querySelector('textarea[name="business_description"]')?.value || '').trim();
            if (reviewIndustryCategory) reviewIndustryCategory.textContent = `${industryValue || '—'}${categoryValue ? ' / ' + categoryValue : ''}`;
            if (reviewAddress) reviewAddress.textContent = address || '—';
            if (reviewLocationLine) reviewLocationLine.textContent = [city, state, country].filter(Boolean).join(', ');
            if (reviewEmail) reviewEmail.textContent = email || '—';
            if (reviewPhone) reviewPhone.textContent = phone || '';

            if (reviewTiming) {
                const days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
                const parts = [];
                days.forEach((d) => {
                    const isClosed = document.querySelector(`input[name="${d}_closed"]`)?.checked;
                    const open = document.querySelector(`input[name="${d}_open"]`)?.value || '';
                    const close = document.querySelector(`input[name="${d}_close"]`)?.value || '';
                    const label = d.charAt(0).toUpperCase() + d.slice(1);

                    if (isClosed) {
                        parts.push(`${label}: Closed`);
                        return;
                    }

                    if (open && close) {
                        parts.push(`${label}: ${open} - ${close}`);
                    }
                });
                reviewTiming.textContent = parts.length ? parts.join(', ') : '—';
            }

            if (reviewServices) {
                const selected = [];
                document.querySelectorAll('input[name="services[]"]:checked').forEach((c) => selected.push(c.dataset.serviceName || c.value));
                reviewServices.innerHTML = selected.length
                    ? selected.map((s) => `<span class="rounded-full bg-secondary/10 px-2 py-0.5 text-xs text-secondary">${s}</span>`).join('')
                    : '';
            }
        };

        // Initial sync (handles server-side validation bounce).
        // If server returns with errors, we keep step=0; user can click Edit to jump.
        syncSteps();
    </script>
</body>
</html>

