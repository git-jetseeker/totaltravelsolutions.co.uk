@php
    $loungeDropdownTimer = [];
    for ($i = 0; $i <= 23; $i++) {
        for ($j = 0; $j <= 45; $j += 15) {
            $loungeDropdownTimer[str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($j, 2, '0', STR_PAD_LEFT)] =
                str_pad($i, 2, '0', STR_PAD_LEFT) . ':' . str_pad($j, 2, '0', STR_PAD_LEFT);
        }
    }

    $loungeVisitTime = old('checkIn_time', request()->checkIn_time ?? request()->checkin_time ?? '09:00');
    $loungeFlightTime = old('flight_time', request()->flight_time);
    if (!$loungeFlightTime) {
        $startParts = explode(':', $loungeVisitTime);
        $endHour = ((int) ($startParts[0] ?? 9) + 4) % 24;
        $endMin = (int) ($startParts[1] ?? 0);
        $loungeFlightTime = sprintf('%02d:%02d', $endHour, $endMin);
    }

    $loungeAdults = (int) old('aladults', request()->aladults ?? request()->adults ?? 1);
    $loungeChildren = (int) old('alchildren', request()->alchildren ?? request()->children ?? 0);
    $loungeInfants = (int) old('alinfants', request()->alinfants ?? 0);
@endphp

<p class="js-panel-hint">Pay-in lounge access before your flight. Enter up to 3 hours before departure.</p>

<form method="post" action="{{ route('searchresult_lounge') }}" id="search_form_lounge" class="js-booking-form">
    @csrf
    <input type="hidden" name="submitted" value="Yes">
    <input type="hidden" name="booking_for" value="airport_lounges">

    <div class="js-booking-row">
        <div class="js-booking-field js-booking-field--airport">
            <label for="lounge_airport_id">Airport</label>
            <div class="form-group">
                <select required name="airport_id" class="js-booking-input js-sync-airport select2me" id="lounge_airport_id">
                    @foreach ($airports as $airport)
                        @if ($airport->id != 12)
                            <option value="{{ $airport->id }}" @if ((string) old('airport_id', request()->airport_id ?? ($selectedAirportId ?? 20)) === (string) $airport->id) selected @endif>{{ $airport->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--date">
            <label for="lounge_checkIn_date">Departure Date</label>
            <div class="form-group form-group-calender">
                <input autocomplete="off" name="checkIn_date" value="{{ old('checkIn_date', request()->checkIn_date ?? request()->checkin_date ?? \Carbon\Carbon::today()->format('d-m-Y')) }}" type="text" id="lounge_checkIn_date" class="js-booking-input cursor-pointer" placeholder="DD-MM-YYYY" readonly required>
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--time">
            <label for="lounge_checkIn_time">Arrival time</label>
            <div class="form-group">
                <select class="js-booking-input select2me" id="lounge_checkIn_time" name="checkIn_time" required>
                    @foreach ($loungeDropdownTimer as $value)
                        <option value="{{ $value }}" @if ($loungeVisitTime === $value) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--time">
            <label for="lounge_flight_time">Flight time</label>
            <div class="form-group">
                <select class="js-booking-input select2me" id="lounge_flight_time" name="flight_time" required>
                    @foreach ($loungeDropdownTimer as $value)
                        <option value="{{ $value }}" @if ($loungeFlightTime === $value) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--guests">
            <label>Guests</label>
            <div class="js-guest-picker" data-guest-picker="lounge">
                <button type="button" class="js-guest-trigger" id="lounge_guest_trigger" aria-expanded="false" aria-haspopup="true">
                    <span class="js-guest-summary" id="lounge_guest_summary">{{ $loungeAdults }} adult{{ $loungeAdults !== 1 ? 's' : '' }}</span>
                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="js-guest-panel" id="lounge_guest_panel">
                    <div class="js-guest-row">
                        <div><strong>Adults</strong><span>12+ years</span></div>
                        <div class="js-stepper">
                            <button type="button" class="js-stepper-btn" data-step="-1" data-target="lounge_aladults" data-min="1" aria-label="Decrease adults">&minus;</button>
                            <span class="js-stepper-value" id="lounge_aladults_display">{{ $loungeAdults }}</span>
                            <button type="button" class="js-stepper-btn" data-step="1" data-target="lounge_aladults" data-max="9" aria-label="Increase adults">+</button>
                        </div>
                    </div>
                    <div class="js-guest-row">
                        <div><strong>Children</strong><span>2–11 years</span></div>
                        <div class="js-stepper">
                            <button type="button" class="js-stepper-btn" data-step="-1" data-target="lounge_alchildren" data-min="0" aria-label="Decrease children">&minus;</button>
                            <span class="js-stepper-value" id="lounge_alchildren_display">{{ $loungeChildren }}</span>
                            <button type="button" class="js-stepper-btn" data-step="1" data-target="lounge_alchildren" data-max="9" aria-label="Increase children">+</button>
                        </div>
                    </div>
                    <div class="js-guest-row">
                        <div><strong>Infants</strong><span>Under 2 years</span></div>
                        <div class="js-stepper">
                            <button type="button" class="js-stepper-btn" data-step="-1" data-target="lounge_alinfants" data-min="0" aria-label="Decrease infants">&minus;</button>
                            <span class="js-stepper-value" id="lounge_alinfants_display">{{ $loungeInfants }}</span>
                            <button type="button" class="js-stepper-btn" data-step="1" data-target="lounge_alinfants" data-max="9" aria-label="Increase infants">+</button>
                        </div>
                    </div>
                    <div class="js-guest-done">
                        <button type="button" id="lounge_guest_done">Done</button>
                    </div>
                </div>
                <input type="hidden" name="aladults" id="lounge_aladults" value="{{ $loungeAdults }}">
                <input type="hidden" name="alchildren" id="lounge_alchildren" value="{{ $loungeChildren }}">
                <input type="hidden" name="alinfants" id="lounge_alinfants" value="{{ $loungeInfants }}">
            </div>
        </div>

        <div class="js-booking-field js-booking-field--promo">
            <label for="lounge_promo">Promo code</label>
            <div class="form-group">
                <input type="text" id="lounge_promo" name="promo" class="js-booking-input" placeholder="Optional" value="{{ old('promo', request()->promo) }}">
            </div>
        </div>
    </div>

    <div class="js-submit-wrap">
        <button type="submit" id="lounge_submit" class="js-booking-submit">Find Lounges</button>
    </div>
</form>
