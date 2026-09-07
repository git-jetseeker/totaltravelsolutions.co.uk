@php
    use Illuminate\Support\Str;
    use App\Support\LoungeFacilities;
    use App\Models\Lounges;

    $isBookfhr = isset($company->park_api) && $company->park_api === 'bookfhr';
    $facilitiesList = LoungeFacilities::parse($company->facilities ?? '');
    $amenitiesLine = implode(' · ', array_slice($facilitiesList, 0, 4));
    $loungeDesc = preg_replace('/\s+/', ' ', strip_tags($company->extraInfo ?? ''));
    $loungeLocation = preg_replace('/\s+/', ' ', strip_tags($company->locationInfo ?? ''));
    $loungeImages = Lounges::normalizeImageList(array_merge(
        $company->images ?? [],
        !empty($company->image) ? [$company->image] : [],
        !empty($company->logo) ? [$company->logo] : []
    ));
    $loungeImages = array_values(array_unique(array_filter($loungeImages)));
    $mainLoungeImage = $loungeImages[0] ?? Lounges::formatImageUrl($company->image ?? $company->logo ?? null);
    $hasLoungeImage = $mainLoungeImage !== '' && !str_contains($mainLoungeImage, 'favicon');
    $adultsCount = (int) $request->input('aladults', $request->input('adults', 1));
    $childrenCount = (int) $request->input('alchildren', $request->input('children', 0));
    $modalId = 'loungeModal' . ($company->companyID ?? $company->product_id ?? ($index ?? uniqid()));
    $displayName = $company->displayName ?? $company->name ?? 'Lounge';
    $terminalLabel = (!empty($company->terminal) && $company->terminal !== 'Terminal Information Not Available') ? $company->terminal : '';
    $terminalNums = [];
    if ($terminalLabel !== '' && preg_match_all('/\d+/', $terminalLabel, $terminalMatches)) {
        $terminalNums = array_values(array_unique($terminalMatches[0]));
    }
    $terminalsAttr = implode(',', $terminalNums);
    $quoteNum = (float) ($company->original_price ?? 0);
    $finalNum = (float) ($company->price ?? 0);
    $discountAmount = (float) ($company->discount_applied ?? 0);
    if ($discountAmount <= 0 && $quoteNum > $finalNum) {
        $discountAmount = $quoteNum - $finalNum;
    }
    $discountPct = ($quoteNum > 0 && $discountAmount > 0) ? (int) round(($discountAmount / $quoteNum) * 100) : 0;
    $showDiscount = $discountAmount > 0 && $quoteNum > $finalNum;
    $bPrice = number_format($finalNum, 2, '.', '');
    $originalPrice = number_format($quoteNum, 2, '.', '');
    $discountLabel = 'Discount applied';
    if ($discountPct > 0) {
        $discountLabel .= ' · ' . $discountPct . '% off';
    }
    $savingsLabel = 'Save £' . number_format($discountAmount, 2);
    if ($discountPct > 0) {
        $savingsLabel .= ' (' . $discountPct . '%)';
    }
    $savingsLabel .= ' on lounge access. Was £' . $originalPrice;
    $badgeNames = array_values(array_filter([
        $company->cancellation_label ?? null,
        $terminalLabel !== '' ? $terminalLabel : null,
    ]));
    if (empty($badgeNames)) {
        $badgeNames = ['Airport lounge'];
    }
@endphp

<div class="js-result-card" @if($terminalsAttr !== '') data-terminals="{{ $terminalsAttr }}" @endif data-sort-index="{{ $index ?? 0 }}">
    <article class="js-apb-deal-card js-apb-deal-card--product">
        <header class="js-apb-deal-card__header">
            <h3 class="js-apb-deal-card__title company-name">{{ $displayName }}</h3>
            @if ($terminalLabel !== '')
                <p class="js-apb-deal-card__subtitle">{{ $terminalLabel }}</p>
            @elseif ($loungeLocation !== '')
                <p class="js-apb-deal-card__subtitle">{{ Str::limit($loungeLocation, 80) }}</p>
            @endif
            <div class="js-apb-deal-card__badges">
                @foreach (array_slice($badgeNames, 0, 3) as $badge)
                    <span class="js-apb-deal-card__badge">{{ $badge }}</span>
                @endforeach
            </div>
        </header>

        <div class="js-apb-deal-card__body">
            <div class="js-apb-deal-card__logo">
                <img src="{{ $mainLoungeImage ?: asset('theme/images/lounge-placeholder.svg') }}"
                     alt="{{ $displayName }}"
                     class="js-apb-deal-card__logo-img js-apb-deal-card__photo"
                     onerror="this.onerror=null;this.src='{{ asset('theme/images/lounge-placeholder.svg') }}';">
                @if ($terminalLabel !== '')
                    <span class="js-apb-deal-card__logo-type">{{ $terminalLabel }}</span>
                @endif
            </div>

            <div class="js-apb-deal-card__features">
                <ul class="list-fac js-apb-deal-card__feature-list">
                    @if (!empty($company->openTime) || !empty($company->closeTime))
                        <li>Open {{ $company->openTime ?? '—' }} · Close {{ $company->closeTime ?? '—' }}</li>
                    @endif
                    @if ($loungeLocation !== '')
                        <li>{{ Str::limit($loungeLocation, 100) }}</li>
                    @endif
                    @if ($loungeDesc !== '')
                        <li>{{ Str::limit($loungeDesc, 120) }}</li>
                    @endif
                    @foreach (array_slice($facilitiesList, 0, 4) as $facility)
                        <li>{{ $facility }}</li>
                    @endforeach
                    @if ($amenitiesLine !== '' && count($facilitiesList) === 0)
                        <li>{{ $amenitiesLine }}</li>
                    @endif
                </ul>
            </div>

            <div class="js-apb-deal-card__pricing">
                @if ($showDiscount)
                    <p class="js-apb-deal-card__was">Was £{{ $originalPrice }}</p>
                    <p class="js-apb-deal-card__discount">{{ $discountLabel }}</p>
                @endif
                @php
                    $guestTotalLabel = $adultsCount . ' adult' . ($adultsCount === 1 ? '' : 's');
                    if ($childrenCount > 0) {
                        $guestTotalLabel .= ', ' . $childrenCount . ' child' . ($childrenCount === 1 ? '' : 'ren');
                    }
                @endphp
                <p class="js-apb-deal-card__total-label">Total for {{ $guestTotalLabel }}</p>
                <p class="js-apb-deal-card__price l-price product-card" data-price="{{ $bPrice }}">£{{ $bPrice }}</p>
            </div>
        </div>

        <div class="js-apb-deal-card__actions">
            <button type="button"
                    class="js-apb-deal-card__btn js-apb-deal-card__btn--info moreinfo"
                    data-toggle="modal"
                    data-target="#{{ $modalId }}">
                <i class="fa fa-info-circle" aria-hidden="true"></i> Lounge details
            </button>

            <form method="POST" action="{{ route('addBookingFormLounge') }}" class="js-apb-deal-card__book-form">
                @csrf
                <input type="hidden" name="search_data" value='@json($company)'>
                <input type="hidden" name="company_id" value="{{ $company->lounge_db_id ?? $company->companyID }}">
                <input type="hidden" name="product_code" value="{{ $company->product_id ?? $company->product_code }}">
                <input type="hidden" name="lounge_name" value="{{ $displayName }}">
                <input type="hidden" name="terminal" value="{{ $company->terminal ?? '' }}">
                <input type="hidden" name="logo" value="{{ $company->image ?? '' }}">
                <input type="hidden" name="logobooking" value="0">
                <input type="hidden" name="airport" value="{{ $request->input('airport_id') }}">
                <input type="hidden" name="checkin_date" value="{{ $request->input('checkIn_date', $request->input('checkin_date')) }}">
                <input type="hidden" name="checkin_time" value="{{ $request->input('checkIn_time', $request->input('checkin_time')) }}">
                <input type="hidden" name="adults" value="{{ $adultsCount }}">
                <input type="hidden" name="children" value="{{ $childrenCount }}">
                <input type="hidden" name="infants" value="{{ $request->input('alinfants', $request->input('infants', 0)) }}">
                <input type="hidden" name="promo" value="{{ $promo ?? '' }}">
                <input type="hidden" name="discount_code" value="{{ $promo ?? '' }}">
                <input type="hidden" name="discount_amount" value="{{ $company->discount_applied ?? 0 }}">
                <input type="hidden" name="booking_amount" value="{{ $company->price ?? 0 }}">
                <input type="hidden" name="pl_id" value="{{ $company->option_id ?? '' }}">
                <input type="hidden" name="park_api" value="{{ $company->park_api ?? 'bookfhr' }}">
                <input type="hidden" name="bookfhrSearchId" value="{{ $company->searchId ?? '' }}">
                <input type="hidden" name="bookfhrOptionId" value="{{ $company->option_id ?? '' }}">
                <input type="hidden" name="bookingfor" value="lounge">
                <button type="submit" class="js-apb-deal-card__btn js-apb-deal-card__btn--book select-parking btn btn-info">
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Book Now
                </button>
            </form>
        </div>

        @if ($showDiscount)
            <footer class="js-apb-deal-card__savings">{{ $savingsLabel }}</footer>
        @endif
    </article>
</div>

<div class="modal fade js-lounge-modal" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}_title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content js-lounge-modal__content">
            <div class="modal-body js-lounge-modal__body">
                <button type="button" class="js-lounge-modal__close" data-dismiss="modal" aria-label="Close">&times;</button>

                @php
                    $modalFacilities = LoungeFacilities::parse($company->facilities ?? '');
                    $modalImages = $loungeImages;
                    $modalMain = $modalImages[0] ?? $mainLoungeImage;
                    $modalThumbs = array_slice($modalImages, 0, 6);
                    $modalDesc = trim(strip_tags($company->extraInfo ?? ''));
                    $modalLocation = trim(strip_tags($company->locationInfo ?? ''));
                    $modalHasImage = $modalMain !== '' && !str_contains($modalMain, 'favicon');
                    $modalPlaceholder = asset('theme/images/lounge-placeholder.svg');
                @endphp

                <header class="js-lounge-modal__header">
                    <div class="js-lounge-modal__heading">
                        <h2 class="js-lounge-modal__title" id="{{ $modalId }}_title">{{ $displayName }}</h2>
                        <div class="js-lounge-modal__badges">
                            @if ($terminalLabel !== '')
                                <span class="js-lounge-modal__badge"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $terminalLabel }}</span>
                            @endif
                            @if (!empty($company->cancellation_label))
                                <span class="js-lounge-modal__badge js-lounge-modal__badge--muted">{{ $company->cancellation_label }}</span>
                            @endif
                        </div>
                    </div>
                </header>

                <div class="js-lounge-detail-card">
                    <div class="js-lounge-detail-media">
                        <div class="js-lounge-detail-media__main">
                            @if ($modalHasImage)
                                <img src="{{ $modalMain }}"
                                     alt="{{ $displayName }}"
                                     class="js-lounge-detail-media__img"
                                     data-fallback="{{ $modalPlaceholder }}"
                                     onerror="this.onerror=null;this.src=this.dataset.fallback||'';">
                            @else
                                <div class="js-lounge-detail-media__placeholder" aria-hidden="true">
                                    <i class="fa fa-coffee"></i>
                                    <span>Lounge photo</span>
                                </div>
                            @endif
                        </div>
                        @if (count($modalThumbs) > 1)
                            <div class="js-lounge-detail-thumbs" role="list" aria-label="Lounge photos">
                                @foreach ($modalThumbs as $thumbIndex => $image)
                                    <button type="button"
                                            class="js-lounge-detail-thumb{{ $thumbIndex === 0 ? ' is-active' : '' }}"
                                            data-full="{{ $image }}"
                                            aria-label="View lounge photo {{ $thumbIndex + 1 }}">
                                        <img src="{{ $image }}" alt="" onerror="this.onerror=null;this.parentElement.remove();">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="js-lounge-detail-info">
                        <ul class="js-lounge-detail-meta">
                            @if (!empty($company->openTime) || !empty($company->closeTime))
                                <li class="js-lounge-detail-meta__item">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    <span><strong>Open</strong> {{ $company->openTime ?? '—' }} · <strong>Close</strong> {{ $company->closeTime ?? '—' }}</span>
                                </li>
                            @endif
                            @if ($modalLocation !== '')
                                <li class="js-lounge-detail-meta__item">
                                    <i class="fa fa-location-arrow" aria-hidden="true"></i>
                                    <span>{{ $modalLocation }}</span>
                                </li>
                            @endif
                            @if (!empty($company->childFrom) || !empty($company->childTo))
                                <li class="js-lounge-detail-meta__item">
                                    <i class="fa fa-child" aria-hidden="true"></i>
                                    <span>Child age {{ $company->childFrom ?? '—' }}–{{ $company->childTo ?? '—' }}</span>
                                </li>
                            @endif
                        </ul>

                        @if ($modalDesc !== '')
                            <div class="js-lounge-detail-info__section">
                                <h3 class="js-lounge-detail-info__heading">About this lounge</h3>
                                <div class="js-lounge-detail-info__desc">{{ $modalDesc }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                @if (count($modalFacilities) > 0)
                    <section class="js-lounge-facilities">
                        <h3 class="js-lounge-facilities__heading">Facilities</h3>
                        <ul class="js-lounge-facilities__list">
                            @foreach ($modalFacilities as $facility)
                                <li class="js-lounge-facilities__item">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    <span>{{ $facility }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
