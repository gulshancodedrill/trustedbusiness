<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suggest Business - Biztrus.to</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('biztrus.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-background text-foreground antialiased">
    <x-site-header />

    <main class="pt-28 min-h-screen">
        @php
            $initialStep = 0;
            $keys = collect($errors->keys());
            if ($keys->contains('industry')) {
                $initialStep = 1;
            } elseif ($keys->contains('business_email') || $keys->contains('business_contact_number') || $keys->contains('website')) {
                $initialStep = 2;
            } else {
                $initialStep = 0;
            }
        @endphp
        <div class="mx-auto w-full max-w-xl px-4 py-6">
            <form id="businessSuggestForm" method="POST" action="{{ route('businesses.suggest.store') }}">
                @csrf
                <div id="suggestFormConfig" class="hidden" data-initial-step="{{ (int) $initialStep }}"></div>

            <button id="backBtn" type="button" data-back-url="{{ route('businesses.add') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i> Back
                </button>

                <div class="w-full">
                    <div class="mb-2 flex items-center justify-between">
                        <span id="stepProgressText" class="text-sm font-medium text-foreground">Step 1 of 3</span>
                        <span id="stepProgressLabel" class="text-sm text-muted-foreground">Business Info</span>
                    </div>
                    <div class="relative h-2 w-full overflow-hidden rounded-full bg-muted">
                        <div id="stepProgressBar" class="h-full rounded-full bg-secondary transition-all duration-500 ease-out" style="width:0%"></div>
                    </div>
                    <div class="mt-3 hidden gap-1 md:flex">
                        <div class="flex flex-1 items-center gap-1.5">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-primary text-primary-foreground">1</div>
                            <span class="truncate text-xs font-medium text-foreground">Business Info</span>
                        </div>
                        <div class="flex flex-1 items-center gap-1.5">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground">2</div>
                            <span class="truncate text-xs text-muted-foreground">Category</span>
                        </div>
                        <div class="flex flex-1 items-center gap-1.5">
                            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold transition-colors bg-muted text-muted-foreground">3</div>
                            <span class="truncate text-xs text-muted-foreground">Details (optional)</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 min-h-[280px]">
                    {{-- Step 0 --}}
                    <section class="step-panel" data-step-panel="0">
                        <h3 class="mb-6 text-xl font-bold text-foreground">Business Info</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-foreground">Business Name *</label>
                                <div class="relative">
                                    <i data-lucide="building" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                    <input name="business_name" value="{{ old('business_name') }}" placeholder="e.g. Joe's Coffee" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                </div>
                                    <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="business_name"></p>
                                @error('business_name')
                                    <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-foreground">Location *</label>
                                <div class="relative">
                                    <i data-lucide="map-pin" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                    <input name="address" value="{{ old('address') }}" placeholder="City or address" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                </div>
                                    <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="address"></p>
                                @error('address')
                                    <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- Step 1 --}}
                    <section class="step-panel hidden" data-step-panel="1">
                        <h3 class="mb-6 text-xl font-bold text-foreground">Category</h3>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-foreground">Industry *</label>
                            <select name="industry" class="w-full rounded-xl border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select industry...</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry }}" @selected(old('industry') === $industry)>{{ $industry }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 hidden text-xs text-destructive" data-client-error-for="industry"></p>
                            @error('industry')
                                <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                    </section>

                    {{-- Step 2 --}}
                    <section class="step-panel hidden" data-step-panel="2">
                        <h3 class="mb-6 text-xl font-bold text-foreground">Additional Details</h3>
                        <p class="mb-4 text-sm text-muted-foreground">Optional — skip if you don't have this info</p>

                        <div class="space-y-4">
                            <div class="relative">
                                <i data-lucide="globe" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                <input name="website" value="{{ old('website') }}" placeholder="Website URL" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                            </div>

                            <div class="relative">
                                <i data-lucide="phone" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                <input name="business_contact_number" value="{{ old('business_contact_number') }}" placeholder="Phone number" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                            </div>

                            <div class="relative">
                                <i data-lucide="mail" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"></i>
                                <input name="business_email" value="{{ old('business_email') }}" placeholder="Email address" class="w-full rounded-xl border border-input bg-background py-3 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring">
                            </div>
                        </div>
                    </section>
                </div>

                <div class="mt-8 flex items-center justify-between border-t border-border pt-5">
                    <button id="backStepBtn" type="button" class="inline-flex items-center gap-1.5 rounded-lg border border-border px-5 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-muted">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i> <span id="backBtnLabel">Cancel</span>
                    </button>

                    <button id="nextOrSubmitBtn" type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-primary-foreground transition-all hover:opacity-90">
                        Next <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        if (window.lucide) window.lucide.createIcons();

        let step = Number(document.getElementById('suggestFormConfig')?.dataset.initialStep || 0);
        const labels = ['Business Info', 'Category', 'Details (optional)'];
        const panels = Array.from(document.querySelectorAll('.step-panel[data-step-panel]'));

        const stepProgressText = document.getElementById('stepProgressText');
        const stepProgressLabel = document.getElementById('stepProgressLabel');
        const stepProgressBar = document.getElementById('stepProgressBar');

        const backBtnLabel = document.getElementById('backBtnLabel');

        const syncSteps = () => {
            panels.forEach((panel) => {
                const panelStep = Number(panel.dataset.stepPanel);
                panel.classList.toggle('hidden', panelStep !== step);
            });

            if (stepProgressText) stepProgressText.textContent = `Step ${step + 1} of 3`;
            if (stepProgressLabel) stepProgressLabel.textContent = labels[step] || '';
            if (stepProgressBar) stepProgressBar.style.width = `${(step / 2) * 100}%`;

            const btn = document.getElementById('nextOrSubmitBtn');
            if (!btn) return;

            if (backBtnLabel) {
                backBtnLabel.textContent = step === 0 ? 'Cancel' : 'Back';
            }

            if (step < 2) {
                btn.className = 'inline-flex items-center gap-1.5 rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-primary-foreground transition-all hover:opacity-90';
                btn.innerHTML = `Next <i data-lucide="arrow-right" class="h-4 w-4"></i>`;
            } else {
                btn.className = 'inline-flex items-center gap-1.5 rounded-lg bg-accent px-8 py-2.5 text-sm font-bold text-accent-foreground shadow-lg transition-all hover:scale-105 hover:shadow-xl';
                btn.innerHTML = `Submit Suggestion <i data-lucide="check-circle" class="h-4 w-4"></i>`;
            }

            if (btn.querySelectorAll('i[data-lucide]').length && window.lucide) window.lucide.createIcons();
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
        const validateStep = () => {
            clearClientErrors();

            if (step === 0) {
                const name = (document.querySelector('input[name="business_name"]')?.value || '').trim();
                const addr = (document.querySelector('input[name="address"]')?.value || '').trim();

                if (!name) {
                    setClientError('business_name', 'Business name is required');
                    return false;
                }
                if (!addr) {
                    setClientError('address', 'Location is required');
                    return false;
                }
            }

            if (step === 1) {
                const industry = (document.querySelector('select[name="industry"]')?.value || '').trim();
                if (!industry) {
                    setClientError('industry', 'Select an industry');
                    return false;
                }
            }

            return true;
        };

        const nextOrSubmit = () => {
            if (step < 2) {
                if (!validateStep()) return;
                step += 1;
                syncSteps();
            } else {
                // Submit the form.
                document.getElementById('businessSuggestForm')?.submit();
            }
        };

        document.getElementById('nextOrSubmitBtn')?.addEventListener('click', nextOrSubmit);
        const topBackBtn = document.getElementById('backBtn');
        if (topBackBtn) {
            const url = topBackBtn.dataset.backUrl;
            topBackBtn.addEventListener('click', () => {
                if (url) window.location = url;
            });
        }
        document.getElementById('backStepBtn')?.addEventListener('click', () => {
            if (step === 0) {
                const url = document.getElementById('backBtn')?.dataset.backUrl;
                window.location = url || '/';
                return;
            }
            step = Math.max(step - 1, 0);
            syncSteps();
        });
        syncSteps();
    </script>
</body>
</html>

