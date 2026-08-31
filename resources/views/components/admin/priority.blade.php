@props(['level' => 'rendah'])

@php
    $level = strtolower($level);
    $map = [
        'rendah'   => ['label' => 'Rendah',   'class' => 'text-amber-500'],
        'menengah' => ['label' => 'Menengah', 'class' => 'text-orange-500'],
        'tinggi'   => ['label' => 'Tinggi',   'class' => 'text-danger'],
    ];
    $p = $map[$level] ?? $map['rendah'];
@endphp

<span class="font-semibold {{ $p['class'] }}">{{ $p['label'] }}</span>
