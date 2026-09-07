@php
    use App\Support\HotelRoomFacilities;

    $gallery = \App\Models\Hotel::normalizeImageList($hotel->images ?? []);
    if (empty($gallery) && !empty($hotel->image)) {
        $gallery = \App\Models\Hotel::normalizeImageList([$hotel->image]);
    }
    $mainImage = $gallery[0] ?? (\App\Models\Hotel::formatImageUrl($hotel->image ?? null) ?: asset('favicon.ico'));
    $thumbs = array_slice($gallery, 0, 8);
    $stars = (int) ($hotel->starRating ?? 0);
    $checkInDisplay = !empty($hotel->checkIn) ? date('H:i', strtotime($hotel->checkIn)) : '15:00';
    $checkOutDisplay = !empty($hotel->checkOut) ? date('H:i', strtotime($hotel->checkOut)) : '11:00';
    $address = preg_replace('/\s+/', ' ', strip_tags($hotel->address ?? ($airport_detail->name ?? '')));
    $description = trim(strip_tags($hotel->description ?? ''));

    $checkinRaw = $request->input('hotel_checkin_date');
    $checkoutRaw = $request->input('hotel_checkout_date');
    $stayNights = (int) ($hotel->nights ?? 0);
    if ($stayNights < 1) {
        $fromTs = strtotime(str_replace('/', '-', (string) $checkinRaw));
        $toTs = strtotime(str_replace('/', '-', (string) $checkoutRaw));
        $stayNights = ($fromTs && $toTs && $toTs > $fromTs)
            ? max(1, (int) round(($toTs - $fromTs) / 86400))
            : 1;
    }

    $boardTypes = array_values(array_filter($hotel->board_types ?? []));
    if (empty($boardTypes)) {
        foreach ($hotel->options ?? [] as $opt) {
            $plan = trim((string) ($opt->plan_type ?? ''));
            if ($plan !== '') {
                $boardTypes[$plan] = $plan;
            }
        }
        $boardTypes = array_values($boardTypes);
    }

    $policyPills = [];
    if (!empty($hotel->cancellation_label)) {
        $policyPills[] = $hotel->cancellation_label;
    }
    foreach ($hotel->options ?? [] as $opt) {
        if (!empty($opt->non_refundable)) {
            $policyPills[] = 'Non-refundable';
        } elseif (!empty($opt->cancellation_until)) {
            try {
                $policyPills[] = 'Cancellation allowed until: ' . \Carbon\Carbon::parse($opt->cancellation_until)->format('d-m-Y');
            } catch (\Throwable $e) {
                // skip unparseable dates
            }
        } elseif (!empty($opt->free_cancellation)) {
            $policyPills[] = 'Free cancellation';
        }
    }
    $policyPills = array_values(array_unique($policyPills));

    $statLabels = [
        'renovated' => 'Renovated',
        'min_checkin_age' => 'Min. check-in age',
        'rooms' => 'Rooms',
        'floors' => 'Floors',
        'twin_rooms' => 'Twin rooms',
        'double_rooms' => 'Double rooms',
        'executive_rooms' => 'Executive rooms',
    ];
    $hotelStats = is_array($hotel->hotel_stats ?? null) ? $hotel->hotel_stats : [];
@endphp

@extends('layouts.booking-shell')

@section('title', ($hotel->name ?? 'Hotel'))

@section('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection

@section('content')

<style>
    .js-hotel-detail-page {
        background: #f0f4f8;
        padding: 24px 0 60px;
        min-height: calc(100vh - 72px);
    }

    .js-hotel-summary {
        background: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 16px rgba(65, 105, 225, 0.08);
        border: 1px solid #e8eef2;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .js-hotel-summary__left {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 14px;
    }

    .js-hotel-summary__tab {
        background: #C2185B;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        text-transform: uppercase;
    }

    .js-hotel-summary__meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 18px;
        font-size: 14px;
        color: #4a5a6a;
    }

    .js-hotel-summary__back {
        background: #C2185B !important;
        color: #fff !important;
        border-radius: 50px !important;
        padding: 8px 18px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }

    .js-hotel-detail-card {
        display: grid;
        grid-template-columns: minmax(280px, 0.9fr) 1.1fr;
        gap: 28px;
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(2, 47, 72, 0.07);
        border: 1px solid #e6edf2;
        margin-bottom: 28px;
    }

    .js-hotel-detail-media__main {
        border-radius: 10px;
        overflow: hidden;
        background: #dce8f0;
        min-height: 280px;
    }

    .js-hotel-detail-media__main img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        display: block;
    }

    .js-hotel-detail-thumbs {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .js-hotel-detail-thumbs img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 8px;
        flex: 0 0 64px;
        background: #dce8f0;
    }

    .js-hotel-detail-info__name {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        color: #C2185B;
        line-height: 1.25;
    }

    .js-hotel-detail-info__stars {
        color: #f5a820;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .js-hotel-detail-info__address,
    .js-hotel-detail-info__times {
        color: #6b7785;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .js-hotel-detail-info__desc {
        color: #5c6b78;
        font-size: 14px;
        line-height: 1.7;
        margin: 12px 0 0;
    }

    .js-hotel-listing__pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 6px 0 2px;
    }

    .js-hotel-pill {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
        background: #eef1f4;
        color: #3d4a56;
    }

    .js-hotel-pill--plan {
        background: #e8efff;
        color: #C2185B;
    }

    .js-hotel-facilities {
        background: #fff;
        border: 1px solid #e6edf2;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 28px;
    }

    .js-hotel-facilities h2 {
        margin: 0 0 16px;
        font-size: 24px;
        font-weight: 800;
        color: #C2185B;
    }

    .js-hotel-facilities__grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }

    .js-hotel-stat {
        background: #fff;
        border: 1px solid #d9e2ea;
        border-radius: 10px;
        padding: 14px;
        text-align: center;
    }

    .js-hotel-stat__value {
        display: block;
        font-size: 18px;
        font-weight: 700;
        color: #1e2a33;
    }

    .js-hotel-stat__label {
        display: block;
        font-size: 12px;
        color: #7a8c9e;
        margin-top: 4px;
    }

    .js-hotel-rooms h3 {
        font-size: 24px;
        font-weight: 800;
        margin: 0 0 18px;
        color: #C2185B;
    }

    .js-hotel-room-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .js-hotel-room-card {
        background: #fff;
        border: 1px solid #e8ebef;
        border-radius: 14px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: relative;
    }

    .js-hotel-room-card--popular {
        border: 2px solid #C2185B;
    }

    .js-hotel-room-card__tag {
        position: absolute;
        top: -12px;
        left: 18px;
        background: #C2185B;
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        padding: 5px 10px;
        border-radius: 6px;
        letter-spacing: 0.4px;
    }

    .js-hotel-room-card__name {
        font-size: 20px;
        font-weight: 700;
        color: #111;
        margin: 0;
    }

    .js-hotel-room-card__meta {
        font-size: 14px;
        color: #666;
    }

    .js-hotel-room-card__cancel {
        font-size: 13px;
        color: #444;
        margin-bottom: 4px;
    }

    .js-hotel-room-card__cancel--free {
        color: #1e8e3e;
        font-weight: 700;
    }

    .js-hotel-room-card__cancel-until {
        font-size: 12px;
        color: #777;
        margin-bottom: 12px;
    }

    .js-hotel-room-facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 12px 0 14px;
        padding-top: 12px;
        border-top: 1px solid #eef2f5;
    }

    .js-hotel-room-facility {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border: 1px solid #d9dee3;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
        color: #333;
        font-weight: 500;
        line-height: 1;
    }

    .js-hotel-room-facility i {
        color: #C2185B;
        font-size: 12px;
    }

    .js-hotel-room-card__footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 12px;
        margin-top: auto;
    }

    .js-hotel-room-card__price {
        font-size: 30px;
        font-weight: 800;
        color: #C2185B;
        line-height: 1;
    }

    .js-hotel-room-card__price--regular {
        color: #111;
    }

    .js-hotel-room-card__price-old {
        font-size: 14px;
        text-decoration: line-through;
        color: #c0392b;
        font-weight: 500;
        display: block;
        margin-bottom: 4px;
    }

    .js-hotel-room-card__price-save {
        display: inline-block;
        font-size: 11px;
        background: #e6f4ea;
        color: #137333;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .js-hotel-room-card__reserve {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 10px 18px !important;
        background: #C2185B !important;
        color: #fff !important;
        border: none !important;
        border-radius: 8px !important;
        font-size: 15px !important;
        font-weight: 700 !important;
    }

    .js-hotel-room-card__reserve:hover,
    .js-hotel-room-card__reserve:focus {
        background: #3050c8 !important;
        color: #fff !important;
    }

    @media screen and (max-width: 991px) {
        .js-hotel-detail-card,
        .js-hotel-room-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="js-hotel-detail-page">
    <div class="container">
        <div class="js-hotel-summary">
            <div class="js-hotel-summary__left">
                <span class="js-hotel-summary__tab">Hotel</span>
                <div class="js-hotel-summary__meta">
                    <span><strong>Airport:</strong> {{ $airport_detail->name ?? 'Airport' }}</span>
                    <span><strong>Stay:</strong> {{ $checkinRaw }} &ndash; {{ $checkoutRaw }} ({{ $stayNights }} night{{ $stayNights === 1 ? '' : 's' }})</span>
                </div>
            </div>
            <a href="{{ url('/hotel/search/results') . ($searchId ? '?searchId=' . urlencode($searchId) : '') }}" class="btn js-hotel-summary__back">Back to hotels</a>
        </div>

        <div class="js-hotel-detail-card">
            <div class="js-hotel-detail-media">
                <div class="js-hotel-detail-media__main">
                    <img src="{{ $mainImage }}" alt="{{ $hotel->name }}" onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';">
                </div>
                @if (count($thumbs) > 1)
                    <div class="js-hotel-detail-thumbs">
                        @foreach ($thumbs as $image)
                            <img src="{{ $image }}" alt="{{ $hotel->name }}" onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="js-hotel-detail-info">
                <h1 class="js-hotel-detail-info__name">{{ $hotel->name }}</h1>
                @if ($stars > 0)
                    <div class="js-hotel-detail-info__stars">
                        {{ $stars }}-star
                        @for ($i = 1; $i <= $stars; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                @endif
                @if ($address !== '')
                    <div class="js-hotel-detail-info__address">{{ $address }}</div>
                @endif
                <div class="js-hotel-detail-info__times">
                    <strong>Check-in</strong> {{ $checkInDisplay }}
                    /
                    <strong>Check-out</strong> {{ $checkOutDisplay }}
                </div>

                @if (!empty($policyPills) || !empty($boardTypes))
                    <div class="js-hotel-listing__pills">
                        @foreach (array_slice($policyPills, 0, 2) as $pill)
                            <span class="js-hotel-pill">{{ $pill }}</span>
                        @endforeach
                        @foreach (array_slice($boardTypes, 0, 3) as $plan)
                            <span class="js-hotel-pill js-hotel-pill--plan">{{ $plan }}</span>
                        @endforeach
                    </div>
                @endif

                @if ($description !== '')
                    <p class="js-hotel-detail-info__desc">{{ $description }}</p>
                @endif
            </div>
        </div>

        @if (!empty($hotelStats))
            <div class="js-hotel-facilities">
                <h2>Facilities</h2>
                <div class="js-hotel-facilities__grid">
                    @foreach ($statLabels as $key => $label)
                        @if (isset($hotelStats[$key]) && $hotelStats[$key] !== '' && $hotelStats[$key] !== null)
                            <div class="js-hotel-stat">
                                <span class="js-hotel-stat__value">{{ $hotelStats[$key] }}</span>
                                <span class="js-hotel-stat__label">{{ $label }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @elseif (!empty($hotel->all_amenities) || !empty($hotel->amenities))
            <div class="js-hotel-facilities">
                <h2>Facilities</h2>
                <div class="js-hotel-listing__pills">
                    @foreach (array_slice($hotel->all_amenities ?? $hotel->amenities ?? [], 0, 12) as $amenity)
                        <span class="js-hotel-pill">{{ $amenity }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="js-hotel-rooms">
            <h3>Select your room</h3>
            <div class="js-hotel-room-grid">
                @foreach ($hotel->options as $index => $option)
                    @php
                        $roomTitle = $option->room_title ?? $option->name ?? 'Room';
                        $roomFacilities = $option->facilities ?? [];
                        $maxGuests = (int) ($option->max_guests ?? $request->input('hotel_adults', 2));
                        $cancelUntil = !empty($option->cancellation_until)
                            ? \Carbon\Carbon::parse($option->cancellation_until)->format('jS F Y')
                            : null;
                        $bookingPayload = [
                            'hotel_db_id' => $hotel->hotel_db_id ?? $hotel->companyID ?? null,
                            'companyID' => $hotel->hotel_db_id ?? $hotel->companyID ?? null,
                            'product_id' => $hotel->product_id,
                            'searchId' => $searchId,
                            'option_id' => $option->option_id,
                            'name' => $hotel->name,
                            'displayName' => $hotel->name,
                            'image' => $hotel->image ?? null,
                            'price' => $option->price,
                            'discount_applied' => $option->discount_applied ?? 0,
                            'room_title' => $option->room_title ?? $option->name,
                            'plan_type' => $option->plan_type ?? 'Room Only',
                        ];
                    @endphp

                    <div class="js-hotel-room-card {{ $index === 0 ? 'js-hotel-room-card--popular' : '' }}">
                        @if ($index === 0)
                            <span class="js-hotel-room-card__tag">MOST POPULAR</span>
                        @endif
                        <h4 class="js-hotel-room-card__name">{{ $roomTitle }}</h4>
                        <div class="js-hotel-room-card__meta">
                            {{ $option->plan_type ?? 'Room Only' }} · Up to {{ $maxGuests }} guest{{ $maxGuests === 1 ? '' : 's' }}
                        </div>

                        @if (!empty($option->is_non_smoking))
                            <div class="js-hotel-room-card__meta" style="margin-top:-4px;">
                                <span class="js-hotel-room-facility" style="display:inline-flex;">
                                    <i class="{{ HotelRoomFacilities::iconClass('Non-smoking') }}"></i>
                                    Non-smoking
                                </span>
                            </div>
                        @endif

                        @if (!empty($roomFacilities))
                            <div class="js-hotel-room-facilities">
                                @foreach ($roomFacilities as $facility)
                                    <span class="js-hotel-room-facility">
                                        <i class="{{ HotelRoomFacilities::iconClass($facility) }}"></i>
                                        {{ $facility }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        @if (!empty($option->free_cancellation))
                            <div class="js-hotel-room-card__cancel js-hotel-room-card__cancel--free">
                                <i class="fas fa-check-circle"></i> Free cancellation
                            </div>
                            @if ($cancelUntil)
                                <div class="js-hotel-room-card__cancel-until">
                                    <i class="far fa-clock"></i> Until {{ $cancelUntil }}
                                </div>
                            @endif
                        @else
                            <div class="js-hotel-room-card__cancel">
                                {{ !empty($option->non_refundable) ? 'Non-refundable' : ($option->cancellation ?: 'See cancellation policy at checkout') }}
                            </div>
                        @endif

                        <div class="js-hotel-room-card__footer">
                            <div>
                                @if (!empty($option->discount_applied) && $option->discount_applied > 0)
                                    <span class="js-hotel-room-card__price-old">£{{ number_format($option->original_price, 2) }}</span>
                                    <div class="js-hotel-room-card__price">£{{ number_format($option->price, 2) }}</div>
                                    <span class="js-hotel-room-card__price-save">Save £{{ number_format($option->discount_applied, 2) }}</span>
                                @else
                                    <div class="js-hotel-room-card__price js-hotel-room-card__price--regular">£{{ number_format($option->price, 2) }}</div>
                                @endif
                                <small style="color:#777;">Total for stay</small>
                            </div>

                            <form method="POST" action="{{ route('addBookingFormHotel') }}" style="margin:0;">
                                @csrf
                                <input type="hidden" name="search_data" value='@json($bookingPayload)'>
                                <input type="hidden" name="company_id" value="{{ $hotel->hotel_db_id ?? $hotel->companyID }}">
                                <input type="hidden" name="product_code" value="{{ $hotel->product_id }}">
                                <input type="hidden" name="hotel_name" value="{{ $hotel->name }}">
                                <input type="hidden" name="room_title" value="{{ $option->room_title ?? $option->name }}">
                                <input type="hidden" name="room_type" value="{{ $request->input('hotel_room_type', 'Double') }}">
                                <input type="hidden" name="logo" value="{{ $mainImage }}">
                                <input type="hidden" name="airport" value="{{ $airport_detail->id ?? '' }}">
                                <input type="hidden" name="checkin_date" value="{{ $request->input('hotel_checkin_date') }}">
                                <input type="hidden" name="checkout_date" value="{{ $request->input('hotel_checkout_date') }}">
                                <input type="hidden" name="checkin_time" value="{{ $request->input('hotel_checkin_time', '14:00') }}">
                                <input type="hidden" name="checkout_time" value="{{ $request->input('hotel_checkout_time', '11:00') }}">
                                <input type="hidden" name="adults" value="{{ $request->input('hotel_adults', 1) }}">
                                <input type="hidden" name="children" value="{{ $request->input('hotel_children', 0) }}">
                                <input type="hidden" name="infants" value="{{ $request->input('hotel_infants', 0) }}">
                                <input type="hidden" name="rooms" value="{{ $request->input('hotel_rooms', 1) }}">
                                <input type="hidden" name="promo" value="{{ $promo ?? '' }}">
                                <input type="hidden" name="discount_code" value="{{ $promo ?? '' }}">
                                <input type="hidden" name="discount_amount" value="{{ $option->discount_applied ?? 0 }}">
                                <input type="hidden" name="booking_amount" value="{{ $option->price }}">
                                <input type="hidden" name="pl_id" value="{{ $option->option_id }}">
                                <input type="hidden" name="park_api" value="bookfhr">
                                <input type="hidden" name="bookfhrSearchId" value="{{ $searchId }}">
                                <input type="hidden" name="bookfhrOptionId" value="{{ $option->option_id }}">
                                <input type="hidden" name="bookingfor" value="hotels">
                                <button type="submit" class="btn js-hotel-room-card__reserve">Reserve Now</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
