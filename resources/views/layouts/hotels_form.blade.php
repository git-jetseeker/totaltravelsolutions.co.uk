@php
    $hotelCheckinDefault = old('hotel_checkin_date', request()->hotel_checkin_date ?? \Carbon\Carbon::today()->addDay()->format('d-m-Y'));
    $hotelCheckoutDefault = old('hotel_checkout_date', request()->hotel_checkout_date ?? \Carbon\Carbon::today()->addDays(2)->format('d-m-Y'));
@endphp

<p class="js-panel-hint">Airport hotels near your terminal. Search by airport — we match stays within 10 miles.</p>

<form method="post" action="{{ route('searchresult_hotel') }}" id="search_form_hotel" class="js-booking-form">
    @csrf
    <input type="hidden" name="submitted" value="Yes">
    <input type="hidden" name="booking_for" value="airport_hotels">
    <input type="hidden" name="hotel_checkin_time" id="hotel_checkin_time" value="{{ old('hotel_checkin_time', request()->hotel_checkin_time ?? '14:00') }}">
    <input type="hidden" name="hotel_checkout_time" id="hotel_checkout_time" value="{{ old('hotel_checkout_time', request()->hotel_checkout_time ?? '11:00') }}">
    <input type="hidden" name="hotel_room_type" id="hotel_room_type" value="{{ old('hotel_room_type', request()->hotel_room_type ?? 'Double') }}">
    <input type="hidden" name="hotel_radius" value="{{ old('hotel_radius', request()->hotel_radius ?? 16000) }}">

    <div class="js-booking-row">
    <div class="js-booking-field js-booking-field--airport">
        <label for="hotel_airport_id">Airport</label>
        <div class="form-group">
            <select required name="airport_id" class="js-booking-input js-sync-airport select2me @error('airport_id') border-red @enderror" id="hotel_airport_id">
                <option value="" disabled>Select</option>
                @isset($airports)
                    @foreach ($airports as $airport)
                        @if ($airport->id != 12)
                            <option
                                value="{{ $airport->id }}"
                                @if ((string) old('airport_id', request()->airport_id ?: ($selectedAirportId ?? 20)) === (string) $airport->id) selected @endif
                            >
                                {{ $airport->name }}
                            </option>
                        @endif
                    @endforeach
                @endisset
            </select>
            @error('airport_id')
                <span class="error error-massage">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="js-booking-field js-booking-field--date">
        <label for="hotel_checkin_date">Check-in</label>
        <div class="form-group form-group-calender">
            <input
                autocomplete="off"
                name="hotel_checkin_date"
                value="{{ $hotelCheckinDefault }}"
                type="text"
                id="hotel_checkin_date"
                class="js-booking-input cursor-pointer @error('hotel_checkin_date') border-red @enderror"
                placeholder="DD-MM-YYYY"
                readonly
                required
            >
            <i class="fa fa-calendar" aria-hidden="true"></i>
            @error('hotel_checkin_date')
                <span class="error error-massage">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="js-booking-field js-booking-field--date">
        <label for="hotel_checkout_date">Check-out</label>
        <div class="form-group form-group-calender">
            <input
                autocomplete="off"
                name="hotel_checkout_date"
                value="{{ $hotelCheckoutDefault }}"
                type="text"
                id="hotel_checkout_date"
                class="js-booking-input cursor-pointer @error('hotel_checkout_date') border-red @enderror"
                placeholder="DD-MM-YYYY"
                readonly
                required
            >
            <i class="fa fa-calendar" aria-hidden="true"></i>
            @error('hotel_checkout_date')
                <span class="error error-massage">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="js-booking-field js-booking-field--guests">
        <label for="hotel_guest_trigger">Guests &amp; rooms</label>
        <div class="form-group js-guest-picker hotel-guest-picker">
            <button type="button" class="js-guest-trigger hotel-guest-trigger" id="hotel_guest_trigger" tabindex="0" aria-expanded="false" aria-haspopup="true">
                <span id="hotel_guest_summary">1 adult, 1 room</span>
                <i class="fa fa-chevron-down" aria-hidden="true"></i>
            </button>
            <div class="js-guest-panel hotel-guest-panel" id="hotel_guest_panel">
                <div class="js-guest-row hotel-guest-row">
                    <div><strong>Adults</strong><span>12+ years</span></div>
                    <div class="js-stepper hotel-guest-stepper">
                        <button type="button" class="js-stepper-btn hotel-guest-minus" data-target="hotel_adults" aria-label="Decrease adults">&minus;</button>
                        <span class="js-stepper-value hotel-guest-count" id="hotel_adults_display">1</span>
                        <button type="button" class="js-stepper-btn hotel-guest-plus" data-target="hotel_adults" aria-label="Increase adults">+</button>
                    </div>
                </div>
                <div class="js-guest-row hotel-guest-row">
                    <div><strong>Children</strong><span>2–11 years</span></div>
                    <div class="js-stepper hotel-guest-stepper">
                        <button type="button" class="js-stepper-btn hotel-guest-minus" data-target="hotel_children" aria-label="Decrease children">&minus;</button>
                        <span class="js-stepper-value hotel-guest-count" id="hotel_children_display">0</span>
                        <button type="button" class="js-stepper-btn hotel-guest-plus" data-target="hotel_children" aria-label="Increase children">+</button>
                    </div>
                </div>
                <div class="hotel-child-ages" id="hotel_child_ages" style="display:none;">
                    <p class="hotel-child-ages__hint">Child ages (required for availability)</p>
                    <div class="hotel-child-ages__list" id="hotel_child_ages_list"></div>
                </div>
                <div class="js-guest-row hotel-guest-row">
                    <div><strong>Infants</strong><span>Under 2 years</span></div>
                    <div class="js-stepper hotel-guest-stepper">
                        <button type="button" class="js-stepper-btn hotel-guest-minus" data-target="hotel_infants" aria-label="Decrease infants">&minus;</button>
                        <span class="js-stepper-value hotel-guest-count" id="hotel_infants_display">0</span>
                        <button type="button" class="js-stepper-btn hotel-guest-plus" data-target="hotel_infants" aria-label="Increase infants">+</button>
                    </div>
                </div>
                <div class="js-guest-row hotel-guest-row">
                    <div><strong>Rooms</strong><span>Needed for this stay</span></div>
                    <div class="js-stepper hotel-guest-stepper">
                        <button type="button" class="js-stepper-btn hotel-guest-minus" data-target="hotel_rooms" aria-label="Decrease rooms">&minus;</button>
                        <span class="js-stepper-value hotel-guest-count" id="hotel_rooms_display">1</span>
                        <button type="button" class="js-stepper-btn hotel-guest-plus" data-target="hotel_rooms" aria-label="Increase rooms">+</button>
                    </div>
                </div>
                <div class="js-guest-done hotel-guest-done">
                    <button type="button" id="hotel_guest_done">Done</button>
                </div>
            </div>
            <input type="hidden" name="hotel_adults" id="hotel_adults" value="{{ old('hotel_adults', request()->hotel_adults ?? 1) }}">
            <input type="hidden" name="hotel_children" id="hotel_children" value="{{ old('hotel_children', request()->hotel_children ?? 0) }}">
            <input type="hidden" name="hotel_infants" id="hotel_infants" value="{{ old('hotel_infants', request()->hotel_infants ?? 0) }}">
            <input type="hidden" name="hotel_rooms" id="hotel_rooms" value="{{ old('hotel_rooms', request()->hotel_rooms ?? 1) }}">
        </div>
    </div>

    <div class="js-booking-field js-booking-field--promo">
        <label for="hotel_promo">Promo code</label>
        <div class="form-group">
            <input type="text" id="hotel_promo" name="hotel_promo" class="js-booking-input" placeholder="Optional" value="{{ old('hotel_promo', request()->hotel_promo) }}">
        </div>
    </div>
    </div>

    <div class="js-submit-wrap">
        <button type="submit" id="hotel_submit" class="js-booking-submit">Find Hotels</button>
    </div>
</form>

<script>
{{-- Init runs from footer after jQuery UI (homepage reloads jQuery in footer). --}}
window.initJsHotelSearchForm = function ($) {
    if (!$ || !$('#search_form_hotel').length) {
        return;
    }

    var guestLimits = {
        hotel_adults: { min: 1, max: 9 },
        hotel_children: { min: 0, max: 8 },
        hotel_infants: { min: 0, max: 4 },
        hotel_rooms: { min: 1, max: 5 }
    };

    var presetChildAges = @json(array_values((array) old('children_ages', request()->input('children_ages', []))));

    function getGuestValue(field) {
        var parsed = parseInt($('#' + field).val(), 10);
        if (isNaN(parsed)) {
            return guestLimits[field].min;
        }
        return parsed;
    }

    function setGuestValue(field, value) {
        var limits = guestLimits[field];
        var next = Math.min(limits.max, Math.max(limits.min, value));
        $('#' + field).val(next);
        $('#' + field + '_display').text(next);
        updateGuestSummary();
        updateGuestButtons();
        syncHotelApiFields();
        if (field === 'hotel_children') {
            renderChildAges();
        }
    }

    function syncHotelApiFields() {
        var occupants = getGuestValue('hotel_adults') + getGuestValue('hotel_children');
        var roomType = 'Double';
        if (occupants <= 1) {
            roomType = 'Single';
        } else if (occupants === 2) {
            roomType = 'Double';
        } else if (occupants === 3) {
            roomType = 'Triple';
        } else {
            roomType = 'Family';
        }
        $('#hotel_room_type').val(roomType);
    }

    function updateGuestSummary() {
        var adults = getGuestValue('hotel_adults');
        var children = getGuestValue('hotel_children');
        var infants = getGuestValue('hotel_infants');
        var rooms = getGuestValue('hotel_rooms');
        var parts = [];

        parts.push(adults + ' adult' + (adults === 1 ? '' : 's'));
        if (children > 0) {
            parts.push(children + ' child' + (children === 1 ? '' : 'ren'));
        }
        if (infants > 0) {
            parts.push(infants + ' infant' + (infants === 1 ? '' : 's'));
        }
        parts.push(rooms + ' room' + (rooms === 1 ? '' : 's'));

        $('#hotel_guest_summary').text(parts.join(', '));
    }

    function updateGuestButtons() {
        $.each(guestLimits, function (field, limits) {
            var value = getGuestValue(field);
            $('.hotel-guest-minus[data-target="' + field + '"]').prop('disabled', value <= limits.min);
            $('.hotel-guest-plus[data-target="' + field + '"]').prop('disabled', value >= limits.max);
        });
    }

    function currentChildAges() {
        var ages = [];
        $('#hotel_child_ages_list select').each(function () {
            ages.push(parseInt($(this).val(), 10) || 8);
        });
        return ages;
    }

    function renderChildAges() {
        var children = getGuestValue('hotel_children');
        var $wrap = $('#hotel_child_ages');
        var $list = $('#hotel_child_ages_list');
        var existing = currentChildAges();
        if (!existing.length && presetChildAges.length) {
            existing = presetChildAges.map(function (age) {
                return parseInt(age, 10) || 8;
            });
        }

        $list.empty();

        if (children < 1) {
            $wrap.hide();
            return;
        }

        for (var i = 0; i < children; i++) {
            var selected = existing[i] || 8;
            var options = '';
            for (var age = 1; age <= 17; age++) {
                options += '<option value="' + age + '"' + (age === selected ? ' selected' : '') + '>' + age + '</option>';
            }
            $list.append(
                '<div class="hotel-child-ages__item">' +
                    '<label for="hotel_child_age_' + i + '">Child ' + (i + 1) + ' age</label>' +
                    '<select name="children_ages[]" id="hotel_child_age_' + i + '">' + options + '</select>' +
                '</div>'
            );
        }

        $wrap.show();
    }

    function toggleGuestPanel(open) {
        var $trigger = $('#hotel_guest_trigger');
        var $panel = $('#hotel_guest_panel');
        var shouldOpen = typeof open === 'boolean' ? open : !$panel.hasClass('is-open');

        $panel.toggleClass('is-open', shouldOpen);
        $trigger.toggleClass('is-open', shouldOpen);
        $trigger.attr('aria-expanded', shouldOpen ? 'true' : 'false');
    }

    function showHotelError($field) {
        $field.addClass('border-red');
        if (!$field.siblings('.error-massage').length) {
            $field.after('<span class="error error-massage">Required</span>');
        }
    }

    function clearHotelErrors() {
        $('#search_form_hotel .border-red').removeClass('border-red');
        $('#search_form_hotel .error-massage').remove();
    }

    function validateHotelForm() {
        clearHotelErrors();
        var valid = true;

        ['#hotel_airport_id', '#hotel_checkin_date', '#hotel_checkout_date'].forEach(function (selector) {
            var $field = $(selector);
            if ($.trim($field.val()) === '') {
                showHotelError($field);
                valid = false;
            }
        });

        if (getGuestValue('hotel_children') > 0 && $('#hotel_child_ages_list select').length === 0) {
            renderChildAges();
        }

        return valid;
    }

    setGuestValue('hotel_adults', getGuestValue('hotel_adults'));
    setGuestValue('hotel_children', getGuestValue('hotel_children'));
    setGuestValue('hotel_infants', getGuestValue('hotel_infants'));
    setGuestValue('hotel_rooms', getGuestValue('hotel_rooms'));
    renderChildAges();

    $('#hotel_guest_trigger').off('click.hotelGuests').on('click.hotelGuests', function (e) {
        e.preventDefault();
        toggleGuestPanel();
    });

    $('#hotel_guest_done').off('click.hotelGuests').on('click.hotelGuests', function () {
        toggleGuestPanel(false);
    });

    $('.hotel-guest-minus, .hotel-guest-plus').off('click.hotelGuests').on('click.hotelGuests', function () {
        var field = $(this).data('target');
        var step = $(this).hasClass('hotel-guest-plus') ? 1 : -1;
        setGuestValue(field, getGuestValue(field) + step);
    });

    $(document).off('click.hotelGuestsOutside').on('click.hotelGuestsOutside', function (e) {
        if (!$(e.target).closest('.hotel-guest-picker').length) {
            toggleGuestPanel(false);
        }
    });

    syncHotelApiFields();

    $('#search_form_hotel').off('submit.hotelForm').on('submit.hotelForm', function (e) {
        syncHotelApiFields();
        if (!validateHotelForm()) {
            e.preventDefault();
            return false;
        }

        $(this).find('#hotel_submit').text('Please Wait..');
    });

    $('#search_form_hotel input, #search_form_hotel select').off('change.hotelForm input.hotelForm').on('change.hotelForm input.hotelForm', function () {
        if ($.trim($(this).val()) !== '') {
            $(this).removeClass('border-red').siblings('.error-massage').remove();
        }
    });
};
</script>
