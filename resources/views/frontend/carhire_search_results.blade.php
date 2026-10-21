@section('title', 'Car Hire Search Results | Total Travel Solutions')
@include('layouts.header')
@include('layouts.nav')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@php
    if (!isset($sitePartner) || !is_array($sitePartner)) {
        $partnerId = function_exists('current_agent_id')
            ? (int) current_agent_id()
            : (int) config('app.default_agent_id', 9);
        $partnerRow = $partnerId > 0 ? \App\Models\partners::find($partnerId) : null;
        $partnerName = trim((string) ($partnerRow->company ?? $partnerRow->alias ?? config('app.name', 'Total Travel Solutions')));
        $badgeSource = trim((string) ($partnerRow->alias ?? '')) ?: $partnerName;
        $sitePartner = [
            'name' => $partnerName,
            'badge' => strtoupper($badgeSource) . ' PARTNER',
            'title' => $partnerName . ' partner',
        ];
    }

    $stayDays = 1;
    try {
        $from = strtotime(str_replace('/', '-', (string) ($search['pickup_date'] ?? '')));
        $to = strtotime(str_replace('/', '-', (string) ($search['dropoff_date'] ?? '')));
        if ($from && $to && $to > $from) {
            $stayDays = max(1, (int) round(($to - $from) / 86400));
        }
    } catch (\Throwable $e) {
        $stayDays = 1;
    }

    $vehicles = collect($vehicles ?? []);
    $cheapestPrice = (float) ($vehicles->min('display_price') ?? 0);

    $categoryOrder = ['Small', 'Medium', 'Large', 'Estate', 'SUV', 'Luxury', 'Other'];
    $categoryMeta = [
        'Small' => ['icon' => 'fa-car', 'label' => 'Small'],
        'Medium' => ['icon' => 'fa-car-side', 'label' => 'Medium'],
        'Large' => ['icon' => 'fa-shuttle-van', 'label' => 'Large'],
        'Estate' => ['icon' => 'fa-truck', 'label' => 'Estate'],
        'SUV' => ['icon' => 'fa-car', 'label' => 'SUV'],
        'Luxury' => ['icon' => 'fa-gem', 'label' => 'Luxury'],
        'Other' => ['icon' => 'fa-car', 'label' => 'Other'],
    ];
    $categories = [];
    foreach ($categoryOrder as $cat) {
        $group = $vehicles->where('category', $cat);
        if ($group->isEmpty()) {
            continue;
        }
        $categories[$cat] = [
            'label' => $categoryMeta[$cat]['label'] ?? $cat,
            'icon' => $categoryMeta[$cat]['icon'] ?? 'fa-car',
            'from' => (float) $group->min('display_price'),
            'count' => $group->count(),
        ];
    }

    $pickupTypes = $vehicles->pluck('pickup_type', 'pickup_type_key')->filter()->unique()->sort();
    $suppliers = $vehicles->pluck('supplier_name')->filter()->unique()->sort()->values();
    $seatBuckets = [
        '4-5' => [4, 5],
        '5-6' => [5, 6],
        '7-8' => [7, 8],
        '9+' => [9, 99],
    ];
    $seatOptions = [];
    foreach ($seatBuckets as $label => [$min, $max]) {
        $count = $vehicles->filter(function ($v) use ($min, $max) {
            $n = (int) ($v['seats_num'] ?? 0);
            return $n >= $min && $n <= $max;
        })->count();
        if ($count > 0) {
            $seatOptions[$label] = $count;
        }
    }

    $residenceName = $search['residence_country_name'] ?? 'United Kingdom';
@endphp

<style>
    .chr-page {
        --chr-navy: #C2185B;
        --chr-navy-deep: #9a1349;
        --chr-gold: #f6ab2e;
        --chr-gold-btn: #ffb338;
        --chr-bg: #eef3f7;
        --chr-card: #ffffff;
        --chr-border: #d9e4ec;
        --chr-muted: #5f7382;
        --chr-text: #173344;
        --chr-green: #1f8a4c;
        background: var(--chr-bg);
        padding: 18px 0 64px;
        font-family: "Poppins", sans-serif;
        color: var(--chr-text);
    }
    .chr-page .container { max-width: 1180px; }
    .chr-steps {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 0;
        margin: 0 auto 22px;
        max-width: 720px;
        list-style: none;
        padding: 0;
    }
    .chr-steps li {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        font-size: 12px;
        font-weight: 600;
        color: var(--chr-muted);
        z-index: 1;
    }
    /* Connector sits only on the dot row — never across labels */
    .chr-steps li:not(:last-child)::before {
        content: "";
        position: absolute;
        top: 13px;
        left: calc(50% + 16px);
        width: calc(100% - 32px);
        height: 2px;
        background: #c9d7e2;
        z-index: 0;
        pointer-events: none;
    }
    .chr-steps li.is-done:not(:last-child)::before,
    .chr-steps li.is-active:not(:last-child)::before { background: var(--chr-navy); }
    .chr-steps .chr-step-dot {
        width: 28px; height: 28px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        margin: 0 0 10px; position: relative; z-index: 2;
        background: #fff; border: 2px solid #c9d7e2; color: var(--chr-muted); font-size: 12px;
        box-shadow: 0 0 0 4px var(--chr-bg);
    }
    .chr-steps .chr-step-label {
        position: relative;
        z-index: 2;
        line-height: 1.3;
        max-width: 140px;
        background: var(--chr-bg);
        padding: 0 4px;
    }
    .chr-steps li.is-active .chr-step-dot,
    .chr-steps li.is-done .chr-step-dot {
        background: var(--chr-navy); border-color: var(--chr-navy); color: #fff;
    }
    .chr-steps li.is-active { color: var(--chr-navy); }
    .chr-steps li.is-done { color: var(--chr-navy-deep); }

    .chr-layout { display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 20px; align-items: start; }
    .chr-sidebar { display: flex; flex-direction: column; gap: 14px; }
    .chr-panel {
        background: var(--chr-card);
        border: 1px solid var(--chr-border);
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(5, 89, 136, 0.06);
        overflow: hidden;
    }
    .chr-trip { padding: 16px 16px 14px; }
    .chr-trip h2 {
        margin: 0 0 12px; font-size: 15px; font-weight: 700; color: var(--chr-navy);
        display: flex; align-items: center; gap: 8px;
    }
    .chr-trip-row { margin: 0 0 12px; }
    .chr-trip-row label {
        display: block; margin: 0 0 4px; font-size: 11px; font-weight: 700;
        letter-spacing: .02em; text-transform: uppercase; color: var(--chr-muted);
    }
    .chr-trip-row p { margin: 0; font-size: 13px; line-height: 1.45; color: var(--chr-text); font-weight: 600; }
    .chr-trip-row p span { display: block; font-weight: 500; color: var(--chr-muted); margin-top: 2px; }
    .chr-edit-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; min-height: 42px; margin-top: 4px;
        border: none; border-radius: 8px; background: var(--chr-navy); color: #fff;
        font-weight: 700; font-size: 14px; cursor: pointer;
    }
    .chr-edit-btn:hover { background: var(--chr-navy-deep); color: #fff; }

    .chr-filters { padding: 14px 16px 16px; }
    .chr-filters-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px; gap: 8px;
    }
    .chr-filters-head h3 { margin: 0; font-size: 15px; font-weight: 700; color: var(--chr-navy); }
    .chr-reset {
        border: 0; background: transparent; color: var(--chr-gold);
        font-size: 11px; font-weight: 800; letter-spacing: .04em; cursor: pointer; padding: 0;
    }
    .chr-filter-group { margin: 0 0 14px; padding-bottom: 12px; border-bottom: 1px solid #eef2f5; }
    .chr-filter-group:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: 0; }
    .chr-filter-group h4 {
        margin: 0 0 10px; font-size: 12px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .04em; color: var(--chr-muted);
    }
    .chr-check {
        display: flex; align-items: center; gap: 10px; margin: 0 0 8px;
        font-size: 13px; font-weight: 600; color: var(--chr-text); cursor: pointer;
    }
    .chr-check input { width: 16px; height: 16px; accent-color: var(--chr-navy); flex: 0 0 auto; }
    .chr-check .chr-check-ico {
        width: 22px; height: 22px; border-radius: 6px; background: #eaf4fb; color: var(--chr-navy);
        display: inline-flex; align-items: center; justify-content: center; font-size: 12px;
    }
    .chr-check .chr-count { margin-left: auto; color: var(--chr-muted); font-weight: 500; font-size: 12px; }

    .chr-main { min-width: 0; }
    .chr-cats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }
    .chr-cat {
        background: #fff; border: 1px solid var(--chr-border); border-radius: 12px;
        padding: 12px 10px; text-align: center; cursor: pointer;
        transition: border-color .15s, box-shadow .15s, transform .15s;
    }
    .chr-cat:hover, .chr-cat.is-active {
        border-color: var(--chr-navy);
        box-shadow: 0 8px 18px rgba(5, 89, 136, 0.12);
        transform: translateY(-1px);
    }
    .chr-cat.is-active { background: linear-gradient(180deg, #f4fafd 0%, #fff 100%); }
    .chr-cat i { color: var(--chr-navy); font-size: 22px; margin-bottom: 6px; display: block; }
    .chr-cat strong { display: block; font-size: 13px; color: var(--chr-text); }
    .chr-cat span { display: block; margin-top: 4px; font-size: 12px; color: var(--chr-muted); font-weight: 600; }

    .chr-results-bar {
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
        background: #fff; border: 1px solid var(--chr-border); border-radius: 10px;
        padding: 12px 16px; margin-bottom: 14px;
    }
    .chr-results-bar strong { font-size: 15px; color: var(--chr-navy); }
    .chr-results-bar .chr-sort {
        border: 1px solid var(--chr-border); border-radius: 8px; min-height: 38px;
        padding: 0 12px; font-size: 13px; color: var(--chr-text); background: #fff;
    }
    .chr-map-link {
        color: var(--chr-navy); font-weight: 700; font-size: 13px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .chr-map-link:hover { color: var(--chr-navy-deep); text-decoration: underline; }

    .chr-card {
        background: #fff; border: 1px solid var(--chr-border); border-radius: 14px;
        box-shadow: 0 8px 22px rgba(5, 89, 136, 0.07);
        margin-bottom: 16px; overflow: hidden; position: relative;
    }
    .chr-card.is-hidden { display: none !important; }
    .chr-card__ribbon {
        display: inline-block;
        margin: 0 0 8px;
        background: var(--chr-navy);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .04em;
        padding: 4px 10px;
        border-radius: 4px;
        line-height: 1.2;
        box-shadow: 0 2px 8px rgba(5, 89, 136, 0.18);
    }
    .chr-card__top {
        display: grid;
        grid-template-columns: 168px minmax(0, 1fr) 210px;
        gap: 16px;
        padding: 18px 18px 14px;
        align-items: center;
    }
    .chr-card__media {
        width: 168px; height: 118px; border-radius: 10px; background: #f4f8fb;
        display: flex; align-items: center; justify-content: center; overflow: hidden;
        border: 1px solid #e8eef3;
    }
    .chr-card__media img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .chr-card__title {
        margin: 0 0 10px; font-size: 20px; font-weight: 800; color: var(--chr-text); line-height: 1.25;
    }
    .chr-card__title span { font-weight: 600; color: var(--chr-muted); font-size: 15px; }
    .chr-feats {
        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px 12px;
    }
    .chr-feat {
        display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; color: var(--chr-muted);
    }
    .chr-feat i {
        width: 28px; height: 28px; border-radius: 8px; background: #eaf4fb; color: var(--chr-navy);
        display: inline-flex; align-items: center; justify-content: center; font-size: 13px; flex: 0 0 auto;
    }
    .chr-feat--mgr i { background: #fff4df; color: #c67f00; }
    .chr-price {
        text-align: right;
        padding-left: 8px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .chr-price__was {
        margin: 0 0 2px; color: #c0392b; font-size: 13px; font-weight: 700;
        text-decoration: line-through;
    }
    .chr-price__now {
        margin: 0; font-size: 30px; font-weight: 800; color: var(--chr-navy); line-height: 1.1;
    }
    .chr-price__save {
        margin: 6px 0 0; font-size: 12px; font-weight: 700; color: var(--chr-green);
    }
    .chr-price__day {
        margin: 4px 0 0; font-size: 12px; color: var(--chr-muted); font-weight: 600;
    }
    .chr-price__paynow {
        margin: 6px 0 0; font-size: 12px; color: var(--chr-muted);
    }
    .chr-price__paynow strong { color: var(--chr-text); }
    .chr-price form {
        width: 100%;
        align-self: stretch;
        margin: 0;
    }
    .chr-view-deal {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        gap: 8px;
        width: 100%;
        min-height: 46px;
        margin-top: 12px;
        padding: 0 16px !important;
        border: none !important;
        border-radius: 8px;
        background: var(--chr-gold-btn) !important;
        color: var(--chr-navy) !important;
        font-family: "Poppins", sans-serif !important;
        font-weight: 800 !important;
        font-size: 15px !important;
        line-height: 1.2 !important;
        cursor: pointer;
        box-shadow: 0 6px 14px rgba(246, 171, 46, 0.28);
        float: none !important;
    }
    .chr-view-deal i {
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1 !important;
        position: static !important;
    }
    .chr-view-deal:hover,
    .chr-view-deal:focus {
        filter: brightness(0.98);
        color: var(--chr-navy-deep) !important;
        background: var(--chr-gold-btn) !important;
    }

    .chr-card__foot {
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        border-top: 1px solid #eef2f5; padding: 12px 18px; background: #fbfcfd;
    }
    .chr-supplier {
        display: inline-flex; align-items: center; gap: 8px; min-width: 120px;
        font-size: 13px; font-weight: 700; color: var(--chr-text);
    }
    .chr-supplier img { height: 22px; max-width: 90px; object-fit: contain; }
    .chr-rating {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff4df; color: #8a5a00; border: 1px solid #f3d59a;
        border-radius: 6px; padding: 5px 8px; font-size: 11px; font-weight: 700;
    }
    .chr-foot-links { display: flex; gap: 14px; flex-wrap: wrap; margin-left: 4px; }
    .chr-foot-links a, .chr-foot-links button {
        background: none; border: 0; padding: 0; color: var(--chr-navy);
        font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: none;
    }
    .chr-foot-links a:hover, .chr-foot-links button:hover { text-decoration: underline; }
    .chr-included {
        margin-left: auto;
        border: 1px solid var(--chr-border); background: #fff; border-radius: 8px;
        min-height: 36px; padding: 0 12px; font-size: 12px; font-weight: 700;
        color: var(--chr-navy); cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .chr-included-panel {
        display: none; width: 100%; flex-basis: 100%;
        background: #fff; border: 1px solid var(--chr-border); border-radius: 10px;
        padding: 12px 14px; margin-top: 4px;
    }
    .chr-included-panel.is-open { display: block; }
    .chr-inc-grid {
        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px 14px;
    }
    .chr-inc-item {
        display: flex; align-items: flex-start; gap: 8px;
        color: var(--chr-text); font-size: 13px; font-weight: 600; line-height: 1.35;
    }
    .chr-inc-item i { color: var(--chr-gold); margin-top: 2px; flex: 0 0 auto; }
    @media (max-width: 767px) {
        .chr-inc-grid { grid-template-columns: 1fr; }
    }

    .chr-empty {
        background: #fff; border-radius: 12px; border-left: 4px solid var(--chr-navy);
        padding: 24px; color: var(--chr-muted); font-weight: 500;
    }
    .chr-no-match {
        display: none; background: #fff; border: 1px dashed var(--chr-border);
        border-radius: 12px; padding: 28px; text-align: center; color: var(--chr-muted);
    }

    @media (max-width: 991px) {
        .chr-layout { grid-template-columns: 1fr; }
        .chr-card__top { grid-template-columns: 140px minmax(0, 1fr); }
        .chr-price { grid-column: 1 / -1; text-align: left; display: flex; flex-wrap: wrap; align-items: flex-end; gap: 8px 18px; }
        .chr-view-deal { width: auto; min-width: 180px; margin-top: 0; }
        .chr-included { margin-left: 0; }
    }
    @media (max-width: 575px) {
        .chr-steps li { font-size: 11px; }
        .chr-card__top { grid-template-columns: 1fr; }
        .chr-card__media { width: 100%; height: 140px; }
        .chr-feats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .chr-price__now { font-size: 26px; }
    }
</style>

<section class="chr-page">
    <div class="container">
        <ol class="chr-steps" aria-label="Booking progress">
            <li class="is-active is-done">
                <span class="chr-step-dot"><i class="fa fa-check" aria-hidden="true"></i></span>
                <span class="chr-step-label">Choose your car</span>
            </li>
            <li>
                <span class="chr-step-dot">2</span>
                <span class="chr-step-label">View deal</span>
            </li>
            <li>
                <span class="chr-step-dot">3</span>
                <span class="chr-step-label">Reservation &amp; payment</span>
            </li>
        </ol>

        @if($vehicles->isEmpty())
            <div class="chr-empty">
                {{ !empty($error) ? $error : 'No cars were found for this search. Try different dates or another location.' }}
            </div>
        @else
            <div class="chr-layout">
                <aside class="chr-sidebar">
                    <div class="chr-panel chr-trip">
                        <h2><i class="fas fa-route" aria-hidden="true"></i> Your trip</h2>
                        <div class="chr-trip-row">
                            <label>Pick up location</label>
                            <p>
                                {{ $search['pickup_location_name'] ?? '—' }}
                                <span>{{ $search['pickup_date'] ?? '' }} at {{ $search['pickup_time'] ?? '' }}</span>
                            </p>
                        </div>
                        <div class="chr-trip-row">
                            <label>Car drop off location</label>
                            <p>
                                {{ $search['dropoff_location_name'] ?? '—' }}
                                <span>{{ $search['dropoff_date'] ?? '' }} at {{ $search['dropoff_time'] ?? '' }}</span>
                            </p>
                        </div>
                        <div class="chr-trip-row">
                            <label>Driver's residence</label>
                            <p>{{ $residenceName }}</p>
                        </div>
                        <button type="button" class="chr-edit-btn" data-toggle="modal" data-target="#carhire_search_modal">
                            <i class="fas fa-pencil-alt" aria-hidden="true"></i> Edit
                        </button>
                    </div>

                    <div class="chr-panel chr-filters">
                        <div class="chr-filters-head">
                            <h3>Filter results</h3>
                            <button type="button" class="chr-reset" id="chr_reset_filters">RESET FILTERS</button>
                        </div>

                        @if($pickupTypes->isNotEmpty())
                            <div class="chr-filter-group" data-filter-group="pickup">
                                <h4>Pickup</h4>
                                @foreach($pickupTypes as $key => $label)
                                    <label class="chr-check">
                                        <input type="checkbox" class="chr-filter-pickup" value="{{ $key }}">
                                        <span class="chr-check-ico" aria-hidden="true">
                                            @if($key === 'meet_and_greet')
                                                <i class="fas fa-handshake"></i>
                                            @elseif($key === 'shuttle')
                                                <i class="fas fa-shuttle-van"></i>
                                            @else
                                                <i class="fas fa-map-marker-alt"></i>
                                            @endif
                                        </span>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($seatOptions))
                            <div class="chr-filter-group" data-filter-group="seats">
                                <h4>Seats</h4>
                                @foreach($seatOptions as $label => $count)
                                    <label class="chr-check">
                                        <input type="checkbox" class="chr-filter-seats" value="{{ $label }}">
                                        <span>{{ $label }}</span>
                                        <span class="chr-count">{{ $count }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        @if($suppliers->isNotEmpty())
                            <div class="chr-filter-group" data-filter-group="supplier">
                                <h4>Supplier</h4>
                                @foreach($suppliers as $supplier)
                                    <label class="chr-check">
                                        <input type="checkbox" class="chr-filter-supplier" value="{{ $supplier }}">
                                        <span>{{ $supplier }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        <div class="chr-filter-group" data-filter-group="transmission">
                            <h4>Transmission</h4>
                            <label class="chr-check">
                                <input type="checkbox" class="chr-filter-transmission" value="Manual">
                                <span>Manual</span>
                            </label>
                            <label class="chr-check">
                                <input type="checkbox" class="chr-filter-transmission" value="Automatic">
                                <span>Automatic</span>
                            </label>
                        </div>
                    </div>
                </aside>

                <div class="chr-main">
                    @if(count($categories) > 1)
                        <div class="chr-cats" id="chr_categories">
                            <button type="button" class="chr-cat is-active" data-category="">
                                <i class="fas fa-th-large" aria-hidden="true"></i>
                                <strong>All cars</strong>
                                <span>{{ $vehicles->count() }} deals</span>
                            </button>
                            @foreach($categories as $key => $cat)
                                <button type="button" class="chr-cat" data-category="{{ $key }}">
                                    <i class="fas {{ $cat['icon'] }}" aria-hidden="true"></i>
                                    <strong>{{ $cat['label'] }}</strong>
                                    <span>from &pound;{{ number_format($cat['from'], 2) }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="chr-results-bar">
                        <strong><span id="chr_visible_count">{{ $vehicles->count() }}</span> cars found</strong>
                        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                            <a class="chr-map-link" href="#chr_listings" id="chr_scroll_map">
                                <i class="fas fa-map-marked-alt" aria-hidden="true"></i> View locations
                            </a>
                            <select id="chr_sort" class="chr-sort" aria-label="Sort results">
                                <option value="price">Price: low to high</option>
                                <option value="price-desc">Price: high to low</option>
                                <option value="name">Vehicle name</option>
                            </select>
                        </div>
                    </div>

                    <div id="chr_listings">
                        @foreach($vehicles as $index => $vehicle)
                            @php
                                $display = (float) ($vehicle['display_price'] ?? $vehicle['price'] ?? 0);
                                $payNow = (float) ($vehicle['price'] ?? 0);
                                $perDay = $stayDays > 0 ? $display / $stayDays : $display;
                                $isCheapest = $cheapestPrice > 0 && abs($display - $cheapestPrice) < 0.009;
                                $seatsNum = (int) ($vehicle['seats_num'] ?? 0);
                                $seatBucket = '';
                                if ($seatsNum >= 9) $seatBucket = '9+';
                                elseif ($seatsNum >= 7) $seatBucket = '7-8';
                                elseif ($seatsNum >= 5) $seatBucket = '5-6';
                                elseif ($seatsNum >= 4) $seatBucket = '4-5';
                                $mapUrl = '';
                                if (!empty($vehicle['branch_lat']) && !empty($vehicle['branch_lng'])) {
                                    $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($vehicle['branch_lat'] . ',' . $vehicle['branch_lng']);
                                } elseif (!empty($vehicle['branch_address'])) {
                                    $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($vehicle['branch_address']);
                                }
                                $typeLabel = $vehicle['vehicle_type'] ?: ($vehicle['category'] ?? '');
                            @endphp
                            <article class="chr-card"
                                     data-category="{{ $vehicle['category'] ?? '' }}"
                                     data-pickup="{{ $vehicle['pickup_type_key'] ?? '' }}"
                                     data-supplier="{{ $vehicle['supplier_name'] ?? '' }}"
                                     data-transmission="{{ $vehicle['transmission'] ?? '' }}"
                                     data-seats="{{ $seatBucket }}"
                                     data-name="{{ $vehicle['vehicle_name'] ?? '' }}"
                                     data-price="{{ $display }}">
                                <div class="chr-card__top">
                                    <div class="chr-card__media">
                                        <img src="{{ $vehicle['image'] ?: asset('theme-new/images/airport-parking.jpg') }}"
                                             alt="{{ $vehicle['vehicle_name'] }}">
                                    </div>

                                    <div class="chr-card__body">
                                        <h2 class="chr-card__title">
                                            {{ $vehicle['vehicle_name'] }}
                                            @if($typeLabel)
                                                <span>or similar ({{ $typeLabel }})</span>
                                            @endif
                                        </h2>
                                        <div class="chr-feats">
                                            @if(!empty($vehicle['seats']))
                                                <div class="chr-feat"><i class="fas fa-user" aria-hidden="true"></i> x{{ $vehicle['seats'] }}</div>
                                            @endif
                                            @if(!empty($vehicle['transmission']))
                                                <div class="chr-feat"><i class="fas fa-cogs" aria-hidden="true"></i> {{ $vehicle['transmission'] }}</div>
                                            @endif
                                            @if(!empty($vehicle['doors']))
                                                <div class="chr-feat"><i class="fas fa-door-open" aria-hidden="true"></i> {{ $vehicle['doors'] }}</div>
                                            @endif
                                            <div class="chr-feat {{ !empty($vehicle['is_meet_and_greet']) ? 'chr-feat--mgr' : '' }}">
                                                <i class="fas {{ !empty($vehicle['is_meet_and_greet']) ? 'fa-handshake' : 'fa-map-marker-alt' }}" aria-hidden="true"></i>
                                                {{ $vehicle['pickup_type'] ?? 'Airport desk' }}
                                            </div>
                                            @if(!empty($vehicle['aircon']))
                                                <div class="chr-feat"><i class="fas fa-snowflake" aria-hidden="true"></i> Air conditioning</div>
                                            @endif
                                            <div class="chr-feat"><i class="fas fa-shield-alt" aria-hidden="true"></i> Price Promise</div>
                                        </div>
                                    </div>

                                    <div class="chr-price">
                                        @if($isCheapest && $index === 0)
                                            <div class="chr-card__ribbon">CHEAPEST!</div>
                                        @endif
                                        <div class="chr-price__now">&pound;{{ number_format($display, 2) }}</div>
                                        <div class="chr-price__day">&pound;{{ number_format($perDay, 2) }} a day · {{ $stayDays }} day{{ $stayDays === 1 ? '' : 's' }}</div>
                                        @if($payNow > 0 && $payNow < $display - 0.01)
                                            <div class="chr-price__paynow">Pay now <strong>&pound;{{ number_format($payNow, 2) }}</strong></div>
                                        @endif
                                        <form method="post" action="{{ route('carhire.deal') }}" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="rate_reference" value="{{ $vehicle['rate_reference'] }}">
                                            <button type="submit" class="chr-view-deal">
                                                View Deal <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="chr-card__foot">
                                    <div class="chr-supplier">
                                        @if(!empty($vehicle['supplier_logo']))
                                            <img src="{{ $vehicle['supplier_logo'] }}" alt="{{ $vehicle['supplier_name'] }}">
                                        @endif
                                        <span>{{ $vehicle['supplier_name'] ?: 'Car hire partner' }}</span>
                                    </div>
                                    <div class="chr-rating" title="{{ $sitePartner['title'] }}">
                                        <i class="fas fa-star" aria-hidden="true"></i> {{ $sitePartner['badge'] }}
                                    </div>
                                    <div class="chr-foot-links">
                                        @if($mapUrl)
                                            <a href="{{ $mapUrl }}" target="_blank" rel="noopener">Map and address</a>
                                        @elseif(!empty($vehicle['branch_address']))
                                            <button type="button" class="chr-more-info" data-info="{{ e($vehicle['branch_address']) }}">Map and address</button>
                                        @endif
                                        @if(!empty($vehicle['branch_instructions']) || !empty($vehicle['fuel_policy']))
                                            <button type="button" class="chr-more-info"
                                                    data-info="{{ e(trim(($vehicle['fuel_policy'] ?? '') . "\n" . ($vehicle['branch_instructions'] ?? ''))) }}">
                                                More info
                                            </button>
                                        @endif
                                    </div>
                                    <button type="button" class="chr-included" aria-expanded="false">
                                        INCLUDED IN THE PRICE <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                    </button>
                                    <div class="chr-included-panel">
                                        @if(!empty($vehicle['inclusions']))
                                            <div class="chr-inc-grid">
                                                @foreach($vehicle['inclusions'] as $inc)
                                                    <div class="chr-inc-item"><i class="fas fa-check" aria-hidden="true"></i> {{ $inc }}</div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p style="margin:0;color:#5f7382;font-size:13px;">No inclusions returned for this rate.</p>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="chr-no-match" id="chr_no_match">No cars match your filters. Try resetting them.</div>
                </div>
            </div>
        @endif
    </div>
</section>

<div class="modal fade" id="carhire_search_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit your car hire search</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @include('layouts.carhire_form', ['search' => $search, 'activeBookingTab' => 'carhire'])
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chr_info_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">More information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p id="chr_info_text" style="white-space:pre-wrap;margin:0;color:#5f7382;line-height:1.6;"></p>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
{{-- Scripts after footer so jQuery (sync in footer) is available — same pattern as search_result.blade.php --}}
<script>
(function () {
    function bootCarhireResults($) {
        var $list = $('#chr_listings');
        var activeCategory = '';

        function cards() {
            return $list.find('.chr-card');
        }

        function checkedValues(selector) {
            return $(selector).filter(':checked').map(function () { return String($(this).val()); }).get();
        }

        function seatMatch(bucket, selected) {
            if (!selected.length) return true;
            return selected.indexOf(bucket) !== -1;
        }

        function applyFilters() {
            var pickups = checkedValues('.chr-filter-pickup');
            var seats = checkedValues('.chr-filter-seats');
            var suppliers = checkedValues('.chr-filter-supplier');
            var transmissions = checkedValues('.chr-filter-transmission');
            var sort = $('#chr_sort').val();
            var visible = [];

            cards().each(function () {
                var $card = $(this);
                var show = true;
                if (activeCategory && String($card.data('category')) !== activeCategory) show = false;
                if (pickups.length && pickups.indexOf(String($card.data('pickup'))) === -1) show = false;
                if (!seatMatch(String($card.data('seats') || ''), seats)) show = false;
                if (suppliers.length && suppliers.indexOf(String($card.data('supplier'))) === -1) show = false;
                if (transmissions.length) {
                    var t = String($card.data('transmission') || '').toLowerCase();
                    var ok = transmissions.some(function (wanted) {
                        return t.indexOf(String(wanted).toLowerCase()) !== -1;
                    });
                    if (!ok) show = false;
                }
                $card.toggleClass('is-hidden', !show);
                if (show) visible.push(this);
            });

            visible.sort(function (a, b) {
                if (sort === 'name') {
                    return String($(a).data('name')).localeCompare(String($(b).data('name')));
                }
                var pa = parseFloat($(a).data('price')) || 0;
                var pb = parseFloat($(b).data('price')) || 0;
                return sort === 'price-desc' ? pb - pa : pa - pb;
            });
            $list.append(visible);
            $('#chr_visible_count').text(visible.length);
            $('#chr_no_match').toggle(visible.length === 0);
        }

        function showInfoModal(text) {
            $('#chr_info_text').text(text || '');
            var $modal = $('#chr_info_modal');
            if ($.fn.modal) {
                $modal.modal('show');
                return;
            }
            // Bootstrap may still be deferred — open with plain classes.
            $modal.addClass('show').css({ display: 'block' }).attr('aria-hidden', 'false');
            if (!$('.modal-backdrop').length) {
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
            }
            $('body').addClass('modal-open');
        }

        function hideInfoModal() {
            var $modal = $('#chr_info_modal');
            if ($.fn.modal) {
                $modal.modal('hide');
                return;
            }
            $modal.removeClass('show').css({ display: 'none' }).attr('aria-hidden', 'true');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }

        $('#chr_categories').on('click', '.chr-cat', function () {
            $('#chr_categories .chr-cat').removeClass('is-active');
            $(this).addClass('is-active');
            activeCategory = String($(this).data('category') || '');
            applyFilters();
        });

        $(document).on(
            'change',
            '.chr-filter-pickup, .chr-filter-seats, .chr-filter-supplier, .chr-filter-transmission, #chr_sort',
            applyFilters
        );

        $('#chr_reset_filters').on('click', function () {
            $('.chr-filters input[type="checkbox"]').prop('checked', false);
            activeCategory = '';
            $('#chr_categories .chr-cat').removeClass('is-active');
            $('#chr_categories .chr-cat[data-category=""]').addClass('is-active');
            $('#chr_sort').val('price');
            applyFilters();
        });

        $list.on('click', '.chr-included', function () {
            var $btn = $(this);
            var $panel = $btn.nextAll('.chr-included-panel').first();
            var open = !$panel.hasClass('is-open');
            $panel.toggleClass('is-open', open);
            $btn.attr('aria-expanded', open ? 'true' : 'false');
            $btn.find('i').toggleClass('fa-chevron-down', !open).toggleClass('fa-chevron-up', open);
        });

        $list.on('click', '.chr-more-info', function (e) {
            e.preventDefault();
            showInfoModal($(this).attr('data-info') || '');
        });

        $('#chr_info_modal').on('click', '[data-dismiss="modal"], .close', function (e) {
            e.preventDefault();
            hideInfoModal();
        });
        $(document).on('click', '#chr_info_modal', function (e) {
            if (e.target === this) hideInfoModal();
        });
        $(document).on('keydown.chrInfoModal', function (e) {
            if (e.key === 'Escape') hideInfoModal();
        });
    }

    function waitForJQuery(attempt) {
        if (window.jQuery) {
            bootCarhireResults(window.jQuery);
            return;
        }
        if ((attempt || 0) > 80) return;
        setTimeout(function () { waitForJQuery((attempt || 0) + 1); }, 50);
    }

    waitForJQuery(0);
})();
</script>
