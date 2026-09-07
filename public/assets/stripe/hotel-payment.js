'use strict';

var stripe = Stripe(typeof stripeKey !== 'undefined' ? stripeKey : '');
var elements = stripe.elements();
var cardElement = elements.create('card', { hidePostalCode: true });
cardElement.mount('#card-element');

(function () {
    var form = document.querySelector('#paymentFrm form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        $('#bookingButton1').prop('disabled', true);

        if ($('#referenceNo').val() === '' || $('#referenceNo').val() === null) {
            alert('Please enter your contact details to continue.');
            $('#bookingButton1').prop('disabled', false);
            return;
        }

        if (!$('#personal_details_form').valid()) {
            $('#bookingButton1').prop('disabled', false);
            var top = $('#personal_details_form').offset().top;
            $(window).scrollTop(top);
            return;
        }

        showSpinner();

        var clientSecret = document.getElementById('intent_secret').value;
        var cardholderName = document.getElementById('firstname').value;
        var cardEmail = $('#uemail').val();

        stripe.handleCardPayment(clientSecret, cardElement, {
            payment_method_data: {
                billing_details: {
                    name: cardholderName,
                    email: cardEmail
                }
            }
        }).then(function (result) {
            if (result.error) {
                hideSpinner();
                $('#bookingButton1').prop('disabled', false);
                $('#error_personal_detail').html(result.error.message);
                return;
            }

            var data = {
                result: result,
                title: $('#title').val(),
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                email: $('#uemail').val(),
                contactno: $('#contactno').val(),
                action: $('#action').val(),
                reference_no: $('#referenceNo').val(),
                _token: $('input[name="_token"]').val(),
                booking_id: $('#bookID').val(),
                alltotal: $('#alltotal').val(),
                company_id: $('#bookingDetails input[name="company_id"]').val(),
                product_code: $('#bookingDetails input[name="product_code"]').val(),
                hotel_name: $('#bookingDetails input[name="hotel_name"]').val(),
                room_title: $('#bookingDetails input[name="room_title"]').val(),
                room_type: $('#bookingDetails input[name="room_type"]').val(),
                checkin_date: $('#bookingDetails input[name="checkin_date"]').val(),
                checkout_date: $('#bookingDetails input[name="checkout_date"]').val(),
                checkin_time: $('#bookingDetails input[name="checkin_time"]').val(),
                checkout_time: $('#bookingDetails input[name="checkout_time"]').val(),
                adults: $('#bookingDetails input[name="adults"]').val(),
                children: $('#bookingDetails input[name="children"]').val(),
                infants: $('#bookingDetails input[name="infants"]').val(),
                rooms: $('#bookingDetails input[name="rooms"]').val(),
                airport: $('#bookingDetails input[name="airport"]').val(),
                bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),
                promo: $('#bookingDetails input[name="promo"]').val(),
                incomplete: $('#bookingDetails input[name="incomplete"]').val(),
                pl_id: $('#bookingDetails input[name="pl_id"]').val(),
                park_api: $('#bookingDetails input[name="park_api"]').val(),
                bookfhrSearchId: $('#bookfhrSearchId').val(),
                bookfhrOptionId: $('#bookfhrOptionId').val(),
                smsfee: $("#smsfee").is(':checked') ? $("#smsfee").val() : 0,
                cancelfee: $("#cancelfee").is(':checked') ? $("#cancelfee").val() : 0
            };

            $.post('hotels/payout', data, function (response) {
                if (response.success == 0) {
                    hideSpinner();
                    $('#bookingButton1').prop('disabled', false);
                    $('#error_personal_detail').html(response.data);
                } else {
                    hideSpinner();
                    window.location.href = 'https://' + window.location.hostname + '/hotels/thankyou/' + $('#referenceNo').val();
                }
            }, 'json').fail(function () {
                hideSpinner();
                $('#bookingButton1').prop('disabled', false);
                $('#error_personal_detail').html('Payment processing failed. Please try again.');
            });
        });
    });
})();
