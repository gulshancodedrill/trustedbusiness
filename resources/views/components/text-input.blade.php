@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'mt-1 w-full rounded-xl border border-border bg-background/70 px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground shadow-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20']) }}>
