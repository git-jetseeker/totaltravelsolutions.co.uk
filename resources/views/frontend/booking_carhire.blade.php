@extends('layouts.booking-shell')

@section('title', 'Car Hire Booking')

@section('stylesheets')
    <link property="stylesheet" rel="stylesheet" href="{{ asset('assets/page.css') }}" type="text/css" media="all" />
    <link rel="stylesheet" href="{{ asset('assets/payzone/payzone_gateway.css?v=1.2') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking.css?v=20261022mobilesummary') }}">
@endsection

@section('content')
@php
    $paymentType = $settings['payment_type'] ?? 'stripe';
    $bookingFee = (float) ($settings['booking_fee'] ?? 0);
    $cancelFee = (float) ($settings['cancellation_fee'] ?? 0);
    $smsFee = (float) ($settings['sms_notification_fee'] ?? 0);
    $totalAmount = (float) ($data['booking_amount'] ?? 0) + $bookingFee;
@endphp
<style>
    .lounge-checkout-page {
        background: #f4f7fa;
        padding: 24px 0 48px;
        min-height: calc(100vh - 72px);
        font-family: "Poppins", sans-serif;
    }
    .lounge-checkout-card {
        background: #fff;
        border: 1px solid #e6edf2;
        border-radius: 14px;
        padding: 26px 28px;
        margin-bottom: 22px;
        box-shadow: 0 2px 14px rgba(194, 24, 91, 0.06);
    }
    .lounge-checkout-card__title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .lounge-checkout-card__title i { color: #C2185B; }
    .lounge-checkout-card label {
        font-weight: 600;
        font-size: 14px;
        color: #333;
        margin-bottom: 7px;
        display: block;
    }
    .lounge-checkout-card .form-control {
        height: 48px;
        border: 1px solid #dadada;
        border-radius: 6px;
        box-shadow: none;
        font-size: 14px;
    }
    .lounge-checkout-card .form-control:focus {
        border-color: #C2185B;
        box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
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
    .lounge-pay-btn {
        width: 100%;
        min-height: 48px;
        background: #C2185B !important;
        border: none !important;
        color: #fff !important;
        font-weight: 700;
        border-radius: 8px !important;
    }
    .lounge-pay-btn:hover { background: #9a1349 !important; color: #fff !important; }
    .lounge-summary-card {
        background: #fff;
        border: 1px solid #e6edf2;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 14px rgba(194, 24, 91, 0.06);
        margin-bottom: 16px;
    }
    .lounge-summary-card__head {
        padding: 16px 20px;
        border-bottom: 1px solid #eef2f5;
        font-size: 17px;
        font-weight: 700;
    }
    .lounge-summary-card__body { padding: 18px 20px 20px; }
    .lounge-summary-badge {
        display: inline-block;
        background: #28a745;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 4px;
        margin-bottom: 6px;
    }
    .lounge-summary-name {
        margin: 0 0 4px;
        font-size: 18px;
        font-weight: 700;
        color: #C2185B;
    }
    .lounge-summary-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px solid #f0f4f7;
        font-size: 14px;
    }
    .lounge-summary-grand {
        display: flex;
        justify-content: space-between;
        margin-top: 14px;
        font-weight: 700;
        font-size: 18px;
    }
    .select-label { display: block; margin: 8px 0; font-size: 13px; font-weight: 600; }
    #error_personal_detail { color: #dc3545; font-weight: 600; text-align: center; margin-top: 10px; }
    .overlay { position: fixed; inset: 0; background: rgba(0,0,0,.35); z-index: 9998; }
    .checkout-progress { display: flex; gap: 16px; margin-bottom: 18px; font-weight: 700; flex-wrap: wrap; }
    .checkout-progress a { color: #C2185B; text-decoration: none; }
</style>

<div class="lounge-checkout-page">
    <div class="container container-min-w-booking">
        <nav class="checkout-progress" aria-label="Checkout steps">
            <a href="{{ route('carhire.search') }}"><span>1</span> Choose your car</a>
            <a href="{{ route('carhire.deal') }}"><span>2</span> View deal</a>
            <a href="#checkout-pay"><span>3</span> Reservation &amp; payment</a>
        </nav>
        <div class="row">
            <div class="col-xs-12 col-md-7 col-lg-8">
                <div class="lounge-checkout-card">
                    <h2 class="lounge-checkout-card__title"><i class="fa fa-user-circle"></i> Checkout details</h2>
                    <form id="personal_details_form">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <select required class="form-control" name="gender" id="title">
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="firstname">First name</label>
                                <input class="form-control" required type="text" name="firstname" id="firstname" autocomplete="given-name">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="lastname">Last name</label>
                                <input class="form-control" type="text" name="lastname" required id="lastname" autocomplete="family-name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uemail">Email address</label>
                            <input type="email" class="form-control" name="email" id="uemail" autocomplete="email" required>
                        </div>
                        <div class="form-group">
                            <label for="contactno">Phone number</label>
                            <input class="form-control" type="tel" name="contactno" maxlength="14" id="contactno" required autocomplete="tel">
                        </div>
                        <div class="form-group">
                            <label for="flight_number">Flight number (optional)</label>
                            <input class="form-control" type="text" name="flight_number" id="flight_number" placeholder="e.g. BA123">
                        </div>
                    </form>
                </div>

                <div class="lounge-checkout-card" id="checkout-pay">
                    <h2 class="lounge-checkout-card__title"><i class="fa fa-credit-card"></i> Payment Details</h2>
                    @if ($paymentType == 'stripe')
                        <div class="paymentFrm" id="paymentFrm">
                            <form method="post">
                                {{ csrf_field() }}
                                <input type="hidden" value="carhireBooking" name="action" id="action">
                                <input type="hidden" id="bookID" name="booking_id" value="{{ (int) ($draftBookingId ?? 0) }}">
                                <input type="hidden" id="referenceNo" name="reference_no" value="{{ $draftReferenceNo ?? '' }}">
                                <input type="hidden" id="intent_secret" name="intent_secret" value="">
                                <input type="hidden" id="intent_id" name="intent_id" value="">
                                <input type="hidden" id="quote_json" name="quote_json" value='@json($quoteSnapshot ?? [])'>
                                <div class="lounge-payment-box">
                                    <div class="lounge-payment-box__head"><i class="fa fa-credit-card"></i> Card</div>
                                    <div class="lounge-payment-box__body">
                                        <label for="card-element">Card details</label>
                                        <div id="card-element"></div>
                                    </div>
                                </div>
                                <div id="imgloader" style="display:none;">
                                    <img src="{{ url('theme/images/timeloader.gif') }}" style="width:50px;" alt="Loading">
                                </div>
                                <p>Please check pick-up and drop-off details before you continue.</p>
                                <button type="submit" id="bookingButton1" class="btn lounge-pay-btn">
                                    <i class="fa fa-lock"></i> <span id="pb">Pay Now</span>
                                </button>
                                <div id="error_personal_detail"></div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-md-5 col-lg-4">
                <div class="lounge-summary-card">
                    <div class="lounge-summary-card__head"><i class="fa fa-shopping-cart"></i> Booking Summary</div>
                    <div class="lounge-summary-card__body">
                        <span class="lounge-summary-badge">CAR HIRE</span>
                        <h3 class="lounge-summary-name">{{ $data['vehicle_name'] }}</h3>
                        <p>{{ $data['supplier_name'] }} @if(!empty($data['package_name'])) — {{ $data['package_name'] }} @endif</p>
                        <div class="lounge-summary-row"><span>Pick-up</span><span>{{ $data['pickup_location'] }}<br>{{ $data['pickup_date'] }} {{ $data['pickup_time'] }}</span></div>
                        <div class="lounge-summary-row"><span>Drop-off</span><span>{{ $data['dropoff_location'] }}<br>{{ $data['dropoff_date'] }} {{ $data['dropoff_time'] }}</span></div>
                        <div class="lounge-summary-row"><span>Driver age</span><span>{{ $data['driver_age'] }}</span></div>
                        @if(!empty($data['inclusions']) && is_array($data['inclusions']))
                            <div style="margin:8px 0 10px;">
                                <div style="font-size:11px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;color:#C2185B;margin-bottom:6px;">Included in the price</div>
                                <ul style="margin:0;padding-left:18px;color:#5f7382;font-size:12px;">
                                    @foreach($data['inclusions'] as $inc)
                                        <li>{{ is_array($inc) ? ($inc['name'] ?? '') : $inc }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(!empty($data['selected_extras']) && is_array($data['selected_extras']))
                            @foreach($data['selected_extras'] as $extra)
                                <div class="lounge-summary-row">
                                    <span>{{ $extra['name'] ?? $extra['code'] }} × {{ $extra['quantity'] ?? 1 }}</span>
                                    <strong>&pound;{{ number_format(((float) ($extra['price'] ?? 0)) * ((int) ($extra['quantity'] ?? 1)), 2) }}</strong>
                                </div>
                            @endforeach
                        @endif
                        @if(!empty($data['pay_local']) && (float) $data['pay_local'] > 0)
                            <div class="lounge-summary-row"><span>Due at pick-up</span><strong>&pound;{{ number_format((float) $data['pay_local'], 2) }}</strong></div>
                        @endif
                        <div class="lounge-summary-row"><span>You Pay Now</span><strong>&pound;{{ number_format((float) $data['booking_amount'], 2) }}</strong></div>
                        <div class="lounge-summary-row"><span>Booking Fee</span><strong id="bookfeeprice">&pound;{{ number_format($bookingFee, 2) }}</strong></div>
                        <label class="select-label">
                            <input class="feeinput" type="checkbox" id="cancelfee" name="cancelfee" value="{{ $cancelFee }}">
                            Add Cancellation Cover at only &pound;{{ number_format($cancelFee, 2) }}
                        </label>
                        <label class="select-label">
                            <input class="feeinput" type="checkbox" id="smsfee" name="smsfee" value="{{ $smsFee }}">
                            Add SMS confirmation at only &pound;{{ number_format($smsFee, 2) }}
                        </label>
                        <div class="lounge-summary-grand">
                            <span>Total to pay now</span>
                            <div class="total">&pound;<span id="totalPrice">{{ number_format($totalAmount, 2) }}</span></div>
                        </div>
                        <div id="bookingDetails" style="display:none;">
                            <input type="hidden" id="bookingprice" name="bookingprice" value="{{ $data['booking_amount'] }}">
                            <input type="hidden" id="alltotal" value="{{ $totalAmount }}">
                            <input type="hidden" id="disAmount" name="discount_amount" value="0">
                            <input type="hidden" name="product_code" value="{{ $data['rate_reference'] }}">
                            <input type="hidden" name="promo" value="{{ $data['promo'] }}">
                            <input type="hidden" name="bookingfor" value="carhire">
                            <input type="hidden" name="incomplete" id="incomplete" value="{{ !empty($draftReferenceNo) ? 'no' : 'yes' }}">
                        </div>
                        <p style="margin-top:14px;font-size:12px;color:#5f7382;">
                            By completing this booking you agree to our
                            <a href="{{ url('terms-and-conditions') }}" target="_blank">Terms &amp; Conditions</a>
                            and <a href="{{ url('privacy-policy') }}" target="_blank">Privacy Policy</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="overlay" style="display: none;"></div>
@endsection

@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/additional-methods.js"></script>
    <script>
        function showSpinner() { $(".overlay").show(); $("#imgloader").show(); }
        function hideSpinner() { $(".overlay").hide(); $("#imgloader").hide(); }

        function CheckoutData(smsfee, canfee) {
            showSpinner();
            $.post('{{ route('carhire.checkout') }}', {
                discount_amount: $('#disAmount').val(),
                booking_amount: $('#bookingprice').val(),
                bookingfor: 'carhire',
                smsfee: smsfee,
                canfee: canfee,
                action: 'booking_checkout',
                _token: "{{ csrf_token() }}"
            }, function (response) {
                $("#totalPrice").text(response.total_amount);
                $("#alltotal").val(response.total_amount);
                $("#intent_secret").val(response.intent_secret);
                $("#intent_id").val(response.intent_id);
                hideSpinner();
            }, 'json').fail(hideSpinner);
        }

        function ap_processCheckout() {
            CheckoutData($("#smsfee").is(':checked') ? 'Yes' : 'No', $("#cancelfee").is(':checked') ? 'Yes' : 'No');
        }

        $(".feeinput").click(ap_processCheckout);

        $(document).ready(function () {
            $("#personal_details_form").validate({
                rules: {
                    email: { required: true, email: true },
                    firstname: { required: true },
                    lastname: { required: true },
                    contactno: { required: true }
                }
            });
            ap_processCheckout();

            $('#personal_details_form').on('change blur', 'input, select', function () {
                if (!$("#personal_details_form").valid()) {
                    return;
                }
                $.post('{{ route('carhire.save') }}', {
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
                    _token: "{{ csrf_token() }}"
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
    @if ($paymentType == 'stripe')
        <script>
            window.STRIPE_PUBLIC_KEY = @json(config('services.stripe.key') ?: ($settings['stripe_public_key'] ?? $stripeKey ?? ''));
            var stripeKey = window.STRIPE_PUBLIC_KEY;
        </script>
        <script src="https://js.stripe.com/v3/"></script>
        <script src="{{ asset('assets/stripe/carhire-payment.js') }}"></script>
    @endif
@endsection
