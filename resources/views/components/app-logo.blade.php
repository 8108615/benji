@php
    $ajuste = \App\Models\Ajuste::query()->select('logo', 'nombre')->first();
    $ajusteLogo = $ajuste->logo ?? null;
    $ajusteNombre = $ajuste->nombre ?? null;
    $logoUrl = null;

    if($ajusteLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($ajusteLogo)) {
        $logoUrl = asset('storage/' . $ajusteLogo);
    }
@endphp
<div class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
    @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="logo" class="size-8 object-cover" />
    @else
        <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
    @endif

</div>
<div class="ms-1 grid flex-1 text-start text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">{{ $ajusteNombre }}</span>
</div>
