{{-- JetSeeker reviews block — no legacy ParkingZone CSS --}}
@php
    $reviewItems = $reviews ?? collect();
@endphp

@if($reviewItems && count($reviewItems) > 0)
<section class="js-reviews-section section-spacing">
    <div class="js-container">
        <div class="js-section-head">
            <span class="js-section-head__badge js-section-head__badge--light">Trusted reviews</span>
            <h2 class="js-section-title">What Our <span class="orangeClr">Customers Say</span></h2>
            <p class="js-section-subtitle">Real feedback from travellers who booked airport parking with Total Travel Solutions.</p>
        </div>

        <div class="js-airport-reviews-grid">
            @foreach ($reviewItems as $review)
                @php
                    $reviewName = trim((string) (is_array($review) ? ($review['username'] ?? 'Customer') : ($review->username ?? 'Customer')));
                    $reviewInitial = mb_strtoupper(mb_substr($reviewName !== '' ? $reviewName : 'C', 0, 1));
                    $reviewText = trim(html_entity_decode(strip_tags((string) (is_array($review) ? ($review['review'] ?? '') : ($review->review ?? ''))), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    $reviewText = preg_replace('/\s+/u', ' ', $reviewText) ?? $reviewText;
                    if (mb_strlen($reviewText) > 220) {
                        $reviewText = mb_substr($reviewText, 0, 217) . '…';
                    }
                @endphp
                <article class="js-airport-review-card">
                    <div class="js-airport-review-card__avatar" aria-hidden="true">{{ $reviewInitial }}</div>
                    <div class="js-airport-review-card__stars" aria-hidden="true">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <p class="js-airport-review-card__text">{{ $reviewText }}</p>
                    <strong class="js-airport-review-card__name">{{ $reviewName }}</strong>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
