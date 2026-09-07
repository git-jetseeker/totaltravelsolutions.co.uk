@section('title', $page->meta_title)
@section('meta_keyword', $page->meta_keyword)
@section('meta_description', $page->meta_description)

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-airport.css?v=20260907tabs4') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20260907noblue2') }}">

   @if(request()->get('src') != '')
    {{ session()->put('bk_src', request()->get('src')) }}
@endif
@if(request()->get('utm_source') == 'ppc')
    {{ session()->put('bk_src', 'PPC') }}
@endif
@if(request()->get('utm_source') == 'bing')
    {{ session()->put('bk_src', 'BING') }}
@endif
@if(request()->get('utm_source') == 'EMAIL')
    {{ session()->put('bk_src', 'EM') }}
@endif

@php
    $airportName = $airports_Detail->name ?? trim(strip_tags($page->page_title ?? 'Airport'));

    $tabParking = trim((string) ($page->airport_parking ?? ''));
    $tabOverview = trim((string) ($page->overview ?? ''));
    $tabFacilities = trim((string) ($page->facilities ?? ''));
    $tabTopThings = trim((string) ($page->topthings ?? ''));

    $stripLegacyTabMarkup = function (string $html): string {
        $html = preg_replace('/<!--.*?-->/s', '', $html) ?? $html;
        $html = preg_replace('/\sclass="[^"]*tab-pane[^"]*"/i', '', $html) ?? $html;
        $html = preg_replace('/\sid="(parking|overview|fac|facilities|top_things|topthings|map)"/i', '', $html) ?? $html;
        return trim($html);
    };

    // Legacy pages store tab HTML inside $page->content as nested Bootstrap panes.
    if ($tabParking === '' && $tabOverview === '' && $tabFacilities === '' && $tabTopThings === '' && !empty($page->content)) {
        $legacyContent = (string) $page->content;
        $mapped = [
            'parking' => '',
            'overview' => '',
            'facilities' => '',
            'topthings' => '',
        ];
        $idMap = [
            'parking' => 'parking',
            'overview' => 'overview',
            'fac' => 'facilities',
            'facilities' => 'facilities',
            'top_things' => 'topthings',
            'topthings' => 'topthings',
            'map' => 'overview',
        ];

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $wrapped = '<div id="tts-legacy-tabs">' . $legacyContent . '</div>';
        $dom->loadHTML('<?xml encoding="UTF-8">' . $wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $panes = $xpath->query('//*[@id="tts-legacy-tabs"]//*[@class and contains(concat(" ", normalize-space(@class), " "), " tab-pane ")]');

        if ($panes !== false) {
            foreach ($panes as $pane) {
                $id = strtolower(trim((string) $pane->getAttribute('id')));
                if (!isset($idMap[$id])) {
                    continue;
                }
                $inner = '';
                foreach ($pane->childNodes as $child) {
                    $inner .= $dom->saveHTML($child);
                }
                $key = $idMap[$id];
                $inner = $stripLegacyTabMarkup($inner);
                if ($id === 'map') {
                    $mapped[$key] .= $inner;
                } elseif ($mapped[$key] === '') {
                    $mapped[$key] = $inner;
                }
            }
        }

        $tabParking = $mapped['parking'];
        $tabOverview = $mapped['overview'];
        $tabFacilities = $mapped['facilities'];
        $tabTopThings = $mapped['topthings'];

        if ($tabParking === '' && $tabOverview === '' && $tabFacilities === '' && $tabTopThings === '') {
            $tabParking = $stripLegacyTabMarkup($legacyContent);
        }
    }

    $tabParking = $stripLegacyTabMarkup($tabParking);
    $tabOverview = $stripLegacyTabMarkup($tabOverview);
    $tabFacilities = $stripLegacyTabMarkup($tabFacilities);
    $tabTopThings = $stripLegacyTabMarkup($tabTopThings);

    if ($tabOverview === '' && !empty($airports_Detail->description)) {
        $tabOverview = (string) $airports_Detail->description;
    }
@endphp

@include('partials.page-hero', [
    'title' => strip_tags($page->page_title ?? ($airportName . ' Airport Parking')),
    'subtitle' => 'Compare trusted parking at ' . $airportName,
    'lead' => 'Pre-book Meet & Greet, Park & Ride, and on-airport parking with transparent pricing and Park Mark accredited operators.',
    'heroClass' => 'js-page-hero--enhanced js-page-hero--airport',
    'withBookingWidget' => true,
    'selectedAirportId' => $id ?? ($airports_Detail->id ?? null),
])

<main class="js-airport-page">

    {{-- Trust strip --}}
    <section class="js-airport-trust">
        <div class="js-container">
            <div class="js-airport-trust__grid">
                <div class="js-airport-trust__item">
                    <span class="js-airport-trust__icon"><img src="{{ asset('assets/images/serviceicon3.webp') }}" alt="" loading="lazy"></span>
                    <p class="js-airport-trust__label">Years of Experience</p>
                             </div>
                <div class="js-airport-trust__item">
                    <span class="js-airport-trust__icon"><img src="{{ asset('assets/images/serviceicon1.webp') }}" alt="" loading="lazy"></span>
                    <p class="js-airport-trust__label">Free Cancellation</p>
                                </div>
                <div class="js-airport-trust__item">
                    <span class="js-airport-trust__icon"><img src="{{ asset('assets/images/serviceicon2.webp') }}" alt="" loading="lazy"></span>
                    <p class="js-airport-trust__label">Never Beaten on Price</p>
                                        </div>
                            </div>
                                        </div>
</section>

    {{-- Tabbed airport guide --}}
    <section class="js-airport-guide">
        <div class="js-container">
            <header class="js-airport-guide__head">
                <span class="js-airport-guide__eyebrow">Airport guide</span>
                <h2 class="js-airport-guide__title">Best Available <span>{{ strip_tags($page->page_title ?? $airportName) }}</span> Deals</h2>
            </header>

            <div class="js-airport-guide__tabs-wrap" data-js-airport-tabs>
                <div class="js-airport-guide__mobile-select">
                    <select class="form-control" id="mobileTabsSelect" aria-label="Select airport information section">
                        <option value="parking">Airport parking</option>
                        <option value="overview">Airport overview</option>
                        <option value="facilities">Airport facilities</option>
                        <option value="things">Top things to do</option>
                    </select>
                </div>

                <div class="js-airport-guide__tabs-nav" role="tablist">
                    <button type="button" class="js-airport-tab is-active" role="tab" aria-selected="true" data-tab="parking">Airport parking</button>
                    <button type="button" class="js-airport-tab" role="tab" aria-selected="false" data-tab="overview">Airport overview</button>
                    <button type="button" class="js-airport-tab" role="tab" aria-selected="false" data-tab="facilities">Airport facilities</button>
                    <button type="button" class="js-airport-tab" role="tab" aria-selected="false" data-tab="things">Top things to do</button>
                </div>

                <div class="js-airport-guide__panel">
                    <div class="js-airport-panel is-active" data-panel="parking" role="tabpanel">
                        <div class="js-airport-content">
                            @if($tabParking !== '')
                                {!! $tabParking !!}
                            @else
                                <p>Parking information for {{ $airportName }} will appear here soon.</p>
                            @endif
                        </div>
                    </div>
                    <div class="js-airport-panel" data-panel="overview" role="tabpanel">
                        <div class="js-airport-content">
                            @if($tabOverview !== '')
                                {!! $tabOverview !!}
                            @else
                                <p>Overview for {{ $airportName }} will appear here soon.</p>
                            @endif
                        </div>
                    </div>
                    <div class="js-airport-panel" data-panel="facilities" role="tabpanel">
                        <div class="js-airport-content">
                            @if($tabFacilities !== '')
                                {!! $tabFacilities !!}
                            @else
                                <p>Facilities information for {{ $airportName }} will appear here soon.</p>
                            @endif
                        </div>
                    </div>
                    <div class="js-airport-panel" data-panel="things" role="tabpanel">
                        <div class="js-airport-content">
                            @if($tabTopThings !== '')
                                {!! $tabTopThings !!}
                            @else
                                <p>Things to do near {{ $airportName }} will appear here soon.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing tables --}}
    @if(count($all_records_md) > 0 || count($all_records_pd) > 0)
    <section class="js-airport-pricing">
        <div class="js-container">

            @if(count($all_records_md) > 0)
            <div class="js-airport-pricing__block">
                <header class="js-airport-pricing__head">
                    <span class="js-airport-pricing__badge">Meet &amp; Greet</span>
                    <h2 class="js-airport-pricing__title">{{ ucwords($airportName) }} Meet &amp; Greet Parking</h2>
                    <p class="js-airport-pricing__intro">{!! $page->meet_and_greet !!}</p>
                </header>
                <div class="js-airport-table-wrap">
                    <table class="js-airport-table">
                                            <thead>
                            <tr>
                                <th>Car Park</th>
                                <th class="d-none d-md-table-cell">Customer Rating</th>
                                <th>Transfer Time</th>
                                <th class="d-none d-md-table-cell">Awards</th>
                                <th>Price <small>(per day)</small></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($all_records_md as $company)
                                @if($company['price'] != '0.00')
                                @php
                                    $modules = \App\Models\reviews::where('type_id', $company['companyID'])->where('status', 'Yes');
                                    $avg = $modules->avg('rating');
                                    $avgRating = round($avg, 2) * 2;
                                    $companyPrice = number_format(($company['price'] / 8), 2);
                                    $expPrice = explode('.', $companyPrice);
                                    $arrangeAwardList = [];
                                    $awards = \App\Models\companies_assign_awards::all()->where('cid', $company['companyID']);
                                    foreach ($awards as $award) { $arrangeAwardList[] = $award; }
                                                            @endphp
                                <tr>
                                    <td>{{ $company['name'] }}</td>
                                    <td class="d-none d-md-table-cell">
                                        @if($avgRating > 0)
                                            <span class="js-airport-table__score">{{ $avgRating }}</span>
                                                            @endif
                                        @for($s = 0; $s < 5; $s++)<i class="fa fa-star reviews-stars" aria-hidden="true"></i>@endfor
                                                    </td>
                                    <td>Chauffeur meets you at terminal</td>
                                    <td class="d-none d-md-table-cell">
                                        @if(count($arrangeAwardList) > 0)
                                            @php $image = str_replace('public/', '', $arrangeAwardList[0]->award->image); @endphp
                                            <img class="awards-img" src="https://dashboard.jetseekergroup.com/storage/{{ $image }}" alt="{{ $company['name'] }}">
                                                        @endif
                                                    </td>
                                    <td><span class="js-airport-table__price">&pound;{{ $expPrice[0] }}.<sup>{{ $expPrice[1] }}</sup></span></td>
                                                </tr>
                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                        </div>
            </div>
                        @endif

            @if(count($all_records_pd) > 0)
            <div class="js-airport-pricing__block">
                <header class="js-airport-pricing__head">
                    <span class="js-airport-pricing__badge">Park &amp; Ride</span>
                    <h2 class="js-airport-pricing__title">{{ ucwords($airportName) }} Park &amp; Ride Parking</h2>
                    <p class="js-airport-pricing__intro">{!! $page->park_and_ride !!}</p>
                </header>
                <div class="js-airport-table-wrap">
                    <table class="js-airport-table">
                                            <thead>
                            <tr>
                                <th>Car Park</th>
                                <th class="d-none d-md-table-cell">Customer Rating</th>
                                <th>Transfer Time</th>
                                <th class="d-none d-md-table-cell">Awards</th>
                                <th>Price <small>(per day)</small></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($all_records_pd as $company)
                                @if($company['price'] != '0.00')
                                @php
                                    $modules = \App\Models\reviews::where('type_id', $company['companyID'])->where('status', 'Yes');
                                    $avg = $modules->avg('rating');
                                    $avgRating = round($avg, 2) * 2;
                                    $companyPrice = number_format(($company['price'] / 8), 2);
                                    $expPrice = explode('.', $companyPrice);
                                    $arrangeAwardList = [];
                                    $awards = \App\Models\companies_assign_awards::all()->where('cid', $company['companyID']);
                                    foreach ($awards as $award) { $arrangeAwardList[] = $award; }
                                                            @endphp
                                <tr>
                                    <td>{{ $company['name'] }}</td>
                                    <td class="d-none d-md-table-cell">
                                        @if($avgRating > 0)
                                            <span class="js-airport-table__score">{{ $avgRating }}</span>
                                                            @endif
                                        @for($s = 0; $s < 5; $s++)<i class="fa fa-star reviews-stars" aria-hidden="true"></i>@endfor
                                                    </td>
                                    <td>Shuttle transfer to terminal</td>
                                    <td class="d-none d-md-table-cell">
                                        @if(count($arrangeAwardList) > 0)
                                            @php $image = str_replace('public/', '', $arrangeAwardList[0]->award->image); @endphp
                                            <img class="awards-img" src="https://dashboard.jetseekergroup.com/storage/{{ $image }}" alt="{{ $company['name'] }}">
                                                        @endif
                                                    </td>
                                    <td><span class="js-airport-table__price">&pound;{{ $expPrice[0] }}.<sup>{{ $expPrice[1] }}</sup></span></td>
                                                </tr>
                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                    @endif

                    </div>
                            </section>
    @endif

    {{-- Parking service types --}}
    <section class="js-airport-services">
        <div class="js-container">
            <header class="js-airport-services__head">
                <h2 class="js-airport-services__title">Our Parking Services</h2>
                @if(!empty($page->alluring))
                    <p class="js-airport-services__intro">{!! $page->alluring !!}</p>
                @endif
            </header>
            <div class="js-airport-services__grid">
                <article class="js-airport-service-card js-airport-service-card--mg">
                    <div class="js-airport-service-card__icon">
                        <img src="{{ asset('assets/images/customer-loyalty-_1_ 1.webp') }}" alt="Meet and Greet" loading="lazy" width="64" height="64">
                        </div>
                    <h3 class="js-airport-service-card__title">Meet and Greet</h3>
                    <div class="js-airport-service-card__body">{!! $page->alluring_meetandgreet !!}</div>
                </article>
                <article class="js-airport-service-card js-airport-service-card--pr">
                    <div class="js-airport-service-card__icon">
                        <img src="{{ asset('assets/images/car-parking-_1_ 1.webp') }}" alt="Park and Ride" loading="lazy" width="64" height="64">
                    </div>
                    <h3 class="js-airport-service-card__title">Park and Ride</h3>
                    <div class="js-airport-service-card__body">{!! $page->alluring_parkandride !!}</div>
                </article>
                <article class="js-airport-service-card js-airport-service-card--oa">
                    <div class="js-airport-service-card__icon">
                        <img src="{{ asset('assets/images/Group.webp') }}" alt="On-Site parking" loading="lazy" width="64" height="64">
        </div>
                    <h3 class="js-airport-service-card__title">On-Site</h3>
                    <div class="js-airport-service-card__body">{!! $page->alluring_onairport !!}</div>
                </article>
        </div>
    </div>
                     </section>

    {{-- Other airports --}}
    <section class="js-airport-others">
        <div class="js-container">
            <h2 class="js-airport-others__title">Other Airport Options</h2>
            <div class="js-airport-others__grid">
                @php $i = 0; @endphp
                @foreach($airports as $airport)
                    @if($page->typeid != $airport->id)
                        @php
                            $name = preg_match('/\s/', $airport->name)
                                ? str_replace(' ', '-', strtolower($airport->name))
                                : trim(strtolower($airport->name));
                            $otherSlug = $name . '-airport-parking';
                    $i++;
                    @endphp
                        <a class="js-airport-others__card" href="{{ route('page', ['slug' => $otherSlug]) }}">
                            <img src="{{ url('https://dashboard.jetseekergroup.com/storage/' . str_replace('public/', '', $airport->profile_image)) }}"
                                 alt="{{ $airport->name }} airport" loading="lazy">
                            <strong>{{ $airport->name }} Airport</strong>
                        </a>
                        @if($i > 7) @break @endif
                    @endif
                     @endforeach
                </div>
            </div>
        </section>

    {{-- Reviews --}}
    <section class="js-airport-reviews">
         @include('frontend.review2')
    </section>

</main>

@include('layouts.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('[data-js-airport-tabs]');
    if (!root) return;

    var tabs = root.querySelectorAll('.js-airport-tab');
    var panels = root.querySelectorAll('.js-airport-panel');
    var mobileSelect = document.getElementById('mobileTabsSelect');

    function activateTab(name) {
        if (!name) return;
        tabs.forEach(function (tab) {
            var on = tab.getAttribute('data-tab') === name;
            tab.classList.toggle('is-active', on);
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panels.forEach(function (panel) {
            panel.classList.toggle('is-active', panel.getAttribute('data-panel') === name);
        });
        if (mobileSelect) {
            mobileSelect.value = name;
        }
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            activateTab(tab.getAttribute('data-tab'));
        });
    });

    if (mobileSelect) {
        mobileSelect.addEventListener('change', function () {
            activateTab(this.value);
        });
    }
});
</script>
