'use strict';

var stripe = Stripe(window.STRIPE_PUBLIC_KEY);
var elements = stripe.elements();
var cardElement = elements.create('card', { hidePostalCode: true });
cardElement.mount('#card-element');

cardElement.on('change', function (event) {
    var messageEl = document.querySelector('#paymentFrm .error .message');
    var errorEl = document.querySelector('#paymentFrm .error');
    if (!messageEl || !errorEl) {
        return;
    }
    if (event.error) {
        errorEl.classList.add('visible');
        messageEl.textContent = event.error.message;
        var paymentBody = document.querySelector('.lounge-payment-box__body');
        if (paymentBody) {
            paymentBody.classList.add('has-card-error');
        }
    } else {
        errorEl.classList.remove('visible');
        messageEl.textContent = '';
        var paymentBodyClear = document.querySelector('.lounge-payment-box__body');
        if (paymentBodyClear) {
            paymentBodyClear.classList.remove('has-card-error');
        }
    }
});

function loungeEnsureBooking(callback) {
    var existingRef = $('#referenceNo').val();
    if (existingRef) {
        callback(true);
        return;
    }

    if (typeof showSpinner === 'function') {
        showSpinner();
    }

    $.post('/checkBookingLounge', {
        title: $('#title').val(),
        firstname: $('#firstname').val(),
        lastname: $('#lastname').val(),
        email: $('#email').val(),
        contactno: $('#contactno').val(),
        action: $('#action').val() || 'airportLoungesBooking',
        reference_no: '',
        booking_fee: $('#bookfeeprice').text().replace(/[^\d.]/g, '') || '0',
        _token: $('input[name="_token"]').val(),
        booking_id: $('#bookID').val() || 0,
        booking_amount: $('#bookingprice').val(),
        discount_amount: $('#disAmount').val(),
        promo: $('#bookingDetails input[name="promo"]').val(),
        park_api: $('#bookingDetails input[name="park_api"]').val(),
        company_id: $('#bookingDetails input[name="company_id"]').val(),
        product_code: $('#bookingDetails input[name="product_code"]').val(),
        lounge_name: $('#bookingDetails input[name="lounge_name"]').val(),
        terminal: $('#bookingDetails input[name="terminal"]').val(),
        checkin_date: $('#bookingDetails input[name="checkin_date"]').val(),
        checkin_time: $('#bookingDetails input[name="checkin_time"]').val(),
        adults: $('#bookingDetails input[name="adults"]').val(),
        children: $('#bookingDetails input[name="children"]').val(),
        infants: $('#bookingDetails input[name="infants"]').val(),
        airport: $('#bookingDetails input[name="airport"]').val(),
        bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),
        smsfee: $('#smsfee').is(':checked') ? 'Yes' : 'No',
        canfee: $('#cancelfee').is(':checked') ? 'Yes' : 'No',
        incomplete: 'yes',
        pl_id: $('#bookingDetails input[name="pl_id"]').val(),
        bookfhrSearchId: $('#bookingDetails input[name="bookfhrSearchId"]').val(),
        bookfhrOptionId: $('#bookingDetails input[name="bookfhrOptionId"]').val(),
        intent_id: $('#intent_id').val()
    }, function (result) {
        if (result && result.booking_id > 0 && result.available == 'Yes') {
            $('#bookID').val(result.booking_id);
            $('#referenceNo').val(result.reference_no);
            $('#incomplete').val('no');
            callback(true);
        } else {
            if (typeof hideSpinner === 'function') {
                hideSpinner();
            }
            $('#bookingButton').prop('disabled', false);
            var msg = 'Unable to create booking. Please check your details and try again.';
            $('#error_personal_detail').html(msg);
            callback(false);
        }
    }, 'json').fail(function (xhr) {
        if (typeof hideSpinner === 'function') {
            hideSpinner();
        }
        $('#bookingButton').prop('disabled', false);
        var msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.data))
            ? (xhr.responseJSON.message || xhr.responseJSON.data)
            : 'Unable to create booking. Please try again.';
        $('#error_personal_detail').html(msg);
        callback(false);
    });
}

function loungeBuildPayoutData(result) {
    return {
        result: result,
        title: $('#title').val(),
        firstname: $('#firstname').val(),
        lastname: $('#lastname').val(),
        email: $('#email').val(),
        contactno: $('#contactno').val(),
        action: $('#action').val(),
        reference_no: $('#referenceNo').val(),
        _token: $('input[name="_token"]').val(),
        booking_id: $('#bookID').val(),
        booking_amount: $('#bookingprice').val(),
        discount: $('#disAmount').val(),
        discount_amount: $('#disAmount').val(),
        company_id: $('#bookingDetails input[name="company_id"]').val(),
        product_code: $('#bookingDetails input[name="product_code"]').val(),
        lounge_name: $('#bookingDetails input[name="lounge_name"]').val(),
        terminal: $('#bookingDetails input[name="terminal"]').val(),
        checkin_date: $('#bookingDetails input[name="checkin_date"]').val(),
        checkin_time: $('#bookingDetails input[name="checkin_time"]').val(),
        adults: $('#bookingDetails input[name="adults"]').val(),
        children: $('#bookingDetails input[name="children"]').val(),
        infants: $('#bookingDetails input[name="infants"]').val(),
        airport: $('#bookingDetails input[name="airport"]').val(),
        bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),
        promo: $('#bookingDetails input[name="promo"]').val(),
        incomplete: $('#incomplete').val(),
        pl_id: $('#bookingDetails input[name="pl_id"]').val(),
        park_api: $('#bookingDetails input[name="park_api"]').val(),
        bookfhrSearchId: $('#bookfhrSearchId').val() || $('#bookingDetails input[name="bookfhrSearchId"]').val(),
        bookfhrOptionId: $('#bookfhrOptionId').val() || $('#bookingDetails input[name="bookfhrOptionId"]').val(),
        intent_id: $('#intent_id').val(),
        smsfee: $('#smsfee').is(':checked') ? 'Yes' : 'No',
        cancelfee: $('#cancelfee').is(':checked') ? 'Yes' : 'No'
    };
}

(function () {
    var form = document.querySelector('#paymentFrm form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        $('#bookingButton').prop('disabled', true);
        $('#error_personal_detail').html('');

        if (typeof $("#personal_details_form").valid === 'function' && !$('#personal_details_form').valid()) {
            $('#bookingButton').prop('disabled', false);
            $('#error_personal_detail').html('Please complete all required personal details.');
            var top = $('#personal_details_form').offset().top - 20;
            $('html, body').animate({ scrollTop: top }, 300);
            return;
        }

        if (!$('#intent_secret').val()) {
            $('#bookingButton').prop('disabled', false);
            $('#error_personal_detail').html('Payment is not ready yet. Please wait a moment and try again.');
            if (typeof ap_processCheckout === 'function') {
                ap_processCheckout();
            }
            return;
        }

        loungeEnsureBooking(function (ok) {
            if (!ok) {
                return;
            }

            if (typeof showSpinner === 'function') {
                showSpinner();
            }

            var clientSecret = $('#intent_secret').val();
            var cardholderName = (($('#firstname').val() || '') + ' ' + ($('#lastname').val() || '')).trim();

            stripe.handleCardPayment(clientSecret, cardElement, {
                payment_method_data: {
                    billing_details: {
                        name: cardholderName,
                        email: $('#email').val()
                    }
                }
            }).then(function (result) {
                if (result.error) {
                    if (typeof hideSpinner === 'function') {
                        hideSpinner();
                    }
                    $('#bookingButton').prop('disabled', false);
                    $('#error_personal_detail').html(result.error.message);
                    return;
                }

                var data = loungeBuildPayoutData(result);

                $.post('/booking/payout_lounge', data, function (response) {
                    if (typeof hideSpinner === 'function') {
                        hideSpinner();
                    }

                    if (response && response.success == 0) {
                        $('#bookingButton').prop('disabled', false);
                        $('#error_personal_detail').html(response.data || 'Booking confirmation failed.');
                        return;
                    }

                    var refid = $('#referenceNo').val();
                    window.location.href = window.location.origin + '/booking_lounge/thankyou/' + encodeURIComponent(refid);
                }, 'json').fail(function (xhr) {
                    if (typeof hideSpinner === 'function') {
                        hideSpinner();
                    }
                    $('#bookingButton').prop('disabled', false);
                    var msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.data))
                        ? (xhr.responseJSON.message || xhr.responseJSON.data)
                        : 'Payment was taken but confirmation failed. Please contact support with your card statement.';
                    $('#error_personal_detail').html(msg);
                });
            });
        });
    });
})();
