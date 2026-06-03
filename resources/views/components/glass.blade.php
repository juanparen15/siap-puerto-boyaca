@props([
    'rounded' => 'rounded-3xl',
    'tint' => '',  // '', 'green', 'amber', 'dark'
])
<div {{ $attributes->class(["lg-glass relative overflow-hidden $rounded", "lg-glass--$tint" => $tint]) }}>
    <div class="lg-glass__distort" aria-hidden="true"></div>
    <div class="lg-glass__tint" aria-hidden="true"></div>
    <div class="lg-glass__edge" aria-hidden="true"></div>
    <div class="relative z-[3]">{{ $slot }}</div>
</div>
