@php
    $facilities = $facilities ?? collect();
    $logo = $logo ?? 0;

    if (session()->get('bk_src') == 'PPC' || session()->get('bk_src') == 'BING') {
        $parking_dis = ($parking_total * 10) / 100;
        $parking_total = $parking_total + $parking_dis;
    }

    $quote_was = $adjusted_price;
    $customer_price = $booking_price;

    if (isset($company->park_api) && $company->park_api == 'bookfhr' && isset($company->new_price)) {
        $quote_was = round((float) $company->new_price, 2);
        $customer_price = $quote_was;
        if ($promo != '') {
            $dis = $dis ?? new \App\Models\discounts();
            $promo_verify = $dis->varifyPromoCode($promo);
            if ($promo_verify == 'Verify') {
                $discount_amount = $dis->getPromoDiscount($promo, $quote_was, $bookingfor, $company->companyID);
                if ($customer_price >= $discount_amount) {
                    $customer_price = $customer_price - $discount_amount;
                }
            }
        }
        $booking_price = $customer_price;
        $adjusted_price = $quote_was;
    }

    $b_price = number_format((float) $booking_price, 2, '.', '');
    $company_price = number_format((float) $adjusted_price, 2, '.', '');
    $quoteNum = (float) $adjusted_price;
    $finalNum = (float) $booking_price;
    $discountPct = ($quoteNum > 0 && $discount_amount > 0) ? (int) round(($discount_amount / $quoteNum) * 100) : 0;
    $perDay = $no_of_days > 0 ? $finalNum / $no_of_days : $finalNum;
    $showWasPrice = $discount_amount > 0 || $request->input('promo') != '' || $request->input('promo2') != '';
    $discountLabel = 'Discount applied';
    if ($discountPct > 0) {
        $discountLabel .= ' · ' . $discountPct . '% off';
    }
    $savingsLabel = 'Save £' . number_format((float) $discount_amount, 2);
    if ($discountPct > 0) {
        $savingsLabel .= ' (' . $discountPct . '%)';
    }
    $savingsLabel .= ' on parking.';
    if ($showWasPrice) {
        $savingsLabel .= ' Quote was £' . $company_price;
    }

    $badgeNames = [];
    if (!empty($company->special_features)) {
        $special_features = array_unique(explode(',', $company->special_features));
        foreach ($companies_special_features as $features) {
            foreach ($special_features as $sfeature) {
                if ($sfeature === $features->name && count($badgeNames) < 3) {
                    $badgeNames[] = $features->name;
                }
            }
        }
    }
    foreach (['Secure site', 'Instant confirmation', $company->parking_type] as $defaultBadge) {
        if (count($badgeNames) >= 3) {
            break;
        }
        if (!in_array($defaultBadge, $badgeNames, true)) {
            $badgeNames[] = $defaultBadge;
        }
    }

    $terminalNums = [];
    $terminalHaystack = trim(($company->name ?? '') . ' ' . ($company->terminal ?? '') . ' ' . ($company->overview ?? ''));
    if ($terminalHaystack !== '' && preg_match_all('/terminal[\s#]*([0-9,\s&\/\-]+)/i', $terminalHaystack, $terminalMatches)) {
        foreach ($terminalMatches[1] as $terminalGroup) {
            if (preg_match_all('/\d+/', $terminalGroup, $nums)) {
                foreach ($nums[0] as $num) {
                    $terminalNums[] = $num;
                }
            }
        }
    }
    $terminalNums = array_values(array_unique($terminalNums));
    $terminalsAttr = implode(',', $terminalNums);
@endphp

<div class="js-result-card" data-parking-type="{{ $company->parking_type }}" @if($terminalsAttr !== '') data-terminals="{{ $terminalsAttr }}" @endif data-sort-index="{{ $index ?? 0 }}">
    <article class="js-apb-deal-card">
        <header class="js-apb-deal-card__header">
            <h3 class="js-apb-deal-card__title company-name">{{ $company->name }}</h3>
            <p class="js-apb-deal-card__subtitle">{{ $company->parking_type }}</p>
            <div class="js-apb-deal-card__badges">
                @foreach ($badgeNames as $badge)
                    <span class="js-apb-deal-card__badge">{{ $badge }}</span>
                @endforeach
            </div>
        </header>

        <div class="js-apb-deal-card__body">
            <div class="js-apb-deal-card__logo">
                @include('partials.results-deal-card-logo')
                <span class="js-apb-deal-card__logo-type">{{ $company->parking_type }}</span>
            </div>

            <div class="js-apb-deal-card__features">
                <ul class="list-fac js-apb-deal-card__feature-list">
                    @if ($facilities != null)
                        @foreach ($facilities as $facility)
                            <li>{!! strip_tags($facility->description) !!}</li>
                        @endforeach
                    @endif
                    @if ($company->parking_type)
                        <li>{{ $company->parking_type }}</li>
                    @endif
                </ul>
            </div>

            <div class="js-apb-deal-card__pricing">
                @if ($showWasPrice && $quoteNum > $finalNum)
                    <p class="js-apb-deal-card__was">Was £{{ $company_price }}</p>
                @endif
                @if ($discount_amount > 0 && $discountPct > 0)
                    <p class="js-apb-deal-card__discount">{{ $discountLabel }}</p>
                @endif
                <p class="js-apb-deal-card__total-label">Total for {{ $no_of_days }} {{ $no_of_days === 1 ? 'day' : 'days' }}</p>
                <p class="js-apb-deal-card__price l-price product-card" data-price="{{ $b_price }}">£{{ $b_price }}</p>
                <p class="js-apb-deal-card__per-day">£{{ number_format($perDay, 2) }} per day</p>
            </div>
        </div>

        <div class="js-apb-deal-card__actions">
            <button type="button" class="js-apb-deal-card__btn js-apb-deal-card__btn--info moreinfo"
                data-toggle="modal" data-target="#exampleModalCenter{{ $company->companyID }}"
                data-id="{{ $company->companyID }}">
                <i class="fa fa-info-circle" aria-hidden="true"></i> More Information
            </button>

            <form id="bookingFrm1" method="post" action="{{ route('addBookingForm') }}" class="js-apb-deal-card__book-form">
                {{ csrf_field() }}
                <input type="hidden" name="company_id" value="{{ $company->companyID }}">
                <input type="hidden" name="product_code" value="{{ $company->product_code }}">
                <input type="hidden" name="parking_type" value="{{ $company->parking_type }}">
                <input type="hidden" name="logo" value="{{ $company->logo }}">
                <input type="hidden" name="parking_name" value="">
                <input type="hidden" name="aphactive" value="{{ @$company->aphactive }}">
                <input type="hidden" name="airport" value="{{ $request->input('airport_id') }}">
                <input type="hidden" name="dropdate" value="{{ $request->input('dropoffdate') }}">
                <input type="hidden" name="pickdate" value="{{ $request->input('departure_date') }}">
                <input type="hidden" name="droptime" value="{{ $request->input('dropoftime') }}">
                <input type="hidden" name="email" value="{{ $request->input('email') }}">
                <input type="hidden" name="picktime" value="{{ $request->input('pickup_time') }}">
                <input type="hidden" name="total_days" value="{{ $no_of_days }}">
                <input type="hidden" name="discount_code" value="{{ $promo }}">
                <input type="hidden" name="discount_amount" value="{{ $discount_amount }}">
                <input type="hidden" name="booking_amount" value="{{ (isset($company->park_api) && $company->park_api == 'bookfhr') ? $parking_total : $booking_price }}">
                <input type="hidden" name="new_price" value="{{ isset($company->park_api) && $company->park_api == 'bookfhr' ? $quote_was : $booking_price }}">
                <input type="hidden" name="booking_fee" value="{{ $booking_fee }}">
                <input type="hidden" name="bookingfor" value="airport_parking">
                <input type="hidden" name="pl_id" value="{{ @$company->pl_id }}">
                <input type="hidden" name="sku" value="">
                <input type="hidden" name="site_codename" value="">
                <input type="hidden" name="speed_park_active" value="">
                <input type="hidden" name="edin_active" value="">
                <input type="hidden" name="edin_search" value="">
                <input type="hidden" name="submitted" value="airport_parking">
                <input type="hidden" name="park_api" value="{{ isset($company->park_api) ? $company->park_api : '' }}">
                <input type="hidden" name="bookfhrSearchId" value="{{ isset($company->searchId) ? $company->searchId : '' }}">
                <input type="hidden" name="bookfhrOptionId" value="{{ isset($company->optionId) ? $company->optionId : '' }}">
                <input type="hidden" name="g_quote" value="{{ isset($company->g_quote) ? $company->g_quote : '' }}">
                <input type="hidden" name="g_token" value="{{ isset($company->g_token) ? $company->g_token : '' }}">
                <input type="hidden" name="src" value="{{ isset($request->src) ? $request->src : 'ORG' }}">
                <input type="hidden" name="logobooking" value="{{ $logo ?? 0 }}">
                <button type="submit" class="js-apb-deal-card__btn js-apb-deal-card__btn--book select-parking btn btn-info">
                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Book Now
                </button>
            </form>
        </div>

        @if ($discount_amount > 0)
            <footer class="js-apb-deal-card__savings">{{ $savingsLabel }}</footer>
        @endif
    </article>
</div>
