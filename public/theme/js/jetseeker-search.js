/**
 * Jetseeker — homepage booking widget (APB-style)
 */
(function ($) {
    'use strict';

    if (!$) {
        return;
    }

    function getCalendarMonths() {
        return window.innerWidth < 640 ? 1 : 2;
    }

    function bindCalendarTrigger($input) {
        $input.closest('.form-group-calender').find('.fa-calendar').off('click.jsCalendar').on('click.jsCalendar', function () {
            $input.datepicker('show');
        });
    }

    function refreshCalendarMonths($input) {
        if (!$input.length || !$input.hasClass('hasDatepicker')) {
            return;
        }
        $input.datepicker('option', 'numberOfMonths', getCalendarMonths());
    }

    function sharedDatepickerOptions() {
        return {
            dateFormat: 'dd-mm-yy',
            numberOfMonths: getCalendarMonths(),
            showAnim: 'fadeIn',
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('js-datepicker-panel');
            }
        };
    }

    function bindDateInput($input) {
        $input.off('click.jsCalendar focus.jsCalendar').on('click.jsCalendar focus.jsCalendar', function () {
            if ($(this).hasClass('hasDatepicker')) {
                $(this).datepicker('show');
            }
        });
        bindCalendarTrigger($input);
    }

    function initHotelDatepickers() {
        var $checkin = $('#hotel_checkin_date');
        var $checkout = $('#hotel_checkout_date');

        if (!$checkin.length || !$checkout.length || !$.fn.datepicker) {
            return;
        }

        if ($checkin.hasClass('hasDatepicker')) {
            $checkin.datepicker('destroy');
        }
        if ($checkout.hasClass('hasDatepicker')) {
            $checkout.datepicker('destroy');
        }

        var shared = sharedDatepickerOptions();

        $checkin.datepicker($.extend({}, shared, {
            minDate: 0,
            onSelect: function () {
                var checkInDate = $checkin.datepicker('getDate');
                if (checkInDate) {
                    var minCheckout = new Date(checkInDate.getTime());
                    minCheckout.setDate(minCheckout.getDate() + 1);
                    $checkout.datepicker('option', 'minDate', minCheckout);

                    var currentCheckOut = $checkout.datepicker('getDate');
                    if (!currentCheckOut || currentCheckOut <= checkInDate) {
                        $checkout.datepicker('setDate', minCheckout);
                    }
                }
                $checkin.removeClass('border-red').siblings('.error-massage').remove();
            }
        }));

        $checkout.datepicker($.extend({}, shared, {
            minDate: 1,
            onSelect: function () {
                $checkout.removeClass('border-red').siblings('.error-massage').remove();
                $checkout.datepicker('hide');
            },
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('js-datepicker-panel');
                var checkInDate = $checkin.datepicker('getDate');
                if (checkInDate) {
                    var minCheckout = new Date(checkInDate.getTime());
                    minCheckout.setDate(minCheckout.getDate() + 1);
                    $(this).datepicker('option', 'minDate', minCheckout);
                }
            }
        }));

        bindDateInput($checkin);
        bindDateInput($checkout);
    }

    function initParkingDatepickers() {
        var $start = $('#startDate');
        var $end = $('#endDate');
        var $form = $('#search_form_1');

        if (!$start.length || !$.fn.datepicker) {
            return;
        }

        if ($start.hasClass('hasDatepicker')) {
            $start.datepicker('destroy');
        }
        if ($end.hasClass('hasDatepicker')) {
            $end.datepicker('destroy');
        }

        var today = new Date();
        today.setHours(0, 0, 0, 0);

        if (!$start.val()) {
            $start.val($.datepicker.formatDate('dd-mm-yy', today));
        }
        if (!$end.val()) {
            var defaultEnd = new Date(today);
            defaultEnd.setDate(defaultEnd.getDate() + 3);
            $end.val($.datepicker.formatDate('dd-mm-yy', defaultEnd));
        }

        var shared = sharedDatepickerOptions();

        $start.datepicker($.extend({}, shared, {
            minDate: 0,
            defaultDate: today,
            onSelect: function () {
                var startDate = $start.datepicker('getDate');
                if (!startDate) {
                    return;
                }
                var endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + 3);
                $end.datepicker('setDate', endDate);
                $end.datepicker('option', 'minDate', startDate);
                setTimeout(function () {
                    $end.datepicker('show');
                }, 180);
            }
        }));

        $end.datepicker($.extend({}, shared, {
            minDate: $.datepicker.parseDate('dd-mm-yy', $start.val()) || 0,
            onSelect: function () {
                $(this).datepicker('hide');
            }
        }));

        bindDateInput($start);
        bindDateInput($end);

        $form.off('submit.jsParkingDates').on('submit.jsParkingDates', function (event) {
            if (!$end.val()) {
                event.preventDefault();
                alert('Please select both drop-off and return dates.');
            }
        });

        var resizeTimer;
        $(window).off('resize.jsParkingCalendar').on('resize.jsParkingCalendar', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                refreshCalendarMonths($start);
                refreshCalendarMonths($end);
            }, 150);
        });
    }

    function initLoungeDatepicker() {
        var $loungeDate = $('#lounge_checkIn_date');

        if (!$loungeDate.length || !$.fn.datepicker) {
            return;
        }

        if ($loungeDate.hasClass('hasDatepicker')) {
            $loungeDate.datepicker('destroy');
        }

        $loungeDate.datepicker($.extend({}, sharedDatepickerOptions(), {
            minDate: 0
        }));

        bindDateInput($loungeDate);
    }

    function initBookingWidget() {
        var $widget = $('[data-js-booking-widget]');
        if (!$widget.length) {
            return;
        }

        function activateProduct(product) {
            $widget.attr('data-active', product);

            $widget.find('.js-product-tab').each(function () {
                var isActive = $(this).data('product') === product;
                $(this).toggleClass('is-active', isActive);
                $(this).attr('aria-selected', isActive ? 'true' : 'false');
            });

            $widget.find('.js-product-panel').each(function () {
                var isActive = $(this).data('panel') === product;
                $(this).toggleClass('is-active', isActive);
                if (isActive) {
                    $(this).removeAttr('hidden');
                } else {
                    $(this).attr('hidden', 'hidden');
                }
            });

            if (product === 'hotels') {
                initHotelDatepickers();
            }
            if (product === 'lounges') {
                initLoungeDatepicker();
            }
            if ($.fn.select2) {
                $widget.find('.js-product-panel.is-active .select2me').select2({ width: '100%' });
            }
        }

        $widget.on('click', '.js-product-tab', function () {
            activateProduct($(this).data('product'));
        });

        var hash = window.location.hash.replace('#', '');
        if (hash === 'lounges' || hash === 'hotels' || hash === 'parking' || hash === 'carhire') {
            activateProduct(hash);
        }

        $widget.on('change', '.js-sync-airport', function () {
            var val = $(this).val();
            $widget.find('.js-sync-airport').not(this).each(function () {
                if ($(this).val() !== val) {
                    $(this).val(val).trigger('change.select2');
                }
            });
        });

        if ($.fn.select2) {
            $widget.find('.js-product-panel.is-active .select2me').select2({ width: '100%' });
        }
    }

    function initLoungeGuestPicker() {
        var $picker = $('[data-guest-picker="lounge"]');
        if (!$picker.length) {
            return;
        }

        function getVal(id) {
            return parseInt($('#' + id).val(), 10) || 0;
        }

        function setVal(id, val, min, max) {
            val = Math.min(max, Math.max(min, val));
            $('#' + id).val(val);
            $('#' + id + '_display').text(val);
            updateSummary();
        }

        function updateSummary() {
            var adults = getVal('lounge_aladults') || 1;
            var children = getVal('lounge_alchildren');
            var infants = getVal('lounge_alinfants');
            var parts = [adults + ' adult' + (adults === 1 ? '' : 's')];
            if (children > 0) {
                parts.push(children + ' child' + (children === 1 ? '' : 'ren'));
            }
            if (infants > 0) {
                parts.push(infants + ' infant' + (infants === 1 ? '' : 's'));
            }
            $('#lounge_guest_summary').text(parts.join(', '));
        }

        $('#lounge_guest_trigger').on('click', function (e) {
            e.preventDefault();
            var $panel = $('#lounge_guest_panel');
            var open = !$panel.hasClass('is-open');
            $panel.toggleClass('is-open', open);
            $(this).toggleClass('is-open', open).attr('aria-expanded', open ? 'true' : 'false');
        });

        $('#lounge_guest_done').on('click', function () {
            $('#lounge_guest_panel').removeClass('is-open');
            $('#lounge_guest_trigger').removeClass('is-open').attr('aria-expanded', 'false');
        });

        $picker.on('click', '.js-stepper-btn', function () {
            var target = $(this).data('target');
            var step = parseInt($(this).data('step'), 10);
            var min = parseInt($(this).data('min'), 10);
            var max = parseInt($(this).data('max'), 10);
            if (isNaN(min)) {
                min = 0;
            }
            if (isNaN(max)) {
                max = 9;
            }
            setVal(target, getVal(target) + step, min, max);
        });

        $(document).on('click.loungeGuests', function (e) {
            if (!$(e.target).closest('[data-guest-picker="lounge"]').length) {
                $('#lounge_guest_panel').removeClass('is-open');
                $('#lounge_guest_trigger').removeClass('is-open').attr('aria-expanded', 'false');
            }
        });

        updateSummary();
    }

    function initResultPageDatepickers() {
        var startDateInput = document.getElementById('startDate');
        var endDateInput = document.getElementById('endDate');
        var form = document.getElementById('search_form_1');

        if (!startDateInput || !endDateInput || !form || !$.fn.datepicker) {
            return;
        }

        if ($(startDateInput).hasClass('hasDatepicker')) {
            $(startDateInput).datepicker('destroy');
        }
        if ($(endDateInput).hasClass('hasDatepicker')) {
            $(endDateInput).datepicker('destroy');
        }

        var today = new Date();
        today.setDate(today.getDate() + 1);

        var startDateValue = startDateInput.value
            ? $.datepicker.parseDate('dd-mm-yy', startDateInput.value)
            : today;

        var endDateValue;
        if (endDateInput.value) {
            endDateValue = $.datepicker.parseDate('dd-mm-yy', endDateInput.value);
        } else {
            endDateValue = new Date(startDateValue);
            endDateValue.setDate(endDateValue.getDate() + 8);
        }

        var shared = sharedDatepickerOptions();

        $(startDateInput).datepicker($.extend({}, shared, {
            minDate: 1,
            defaultDate: startDateValue,
            onSelect: function () {
                var startDate = $(startDateInput).datepicker('getDate');
                if (!endDateInput.value || $.datepicker.parseDate('dd-mm-yy', endDateInput.value) < startDate) {
                    var minEndDate = new Date(startDate);
                    minEndDate.setDate(minEndDate.getDate() + 8);
                    endDateInput.value = $.datepicker.formatDate('dd-mm-yy', minEndDate);
                }
                $(endDateInput).datepicker('option', 'minDate', startDate);
                $(startDateInput).datepicker('hide');
            }
        }));

        $(endDateInput).datepicker($.extend({}, shared, {
            minDate: startDateValue,
            defaultDate: endDateValue,
            onSelect: function () {
                $(endDateInput).datepicker('hide');
            }
        }));

        if (!startDateInput.value) {
            startDateInput.value = $.datepicker.formatDate('dd-mm-yy', startDateValue);
        }
        if (!endDateInput.value) {
            endDateInput.value = $.datepicker.formatDate('dd-mm-yy', endDateValue);
        }

        form.addEventListener('submit', function (event) {
            if (!endDateInput.value) {
                event.preventDefault();
                alert('Please select drop-off date.');
            }
        });
    }

    window.JetseekerSearch = {
        initParkingDatepickers: initParkingDatepickers,
        initLoungeDatepicker: initLoungeDatepicker,
        initHotelDatepickers: initHotelDatepickers,
        initBookingWidget: initBookingWidget,
        initResultPageDatepickers: initResultPageDatepickers,
        getCalendarMonths: getCalendarMonths
    };

    window.initJsHotelDatepickers = initHotelDatepickers;

    $(document).ready(function () {
        initBookingWidget();
        initLoungeGuestPicker();

        if ($('#search_form_1').length && $('#startDate').length && window.location.pathname.indexOf('result') === -1) {
            initParkingDatepickers();
        }
        initLoungeDatepicker();
    });
}(window.jQuery));
