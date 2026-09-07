{{-- Booking summary sidebar — OneStopBee-style layout, Total Travel Solutions colors --}}
@php
    use Illuminate\Support\Str;

    $companyRecord = $company ?? null;
    $logoPath = $data['logo'] ?? ($companyRecord->logo ?? '');
    $parkApi = $data['park_api'] ?? ($companyRecord->park_api ?? '');
    $parkingType = $data['parking_type'] ?? ($companyRecord->parking_type ?? '');
    $logobooking = (string) ($data['logobooking'] ?? '0');
    $aphId = $companyRecord->aph_id ?? '';
    $companyName = $data['company_name'] ?? ($companyRecord->name ?? 'Parking');
    $summaryLogoUrl = null;

    if ($logobooking === '1') {
        $summaryLogoUrl = asset('storage/app/companies/Park_Ride.jpg');
    } elseif ($logobooking === '2') {
        $summaryLogoUrl = asset('storage/app/companies/Meet_Greet_Logo.jpg');
    } elseif ($aphId === '' || $aphId === null) {
        if ($logoPath === '') {
            $summaryLogoUrl = $parkingType === 'Park and Ride'
                ? asset('storage/app/companies/Park_Ride.jpg')
                : asset('storage/app/companies/Meet_Greet_Logo.jpg');
        } else {
            $containsCompanies = Str::contains($logoPath, 'companies');
            if ($parkApi === 'holiday' && ! $containsCompanies) {
                $summaryLogoUrl = $logoPath;
            } elseif ($parkApi === 'a2z' && ! $containsCompanies) {
                $summaryLogoUrl = $logoPath;
            } elseif ($parkApi === 'Opitech') {
                $summaryLogoUrl = $logoPath;
            } else {
                $summaryLogoUrl = 'https://www.dashboard.jetseekergroup.com/' . str_replace('public/', 'storage/', $logoPath);
            }
        }
    } elseif ($logoPath === '') {
        $summaryLogoUrl = $parkingType === 'Park and Ride'
            ? asset('storage/app/companies/Park_Ride.jpg')
            : asset('storage/app/companies/Meet_Greet_Logo.jpg');
    } else {
        $summaryLogoUrl = Str::startsWith($logoPath, 'http') ? $logoPath : 'https:' . ltrim($logoPath, ':');
    }

    if (empty($summaryLogoUrl)) {
        $summaryLogoUrl = asset('theme/images/logo-black.png');
    }

    $airport = \App\Models\airport::find($data['airport']);
    $bookingPriceDisplay = number_format($customerParkingPrice + ($data['discount_amount'] ?? 0), 2);
    $logoFallback = asset('theme/images/logo-black.png');
    $initialTotal = number_format(($data['booking_amount'] ?? 0) + ($data['booking_fee'] ?? 0), 2);
@endphp

<aside class="side-bar js-booking-summary" id="show_cart">
    <div class="section-borders js-booking-summary-card">
        <div class="cardDetail apb-summary-header">
            <div class="js-booking-summary-logo">
                <img
                    class="parkImg img-fluid"
                    src="{{ $summaryLogoUrl }}"
                    alt="{{ $companyName }}"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='{{ $logoFallback }}';"
                >
            </div>
            <div class="js-booking-summary-info">
                <h4 class="booking-second-sec-h2" id="company_name">{{ $companyName }}</h4>
                <p class="booking-second-sec-p">{{ $parkingType }} Service</p>
            </div>
        </div>

        <div class="departCont">
            <table class="w-100">
                <tbody>
                    <tr>
                        <td class="booking-second-sec-table"><b>Airport</b></td>
                        <td class="booking-second-sec-table-2 text-right">{{ $airport->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="booking-second-sec-table"><b>Drop-Off</b></td>
                        <td class="booking-second-sec-table-2 text-right">
                            {{ \Carbon\Carbon::parse($data['dropdate'])->format('D d M Y') }} at {{ $data['droptime'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="noOfDateCont">
            <table class="w-100">
                <tbody>
                    <tr>
                        <td class="booking-second-sec-table"><b>Return</b></td>
                        <td class="booking-second-sec-table-2 text-right">
                            {{ \Carbon\Carbon::parse($data['pickdate'])->format('D d M Y') }} at {{ $data['picktime'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="booking-second-sec-table"><b>No of Days</b></td>
                        <td class="booking-second-sec-table-2 text-right">{{ $data['total_days'] }}</td>
                    </tr>
                    <tr>
                        <td class="booking-second-sec-table"><b>Booking Price</b></td>
                        <td class="booking-second-sec-table-2 text-right">&pound;{{ $bookingPriceDisplay }}</td>
                    </tr>
                    @if (($data['discount_amount'] ?? 0) > 0)
                        <tr class="apb-discount-row">
                            <td class="booking-second-sec-table">Discount price</td>
                            <td class="booking-second-sec-table-2 text-right">&minus; &pound;{{ $data['discount_amount'] }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="booking-second-sec-table"><b>Booking Fee</b></td>
                        <td class="booking-second-sec-table-2 text-right">&pound;{{ $data['booking_fee'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="apb-sidebar-extras">
            <label class="select-label text-white">
                <input class="feeinput" type="checkbox" id="smsfee" name="smsfee" value="{{ $settings['sms_notification_fee'] }}">
                Add SMS confirmation at only &pound;{{ $settings['sms_notification_fee'] }}
                <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Why not have your booking details sent direct to your mobile, for a quick and easy check in."></span>
            </label>
            <label class="select-label text-white">
                <input class="feeinput" type="checkbox" id="cancelfee" name="cancelfee" value="{{ $settings['cancellation_fee'] }}">
                Add Cancellation Cover at only &pound;{{ $settings['cancellation_fee'] }}
                <span class="cls-pointer fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Our cancellation cover protects you if you do need to cancel or amend your booking."></span>
            </label>
        </div>

        <div class="apb-summary-total-row booking-second-sec-table-total">
            <span class="apb-summary-total-label booking-second-sec-table"><b>Total</b></span>
            <span class="apb-summary-total-value booking-second-sec-table-2">
                &pound;<span id="totalPrice" class="total">{{ $initialTotal }}</span>
            </span>
        </div>

        <div id="bookingDetails">
            <div id="disamount">
                <input type="hidden" id="disAmount" name="discount_amount" value="{{ $data['discount_amount'] }}">
            </div>
            <input type="hidden" id="bookingprice" value="{{ $data['booking_amount'] }}">
            <input type="hidden" id="alltotal" value="{{ $data['booking_amount'] }}">
            <input type="hidden" name="company_id" value="{{ $data['company_id'] }}">
            <input type="hidden" name="product_code" value="{{ $data['product_code'] }}">
            <input type="hidden" name="parking_type" value="{{ $data['parking_type'] }}">
            <input type="hidden" name="pickdate" value="{{ $data['pickdate'] }}">
            <input type="hidden" name="dropdate" value="{{ $data['dropdate'] }}">
            <input type="hidden" name="droptime" value="{{ $data['droptime'] }}">
            <input type="hidden" name="email" value="{{ $data['email'] }}">
            <input type="hidden" name="picktime" value="{{ $data['picktime'] }}">
            <input type="hidden" name="total_days" value="{{ $data['total_days'] }}">
            <input type="hidden" name="airport" value="{{ $data['airport'] }}">
            <input type="hidden" name="promo" value="{{ $data['discount_code'] }}">
            <input type="hidden" name="pl_id" value="{{ $data['pl_id'] }}">
            <input type="hidden" name="site_codename" value="">
            <input type="hidden" name="bookingfor" value="airport_parking">
            <input type="hidden" name="incomplete" id="incomplete" value="yes">
            <input type="hidden" name="aphactive" id="aphactivebook" value="{{ $data['aphactive'] }}">
            <input type="hidden" name="park_api" value="{{ $data['park_api'] }}">
            <input type="hidden" name="new_price" value="{{ $data['new_price'] ?? ($customerParkingPrice ?? $data['booking_amount']) }}">
            <input type="hidden" name="bookfhrSearchId" id="bookfhrSearchId" value="{{ $data['bookfhrSearchId'] ?? '' }}">
            <input type="hidden" name="bookfhrOptionId" id="bookfhrOptionId" value="{{ $data['bookfhrOptionId'] ?? '' }}">
            <input type="hidden" name="g_quote" value="{{ $data['g_quote'] }}">
            <input type="hidden" name="g_token" value="{{ $data['g_token'] }}">
            <input type="hidden" id="src" value="{{ $data['src'] ?? 'ORG' }}">
        </div>
    </div>
</aside>
