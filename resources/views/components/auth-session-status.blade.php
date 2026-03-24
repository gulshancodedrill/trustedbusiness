@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-secondary/40 bg-secondary/10 px-4 py-3 text-sm font-medium text-secondary']) }}>
        {{ $status }}
    </div>
@endif
