@php
    $selectedAirportId = $selectedAirportId ?? old('airport_id', request()->airport_id ?? 20);
    $discount = $discount ?? 0;
    $dropoffDefault = $dropoffDefault ?? \Carbon\Carbon::today()->format('d-m-Y');
    $pickupDefault = $pickupDefault ?? \Carbon\Carbon::today()->addDays(3)->format('d-m-Y');
    $selectedDropoffTime = $selectedDropoffTime ?? request()->dropoftime ?? '09:00';
    $selectedPickupTime = $selectedPickupTime ?? request()->pickup_time ?? '09:00';
    $searchEmail = $searchEmail ?? request()->email;

    if (empty($airports)) {
        $airports = \App\Models\airport::all()->where('status', 'Yes');
    }

    if (!isset($dropdown_timer)) {
        $dropdown_timer = [];
        for ($i = 0; $i <= 23; $i++) {
            for ($j = 0; $j <= 45; $j += 15) {
                $dropdown_timer[str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($j, 2, '0', STR_PAD_LEFT)] =
                    str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($j, 2, '0', STR_PAD_LEFT);
            }
        }
    }

    $bookingCardId = $bookingCardId ?? 'home_search_form';
    $bookingCardClass = trim('js-booking-card ' . ($bookingCardClass ?? ''));
@endphp

@include('partials.booking-widget-setup', [
    'skipRefTracking' => $skipRefTracking ?? false,
])

<div id="{{ $bookingCardId }}" class="{{ $bookingCardClass }}">
    <div class="js-booking-widget" data-js-booking-widget data-active="parking">
        <div class="js-product-tabs" role="tablist" aria-label="Search parking, lounges, hotels or car hire">
            <button type="button" class="js-product-tab is-active" role="tab" id="js-tab-btn-parking" aria-controls="js-panel-parking" aria-selected="true" data-product="parking">
                <i class="fa fa-car" aria-hidden="true"></i>
                <span>Parking</span>
            </button>
            <button type="button" class="js-product-tab" role="tab" id="js-tab-btn-lounges" aria-controls="js-panel-lounges" aria-selected="false" data-product="lounges">
                <i class="fa fa-coffee" aria-hidden="true"></i>
                <span>Lounges</span>
                <span class="js-soon-badge">Coming Soon</span>
            </button>
            <button type="button" class="js-product-tab" role="tab" id="js-tab-btn-hotels" aria-controls="js-panel-hotels" aria-selected="false" data-product="hotels">
                <i class="fa fa-bed" aria-hidden="true"></i>
                <span>Hotels</span>
                <span class="js-soon-badge">Coming Soon</span>
            </button>
            <button type="button" class="js-product-tab" role="tab" id="js-tab-btn-carhire" aria-controls="js-panel-carhire" aria-selected="false" data-product="carhire">
                <i class="fa fa-cab" aria-hidden="true"></i>
                <span>Car Hire</span>
                <span class="js-soon-badge">Coming Soon</span>
            </button>
        </div>

        <div class="js-booking-widget-body">
            {{-- Parking --}}
            <div class="js-product-panel is-active" id="js-panel-parking" role="tabpanel" aria-labelledby="js-tab-btn-parking" data-panel="parking">
                <p class="js-panel-hint">Compare meet &amp; greet, park &amp; ride and on-site parking.</p>
                <form method="get" action="{{ route('searchresult') }}" id="search_form_1" class="js-booking-form">
                    @if (!empty($searchEmail))
                        <input type="hidden" name="email" value="{{ $searchEmail }}">
                    @endif
                    <div class="js-booking-row">
                        <div class="js-booking-field js-booking-field--airport">
                            <label for="airports">Airport</label>
                            <div class="form-group">
                                <select required name="airport_id" class="js-booking-input js-sync-airport select2me" id="airports">
                                    @foreach ($airports as $airport)
                                        @if ($airport->id != 12)
                                            <option @if ((string) $airport->id === (string) $selectedAirportId) selected @endif value="{{ $airport->id }}">{{ $airport->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="js-booking-field js-booking-field--date">
                            <label for="startDate">Drop-off date</label>
                            <div class="form-group form-group-calender">
                                <input autocomplete="off" name="dropoffdate" value="{{ $dropoffDefault }}" type="text" id="startDate" class="js-booking-input cursor-pointer" placeholder="DD-MM-YYYY" readonly required>
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="js-booking-field js-booking-field--time">
                            <label for="dropoftime">Time</label>
                            <div class="form-group">
                                <select class="js-booking-input select2me" id="dropoftime" name="dropoftime">
                                    @foreach ($dropdown_timer as $value)
                                        <option value="{{ $value }}" @if ($value == $selectedDropoffTime) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="js-booking-field js-booking-field--date">
                            <label for="endDate">Return date</label>
                            <div class="form-group form-group-calender">
                                <input type="text" readonly autocomplete="off" value="{{ $pickupDefault }}" name="departure_date" id="endDate" class="js-booking-input cursor-pointer" placeholder="DD-MM-YYYY" required>
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="js-booking-field js-booking-field--time">
                            <label for="pickup_time">Time</label>
                            <div class="form-group">
                                <select class="js-booking-input select2me" id="pickup_time" name="pickup_time">
                                    @foreach ($dropdown_timer as $value)
                                        <option value="{{ $value }}" @if ($value == $selectedPickupTime) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($discount)
                            <div class="alert alert-success" style="flex:1 1 100%; margin: 0 8px;">Discount Applied.</div>
                        @else
                            <div class="js-booking-field js-booking-field--promo">
                                <label for="promo_code">Promo code</label>
                                <div class="form-group">
                                    <input type="text" id="promo_code" name="promo" class="js-booking-input" placeholder="Optional" value="{{ request()->get('promo', 'JS-Og-05') }}">
                                    <input type="hidden" name="submitted" value="Yes">
                                    <input type="hidden" name="booking_for" value="airport_parking">
                                </div>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" value="{{ request()->get('promo') != null ? 'EM' : 'ORG' }}" name="src">
                    <div class="js-submit-wrap">
                        <button type="submit" class="js-booking-submit">Find Parking</button>
                    </div>
                </form>
            </div>

            {{-- Lounges (coming soon) --}}
            <div class="js-product-panel" id="js-panel-lounges" role="tabpanel" aria-labelledby="js-tab-btn-lounges" data-panel="lounges" hidden>
                <div class="js-carhire-soon">
                    <span class="js-carhire-soon__badge">Coming Soon</span>
                    <h3 class="js-carhire-soon__title">Airport lounges are on the way</h3>
                    <p class="js-carhire-soon__text">We're getting live lounge access ready. Check back shortly to search, compare and book airport lounges.</p>
                </div>
            </div>

            {{-- Hotels (coming soon) --}}
            <div class="js-product-panel" id="js-panel-hotels" role="tabpanel" aria-labelledby="js-tab-btn-hotels" data-panel="hotels" hidden>
                <div class="js-carhire-soon">
                    <span class="js-carhire-soon__badge">Coming Soon</span>
                    <h3 class="js-carhire-soon__title">Airport hotels are on the way</h3>
                    <p class="js-carhire-soon__text">We're getting live hotel rates ready. Check back shortly to search, compare and book airport hotels.</p>
                </div>
            </div>

            {{-- Car Hire (coming soon) --}}
            <div class="js-product-panel" id="js-panel-carhire" role="tabpanel" aria-labelledby="js-tab-btn-carhire" data-panel="carhire" hidden>
                @include('layouts.carhire_form')
            </div>
        </div>
    </div>
</div>
