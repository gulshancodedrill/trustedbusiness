@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-foreground']) }}>
    {{ $value ?? $slot }}
</label>
