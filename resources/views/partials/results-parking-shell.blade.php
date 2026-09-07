@php
    $resultsAirport = \App\Models\airport::find(request()->airport_id);
    $resultsAirportName = $resultsAirport->name ?? 'Airport';
    $dropoffDisplay = request()->dropoffdate;
    $returnDisplay = request()->departure_date;
    $dropoffTime = request()->dropoftime ?? '09:00';
    $returnTime = request()->pickup_time ?? '09:00';
    $durationDays = 1;

    try {
        $dropNorm = str_replace('-', '/', (string) $dropoffDisplay);
        $returnNorm = str_replace('-', '/', (string) $returnDisplay);
        $dropDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dropNorm);
        $returnDate = \Carbon\Carbon::createFromFormat('d/m/Y', $returnNorm);
        $durationDays = max(1, (int) $dropDate->diffInDays($returnDate));
    } catch (\Exception $e) {
        $durationDays = 1;
    }

    $widgetDropoff = str_replace('/', '-', (string) $dropoffDisplay);
    $widgetReturn = str_replace('/', '-', (string) $returnDisplay);
@endphp

{{-- Title bar: dark charcoal + centered airport name & competitive prices (APB-style) --}}
<section class="js-results-title-bar">
    <div class="container js-results-title-bar__inner">
        <div class="js-results-title-bar__copy">
            <h1 class="js-results-title-bar__heading">{{ $resultsAirportName }} Airport Parking</h1>
            <p class="js-results-title-bar__tag">Competitive Prices</p>
        </div>
    </div>
</section>

{{-- Unified amend search bar — full container width --}}
<section class="js-results-amend-section">
    <div class="container js-results-content">
        <div class="js-results-amend-bar">
            <div class="js-results-amend-bar__summary">
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Duration:</span>
                    <span class="js-results-summary-value">{{ $durationDays }} {{ $durationDays === 1 ? 'day' : 'days' }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Airport:</span>
                    <span class="js-results-summary-value">{{ $resultsAirportName }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Departure:</span>
                    <span class="js-results-summary-value">{{ $dropoffDisplay }} at {{ $dropoffTime }}</span>
                </span>
                <span class="js-results-summary-item">
                    <span class="js-results-summary-label">Return:</span>
                    <span class="js-results-summary-value">{{ $returnDisplay }} at {{ $returnTime }}</span>
                </span>
            </div>

            <button type="button" class="js-results-meta__edit" id="js-results-meta-edit" aria-expanded="false" aria-controls="js-results-amend-panel">
                Edit
            </button>
        </div>
    </div>
</section>

{{-- Collapsible amend search / booking widget --}}
<section class="js-results-amend-panel" id="js-results-amend-panel" aria-hidden="true" hidden>
    <div class="container js-results-content">
        @include('partials.booking-widget', [
            'selectedAirportId' => request()->airport_id ?? 20,
            'dropoffDefault' => $widgetDropoff,
            'pickupDefault' => $widgetReturn,
            'selectedDropoffTime' => $dropoffTime,
            'selectedPickupTime' => $returnTime,
            'bookingCardId' => 'results_amend_widget',
            'bookingCardClass' => 'js-booking-card--results-amend',
        ])
    </div>
</section>

{{-- Filters (left) + deals (right) — centered, same width as amend bar --}}
<div class="js-results-body">
    <div class="container js-results-content">
        <div class="js-results-layout">
        @include('partials.results-filters-apb')

        <main class="js-results-main">
            <div id="ajax_search_results">
                <div class="js-results-loader spiner_container" aria-live="polite" aria-busy="true">
                    <div class="js-results-loader__card popup1">
                        <p class="js-results-loader__brand">Total Travel Solutions</p>
                        <h2 class="js-results-loader__heading">Finding the best parking</h2>
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
        </div>{{-- /.js-results-layout --}}
    </div>
</div>
