{{-- Credit: Heroicons (https://heroicons.com) --}}

@props([
    'variant' => 'outline',
])

@php
$classes = Flux::classes('shrink-0')
    ->add(match($variant) {
        'outline' => '[:where(&)]:size-6',
        'solid' => '[:where(&)]:size-6',
        'mini' => '[:where(&)]:size-5',
        'micro' => '[:where(&)]:size-4',
    });
@endphp

<?php switch ($variant): case ('outline'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
  <path stroke-linecap="round" stroke-linejoin="round" d="m16.49 12 3.75-3.751m0 0-3.75-3.75m3.75 3.75H3.74V19.5"/>
</svg>

        <?php break; ?>

    <?php case ('solid'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M3.74 20.25a.75.75 0 0 0 .75-.75V8.999h13.938l-2.47 2.47a.75.75 0 0 0 1.061 1.06l3.75-3.75a.75.75 0 0 0 0-1.06l-3.75-3.75a.75.75 0 0 0-1.06 1.06l2.47 2.47H3.738a.75.75 0 0 0-.75.75V19.5c0 .414.336.75.75.75Z" clip-rule="evenodd"/>
</svg>

        <?php break; ?>

    <?php case ('mini'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M3.75 17a.75.75 0 0 0 .75-.75v-7.5h10.94l-1.97 1.97a.75.75 0 1 0 1.06 1.06l3.25-3.25a.75.75 0 0 0 0-1.06l-3.25-3.25a.75.75 0 1 0-1.06 1.06l1.97 1.97H3.75A.75.75 0 0 0 3 8v8.25c0 .414.336.75.75.75Z" clip-rule="evenodd"/>
</svg>

        <?php break; ?>

    <?php case ('micro'): ?>
<svg {{ $attributes->class($classes) }} data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M2.75 14a.75.75 0 0 0 .75-.75v-6.5h7.94l-.97.97a.75.75 0 0 0 1.06 1.06l2.25-2.25a.75.75 0 0 0 0-1.06l-2.25-2.25a.75.75 0 1 0-1.06 1.06l.97.97H2.75A.75.75 0 0 0 2 6v7.25c0 .414.336.75.75.75Z" clip-rule="evenodd"/>
</svg>

        <?php break; ?>

<?php endswitch; ?>