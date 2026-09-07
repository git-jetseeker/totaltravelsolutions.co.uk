@extends('layouts.booking-shell')

@section('title', 'Lounge Booking')

@section('stylesheets')
    <link property="stylesheet" rel="stylesheet" href="{{ asset('assets/page.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('assets/payzone/payzone_gateway.css?v=1.2') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking.css?v=20250902l') }}">
@endsection

@section('content')
    @php
        $airportDetail = \App\Models\airport::find($data['airport']);
        $total_amount = ($data['booking_amount'] ?? 0) + ($data['discount_amount'] ?? 0);
        $guestParts = [];
        if ((int) ($data['adults'] ?? 0) > 0) {
            $guestParts[] = $data['adults'] . ' Adult' . ((int) $data['adults'] > 1 ? 's' : '');
        }
        if ((int) ($data['children'] ?? 0) > 0) {
            $guestParts[] = $data['children'] . ' Child' . ((int) $data['children'] > 1 ? 'ren' : '');
        }
        if ((int) ($data['infants'] ?? 0) > 0) {
            $guestParts[] = $data['infants'] . ' Infant' . ((int) $data['infants'] > 1 ? 's' : '');
        }
        $guestLabel = count($guestParts) ? implode(', ', $guestParts) : '1 Adult';
        $cancellationLabel = trim((string) ($data['cancellation_label'] ?? ''));
    @endphp

    <style>
        .lounge-checkout-page {
            background: #f4f7fa;
            padding: 24px 0 48px;
            min-height: calc(100vh - 72px);
        }

        .lounge-checkout-card {
            background: #fff;
            border: 1px solid #e6edf2;
            border-radius: 14px;
            padding: 26px 28px;
            margin-bottom: 22px;
            box-shadow: 0 2px 14px rgba(65, 105, 225, 0.06);
        }

        .lounge-checkout-card__title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 20px;
            text-align: left;
        }

        .lounge-checkout-card label {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 7px;
            text-align: left;
            display: block;
        }

        .lounge-checkout-card .form-control,
        .lounge-checkout-card .bf-slctfld,
        .lounge-checkout-card .bf-inptfld {
            height: 48px;
            border: 1px solid #dadada;
            border-radius: 6px;
            box-shadow: none;
            font-size: 14px;
        }

        .lounge-checkout-card .form-control:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.1);
        }

        .lounge-payment-box {
            border: 1px solid #e0e6eb;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .lounge-payment-box__head {
            background: #f8fafc;
            padding: 12px 16px;
            font-weight: 600;
            color: #333;
            border-bottom: 1px solid #e0e6eb;
        }

        .lounge-payment-box__body { padding: 16px; }

        .lounge-card-logos img { max-width: 220px; height: auto; }

        .lounge-pay-btn {
            width: 100%;
            min-height: 48px;
            background: #C2185B !important;
            border: none !important;
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px !important;
        }

        .lounge-pay-btn:hover { background: #3050c8 !important; color: #fff !important; }

        .lounge-summary-card {
            background: #fff;
            border: 1px solid #e6edf2;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 14px rgba(65, 105, 225, 0.06);
            margin-bottom: 16px;
        }

        .lounge-summary-card__head {
            padding: 16px 20px;
            border-bottom: 1px solid #eef2f5;
            font-size: 17px;
            font-weight: 700;
            color: #1a1a1a;
            text-align: left;
        }
        .lounge-summary-card__body { padding: 18px 20px 20px; }

        .lounge-summary-top {
            display: flex;
            gap: 14px;
            margin-bottom: 16px;
        }

        .lounge-summary-top img {
            width: 96px;
            height: 72px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e8eef2;
            flex-shrink: 0;
        }

        .lounge-summary-badge {
            display: inline-block;
            background: #C2185B;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 6px;
        }

        .lounge-summary-name {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 4px;
            line-height: 1.35;
        }

        .lounge-summary-terminal,
        .lounge-summary-location {
            font-size: 13px;
            color: #7a8c9e;
            margin: 0 0 4px;
        }

        .lounge-summary-rows {
            border-top: 1px solid #eef2f5;
            padding-top: 14px;
            margin-bottom: 14px;
        }

        .lounge-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 13px;
            color: #555;
            margin-bottom: 10px;
        }

        .lounge-summary-row span:last-child {
            color: #1a1a1a;
            font-weight: 500;
            text-align: right;
        }

        .lounge-summary-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        .lounge-summary-total-row strong { color: #1a1a1a; font-size: 16px; }

        .lounge-summary-grand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 2px solid #eef2f5;
            margin-top: 4px;
        }

        .lounge-summary-grand .total {
            font-size: 22px;
            font-weight: 800;
            color: #C2185B;
        }

        .lounge-summary-extras {
            margin: 14px 0;
            padding: 12px 0;
            border-top: 1px solid #eef2f5;
        }

        .lounge-summary-extras label {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #444 !important;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .lounge-summary-legal {
            font-size: 11px;
            color: #9aa8b5;
            margin-top: 14px;
            line-height: 1.5;
        }

        .lounge-summary-legal a { color: #C2185B; }

        .StripeElement {
            box-sizing: border-box;
            height: 44px;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            background: #fff;
        }

        #imgloader { text-align: center; margin: 10px 0; }

        .lounge-checkout-card label.error,
        .lounge-checkout-card .error-message,
        #paymentFrm .error,
        #paymentFrm .error .message,
        #error_personal_detail {
            display: block;
            color: #dc3545 !important;
            font-size: 13px;
            font-weight: 600;
            margin-top: 6px;
            text-align: left;
            line-height: 1.35;
        }

        #paymentFrm .error {
            margin-top: 10px;
        }

        #paymentFrm .error:not(.visible):empty,
        #paymentFrm .error:not(.visible) .message:empty {
            display: none;
        }

        #paymentFrm .error.visible {
            display: block;
        }

        #error_personal_detail:empty {
            display: none;
        }

        .lounge-checkout-card .form-control.error,
        .lounge-checkout-card select.error,
        .lounge-checkout-card input.error,
        .lounge-checkout-card .form-control.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.12);
        }

        .lounge-payment-box__body.has-card-error #card-element,
        #card-element.StripeElement--invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.12);
        }

        @media (max-width: 991px) {
            .lounge-checkout-page { padding-top: 16px; }
            .lounge-checkout-card { padding: 20px 18px; }
        }
    </style>

    <div class="lounge-checkout-page js-booking-page">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-7 col-lg-8">
                    <div class="lounge-checkout-card">
                        <h2 class="lounge-checkout-card__title">Personal Information</h2>
                    <form id="personal_details_form">
                            <input type="hidden" name="bookfhrSearchId" id="bookfhrSearchId" value="{{ $data['bookfhrSearchId'] ?? '' }}">
                            <input type="hidden" name="bookfhrOptionId" id="bookfhrOptionId" value="{{ $data['bookfhrOptionId'] ?? '' }}">

                            <div class="form-group">
                                <label for="title">Title</label>
                                <select required class="form-control" name="title" id="title">
                                    <option value="">Select title</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Mrs">Mrs</option>
                                    <option value="Miss">Miss</option>
                                    <option value="Ms">Ms</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label for="firstname">First name</label>
                                    <input class="form-control" required type="text" name="firstname" id="firstname" autocomplete="given-name" maxlength="60">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label for="lastname">Last name</label>
                                    <input class="form-control" type="text" name="lastname" required id="lastname" autocomplete="family-name" maxlength="60">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" autocomplete="email" value="{{ $data['email'] ?? '' }}" required maxlength="120">
                            </div>

                            <div class="form-group">
                                <label for="contactno">Phone number</label>
                                <input class="form-control" type="tel" name="contactno" maxlength="14" id="contactno" required autocomplete="tel" placeholder="e.g. 07123456789">
                            </div>
                        </form>
                        </div>

                    <div class="lounge-checkout-card" id="checkout-pay">
                        <h2 class="lounge-checkout-card__title">Payment Details</h2>

                        @if (($settings['payment_type'] ?? '') == 'stripe')
                    <div class="paymentFrm" id="paymentFrm">
                        <form method="post">
                            {{ csrf_field() }}
                            <div id="creditDiv">
                                        <input type="hidden" value="airportLoungesBooking" name="action" id="action">
                                        <input type="hidden" id="bookID" name="booking_id" value="0">
                                        <input type="hidden" id="referenceNo" name="reference_no" value="">
                                        <input type="hidden" id="intent_secret" name="intent_secret" value="">
                                        <input type="hidden" id="intent_id" name="intent_id" value="">
                                    </div>

                                    <div class="lounge-payment-box">
                                        <div class="lounge-payment-box__head">Card</div>
                                        <div class="lounge-payment-box__body">
                                            <label for="card-element">Card details</label>
                                            <div id="card-element"></div>
                                            <div class="lounge-card-logos" style="margin-top:12px;">
                                                <img src="{{ asset('assets/payzone/images/payzone_cards_accepted.png') }}" alt="Accepted cards">
                                            </div>
                                        </div>
                                    </div>

                                    <p style="text-align:center;font-size:14px;color:#555;margin:12px 0;">
                                        Your card will be charged <strong>&pound;<span id="ccPrice">0.00</span></strong>
                                    </p>

                                    <div id="imgloader" style="display:none;">
                                        <img src="{{ asset('theme/images/timeloader.gif') }}" style="width:50px;" alt="Loading">
                                    </div>

                                    <button type="submit" id="bookingButton" class="btn lounge-pay-btn">
                                        Confirm Booking
                                    </button>

                                    <div class="error" role="alert" aria-live="polite"><span class="message"></span></div>
                                    <div id="error_personal_detail" role="alert" aria-live="polite"></div>
                                </form>
                            </div>
                        @endif
                    </div>
                                    </div>

                <div class="col-xs-12 col-md-5 col-lg-4">
                    <div class="lounge-summary-card">
                        <div class="lounge-summary-card__head">Booking Summary</div>
                        <div class="lounge-summary-card__body">
                            <div class="lounge-summary-top">
                                <img src="{{ $data['logobooking2'] }}" alt="{{ $data['lounge_name'] }}" onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';">
                                <div>
                                    <span class="lounge-summary-badge">LOUNGE</span>
                                    <h3 class="lounge-summary-name">{{ $data['lounge_name'] }}</h3>
                                    @if (!empty($data['terminal']))
                                        <p class="lounge-summary-terminal">{{ $data['terminal'] }}</p>
                                    @endif
                                    @if (!empty($data['location_info']))
                                        <p class="lounge-summary-location">{{ \Illuminate\Support\Str::limit(strip_tags($data['location_info']), 90) }}</p>
                                    @endif
                                </div>
                                </div>

                            <div class="lounge-summary-rows">
                                <div class="lounge-summary-row">
                                    <span>Location</span>
                                    <span>{{ $airportDetail->name ?? 'N/A' }}</span>
                                </div>
                                <div class="lounge-summary-row">
                                    <span>Check-in</span>
                                    <span>{{ $data['checkin_date'] }} at {{ $data['checkin_time'] }}</span>
                                    </div>
                                <div class="lounge-summary-row">
                                    <span>Guests</span>
                                    <span>{{ $guestLabel }}</span>
                                </div>
                                @if ($cancellationLabel !== '')
                                    <div class="lounge-summary-row">
                                        <span>Cancellation</span>
                                        <span>{{ $cancellationLabel }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="lounge-summary-total-row">
                                <span>Lounge Price</span>
                                <strong>&pound;{{ number_format($total_amount, 2) }}</strong>
                            </div>

                            @if (($data['discount_amount'] ?? 0) > 0)
                                <div class="lounge-summary-total-row" id="disfee">
                                    <span>Discount</span>
                                    <strong style="color:#28a745;">- &pound;<span id="disfeeprice">{{ number_format($data['discount_amount'], 2) }}</span></strong>
                                </div>
                            @endif

                            <div class="lounge-summary-total-row">
                                <span>Booking Fee</span>
                                <strong id="bookfeeprice">&pound;{{ $settings['booking_fee'] ?? '0.00' }}</strong>
                            </div>

                            <div class="lounge-summary-extras">
                                @if (!empty($settings['cancellation_fee']))
                                    <label>
                                        <input class="feeinput" type="checkbox" id="cancelfee" name="cancelfee" value="{{ $settings['cancellation_fee'] }}">
                                        Add Cancellation Cover at only &pound;{{ $settings['cancellation_fee'] }}
                                    </label>
                                @endif
                                @if (!empty($settings['sms_notification_fee']))
                                    <label>
                                        <input class="feeinput" type="checkbox" id="smsfee" name="smsfee" value="{{ $settings['sms_notification_fee'] }}">
                                        Add SMS confirmation at only &pound;{{ $settings['sms_notification_fee'] }}
                                    </label>
                                @endif
                            </div>

                            <div class="lounge-summary-grand">
                                <span>Total to pay now</span>
                                <div class="total">&pound;<span id="totalPrice">0.00</span></div>
                            </div>

                            <div id="bookingDetails" style="display:none;">
                                <input type="hidden" id="bookingprice" name="bookingprice" value="{{ $data['booking_amount'] }}">
                                <input type="hidden" id="alltotal" value="{{ $data['booking_amount'] }}">
                                <input type="hidden" id="disAmount" name="discount_amount" value="{{ $data['discount_amount'] }}">
                            <input type="hidden" name="company_id" value="{{ $data['company_id'] }}">
                            <input type="hidden" name="product_code" value="{{ $data['product_code'] }}">
                            <input type="hidden" name="lounge_name" value="{{ $data['lounge_name'] }}">
                            <input type="hidden" name="terminal" value="{{ $data['terminal'] }}">
                            <input type="hidden" name="checkin_date" value="{{ $data['checkin_date'] }}">
                            <input type="hidden" name="checkin_time" value="{{ $data['checkin_time'] }}">
                            <input type="hidden" name="adults" value="{{ $data['adults'] }}">
                            <input type="hidden" name="children" value="{{ $data['children'] }}">
                            <input type="hidden" name="infants" value="{{ $data['infants'] ?? 0 }}">
                            <input type="hidden" name="airport" value="{{ $data['airport'] }}">
                            <input type="hidden" name="promo" value="{{ $data['discount_code'] }}">
                            <input type="hidden" name="pl_id" value="{{ $data['pl_id'] }}">
                            <input type="hidden" name="site_codename" value="">
                            <input type="hidden" name="park_api" value="{{ $data['park_api'] }}">
                            <input type="hidden" name="bookfhrSearchId" value="{{ $data['bookfhrSearchId'] ?? '' }}">
                            <input type="hidden" name="bookfhrOptionId" value="{{ $data['bookfhrOptionId'] ?? '' }}">
                            <input type="hidden" name="bookingfor" value="lounge">
                            <input type="hidden" name="incomplete" id="incomplete" value="yes">
                            </div>

                            <p class="lounge-summary-legal">
                                By completing this booking you agree to our
                                <a href="{{ url('terms-and-conditions') }}" target="_blank">Terms &amp; Conditions</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overlay" style="display:none;"></div>
@endsection

@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.js"></script>

    <script>
        function valid_address() { return true; }
        function validate_vechiledetail() { return true; }

        function showSpinner() {
            $(".overlay").show();
            $("#imgloader").show();
        }

        function hideSpinner() {
            $(".overlay").hide();
            $("#imgloader").hide();
        }

        function ap_processCheckout() {
            var smsfee = $("#smsfee").is(':checked') ? 'Yes' : 'No';
            var canfee = $("#cancelfee").is(':checked') ? 'Yes' : 'No';
            CheckoutData(smsfee, canfee);
        }

        $(".feeinput").on('click', function () {
            ap_processCheckout();
        });

        function CheckoutData(smsfee, canfee) {
            showSpinner();

            $.post('{{ route('lounge_checkout') }}', {
                discount: 1,
                promo: $('#bookingDetails input[name="promo"]').val(),
                discount_amount: $('#bookingDetails input[name="discount_amount"]').val(),
                booking_amount: $('#bookingprice').val(),
                airport: $('#bookingDetails input[name="airport"]').val(),
                company_id: $('#bookingDetails input[name="company_id"]').val(),
                product_code: $('#bookingDetails input[name="product_code"]').val(),
                checkin_date: $('#bookingDetails input[name="checkin_date"]').val(),
                checkin_time: $('#bookingDetails input[name="checkin_time"]').val(),
                adults: $('#bookingDetails input[name="adults"]').val(),
                children: $('#bookingDetails input[name="children"]').val(),
                infants: $('#bookingDetails input[name="infants"]').val(),
                pl_id: $('#bookingDetails input[name="pl_id"]').val(),
                site_codename: $('#bookingDetails input[name="site_codename"]').val(),
                bookingfor: $('#bookingDetails input[name="bookingfor"]').val(),
                total_amount: {{ $total_amount }},
                park_api: $('#bookingDetails input[name="park_api"]').val(),
                smsfee: smsfee,
                canfee: canfee,
                action: 'booking_checkout',
                _token: "{{ csrf_token() }}"
            }, function (response) {
                $("#totalPrice").text(parseFloat(response.total_amount).toFixed(2));
                $("#ccPrice").text(parseFloat(response.total_amount).toFixed(2));
                $("#alltotal").val(response.total_amount);
                $("#disAmount").val(response.discount_amount);
                $("#intent_secret").val(response.intent_secret);
                $("#intent_id").val(response.intent_id);

                if (response.booking_amount > 0) {
                    $("#bookingprice").val(response.booking_amount);
                }

                if (response.discount_amount > 0) {
                    $("#disfeeprice").text(parseFloat(response.discount_amount).toFixed(2));
                    $("#disfee").show();
                } else {
                    $("#disfee").hide();
                }

                if (response.booking_fee > 0) {
                    $("#bookfeeprice").text('£' + response.booking_fee);
                }

                hideSpinner();
            }, 'json').fail(function () {
                hideSpinner();
            });
        }

        $(document).ready(function () {
            if ($.validator && !$.validator.methods.phoneUKLoose) {
                $.validator.addMethod('phoneUKLoose', function (value, element) {
                    if (this.optional(element)) {
                        return true;
                    }
                    var digits = String(value).replace(/[^\d]/g, '');
                    return digits.length >= 10 && digits.length <= 14;
                }, 'Please enter a valid phone number.');
            }

            $("#personal_details_form").validate({
                ignore: [],
                errorElement: 'label',
                errorClass: 'error',
                validClass: 'valid',
                focusInvalid: true,
                rules: {
                    title: { required: true },
                    firstname: {
                        required: true,
                        minlength: 2,
                        maxlength: 60
                    },
                    lastname: {
                        required: true,
                        minlength: 2,
                        maxlength: 60
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 120
                    },
                    contactno: {
                        required: true,
                        phoneUKLoose: true
                    }
                },
                messages: {
                    title: {
                        required: 'Please select a title.'
                    },
                    firstname: {
                        required: 'Please enter your first name.',
                        minlength: 'First name must be at least 2 characters.',
                        maxlength: 'First name cannot exceed 60 characters.'
                    },
                    lastname: {
                        required: 'Please enter your last name.',
                        minlength: 'Last name must be at least 2 characters.',
                        maxlength: 'Last name cannot exceed 60 characters.'
                    },
                    email: {
                        required: 'Please enter your email address.',
                        email: 'Please enter a valid email address.',
                        maxlength: 'Email cannot exceed 120 characters.'
                    },
                    contactno: {
                        required: 'Please enter your phone number.',
                        phoneUKLoose: 'Please enter a valid phone number (at least 10 digits).'
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                },
                highlight: function (element) {
                    $(element).addClass('error is-invalid').removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('error is-invalid').addClass('valid');
                },
                invalidHandler: function () {
                    $('#error_personal_detail').html('Please fix the highlighted fields below.');
                },
                submitHandler: function () {
                    return false;
                }
            });

            ap_processCheckout();

            $('#contactno').on('change blur', function () {
                if (!$("#personal_details_form").valid()) {
                    $('#error_personal_detail').html('Please complete all required personal details.');
                    return;
                }

                $('#error_personal_detail').html('');

                $.post('{{ route('checkBookingLounge') }}', {
                    title: $('#title').val(),
                    firstname: $('#firstname').val(),
                    lastname: $('#lastname').val(),
                    email: $('#email').val(),
                    contactno: $('#contactno').val(),
                    action: $('#action').val(),
                    reference_no: $('#referenceNo').val(),
                    booking_fee: "{{ $settings['booking_fee'] ?? 0 }}",
                    _token: "{{ csrf_token() }}",
                    booking_id: $('#bookID').val(),
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
                    smsfee: $("#smsfee").is(':checked') ? 'Yes' : 'No',
                    canfee: $("#cancelfee").is(':checked') ? 'Yes' : 'No',
                    incomplete: $('#incomplete').val(),
                    pl_id: $('#bookingDetails input[name="pl_id"]').val(),
                    bookfhrSearchId: $('#bookingDetails input[name="bookfhrSearchId"]').val(),
                    bookfhrOptionId: $('#bookingDetails input[name="bookfhrOptionId"]').val(),
                    intent_id: $('#intent_id').val()
                }, function (result) {
                    if (result.booking_id > 0 && result.available == "Yes") {
                        $("#bookID").val(result.booking_id);
                        $("#referenceNo").val(result.reference_no);
                        $("#incomplete").val('no');
                    }
                }, 'json');
            });
        });
    </script>

    @if (($settings['payment_type'] ?? '') == 'stripe')
        <script src="https://js.stripe.com/v3/"></script>
        <script>window.STRIPE_PUBLIC_KEY = @json(config('services.stripe.key'));</script>
        <script src="{{ asset('assets/stripe/checkout_lounge.js?v=127') }}"></script>
    @endif
@endsection
