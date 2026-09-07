'use strict';

var stripe = Stripe('pk_live_7s7XfrdJ0SU08f7n7KBddpbT00IWLGWHSt');
//var stripe = Stripe('pk_test_Ze6x0OSDxEwtxQWeOmHKa2ZL');
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


                if ($("#personal_details_form").valid()) {

                    if (valid_address()) {
                           var smsfee=0;
                              var cancelfee=0;

                        if (validate_vechiledetail()) {
                              var smsfee=0;

                            if($("#smsfee").prop("checked")==true) {

                                smsfee=$("#smsfee").val();

                            }



                         

                            if($("#cancelfee").prop("checked")==true) {

                                cancelfee=$("#cancelfee").val();

                            }

                            var cardholderName = document.getElementById('firstname');
                                var cardButton = document.getElementById('card-button');
                                var clientSecret = document.getElementById('intent_secret');
                                console.log('test');
                                console.log(clientSecret);
                                  stripe.handleCardPayment(
                                    clientSecret.value, cardElement, {
                                      payment_method_data: {
                                        billing_details: {name: cardholderName.value}
                                      }
                                    }
                                  ).then(function(result) {
                                    if (result.error) {
                                          hideSpinner();
                                         $("#error_personal_detail").html(result.error.message);


                                        console.log(result.error);
                                        alert(result.error.message);
                                      // Display error.message in your UI.
                                    } else {

                            var data = {};
                             data['result']=result;
                            data['title'] = $('#title').val();
                            data['firstname'] = $('#firstname').val();
                            data['lastname'] = $('#lastname').val();
                            data['email'] = $('#email').val();
                            data['contactno'] = $('#contactno').val();
                            data['action'] = $('#action').val();
                            data['reference_no'] = $('#referenceNo').val();

                            data['_token'] = $('input[name="_token"]').val();
                            data['refr'] = $('#refr').val();
                            //alert(data['refr']);
                            data['booking_id'] = $('#bookID').val();
                            data['alltotal'] = $('#alltotal').val();

                            // if (data['action'] == 'airportParkingBooking') {
//
                            data['company_id'] = $('#bookingDetails input[name="company_id"]').val(),
                            data['product_code'] = $('#bookingDetails input[name="product_code"]').val(),
                            data['parking_type'] = $('#bookingDetails input[name="parking_type"]').val(),
                            data['pickdate'] = $('#bookingDetails input[name="pickdate"]').val(),
                            data['dropdate'] = $('#bookingDetails input[name="dropdate"]').val(),
                            data['droptime'] = $('#bookingDetails input[name="droptime"]').val(),
                            data['picktime'] = $('#bookingDetails input[name="picktime"]').val(),
                            data['total_days'] = $('#bookingDetails input[name="total_days"]').val(),
                            data['airport'] = $('#bookingDetails input[name="airport"]').val(),
                            data['bookingfor'] = $('#bookingDetails input[name="bookingfor"]').val(),
                            data['promo'] = $('#bookingDetails input[name="promo"]').val(),
                            data['smsfee'] = $("#smsfee").val(),
                            data['cancelfee'] = $("#cancelfee").val(),
                            data['passenger'] = $('#passenger').val(),
                            data['incomplete'] = $('#bookingDetails input[name="incomplete"]').val(),
                            data['pl_id'] = $('#bookingDetails input[name="pl_id"]').val(),
                            data['speed_park_active'] = $('#bookingDetails input[name="speed_park_active"]').val(),
                            data['aphactive'] = $('#bookingDetails input[name="aphactive"]').val(),
                            data['site_codename'] = $('#bookingDetails input[name="site_codename"]').val(),
                            data['sku'] = $('#bookingDetails input[name="sku"]').val(),
                               data['token'] ="test";


                            data['model'] = $('#vechile_detail input[name="model"]').val()
                            data['color'] = $('#vechile_detail input[name="color"]').val()
                            data['make'] = $('#vechile_detail input[name="make"]').val()
                            data['registration'] = $('#vechile_detail input[name="registration"]').val()
                            data['subscribe'] = $('#bookingDetails input[name="subscribe"]').val()
                            data['departterminal'] = $('#departterminal ').val()
                            data['arrivalterminal'] = $('#arrivalterminal').val()
                            data['returnflight'] = $('#returnflight').val()

                            data['address'] = $('#address').val()

                            data['fulladdress'] = $('#getaddress_dropdown option:selected').text()
                            data['address2'] = $('#address2').val()
                            data['town'] = $('#town').val()

                            data['post_code'] = $('#post_code').val()


                            //data['debug']       = 1
                            //}
//
                            //alert(result.token.id);
                            $.post('booking/payout', data, function (data) {
                                console.log("data===", data);

                                if (data.success == 0) {
                                    hideSpinner();
                                    alert(data.data);

                                    $("#error_personal_detail").html(data.data);
                                }else {
                                    hideSpinner();
                                    var refid = $('#referenceNo').val();
                                    window.location.href = "https://" + window.location.hostname + "/booking/thankyou/"+refid;

                                }

                            }, 'json');
                        
                         }
                                  });

                          
                        }
                        else {
                            hideSpinner();
                            var top = $('#personal_details_form').position().top;
                            $(window).scrollTop(top);
                        }
                    } else {
                        hideSpinner();
                        //vechile_detail
                        var top = $('#vechile_detail').position().top;
                        $(window).scrollTop(top);
                    }
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