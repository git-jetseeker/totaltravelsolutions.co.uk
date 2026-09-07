'use strict';
var stripe = Stripe(window.STRIPE_PUBLIC_KEY);
var elements = stripe.elements();
var cardElement = elements.create('card', {hidePostalCode: true});
cardElement.mount('#card-element');

function registerElements(elements, exampleName) {
    var formClass = '.' + exampleName;
    var example = document.querySelector(formClass);

    var form = example.querySelector('form');
    var resetButton = example.querySelector('a.reset');
    var error = form.querySelector('.error');
    var errorMessage = error.querySelector('.message');

    function enableInputs() {
        Array.prototype.forEach.call(
            form.querySelectorAll(
                "input[type='text'], input[type='email'], input[type='tel']"
            ),
            function (input) {
                input.removeAttribute('disabled');
            }
        );
    }

    function disableInputs() {
        Array.prototype.forEach.call(
            form.querySelectorAll(
                "input[type='text'], input[type='email'], input[type='tel']"
            ),
            function (input) {
                input.setAttribute('disabled', 'true');
            }
        );
    }

    function triggerBrowserValidation() {
        // The only way to trigger HTML5 form validation UI is to fake a user submit
        // event.
        var submit = document.createElement('input');
        submit.type = 'submit';
        submit.style.display = 'none';
        form.appendChild(submit);
        submit.click();
        submit.remove();
    }

    // Listen for errors from each Element, and show error messages in the UI.
    var savedErrors = {};
    elements.forEach(function (element, idx) {
        element.on('change', function (event) {
            if (event.error) {
                error.classList.add('visible');
                savedErrors[idx] = event.error.message;
                errorMessage.innerText = event.error.message;
            } else {
                savedErrors[idx] = null;

                // Loop over the saved errors and find the first one, if any.
                var nextError = Object.keys(savedErrors)
                    .sort()
                    .reduce(function (maybeFoundError, key) {
                        return maybeFoundError || savedErrors[key];
                    }, null);

                if (nextError) {
                    // Now that they've fixed the current error, show another one.
                    errorMessage.innerText = nextError;
                } else {
                    // The user fixed the last error; no more errors.
                    error.classList.remove('visible');
                }
            }
        });
    });

       form.addEventListener('submit', function (e) {

        e.preventDefault();

   

hideSpinner();
        // Trigger HTML5 validation UI on the form if any of the inputs fail
        // validation.
     

        // Show a loading screen...
        example.classList.add('submitting');

        // Disable all inputs.
        //disableInputs();

        // Gather additional customer data we may have collected in our form.
     
        
        showSpinner();

        // Use Stripe.js to create a token. We only need to pass in one Element
        // from the Element group in order to create a token. We can also pass
        // in the additional customer data we collected in our form.

            // Stop loading!





            example.classList.remove('submitting');
             {
                // If we received a token, show the token ID.
                // example.querySelector('.token').innerText = result.token.id;
                // example.classList.add('submitted');


                if($("#personal_details_form").valid() && $("#travel_details_form").valid()) {
                    var smsfee=0;
                    var cancelfee=0;
                    var smsfee=0;
    
                    if($("#smsfee").prop("checked")==true) {
    
                        smsfee=$("#smsfee").val();
    
                    }
    
                    if($("#cancelfee").prop("checked")==true) {
    
                        cancelfee=$("#cancelfee").val();
    
                    }
                    var data = {};
                    data['title'] = $('#title').val();
    
                    data['firstname'] = $('#firstname').val();
    
                    data['lastname'] = $('#lastname').val();
    
                    data['email'] = $('#email').val();
    
                    data['contactno'] = $('#contactno').val();
    
                    data['action'] = $('#action').val();
    
                    data['reference_no'] = $('#referenceNo').val();
    
                    data['refr'] = $('#refr').val();
    
                    data['booking_id'] = $('#bookID').val();
    
    
                    data['booking_amount'] = $('#bookingprice').val();
                    
                    data['park_api'] = $('#bookingDetails input[name="park_api"]').val();
             
                    
                    data['company_id'] = $('#bookingDetails input[name="company_id"]').val();
                    data['product_code'] = $('#bookingDetails input[name="product_code"]').val();
                    data['transfer_name'] = $('#bookingDetails input[name="transfer_name"]').val();
                    data['booking_url'] = $('#bookingDetails input[name="booking_url"]').val();
                    
                    
                    data['checkin_date'] = $('#bookingDetails input[name="checkin_date"]').val();
                    
                    data['checkin_time'] = $('#bookingDetails input[name="checkin_time"]').val();
                    
                    data['adults'] = $('#bookingDetails input[name="adults"]').val();
                    data['children'] = $('#bookingDetails input[name="children"]').val();
                    data['infants'] = $('#bookingDetails input[name="infants"]').val();
                   
                    data['loc_type'] = $('#bookingDetails input[name="loc_type"]').val();
                    data['loc_code'] = $('#bookingDetails input[name="loc_code"]').val();
                    data['loc_name'] = $('#bookingDetails input[name="loc_name"]').val();
                    data['loc_lat'] = $('#bookingDetails input[name="loc_lat"]').val();
                    data['loc_long'] = $('#bookingDetails input[name="loc_long"]').val();
                    data['loc_id'] = $('#bookingDetails input[name="loc_id"]').val();
                    data['loc_country'] = $('#bookingDetails input[name="loc_country"]').val();
                    
                    data['loc_type_drop'] = $('#bookingDetails input[name="loc_type_drop"]').val();
                    data['loc_code_drop'] = $('#bookingDetails input[name="loc_code_drop"]').val();
                    data['loc_name_drop'] = $('#bookingDetails input[name="loc_name_drop"]').val();
                    data['loc_lat_drop'] = $('#bookingDetails input[name="loc_lat_drop"]').val();
                    data['loc_long_drop'] = $('#bookingDetails input[name="loc_long_drop"]').val();
                    data['loc_id_drop'] = $('#bookingDetails input[name="loc_id_drop"]').val();
                    data['loc_country_drop'] = $('#bookingDetails input[name="loc_country_drop"]').val();
                    
                    
                    data['flight_time'] = $('#flight_time').val();
                    data['arrival_flight'] = $('#arrival_flight').val();
                    data['return_flight'] = $('#return_flight').val();
                    
                    data['bookingfor'] = $('#bookingDetails input[name="bookingfor"]').val();
                    data['smsfee'] = $("#smsfee").is(':checked') ? 'Yes' : 'No';
                    data['cancelfee'] = $("#cancelfee").is(':checked') ? 'Yes' : 'No';
                    data['incomplete'] = $('#bookingDetails input[name="incomplete"]').val();
                    
                    
                    data['sku'] = $('#bookingDetails input[name="sku"]').val();
                    
                    data['intent_id'] = $('#intent_id').val();
                    data['token'] ="test";
                    
                    
    
                    //var cardholderName = document.getElementById('firstname');
                    var cardholderName = $('#firstname').val() + ' ' + $('#lastname').val();
                    var cardButton = document.getElementById('card-button');
                    var clientSecret = document.getElementById('intent_secret');
                    console.log('test');
                    console.log(clientSecret);
                      stripe.handleCardPayment(
                        clientSecret.value, cardElement, {
                          payment_method_data: {
                            billing_details: {name: cardholderName}
                          }
                        }
                      ).then(function(result) {
                        if (result.error) {
                            hideSpinner();
                            $("#error_personal_detail").html(result.error.message);
    
    
                            console.log(result.error);
                            alert(result.error.message);
                            
                            $.post('booking/payout_failed_transfer', data, function (resp) {
                                console.log("data===", resp);
                            }, 'json');
                            
                            
                            
                          // Display error.message in your UI.
                        } else {
                            data['result']=result;
    
                            //data['debug']       = 1
                            //}
    //
                            //alert(result.token.id);
                            $.post('booking/payout_transfer', data, function (data) {
                                console.log("data===", data);
    
                                if (data.success == 0) {
                                    hideSpinner();
                                    alert(data.data);
    
                                    $("#error_personal_detail").html(data.data);
                                }else {
                                    hideSpinner();
                                    var refid = $('#referenceNo').val();
                                    window.location.href = "https://" + window.location.hostname + "/booking_lounge/thankyou/"+refid;
    
                                }
    
                            }, 'json');
                         }
                    });
                    
                } else {
                    hideSpinner();
                    //$("#ad_field").after('<label class="error error-vech" >This field is required.</label>');
                    var top = $('#personal_details_form').position().top;
                    $(window).scrollTop(top);
                }

            }
        });
  

    resetButton.addEventListener('click', function (e) {
        e.preventDefault();
        // Resetting the form (instead of setting the value to `''` for each input)
        // helps us clear webkit autofill styles.
        form.reset();

        // Clear each Element.
        elements.forEach(function (element) {
            element.clear();
        });

        // Reset error state as well.
        error.classList.remove('visible');

        // Resetting the form does not un-disable inputs, so we need to do it separately:
        enableInputs();
        example.classList.remove('submitted');
    });
}


function submit_payment() {
    $("#firstname").val();
}

function getnerateError(id) {
    var html = '<label id="\'+id+\'-error" class="error" for="' + id + '">This field is required.</label>';
    $("#" + id).after(html);
}