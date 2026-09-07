@php
    $resultsAirport = \App\Models\airport::find(request()->airport_id);
    $resultsAirportName = $resultsAirport->name ?? 'Airport';
    $visitDate = request()->input('checkIn_date', request()->input('checkin_date'));
    $visitTime = request()->input('checkIn_time', request()->input('checkin_time', '09:00'));
    $flightTime = request()->input('flight_time', '13:00');
    $adults = (int) request()->input('aladults', request()->input('adults', 1));
    $children = (int) request()->input('alchildren', request()->input('children', 0));
    $infants = (int) request()->input('alinfants', request()->input('infants', 0));
    $guestSummary = $adults . ' adult' . ($adults === 1 ? '' : 's');
    if ($children > 0) {
        $guestSummary .= ', ' . $children . ' child' . ($children === 1 ? '' : 'ren');
    }
    if ($infants > 0) {
        $guestSummary .= ', ' . $infants . ' infant' . ($infants === 1 ? '' : 's');
    }
    $widgetVisitDate = str_replace('/', '-', (string) $visitDate);
@endphp

<section class="js-results-title-bar">
    <div class="container js-results-title-bar__inner">
        <div class="js-results-title-bar__copy">
            <h1 class="js-results-title-bar__heading">{{ $resultsAirportName }} Airport Lounges</h1>
            <p class="js-results-title-bar__tag">Competitive Prices</p>
        </div>
    </div>
</section>

<section class="js-results-amend-section">
    <div class="container js-results-content">
        <div class="js-results-amend-bar">
            <div class="js-results-amend-bar__summary">
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Airport:</span>
                    <span class="js-results-summary-value">{{ $resultsAirportName }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Visit:</span>
                    <span class="js-results-summary-value">{{ $visitDate }} at {{ $visitTime }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Flight:</span>
                    <span class="js-results-summary-value">{{ $flightTime }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Guests:</span>
                    <span class="js-results-summary-value">{{ $guestSummary }}</span>
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
                @include('layouts.lounges_form', [
                    'selectedAirportId' => request()->airport_id ?? 20,
                    'airports' => $airports ?? collect(),
                ])
            </div>
        </div>
    </div>
</section>

<div class="js-results-body">
    <div class="container js-results-content">
        <div class="js-results-layout">
            @include('partials.results-filters-lounge')

            <main class="js-results-main">
                <div id="ajax_search_results">
                    <div class="js-results-loader spiner_container" aria-live="polite" aria-busy="true">
                        <div class="js-results-loader__card popup1">
                            <p class="js-results-loader__brand">Total Travel Solutions</p>
                            <h2 class="js-results-loader__heading">Finding the best lounges</h2>
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
                </div>
            </main>
        </div>
    </div>
</div>
