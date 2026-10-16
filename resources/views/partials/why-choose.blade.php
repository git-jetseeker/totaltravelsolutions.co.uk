{{-- Shared dark "Why choose us" feature strip --}}
@php
    $badge = $badge ?? 'Why book with us';
    $title = $title ?? 'Why Customers Choose Us';
    $subtitle = $subtitle ?? 'Trusted airport parking solutions, competitive prices, and support when you need it.';
    $withReveal = !empty($withReveal);
    $sectionClass = trim('js-why-section section-spacing ' . ($sectionClass ?? ''));

    $items = $items ?? [
        [
            'icon' => 'fa-tags',
            'title' => 'Best prices',
            'text' => 'We compare parking lots near airports and show you a clear comparison list so you can book with confidence.',
        ],
        [
            'icon' => 'fa-shield',
            'title' => 'Trusted partners',
            'text' => 'The car parking providers we work with are secure, established operators you can rely on before you travel.',
        ],
        [
            'icon' => 'fa-gbp',
            'title' => 'Low price promise',
            'text' => 'We help you find competitive airport parking rates and great value across Meet & Greet, Park & Ride, and onsite options.',
        ],
        [
            'icon' => 'fa-headphones',
            'title' => 'Support',
            'text' => 'Our team is only a phone call or email away whenever you need help with a booking or a change to your trip.',
        ],
    ];
@endphp

<section class="{{ $sectionClass }}">
    <div class="js-why-section__mesh" aria-hidden="true"></div>
    <div class="container">
        <div class="js-section-head{{ $withReveal ? ' js-reveal' : '' }}">
            <span class="js-section-head__badge">{{ $badge }}</span>
            <h2 class="js-section-title">{!! $title !!}</h2>
            <p class="js-section-subtitle">{!! $subtitle !!}</p>
        </div>

        <div class="js-why-grid">
            @foreach($items as $i => $item)
                @php
                    $delay = $i * 80;
                    $index = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
                    $icon = $item['icon'] ?? 'fa-check';
                    $img = $item['image'] ?? null;
                    $itemTitle = $item['title'] ?? '';
                    $itemText = $item['text'] ?? '';
                @endphp
                <article class="js-why-item{{ $withReveal ? ' js-reveal' : '' }}"@if($withReveal) style="--reveal-delay: {{ $delay }}ms"@endif>
                    <div class="js-why-item__icon-wrap">
                        @if($img)
                            <img src="{{ $img }}" alt="" loading="lazy" width="56" height="56">
                        @else
                            <i class="fa {{ $icon }}" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="js-why-item__content">
                        <span class="js-why-item__index">{{ $index }}</span>
                        <h3>{{ $itemTitle }}</h3>
                        <p>{!! $itemText !!}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
