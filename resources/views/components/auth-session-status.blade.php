@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-400 bg-green-500/10 border border-green-500/30 px-4 py-3 rounded-xl']) }}>
        {{ $status }}
    </div>
@endif
