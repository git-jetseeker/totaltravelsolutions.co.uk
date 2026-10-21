@section('title', 'View Car Hire Deal | Total Travel Solutions')
@include('layouts.header')
@include('layouts.nav')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@php
    $deal = $deal ?? [];
    $search = $search ?? [];
    $extras = collect($deal['extras'] ?? []);
    $protection = $extras->first(function ($e) {
        return strcasecmp((string) ($e['group_type'] ?? ''), 'DamageProtection') === 0;
    });
    $cancelProtection = $extras->first(function ($e) {
        return strcasecmp((string) ($e['group_type'] ?? ''), 'CancelProtection') === 0;
    });
    $optionalExtras = $extras->filter(function ($e) {
        $g = strtolower((string) ($e['group_type'] ?? ''));
        return $g !== 'damageprotection' && $g !== 'cancelprotection';
    })->values();

    $payNow = (float) ($deal['pay_now'] ?? 0);
    $payLocal = (float) ($deal['pay_local'] ?? 0);
    $estimated = (float) ($deal['estimated_total'] ?? 0);
    $hasDeposit = !empty($deal['has_deposit_option']);
    $defaultMode = $deal['default_payment_mode'] ?? ($hasDeposit ? 'deposit' : 'full');
    $currency = strtoupper((string) ($deal['currency'] ?? 'GBP'));
    $symbol = $currency === 'GBP' ? '£' : ($currency === 'EUR' ? '€' : $currency . ' ');

    $mapUrl = '';
    if (!empty($deal['branch_lat']) && !empty($deal['branch_lng'])) {
        $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($deal['branch_lat'] . ',' . $deal['branch_lng']);
    } elseif (!empty($deal['branch_address'])) {
        $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($deal['branch_address']);
    }

    $inclusions = $deal['inclusions'] ?? [];
@endphp

<style>
    .chd-page {
        --chd-navy: #C2185B;
        --chd-navy-deep: #9a1349;
        --chd-gold: #f6ab2e;
        --chd-gold-btn: #ffb338;
        --chd-bg: #eef3f7;
        --chd-card: #fff;
        --chd-border: #d9e4ec;
        --chd-muted: #5f7382;
        --chd-text: #173344;
        --chd-green: #1f8a4c;
        background: var(--chd-bg);
        padding: 18px 0 64px;
        font-family: "Poppins", sans-serif;
        color: var(--chd-text);
    }
    .chd-page .container { max-width: 1180px; }
    .chd-steps {
        display: flex; justify-content: center; align-items: flex-start; gap: 0;
        margin: 0 auto 22px; max-width: 720px; list-style: none; padding: 0;
    }
    .chd-steps li {
        flex: 1; display: flex; flex-direction: column; align-items: center;
        text-align: center; position: relative; z-index: 1;
        font-size: 12px; font-weight: 600; color: var(--chd-muted);
    }
    .chd-steps li:not(:last-child)::before {
        content: ""; position: absolute; top: 13px; left: calc(50% + 16px);
        width: calc(100% - 32px); height: 2px; background: #c9d7e2;
        z-index: 0; pointer-events: none;
    }
    .chd-steps li.is-done:not(:last-child)::before,
    .chd-steps li.is-active:not(:last-child)::before { background: var(--chd-navy); }
    .chd-steps .chd-step-dot {
        width: 28px; height: 28px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        margin: 0 0 10px; position: relative; z-index: 2;
        background: #fff; border: 2px solid #c9d7e2; color: var(--chd-muted); font-size: 12px;
        box-shadow: 0 0 0 4px var(--chd-bg);
    }
    .chd-steps .chd-step-label {
        position: relative; z-index: 2; line-height: 1.3; max-width: 140px;
        background: var(--chd-bg); padding: 0 4px;
    }
    .chd-steps li.is-active .chd-step-dot,
    .chd-steps li.is-done .chd-step-dot {
        background: var(--chd-navy); border-color: var(--chd-navy); color: #fff;
    }
    .chd-steps li.is-active { color: var(--chd-navy); }
    .chd-steps li.is-done { color: var(--chd-navy-deep); }
    .chd-layout { display: grid; grid-template-columns: 340px minmax(0, 1fr); gap: 20px; align-items: start; }
    .chd-panel {
        background: var(--chd-card); border: 1px solid var(--chd-border); border-radius: 14px;
        box-shadow: 0 8px 20px rgba(5, 89, 136, 0.06); overflow: hidden;
    }
    .chd-summary { position: sticky; top: 16px; }
    .chd-summary__head {
        padding: 14px 16px; background: linear-gradient(135deg, #C2185B 0%, #9a1349 100%);
        color: #fff; font-size: 15px; font-weight: 700;
    }
    .chd-summary__body { padding: 16px; }
    .chd-trip-row { margin: 0 0 12px; }
    .chd-trip-row label {
        display: block; margin: 0 0 3px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em; color: var(--chd-muted);
    }
    .chd-trip-row p { margin: 0; font-size: 13px; font-weight: 600; line-height: 1.4; }
    .chd-trip-row p span { display: block; margin-top: 2px; font-weight: 500; color: var(--chd-muted); }
    .chd-car {
        display: grid; grid-template-columns: 110px minmax(0, 1fr); gap: 12px;
        padding: 12px 0; border-top: 1px solid #eef2f5; border-bottom: 1px solid #eef2f5; margin-bottom: 12px;
    }
    .chd-car img {
        width: 110px; height: 78px; object-fit: contain; background: #f4f8fb;
        border: 1px solid #e8eef3; border-radius: 8px;
    }
    .chd-car h3 { margin: 0 0 6px; font-size: 15px; font-weight: 800; line-height: 1.25; }
    .chd-car h3 span { display: block; font-size: 12px; font-weight: 600; color: var(--chd-muted); }
    .chd-supplier-row { display: flex; align-items: center; gap: 8px; margin-top: 6px; }
    .chd-supplier-row img { height: 18px; max-width: 80px; object-fit: contain; }
    .chd-rating {
        display: inline-flex; align-items: center; gap: 4px; background: #fff4df;
        color: #8a5a00; border: 1px solid #f3d59a; border-radius: 5px;
        padding: 2px 6px; font-size: 11px; font-weight: 700;
    }
    .chd-inc-title {
        margin: 0 0 8px; font-size: 12px; font-weight: 800; text-transform: uppercase;
        letter-spacing: .04em; color: var(--chd-navy);
    }
    .chd-inc-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 6px 10px; margin-bottom: 12px;
    }
    .chd-inc-item {
        display: flex; align-items: flex-start; gap: 7px;
        font-size: 12px; font-weight: 600; color: var(--chd-text); line-height: 1.35;
    }
    .chd-inc-item i { color: var(--chd-navy); margin-top: 2px; flex: 0 0 auto; }
    .chd-feats {
        display: grid; grid-template-columns: 1fr 1fr; gap: 6px 8px; margin-bottom: 14px;
    }
    .chd-feat {
        display: flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 600; color: var(--chd-muted);
    }
    .chd-feat i {
        width: 24px; height: 24px; border-radius: 6px; background: #eaf4fb; color: var(--chd-navy);
        display: inline-flex; align-items: center; justify-content: center; font-size: 11px;
    }
    .chd-feat--mgr i { background: #fff4df; color: #c67f00; }
    .chd-price-box {
        background: #f7fafc; border: 1px solid #e6edf2; border-radius: 10px; padding: 12px;
    }
    .chd-price-row {
        display: flex; justify-content: space-between; gap: 10px;
        font-size: 13px; margin-bottom: 6px; color: var(--chd-muted);
    }
    .chd-price-row strong { color: var(--chd-text); }
    .chd-price-row.is-total { font-size: 16px; font-weight: 800; color: var(--chd-navy); margin-top: 8px; }
    .chd-due {
        margin-top: 10px; border: 2px solid var(--chd-gold); border-radius: 8px;
        padding: 10px 12px; display: flex; justify-content: space-between; align-items: center;
        font-weight: 800; color: var(--chd-navy); background: #fffdf7;
    }
    .chd-links { display: flex; flex-wrap: wrap; gap: 12px; margin: 12px 0 0; }
    .chd-links a, .chd-links button {
        background: none; border: 0; padding: 0; color: var(--chd-navy);
        font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: none;
    }

    .chd-main { display: flex; flex-direction: column; gap: 14px; }
    .chd-section { padding: 18px; }
    .chd-section h2 {
        margin: 0 0 14px; font-size: 18px; font-weight: 800; color: var(--chd-navy);
    }
    .chd-pay-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .chd-pay-card {
        border: 2px solid var(--chd-border); border-radius: 12px; padding: 14px;
        cursor: pointer; background: #fff; transition: border-color .15s, background .15s;
        position: relative;
    }
    .chd-pay-card.is-active {
        border-color: var(--chd-navy); background: #f3f9fc;
    }
    .chd-pay-card input { position: absolute; opacity: 0; pointer-events: none; }
    .chd-pay-card__title {
        display: flex; align-items: baseline; justify-content: space-between; gap: 8px;
        font-size: 15px; font-weight: 800; color: var(--chd-text); margin-bottom: 6px;
    }
    .chd-pay-card__title span { color: var(--chd-navy); font-size: 18px; }
    .chd-pay-card p { margin: 0; font-size: 12px; color: var(--chd-muted); line-height: 1.45; font-weight: 600; }
    .chd-continue {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        gap: 8px;
        width: 100%;
        min-height: 50px;
        margin-top: 0;
        padding: 0 16px !important;
        border: none !important;
        border-radius: 8px;
        background: var(--chd-gold-btn) !important;
        color: var(--chd-navy) !important;
        font-family: "Poppins", sans-serif !important;
        font-weight: 800 !important;
        font-size: 16px !important;
        line-height: 1.2 !important;
        cursor: pointer;
        box-shadow: 0 6px 14px rgba(246, 171, 46, 0.28);
        float: none !important;
    }
    .chd-continue i {
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1 !important;
        position: static !important;
    }
    .chd-continue:hover,
    .chd-continue:focus {
        filter: brightness(0.98);
        color: var(--chd-navy-deep) !important;
        background: var(--chd-gold-btn) !important;
    }

    .chd-upsell {
        border: 1px solid #f0d69a; border-radius: 12px; background: #fffaf0;
        padding: 14px 16px; display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 14px 18px;
        align-items: start; position: relative; margin-bottom: 10px;
    }
    .chd-upsell__title-row {
        display: flex; flex-wrap: wrap; align-items: center; gap: 8px 10px; margin: 0 0 6px;
    }
    .chd-upsell__badge {
        display: inline-block; background: var(--chd-navy); color: #fff;
        font-size: 10px; font-weight: 800; letter-spacing: .04em; padding: 3px 8px; border-radius: 4px;
        line-height: 1.2; flex: 0 0 auto;
    }
    .chd-upsell h3 {
        margin: 0; font-size: 15px; font-weight: 800; color: var(--chd-navy); line-height: 1.3;
    }
    .chd-upsell p { margin: 0; font-size: 12px; color: var(--chd-muted); line-height: 1.5; }
    .chd-upsell__side {
        text-align: right;
        min-width: 120px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }
    .chd-upsell__price {
        display: block; font-size: 18px; font-weight: 800; color: var(--chd-text);
        line-height: 1.2; margin: 0;
    }
    .chd-add-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        min-height: 36px; padding: 0 14px; border-radius: 8px; border: none;
        background: var(--chd-navy); color: #fff; font-weight: 800; font-size: 13px; cursor: pointer;
    }
    .chd-add-btn.is-on {
        background: #fff;
        color: #c0392b;
        border: 2px solid #c0392b;
    }
    .chd-add-btn.is-on:hover { background: #fdf2f0; }
    .chd-extra-row {
        display: grid; grid-template-columns: 72px minmax(0, 1fr) auto; gap: 12px;
        align-items: center; padding: 12px 0; border-bottom: 1px solid #eef2f5;
    }
    .chd-extra-row:last-child { border-bottom: 0; }
    .chd-extra-row select {
        width: 72px; height: 40px; border: 1px solid var(--chd-border); border-radius: 8px;
        padding: 0 8px; font-weight: 700; color: var(--chd-text); background: #fff;
    }
    .chd-extra-row h4 { margin: 0 0 4px; font-size: 14px; font-weight: 750; }
    .chd-extra-row p { margin: 0; font-size: 12px; color: var(--chd-muted); }
    .chd-extra-row .chd-more {
        background: none; border: 0; padding: 0; color: var(--chd-navy);
        font-size: 12px; font-weight: 700; cursor: pointer;
    }
    .chd-extra-price { text-align: right; font-size: 14px; font-weight: 800; color: var(--chd-navy); white-space: nowrap; }
    .chd-extra-price small { display: block; font-size: 11px; color: var(--chd-muted); font-weight: 600; }
    .chd-back {
        display: inline-flex; align-items: center; gap: 6px; margin-bottom: 10px;
        color: var(--chd-navy); font-weight: 700; text-decoration: none; font-size: 13px;
    }
    .chd-back:hover { text-decoration: underline; color: var(--chd-navy-deep); }

    @media (max-width: 991px) {
        .chd-layout { grid-template-columns: 1fr; }
        .chd-summary { position: static; }
        .chd-pay-options { grid-template-columns: 1fr; }
    }
    @media (max-width: 575px) {
        .chd-inc-grid, .chd-feats { grid-template-columns: 1fr; }
        .chd-extra-row { grid-template-columns: 64px minmax(0, 1fr); }
        .chd-extra-price { grid-column: 2; text-align: left; }
    }
</style>

<section class="chd-page">
    <div class="container">
        <ol class="chd-steps" aria-label="Booking progress">
            <li class="is-done">
                <span class="chd-step-dot"><i class="fa fa-check" aria-hidden="true"></i></span>
                <span class="chd-step-label">Choose your car</span>
            </li>
            <li class="is-active is-done">
                <span class="chd-step-dot"><i class="fa fa-check" aria-hidden="true"></i></span>
                <span class="chd-step-label">View deal</span>
            </li>
            <li>
                <span class="chd-step-dot">3</span>
                <span class="chd-step-label">Reservation &amp; payment</span>
            </li>
        </ol>

        <a class="chd-back" href="{{ route('carhire.search') }}"><i class="fas fa-arrow-left" aria-hidden="true"></i> Back to results</a>

        <form method="post" action="{{ route('carhire.booking') }}" id="chd_deal_form">
            @csrf
            <input type="hidden" name="rate_reference" value="{{ $deal['rate_reference'] ?? '' }}">
            <input type="hidden" name="payment_mode" id="chd_payment_mode" value="{{ $defaultMode }}">

            <div class="chd-layout">
                <aside class="chd-summary chd-panel">
                    <div class="chd-summary__head">Your reservation summary</div>
                    <div class="chd-summary__body">
                        <div class="chd-trip-row">
                            <label>Pick up</label>
                            <p>
                                {{ $deal['pickup_location'] ?? '' }}
                                <span>{{ $deal['pickup_date'] ?? '' }} {{ $deal['pickup_time'] ?? '' }}</span>
                            </p>
                        </div>
                        <div class="chd-trip-row">
                            <label>Drop off</label>
                            <p>
                                {{ $deal['dropoff_location'] ?? '' }}
                                <span>{{ $deal['dropoff_date'] ?? '' }} {{ $deal['dropoff_time'] ?? '' }}</span>
                            </p>
                        </div>

                        <div class="chd-car">
                            <img src="{{ $deal['image'] ?: asset('theme-new/images/airport-parking.jpg') }}" alt="{{ $deal['vehicle_name'] ?? 'Car' }}">
                            <div>
                                <h3>
                                    {{ $deal['vehicle_name'] ?? 'Car' }}
                                    @if(!empty($deal['vehicle_type']))
                                        <span>or similar ({{ $deal['vehicle_type'] }})</span>
                                    @endif
                                </h3>
                                <div class="chd-supplier-row">
                                    @if(!empty($deal['supplier_logo']))
                                        <img src="{{ $deal['supplier_logo'] }}" alt="{{ $deal['supplier_name'] ?? '' }}">
                                    @endif
                                    <span style="font-size:12px;font-weight:700;">{{ $deal['supplier_name'] ?? '' }}</span>
                                    @if(!empty($deal['review_score']))
                                        <span class="chd-rating">{{ number_format((float) $deal['review_score'], 1) }} / 5</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!empty($inclusions))
                            <div class="chd-inc-title">Included in the price</div>
                            <div class="chd-inc-grid">
                                @foreach($inclusions as $inc)
                                    <div class="chd-inc-item">
                                        <i class="fas fa-check" aria-hidden="true"></i>
                                        <span>{{ $inc['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="chd-feats">
                            @if(!empty($deal['seats']))
                                <div class="chd-feat"><i class="fas fa-user" aria-hidden="true"></i> x{{ $deal['seats'] }}</div>
                            @endif
                            @if(!empty($deal['suitcases']))
                                <div class="chd-feat"><i class="fas fa-suitcase" aria-hidden="true"></i> {{ $deal['suitcases'] }} bag{{ (int)$deal['suitcases'] === 1 ? '' : 's' }}</div>
                            @endif
                            @if(!empty($deal['transmission']))
                                <div class="chd-feat"><i class="fas fa-cogs" aria-hidden="true"></i> {{ $deal['transmission'] }}</div>
                            @endif
                            @if(!empty($deal['aircon']))
                                <div class="chd-feat"><i class="fas fa-snowflake" aria-hidden="true"></i> Air conditioning</div>
                            @endif
                            <div class="chd-feat {{ !empty($deal['is_meet_and_greet']) ? 'chd-feat--mgr' : '' }}">
                                <i class="fas {{ !empty($deal['is_meet_and_greet']) ? 'fa-handshake' : 'fa-map-marker-alt' }}" aria-hidden="true"></i>
                                {{ $deal['pickup_type'] ?? 'Airport desk' }}
                            </div>
                            <div class="chd-feat"><i class="fas fa-shield-alt" aria-hidden="true"></i> Price Promise</div>
                        </div>

                        <div class="chd-price-box">
                            <div class="chd-price-row">
                                <span>Car rental</span>
                                <strong id="chd_summary_car">{{ $symbol }}{{ number_format($estimated, 2) }}</strong>
                            </div>
                            <div class="chd-price-row" id="chd_summary_extras_row" style="display:none;">
                                <span>Extras (pay now)</span>
                                <strong id="chd_summary_extras">{{ $symbol }}0.00</strong>
                            </div>
                            <div class="chd-price-row is-total">
                                <span>Total</span>
                                <span id="chd_summary_total">{{ $symbol }}{{ number_format($estimated, 2) }}</span>
                            </div>
                            <div class="chd-due">
                                <span>Due today</span>
                                <span id="chd_summary_due">{{ $symbol }}{{ number_format($defaultMode === 'full' ? $estimated : $payNow, 2) }}</span>
                            </div>
                            <div class="chd-price-row" style="margin-top:8px;margin-bottom:0;">
                                <span>Due at pick-up</span>
                                <strong id="chd_summary_local">{{ $symbol }}{{ number_format($defaultMode === 'full' ? 0 : $payLocal, 2) }}</strong>
                            </div>
                        </div>

                        <div class="chd-links">
                            @if($mapUrl)
                                <a href="{{ $mapUrl }}" target="_blank" rel="noopener"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Map and address</a>
                            @endif
                            @if(!empty($deal['branch_instructions']))
                                <button type="button" id="chd_more_info_btn"><i class="fas fa-info-circle" aria-hidden="true"></i> More info</button>
                            @endif
                        </div>
                    </div>
                </aside>

                <div class="chd-main">
                    <div class="chd-panel chd-section">
                        <h2>Payment options</h2>
                        <div class="chd-pay-options">
                            @if($hasDeposit)
                                <label class="chd-pay-card {{ $defaultMode === 'full' ? 'is-active' : '' }}" data-mode="full">
                                    <input type="radio" name="payment_mode_ui" value="full" {{ $defaultMode === 'full' ? 'checked' : '' }}>
                                    <div class="chd-pay-card__title">
                                        Pay now
                                        <span>{{ $symbol }}{{ number_format($estimated, 2) }}</span>
                                    </div>
                                    <p>{{ $symbol }}{{ number_format($estimated, 2) }} car rental total. Pay the full amount online today.</p>
                                </label>
                                <label class="chd-pay-card {{ $defaultMode === 'deposit' ? 'is-active' : '' }}" data-mode="deposit">
                                    <input type="radio" name="payment_mode_ui" value="deposit" {{ $defaultMode === 'deposit' ? 'checked' : '' }}>
                                    <div class="chd-pay-card__title">
                                        Pay deposit
                                        <span>{{ $symbol }}{{ number_format($payNow, 2) }}</span>
                                    </div>
                                    <p>
                                        {{ $symbol }}{{ number_format($estimated, 2) }} car rental total.
                                        {{ $symbol }}{{ number_format($payLocal, 2) }} due at pick up.
                                    </p>
                                </label>
                            @else
                                <label class="chd-pay-card is-active" data-mode="full" style="grid-column:1/-1;">
                                    <input type="radio" name="payment_mode_ui" value="full" checked>
                                    <div class="chd-pay-card__title">
                                        Pay now
                                        <span>{{ $symbol }}{{ number_format($estimated > 0 ? $estimated : $payNow, 2) }}</span>
                                    </div>
                                    <p>{{ $symbol }}{{ number_format($estimated > 0 ? $estimated : $payNow, 2) }} car rental total.</p>
                                </label>
                            @endif
                        </div>
                    </div>

                    @if($protection)
                        <div class="chd-panel chd-section">
                            <h2>Protection</h2>
                            <div class="chd-upsell">
                                <div>
                                    <div class="chd-upsell__title-row">
                                        <h3>{{ $protection['name'] }}</h3>
                                        <span class="chd-upsell__badge">RECOMMENDED</span>
                                    </div>
                                    <p>{{ $protection['description'] }}</p>
                                    @if(!empty($protection['dynamic']['inclusions']) && is_array($protection['dynamic']['inclusions']))
                                        <ul style="margin:8px 0 0;padding-left:18px;color:#5f7382;font-size:12px;">
                                            @foreach($protection['dynamic']['inclusions'] as $line)
                                                <li>{{ is_string($line) ? $line : '' }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="chd-upsell__side">
                                    <span class="chd-upsell__price">{{ $symbol }}{{ number_format((float) $protection['price'], 2) }}</span>
                                    <input type="hidden" name="extras[{{ $protection['code'] }}]" id="chd_extra_{{ $protection['code'] }}" value="0" class="chd-extra-input"
                                           data-price="{{ (float) $protection['price'] }}"
                                           data-paytype="{{ $protection['payment_type'] }}">
                                    <button type="button" class="chd-add-btn" data-target="chd_extra_{{ $protection['code'] }}">+ ADD</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($cancelProtection)
                        <div class="chd-panel chd-section">
                            <h2>Cancellation protection</h2>
                            <div class="chd-upsell">
                                <div>
                                    <h3>{{ $cancelProtection['name'] }}</h3>
                                    <p>{{ $cancelProtection['description'] }}</p>
                                </div>
                                <div class="chd-upsell__side">
                                    <span class="chd-upsell__price">{{ $symbol }}{{ number_format((float) $cancelProtection['price'], 2) }}</span>
                                    <input type="hidden" name="extras[{{ $cancelProtection['code'] }}]" id="chd_extra_{{ $cancelProtection['code'] }}" value="0" class="chd-extra-input"
                                           data-price="{{ (float) $cancelProtection['price'] }}"
                                           data-paytype="{{ $cancelProtection['payment_type'] }}">
                                    <button type="button" class="chd-add-btn" data-target="chd_extra_{{ $cancelProtection['code'] }}">+ ADD</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($optionalExtras->isNotEmpty())
                        <div class="chd-panel chd-section">
                            <h2>Optional extras</h2>
                            @foreach($optionalExtras as $extra)
                                <div class="chd-extra-row">
                                    <select class="chd-extra-qty chd-extra-input"
                                            name="extras[{{ $extra['code'] }}]"
                                            data-price="{{ (float) $extra['price'] }}"
                                            data-paytype="{{ $extra['payment_type'] }}">
                                        @for($q = 0; $q <= (int) $extra['max_quantity']; $q++)
                                            <option value="{{ $q }}">{{ $q }}</option>
                                        @endfor
                                    </select>
                                    <div>
                                        <h4>{{ $extra['name'] }}</h4>
                                        @if(!empty($extra['description']))
                                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($extra['description']), 120) }}</p>
                                            <button type="button" class="chd-more" data-info="{{ e($extra['description']) }}">More info</button>
                                        @endif
                                    </div>
                                    <div class="chd-extra-price">
                                        {{ $symbol }}{{ number_format((float) $extra['price'], 2) }}
                                        <small>
                                            @if(strcasecmp((string) $extra['payment_type'], 'Local') === 0)
                                                due at pick-up
                                            @else
                                                per rental
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="chd-panel chd-section">
                        <button type="submit" class="chd-continue">
                            Continue to payment <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<div class="modal fade" id="chd_info_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">More information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="chd_info_text" style="color:#5f7382;line-height:1.6;white-space:pre-wrap;"></div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
{{-- Scripts after footer so jQuery is available --}}
<script>
(function () {
    var symbol = @json($symbol);
    var estimated = {{ json_encode($estimated) }};
    var payNow = {{ json_encode($payNow) }};
    var payLocal = {{ json_encode($payLocal) }};
    var mode = @json($defaultMode);
    var branchInfo = @json($deal['branch_instructions'] ?? '');

    function bootCarhireDeal($) {
        function money(n) {
            return symbol + (Math.round((n + Number.EPSILON) * 100) / 100).toFixed(2);
        }

        function extrasTotals() {
            var now = 0;
            var local = 0;
            $('.chd-extra-input').each(function () {
                var $el = $(this);
                var qty = parseInt($el.val(), 10) || 0;
                var price = parseFloat($el.data('price')) || 0;
                var paytype = String($el.data('paytype') || '');
                var line = qty * price;
                if (paytype.toLowerCase() === 'local') local += line;
                else now += line;
            });
            return { now: now, local: local };
        }

        function refreshSummary() {
            var extras = extrasTotals();
            var carDue = mode === 'full' ? estimated : payNow;
            if (!(carDue > 0)) carDue = estimated || payNow;
            var localDue = mode === 'full' ? extras.local : (payLocal + extras.local);
            var dueToday = carDue + extras.now;
            var total = estimated + extras.now + extras.local;

            $('#chd_summary_car').text(money(estimated));
            if (extras.now > 0) {
                $('#chd_summary_extras_row').show();
                $('#chd_summary_extras').text(money(extras.now));
            } else {
                $('#chd_summary_extras_row').hide();
            }
            $('#chd_summary_total').text(money(total));
            $('#chd_summary_due').text(money(dueToday));
            $('#chd_summary_local').text(money(localDue));
            $('#chd_payment_mode').val(mode);
        }

        function showInfoModal(htmlOrText, asHtml) {
            if (asHtml) {
                $('#chd_info_text').html(htmlOrText || '');
            } else {
                $('#chd_info_text').text(htmlOrText || '');
            }
            var $modal = $('#chd_info_modal');
            if ($.fn.modal) {
                $modal.modal('show');
                return;
            }
            $modal.addClass('show').css({ display: 'block' }).attr('aria-hidden', 'false');
            if (!$('.modal-backdrop').length) {
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
            }
            $('body').addClass('modal-open');
        }

        function hideInfoModal() {
            var $modal = $('#chd_info_modal');
            if ($.fn.modal) {
                $modal.modal('hide');
                return;
            }
            $modal.removeClass('show').css({ display: 'none' }).attr('aria-hidden', 'true');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }

        $(document).on('click', '.chd-pay-card', function () {
            $('.chd-pay-card').removeClass('is-active');
            $(this).addClass('is-active');
            $(this).find('input[type=radio]').prop('checked', true);
            mode = String($(this).data('mode') || 'deposit');
            refreshSummary();
        });

        $(document).on('click', '.chd-add-btn', function (e) {
            e.preventDefault();
            var id = $(this).data('target');
            var $input = $('#' + id);
            if (!$input.length) return;
            var on = parseInt($input.val(), 10) === 1;
            $input.val(on ? 0 : 1);
            $(this).toggleClass('is-on', !on).text(on ? '+ ADD' : '− REMOVE');
            refreshSummary();
        });

        $(document).on('change', '.chd-extra-qty', refreshSummary);

        $(document).on('click', '#chd_more_info_btn', function (e) {
            e.preventDefault();
            showInfoModal(branchInfo || '', false);
        });

        $(document).on('click', '.chd-more', function (e) {
            e.preventDefault();
            showInfoModal(String($(this).attr('data-info') || '').replace(/\n/g, '<br>'), true);
        });

        $('#chd_info_modal').on('click', '[data-dismiss="modal"], .close', function (e) {
            e.preventDefault();
            hideInfoModal();
        });
        $(document).on('click', '#chd_info_modal', function (e) {
            if (e.target === this) hideInfoModal();
        });

        refreshSummary();
    }

    function waitForJQuery(attempt) {
        if (window.jQuery) {
            bootCarhireDeal(window.jQuery);
            return;
        }
        if ((attempt || 0) > 80) return;
        setTimeout(function () { waitForJQuery((attempt || 0) + 1); }, 50);
    }

    waitForJQuery(0);
})();
</script>
