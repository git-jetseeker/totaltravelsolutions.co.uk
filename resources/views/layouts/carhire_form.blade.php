@if (!carhire_enabled())
    @include('layouts.carhire_coming_soon')
@else
@php
    $carhireSearch = $search ?? session('carhire_search', []);
    $defaultPickupDate = old('pickup_date', $carhireSearch['pickup_date'] ?? date('d/m/Y', strtotime('+7 days')));
    $defaultDropoffDate = old('dropoff_date', $carhireSearch['dropoff_date'] ?? date('d/m/Y', strtotime('+14 days')));
    $sameDropoff = (string) old('same_dropoff', $carhireSearch['same_dropoff'] ?? '1') === '1';
    $standardAge = (string) old('driver_age_standard', $carhireSearch['driver_age_standard'] ?? '1') === '1';
    $pickupId = old('pickup_location_id', $carhireSearch['pickup_location_id'] ?? '');
    $pickupName = old('pickup_location_name', $carhireSearch['pickup_location_name'] ?? '');
    $dropId = old('dropoff_location_id', $carhireSearch['dropoff_location_id'] ?? $pickupId);
    $dropName = old('dropoff_location_name', $carhireSearch['dropoff_location_name'] ?? $pickupName);
    $driverAge = (int) old('driver_age', $carhireSearch['driver_age'] ?? 30);
    $pickupTime = old('pickup_time', $carhireSearch['pickup_time'] ?? '12:00');
    $dropoffTime = old('dropoff_time', $carhireSearch['dropoff_time'] ?? '12:00');
@endphp

<style>
    .js-carhire-options {
        display: flex;
        flex-wrap: wrap;
        gap: 16px 32px;
        margin-top: 12px;
        align-items: flex-start;
    }
    .js-carhire-option { min-width: 240px; max-width: 320px; }
    .js-carhire-check {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 10px;
        font-size: 14px;
        color: #1a1a1a;
        font-weight: 600;
        cursor: pointer;
    }
    .js-carhire-check input { width: 16px; height: 16px; accent-color: var(--js-primary, #C2185B); }
    .js-carhire-option-field { display: none; }
    .js-carhire-option-field.is-open { display: block; }
    .carhire-suggest {
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        z-index: 40;
        background: #fff;
        border: 1px solid #e6edf2;
        border-radius: 6px;
        box-shadow: 0 10px 24px rgba(194, 24, 91, 0.1);
        max-height: 240px;
        overflow-y: auto;
        display: none;
        margin-top: 4px;
    }
    .carhire-suggest button {
        display: block;
        width: 100%;
        text-align: left;
        border: 0;
        background: transparent;
        padding: 10px 12px;
        font-size: 13px;
        color: #1a1a1a;
        cursor: pointer;
    }
    .carhire-suggest button:hover { background: #faf5f7; }
    .js-booking-field--carhire-location { position: relative; flex: 1.4 1 220px; }
    .js-carhire-form-error {
        flex: 1 1 100%;
        margin: 0 0 4px;
        color: #c0392b;
        font-size: 13px;
        font-weight: 600;
    }
    @media screen and (max-width: 767px) {
        .js-carhire-option { min-width: 0; max-width: none; width: 100%; }
    }
</style>

<p class="js-panel-hint">{{ crm('home.panel_hint_carhire', 'Search airport and city car hire. Compare live rates from trusted suppliers.') }}</p>

<form method="post" action="{{ route('carhire.search') }}" id="search_form_carhire" class="js-booking-form" autocomplete="off">
    @csrf
    <input type="hidden" name="submitted" value="Yes">
    <input type="hidden" name="booking_for" value="carhire">
    <input type="hidden" name="pickup_location_id" id="carhire_pickup_id" value="{{ $pickupId }}">
    <input type="hidden" name="dropoff_location_id" id="carhire_dropoff_id" value="{{ $dropId }}">
    <input type="hidden" name="residence_country" value="{{ old('residence_country', $carhireSearch['residence_country'] ?? 'GB') }}">

    <div class="js-booking-row">
        @if(session('error') && ($activeBookingTab ?? '') === 'carhire')
            <p class="js-carhire-form-error">{{ session('error') }}</p>
        @endif

        <div class="js-booking-field js-booking-field--carhire-location">
            <label for="carhire_pickup_name">Pick-up location</label>
            <div class="form-group">
                <input type="text" class="js-booking-input" id="carhire_pickup_name" name="pickup_location_name" value="{{ $pickupName }}" placeholder="Airport, city or location" required>
                <div class="carhire-suggest" id="carhire_pickup_suggest"></div>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--date">
            <label for="carhire_pickup_date">Pick-up date</label>
            <div class="form-group form-group-calender">
                <input type="text" class="js-booking-input cursor-pointer" id="carhire_pickup_date" name="pickup_date" placeholder="DD/MM/YY" value="{{ $defaultPickupDate }}" readonly required>
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--time">
            <label for="carhire_pickup_time">Time</label>
            <div class="form-group">
                <select class="js-booking-input" id="carhire_pickup_time" name="pickup_time" required>
                    @for($h = 0; $h <= 23; $h++)
                        @for($m = 0; $m <= 45; $m += 15)
                            @php $t = sprintf('%02d:%02d', $h, $m); @endphp
                            <option value="{{ $t }}" @if($pickupTime === $t) selected @endif>{{ $t }}</option>
                        @endfor
                    @endfor
                </select>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--date">
            <label for="carhire_dropoff_date">Drop-off date</label>
            <div class="form-group form-group-calender">
                <input type="text" class="js-booking-input cursor-pointer" id="carhire_dropoff_date" name="dropoff_date" placeholder="DD/MM/YY" value="{{ $defaultDropoffDate }}" readonly required>
                <i class="fa fa-calendar" aria-hidden="true"></i>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--time">
            <label for="carhire_dropoff_time">Time</label>
            <div class="form-group">
                <select class="js-booking-input" id="carhire_dropoff_time" name="dropoff_time" required>
                    @for($h = 0; $h <= 23; $h++)
                        @for($m = 0; $m <= 45; $m += 15)
                            @php $t = sprintf('%02d:%02d', $h, $m); @endphp
                            <option value="{{ $t }}" @if($dropoffTime === $t) selected @endif>{{ $t }}</option>
                        @endfor
                    @endfor
                </select>
            </div>
        </div>

        <div class="js-booking-field js-booking-field--promo">
            <label for="carhire_promo">Promo code</label>
            <div class="form-group">
                <input class="js-booking-input" type="text" name="promo" id="carhire_promo" value="{{ old('promo', $carhireSearch['promo'] ?? request()->promo) }}" placeholder="Optional">
            </div>
        </div>
    </div>

    <div class="js-carhire-options">
        <div class="js-carhire-option">
            <label class="js-carhire-check">
                <input type="checkbox" name="same_dropoff" id="carhire_same_dropoff" value="1" {{ $sameDropoff ? 'checked' : '' }}>
                Return car to same location?
            </label>
            <div class="js-carhire-option-field {{ $sameDropoff ? '' : 'is-open' }}" id="carhire_dropoff_wrap">
                <label for="carhire_dropoff_name">Drop-off location</label>
                <div class="form-group" style="position:relative;">
                    <input type="text" class="js-booking-input" id="carhire_dropoff_name" name="dropoff_location_name" value="{{ $dropName }}" placeholder="Airport, city or location">
                    <div class="carhire-suggest" id="carhire_dropoff_suggest"></div>
                </div>
            </div>
        </div>
        <div class="js-carhire-option">
            <label class="js-carhire-check">
                <input type="checkbox" name="driver_age_standard" id="carhire_age_standard" value="1" {{ $standardAge ? 'checked' : '' }}>
                Driver aged 30–65 years?
            </label>
            <div class="js-carhire-option-field {{ $standardAge ? '' : 'is-open' }}" id="carhire_age_wrap">
                <label for="carhire_driver_age">Driver age</label>
                <input class="js-booking-input" type="number" min="21" max="80" name="driver_age" id="carhire_driver_age" value="{{ $driverAge }}">
            </div>
        </div>
    </div>

    <div class="js-submit-wrap">
        <button type="submit" id="carhire_submit" class="js-booking-submit">Find Cars</button>
    </div>
</form>

<script>
{{-- Init runs from footer after jQuery UI (homepage reloads jQuery in footer). --}}
window.initJsCarhireSearchForm = function ($) {
    if (!$ || !$('#search_form_carhire').length) {
        return;
    }
    if (window.__jsCarhireFormBound) {
        return;
    }
    window.__jsCarhireFormBound = true;

    function bindDatepickers() {
        if (!$.fn.datepicker) { return; }
        if ($('#carhire_pickup_date').hasClass('hasDatepicker')) {
            $('#carhire_pickup_date').datepicker('destroy');
        }
        if ($('#carhire_dropoff_date').hasClass('hasDatepicker')) {
            $('#carhire_dropoff_date').datepicker('destroy');
        }
        $('#carhire_pickup_date').datepicker({
            minDate: 0,
            dateFormat: 'dd/mm/yy',
            onSelect: function (dateText) {
                $('#carhire_dropoff_date').datepicker('option', 'minDate', dateText);
            }
        });
        $('#carhire_dropoff_date').datepicker({ minDate: 0, dateFormat: 'dd/mm/yy' });
    }

    function syncOptions() {
        var same = $('#carhire_same_dropoff').is(':checked');
        $('#carhire_dropoff_wrap').toggleClass('is-open', !same);
        if (same) {
            $('#carhire_dropoff_name').val($('#carhire_pickup_name').val());
            $('#carhire_dropoff_id').val($('#carhire_pickup_id').val());
            $('#carhire_dropoff_suggest').hide().empty();
        }
        $('#carhire_age_wrap').toggleClass('is-open', !$('#carhire_age_standard').is(':checked'));
    }

    function renderSuggest($box, rows, target) {
        $box.empty();
        if (!rows || !rows.length) { $box.hide(); return; }
        rows.forEach(function (row) {
            var $btn = $('<button type="button"></button>');
            $btn.text(row.name + (row.isAirport ? ' (Airport)' : '') + (row.country ? ' · ' + row.country : ''));
            $btn.on('click', function () {
                if (target === 'pickup') {
                    $('#carhire_pickup_id').val(row.id);
                    $('#carhire_pickup_name').val(row.name);
                    if ($('#carhire_same_dropoff').is(':checked')) {
                        $('#carhire_dropoff_id').val(row.id);
                        $('#carhire_dropoff_name').val(row.name);
                    }
                } else {
                    $('#carhire_dropoff_id').val(row.id);
                    $('#carhire_dropoff_name').val(row.name);
                }
                $box.hide().empty();
            });
            $box.append($btn);
        });
        $box.show();
    }

    var timers = {};
    function debounceSearch(target, value) {
        clearTimeout(timers[target]);
        timers[target] = setTimeout(function () {
            if (!value || value.length < 2) { return; }
            $.getJSON(@json(route('carhire.locations')), { q: value }, function (res) {
                renderSuggest(target === 'pickup' ? $('#carhire_pickup_suggest') : $('#carhire_dropoff_suggest'), res.data || [], target);
            });
        }, 280);
    }

    bindDatepickers();
    syncOptions();
    $('#carhire_same_dropoff, #carhire_age_standard').on('change', syncOptions);
    $('#carhire_pickup_name').on('input', function () {
        $('#carhire_pickup_id').val('');
        debounceSearch('pickup', $(this).val());
        if ($('#carhire_same_dropoff').is(':checked')) {
            $('#carhire_dropoff_name').val($(this).val());
        }
    });
    $('#carhire_dropoff_name').on('input', function () {
        $('#carhire_dropoff_id').val('');
        debounceSearch('dropoff', $(this).val());
    });
    $(document).on('click.carhireSuggest', function (e) {
        if (!$(e.target).closest('.js-booking-field--carhire-location, .js-carhire-option-field').length) {
            $('.carhire-suggest').hide();
        }
    });
    $('#search_form_carhire').off('submit.carhireForm').on('submit.carhireForm', function () {
        syncOptions();
        if (!$('#carhire_pickup_id').val()) {
            alert('Please choose a pick-up location from the list.');
            return false;
        }
        if (!$('#carhire_same_dropoff').is(':checked') && !$('#carhire_dropoff_id').val()) {
            alert('Please choose a drop-off location from the list.');
            return false;
        }
        $(this).find('#carhire_submit').text('Please Wait..');
    });
};
</script>
@endif
