<x-guest-layout>
    <p class="mb-5 text-sm text-muted-foreground">{{ __('Thanks for signing up! Please verify your email using the link we sent. If you did not receive it, we can send another one.') }}</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 rounded-xl border border-secondary/40 bg-secondary/10 px-4 py-3 text-sm font-medium text-secondary">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-medium text-primary hover:text-primary/80">{{ __('Log Out') }}</button>
        </form>
    </div>
</x-guest-layout>
