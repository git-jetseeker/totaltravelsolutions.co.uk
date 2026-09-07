@php
    $req = $request ?? request();
    $resultsAirport = \App\Models\airport::find($req->input('airport_id'));
    $resultsAirportName = $resultsAirport->name ?? 'Airport';
    $checkinDisplay = $req->input('hotel_checkin_date');
    $checkoutDisplay = $req->input('hotel_checkout_date');
    $checkinTime = $req->input('hotel_checkin_time', '14:00');
    $checkoutTime = $req->input('hotel_checkout_time', '11:00');
    $adults = (int) $req->input('hotel_adults', 1);
    $children = (int) $req->input('hotel_children', 0);
    $infants = (int) $req->input('hotel_infants', 0);
    $rooms = (int) $req->input('hotel_rooms', 1);
    $nights = 1;

    try {
        $inNorm = str_replace('-', '/', (string) $checkinDisplay);
        $outNorm = str_replace('-', '/', (string) $checkoutDisplay);
        $inDate = \Carbon\Carbon::createFromFormat('d/m/Y', $inNorm);
        $outDate = \Carbon\Carbon::createFromFormat('d/m/Y', $outNorm);
        $nights = max(1, (int) $inDate->diffInDays($outDate));
    } catch (\Exception $e) {
        $nights = 1;
    }

    $guestBits = [$adults . ' adult(s)'];
    if ($children > 0) {
        $guestBits[] = $children . ' child(ren)';
    }
    if ($infants > 0) {
        $guestBits[] = $infants . ' infant(s)';
    }
@endphp

<section class="js-results-title-bar">
    <div class="container js-results-title-bar__inner">
        <div class="js-results-title-bar__copy">
            <h1 class="js-results-title-bar__heading">{{ $resultsAirportName }} Airport Hotels</h1>
            <p class="js-results-title-bar__tag">Competitive Prices</p>
        </div>
    </div>
</section>

<section class="js-results-amend-section">
    <div class="container js-results-content">
        <div class="js-results-amend-bar">
            <div class="js-results-amend-bar__summary">
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Stay:</span>
                    <span class="js-results-summary-value">{{ $nights }} {{ $nights === 1 ? 'night' : 'nights' }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Airport:</span>
                    <span class="js-results-summary-value">{{ $resultsAirportName }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Check-in:</span>
                    <span class="js-results-summary-value">{{ $checkinDisplay }} at {{ $checkinTime }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Check-out:</span>
                    <span class="js-results-summary-value">{{ $checkoutDisplay }} at {{ $checkoutTime }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Guests:</span>
                    <span class="js-results-summary-value">{{ implode(', ', $guestBits) }} · {{ $rooms }} room{{ $rooms === 1 ? '' : 's' }}</span>
                </span>
            </div>

            <button type="button" class="js-results-meta__edit" id="js-results-meta-edit" aria-expanded="false" aria-controls="js-results-amend-panel">
                Edit
            </button>
        </div>
    </div>
</section>

<section class="js-results-amend-panel" id="js-results-amend-panel" aria-hidden="true" hidden>
    <div class="container js-results-content">
        <div class="js-booking-card js-booking-card--results-amend">
            <div class="js-booking-widget-body js-booking-widget-body--results-amend">
                @include('layouts.hotels_form', [
                    'selectedAirportId' => $req->input('airport_id') ?? 20,
                    'airports' => $airports ?? collect(),
                ])
            </div>
        </div>
    </div>
</section>

<div class="js-results-body">
    <div class="container js-results-content">
        <div class="js-results-layout">
            @include('partials.results-filters-hotel')

            <main class="js-results-main">
                <div id="ajax_search_results">
                    @if (!empty($preloadedResults))
                        {!! $preloadedResults !!}
                    @else
                        <div class="js-results-loader spiner_container" aria-live="polite" aria-busy="true">
                            <div class="js-results-loader__card popup1">
                                <p class="js-results-loader__brand">Total Travel Solutions</p>
                                <h2 class="js-results-loader__heading">Finding the best hotels</h2>
                                <p class="js-results-loader__airport">{{ $resultsAirportName }} Airport</p>
                                <ul class="js-results-loader__steps list list-unstyled">
                                    <li class="li-css"><span><i class="fa fa-check-circle icon1" aria-hidden="true"></i> Contacting suppliers</span></li>
                                    <li class="li-css"><span><i class="fa fa-check-circle icon2" aria-hidden="true"></i> Checking availability</span></li>
                                    <li class="li-css"><span><i class="fa fa-check-circle icon3" aria-hidden="true"></i> Finding the best prices</span></li>
                                    <li class="li-css"><span><i class="fa fa-check-circle icon4" aria-hidden="true"></i> Applying quality score</span></li>
                                </ul>
                                <p class="js-results-loader__thanks">Thank you for your patience</p>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</div>
