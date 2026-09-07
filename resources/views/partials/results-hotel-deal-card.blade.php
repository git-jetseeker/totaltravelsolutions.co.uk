@php
    use Illuminate\Support\Str;

    $gallery = \App\Models\Hotel::normalizeImageList($company->images ?? []);
    if (empty($gallery) && !empty($company->image)) {
        $gallery = \App\Models\Hotel::normalizeImageList([$company->image]);
    }
    $mainImage = $gallery[0] ?? (\App\Models\Hotel::formatImageUrl($company->image ?? null) ?: asset('favicon.ico'));
    $roomCount = (int) ($company->room_type_count ?? count($company->options ?? []));
    $nights = (int) ($company->nights ?? ($stayNights ?? 1));
    $stars = (int) ($company->starRating ?? 0);
    $boardTypes = array_values(array_filter($company->board_types ?? []));
    $cancelLabel = $company->cancellation_label ?? null;
    $address = preg_replace('/\s+/', ' ', strip_tags($company->address ?? ''));
    $description = preg_replace('/\s+/', ' ', strip_tags($company->description ?? ''));
    $amenities = array_slice($company->amenities ?? [], 0, 4);
    $bPrice = number_format((float) ($company->price ?? 0), 2, '.', '');
    $quoteNum = (float) ($company->original_price ?? 0);
    $finalNum = (float) ($company->price ?? 0);
    $discountAmount = (float) ($company->discount_applied ?? 0);
    if ($discountAmount <= 0 && $quoteNum > $finalNum) {
        $discountAmount = $quoteNum - $finalNum;
    }
    $discountPct = ($quoteNum > 0 && $discountAmount > 0) ? (int) round(($discountAmount / $quoteNum) * 100) : 0;
    $showDiscount = $discountAmount > 0 && $quoteNum > $finalNum;
    $originalPrice = number_format($quoteNum, 2, '.', '');
    $discountLabel = 'Discount applied';
    if ($discountPct > 0) {
        $discountLabel .= ' · ' . $discountPct . '% off';
    }
    $savingsLabel = 'Save £' . number_format($discountAmount, 2);
    if ($discountPct > 0) {
        $savingsLabel .= ' (' . $discountPct . '%)';
    }
    $savingsLabel .= ' on your stay. Was £' . $originalPrice;
    $badgeNames = array_values(array_filter(array_merge(
        [$cancelLabel],
        array_slice($boardTypes, 0, 2)
    )));
    if (empty($badgeNames) && $stars > 0) {
        $badgeNames = [$stars . '-star hotel'];
    }
@endphp

<div class="js-result-card" data-star-rating="{{ $stars }}" data-sort-index="{{ $index ?? 0 }}">
    <article class="js-apb-deal-card js-apb-deal-card--product">
        <header class="js-apb-deal-card__header">
            <h3 class="js-apb-deal-card__title company-name">{{ $company->name }}</h3>
            @if ($stars > 0)
                <p class="js-apb-deal-card__subtitle">{{ $stars }}-star hotel</p>
            @elseif ($address !== '')
                <p class="js-apb-deal-card__subtitle">{{ Str::limit($address, 80) }}</p>
            @endif
            <div class="js-apb-deal-card__badges">
                @foreach (array_slice($badgeNames, 0, 3) as $badge)
                    <span class="js-apb-deal-card__badge">{{ $badge }}</span>
                @endforeach
            </div>
        </header>

        <div class="js-apb-deal-card__body">
            <div class="js-apb-deal-card__logo">
                <img src="{{ $mainImage }}"
                     alt="{{ $company->name ?? 'Hotel' }}"
                     class="js-apb-deal-card__logo-img js-apb-deal-card__photo"
                     loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';">
                @if ($stars > 0)
                    <span class="js-apb-deal-card__logo-type">{{ $stars }} star</span>
                @endif
            </div>

            <div class="js-apb-deal-card__features">
                <ul class="list-fac js-apb-deal-card__feature-list">
                    @if ($address !== '')
                        <li>{{ Str::limit($address, 100) }}</li>
                    @endif
                    <li>Check-in {{ $company->checkIn ?? '15:00' }} · Check-out {{ $company->checkOut ?? '11:00' }}</li>
                    @if ($description !== '')
                        <li>{{ Str::limit($description, 120) }}</li>
                    @endif
                    @foreach ($amenities as $amenity)
                        <li>{{ $amenity }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="js-apb-deal-card__pricing">
                @if ($showDiscount)
                    <p class="js-apb-deal-card__was">Was £{{ $originalPrice }}</p>
                    <p class="js-apb-deal-card__discount">{{ $discountLabel }}</p>
                @endif
                <p class="js-apb-deal-card__total-label">From · {{ $nights }} night{{ $nights === 1 ? '' : 's' }}</p>
                <p class="js-apb-deal-card__price l-price product-card" data-price="{{ $bPrice }}">£{{ $bPrice }}</p>
                <p class="js-apb-deal-card__per-day">{{ $roomCount }} room type{{ $roomCount === 1 ? '' : 's' }}</p>
                <a href="{{ route('hotel.detail', ['productId' => $company->product_id, 'searchId' => $searchId ?? $company->searchId ?? null]) }}"
                   class="js-apb-deal-card__btn js-apb-deal-card__btn--book js-apb-deal-card__btn--pricing select-parking btn btn-info">
                    <i class="fa fa-bed" aria-hidden="true"></i> View rooms
                </a>
            </div>
        </div>

        @if ($showDiscount)
            <footer class="js-apb-deal-card__savings">{{ $savingsLabel }}</footer>
        @endif
    </article>
</div>
