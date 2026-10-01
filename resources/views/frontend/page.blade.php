@section('title', $page->meta_title)
@section('meta_keyword', $page->meta_keyword)
@section('meta_description', $page->meta_description)

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20261001light') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-airport.css?v=20261001airport5') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-booking-widget.css?v=20261001mock') }}">

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

    // Strip Google Docs / CMS inline styles so content inherits the site design system.
    $cleanCmsHtml = function (string $html): string {
        if ($html === '') {
            return '';
        }

        $html = preg_replace('/<!--.*?-->/s', '', $html) ?? $html;
        $html = preg_replace('/\sstyle=("[^"]*"|\'[^\']*\')/i', '', $html) ?? $html;
        $html = preg_replace('/\s(face|size|color|bgcolor|align|width|height|border|cellpadding|cellspacing)=("[^"]*"|\'[^\']*\')/i', '', $html) ?? $html;
        $html = preg_replace('/\sdir=("[^"]*"|\'[^\']*\')/i', '', $html) ?? $html;
        $html = preg_replace('/<\/?font\b[^>]*>/i', '', $html) ?? $html;
        $html = preg_replace('/<span\b[^>]*>/i', '', $html) ?? $html;
        $html = preg_replace('/<\/span>/i', '', $html) ?? $html;
        $html = preg_replace('/<(p|h1|h2|h3|h4|h5|h6|div|li|ul|ol|td|th|tr|table)(\s[^>]*)?>/i', '<$1>', $html) ?? $html;
        $html = preg_replace('/<(p|div|h1|h2|h3|h4|h5|h6)>\s*<\/\1>/i', '', $html) ?? $html;
        $html = preg_replace('/(?:<br\s*\/?>\s*){3,}/i', '<br><br>', $html) ?? $html;
        $html = str_replace('&nbsp;', ' ', $html);
        $html = preg_replace('/Parkinga\b/i', 'Parking', $html) ?? $html;
        $html = preg_replace('/\b(JETSEEKER|JET SEEKER|JetSeeker|Jetseeker|ParkingZone)\b/i', 'Total Travel Solutions', $html) ?? $html;

        return trim($html);
    };

    $tabParking = $cleanCmsHtml($tabParking);
    $tabOverview = $cleanCmsHtml($tabOverview);
    $tabFacilities = $cleanCmsHtml($tabFacilities);
    $tabTopThings = $cleanCmsHtml($tabTopThings);

    if ($tabOverview === '' && !empty($airports_Detail->description)) {
        $tabOverview = $cleanCmsHtml((string) $airports_Detail->description);
    }

    $guideTitleAirport = preg_replace('/\s+Airport$/i', '', $airportName) ?: $airportName;
    $guideTitle = trim($guideTitleAirport . ' Airport Parking');

    $heroTitle = preg_replace('/Parkinga\b/i', 'Parking', strip_tags($page->page_title ?? ($airportName . ' Airport Parking')));
    $heroSubtitle = 'Compare trusted Meet & Greet, Park & Ride, and on-site parking at ' . $airportName . '.';
    $heroEyebrow = $airportName . ' Parking';
    $selectedAirportId = $id ?? ($airports_Detail->id ?? null);
    $bookingCardId = 'airport_search_form';
@endphp

@include('layouts.search_form')

<main class="js-airport-page">

    {{-- Trust / why choose us --}}
    @include('partials.why-choose')

    {{-- Tabbed airport guide --}}
    <section class="js-airport-guide">
        <div class="js-container">
            <header class="js-airport-guide__head">
                <span class="js-airport-guide__eyebrow">Airport guide</span>
                <h2 class="js-airport-guide__title">Best Available <span>{{ $guideTitle }}</span> Deals</h2>
                <p class="js-airport-guide__lead">Everything you need to know about parking at {{ $airportName }} — options, facilities, and local tips.</p>
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

    {{-- Pricing — JetSeeker comparison table --}}
    @if(count($all_records_md) > 0 || count($all_records_pd) > 0)
    <section class="js-airport-pricing section-spacing">
        <div class="container">
            @if(count($all_records_md) > 0)
            <div class="js-airport-pricing__block" id="meet-greet-pricing">
                <div class="js-section-head">
                    <span class="js-section-head__badge js-section-head__badge--light">Meet &amp; Greet</span>
                    <h2 class="js-section-title">{{ ucwords($airportName) }} Meet &amp; Greet Parking</h2>
                    <p class="js-section-subtitle">Compare Meet &amp; Greet deals at {{ $airportName }} — chauffeur service to the terminal.</p>
                </div>
                <div class="js-airport-table-wrap">
                    <table class="js-airport-table">
                        <thead>
                            <tr>
                                <th class="js-airport-table__name">Car park</th>
                                <th class="js-airport-table__rating">Rating</th>
                                <th class="js-airport-table__transfer">Transfer</th>
                                <th class="js-airport-table__awards">Awards</th>
                                <th class="js-airport-table__price-col">From / day</th>
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
                                    foreach ($awards as $award) {
                                        $arrangeAwardList[] = $award;
                                    }
                                @endphp
                                <tr>
                                    <td class="js-airport-table__name" data-label="Car park">{{ $company['name'] }}</td>
                                    <td class="js-airport-table__rating" data-label="Rating">
                                        <span class="js-airport-table__stars" aria-label="{{ $avgRating > 0 ? $avgRating . ' out of 10' : 'Rated' }}">
                                            @for($s = 0; $s < 5; $s++)<i class="fa fa-star" aria-hidden="true"></i>@endfor
                                            @if($avgRating > 0)<em>{{ $avgRating }}</em>@endif
                                        </span>
                                    </td>
                                    <td class="js-airport-table__transfer" data-label="Transfer">Chauffeur at terminal</td>
                                    <td class="js-airport-table__awards" data-label="Awards">
                                        @if(count($arrangeAwardList) > 0)
                                            <img class="awards-img" src="{{ asset('storage/' . $arrangeAwardList[0]->award->image) }}" alt="" loading="lazy" width="40" height="40">
                                        @else
                                            <span class="js-airport-table__dash">—</span>
                                        @endif
                                    </td>
                                    <td class="js-airport-table__price-col" data-label="From / day">
                                        <span class="js-airport-table__price">&pound;{{ $expPrice[0] }}.<sup>{{ $expPrice[1] }}</sup></span>
                                    </td>
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
                <div class="js-section-head">
                    <span class="js-section-head__badge js-section-head__badge--light">Park &amp; Ride</span>
                    <h2 class="js-section-title">{{ ucwords($airportName) }} Park &amp; Ride Parking</h2>
                    <p class="js-section-subtitle">Compare Park &amp; Ride deals at {{ $airportName }} with free shuttle to the terminal.</p>
                </div>
                <div class="js-airport-table-wrap">
                    <table class="js-airport-table">
                        <thead>
                            <tr>
                                <th class="js-airport-table__name">Car park</th>
                                <th class="js-airport-table__rating">Rating</th>
                                <th class="js-airport-table__transfer">Transfer</th>
                                <th class="js-airport-table__awards">Awards</th>
                                <th class="js-airport-table__price-col">From / day</th>
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
                                    foreach ($awards as $award) {
                                        $arrangeAwardList[] = $award;
                                    }
                                @endphp
                                <tr>
                                    <td class="js-airport-table__name" data-label="Car park">{{ $company['name'] }}</td>
                                    <td class="js-airport-table__rating" data-label="Rating">
                                        <span class="js-airport-table__stars" aria-label="{{ $avgRating > 0 ? $avgRating . ' out of 10' : 'Rated' }}">
                                            @for($s = 0; $s < 5; $s++)<i class="fa fa-star" aria-hidden="true"></i>@endfor
                                            @if($avgRating > 0)<em>{{ $avgRating }}</em>@endif
                                        </span>
                                    </td>
                                    <td class="js-airport-table__transfer" data-label="Transfer">Shuttle to terminal</td>
                                    <td class="js-airport-table__awards" data-label="Awards">
                                        @if(count($arrangeAwardList) > 0)
                                            <img class="awards-img" src="{{ asset('storage/' . $arrangeAwardList[0]->award->image) }}" alt="" loading="lazy" width="40" height="40">
                                        @else
                                            <span class="js-airport-table__dash">—</span>
                                        @endif
                                    </td>
                                    <td class="js-airport-table__price-col" data-label="From / day">
                                        <span class="js-airport-table__price">&pound;{{ $expPrice[0] }}.<sup>{{ $expPrice[1] }}</sup></span>
                                    </td>
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

    {{-- Parking types — identical to home gates --}}
    @include('partials.parking-gates', [
        'badge' => 'Parking types',
        'title' => 'Parking Options at ' . $airportName,
        'subtitle' => 'Choose Meet & Greet, on-site, or Park & Ride to suit your trip.',
    ])

    {{-- Other airports --}}
    <section class="js-airport-others">
        <div class="js-container">
            <header class="js-section-head">
                <span class="js-section-head__badge js-section-head__badge--light">More airports</span>
                <h2 class="js-section-title">Other Airport Options</h2>
                <p class="js-section-subtitle">Compare parking at more major UK airports.</p>
            </header>
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
                            <img src="{{ ttss_dashboard_asset_url($airport->profile_image) }}"
                                alt="{{ $airport->name }} airport" loading="lazy"
                                onerror="this.onerror=null;this.src='{{ asset('theme/images/logo-black.png') }}';">
                            <strong>{{ $airport->name }} Airport</strong>
                        </a>
                        @if($i > 7) @break @endif
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.reviews-section')

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
