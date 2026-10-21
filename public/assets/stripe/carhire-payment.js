'use strict';

var stripe = Stripe(window.STRIPE_PUBLIC_KEY || (typeof stripeKey !== 'undefined' ? stripeKey : ''));
var elements = stripe.elements();
var cardElement = elements.create('card', { hidePostalCode: true });
cardElement.mount('#card-element');

(function () {
    var form = document.querySelector('#paymentFrm form');
    if (!form) {
        return;
    }

    function saveCheckoutDetails() {
        return $.post('/car-hire/save-details', {
            title: $('#title').val(),
            firstname: $('#firstname').val(),
            lastname: $('#lastname').val(),
            email: $('#uemail').val(),
            contactno: $('#contactno').val(),
            reference_no: $('#referenceNo').val(),
            booking_amount: $('#bookingprice').val(),
            discount_amount: $('#disAmount').val(),
            product_code: $('#bookingDetails input[name="product_code"]').val(),
            quote_json: $('#quote_json').val(),
            promo: $('#bookingDetails input[name="promo"]').val(),
            smsfee: $("#smsfee").is(':checked') ? 'Yes' : 'No',
            canfee: $("#cancelfee").is(':checked') ? 'Yes' : 'No',
            _token: $('input[name="_token"]').val()
        }, null, 'json').then(function (result) {
            if (result && result.booking_id > 0 && result.available == 'Yes') {
                $('#bookID').val(result.booking_id);
                $('#referenceNo').val(result.reference_no);
                $('#incomplete').val('no');
            }
            return result;
        });
    }

    function postPayout(result) {
        var data = {
            result: result,
            title: $('#title').val(),
            firstname: $('#firstname').val(),
            lastname: $('#lastname').val(),
            email: $('#uemail').val(),
            contactno: $('#contactno').val(),
            flight_number: $('#flight_number').val(),
            action: $('#action').val(),
            reference_no: $('#referenceNo').val(),
            _token: $('input[name="_token"]').val(),
            booking_id: $('#bookID').val(),
            alltotal: $('#alltotal').val(),
            product_code: $('#bookingDetails input[name="product_code"]').val(),
            quote_json: $('#quote_json').val(),
            bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),
            promo: $('#bookingDetails input[name="promo"]').val(),
            smsfee: $("#smsfee").is(':checked') ? $("#smsfee").val() : 0,
            cancelfee: $("#cancelfee").is(':checked') ? $("#cancelfee").val() : 0
        };

        return $.post('/car-hire/payout', data, null, 'json');
    }

    function finishSuccess() {
        hideSpinner();
        window.location.href = '/car-hire/thankyou/' + $('#referenceNo').val();
    }

    function finishError(message) {
        hideSpinner();
        $('#bookingButton1').prop('disabled', false);
        $('#error_personal_detail').html(message || 'Payment processing failed. Please try again.');
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        $('#bookingButton1').prop('disabled', true);
        $('#error_personal_detail').html('');

        if (!$('#personal_details_form').valid()) {
            $('#bookingButton1').prop('disabled', false);
            var top = $('#personal_details_form').offset().top;
            $(window).scrollTop(top);
            return;
        }

        showSpinner();

        saveCheckoutDetails().then(function () {
            if ($('#referenceNo').val() === '' || $('#referenceNo').val() === null) {
                finishError('Please enter your contact details to continue.');
                return;
            }

            var clientSecret = document.getElementById('intent_secret').value;
            if (!clientSecret) {
                finishError('Payment session expired. Please refresh the page and try again.');
                return;
            }

            var cardholderName = document.getElementById('firstname').value;
            var cardEmail = $('#uemail').val();

            return stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardholderName,
                        email: cardEmail
                    }
                }
            }).then(function (result) {
                // Retry after a prior Stripe success + supplier failure: PI is already succeeded.
                if (result.error) {
                    var pi = result.error.payment_intent;
                    if (pi && pi.status === 'succeeded') {
                        result = { paymentIntent: pi };
                    } else {
                        finishError(result.error.message);
                        return;
                    }
                }

                return postPayout(result).then(function (response) {
                    if (response.success == 0) {
                        finishError(response.data);
                    } else {
                        finishSuccess();
                    }
                }).fail(function () {
                    finishError('Payment processing failed. Please try again.');
                });
            });
        }).fail(function () {
            finishError('Unable to save your details. Please try again.');
        });
    });
})();
