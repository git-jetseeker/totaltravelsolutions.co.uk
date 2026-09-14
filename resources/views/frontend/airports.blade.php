@php $meta = function_exists('crm_page_meta') ? crm_page_meta('airports') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: 'Total Travel Solutions | Compare Affordable & Convenient Airport Parking Options')
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: 'airports')
@section('meta_description', ($meta->meta_description ?? null) ?: 'Compare top airport parking services for the best deals on secure, affordable, and convenient parking options near major airports.')

@include('layouts.header')
@include('layouts.nav')
@include('layouts.search_form')

<section class="pz-page-content">
    <div class="pz-page-wrap pz-page-wrap--wide">
        <div class="pz-airports-intro">
            <h2>{{ crm('airports.intro_title', 'All Major UK Airports') }}</h2>
            <p>{{ crm('airports.intro_text', 'Compare and book secure, affordable parking at every major UK airport with best-price deals and trusted operators.') }}</p>
        </div>

        <div class="pz-airports-grid">
            @php $p = 28; @endphp
            @foreach ($airports as $index => $airport)
                @if($airport->id == '20')
                    @php $p = 4; @endphp
                @endif
                @if($airport->id == '27')
                    @php $p = 5; @endphp
                @endif
                @if($airport->id == '26')
                    @php $p = 5; @endphp
                @endif
                @if($airport->id == '40')
                    @php $p = 9; @endphp
                @endif
                @if($airport->id == '24')
                    @php $p = 4; @endphp
                @endif
                @if($airport->id == '1')
                    @php $p = 4; @endphp
                @endif
                @php
                    if (preg_match('/\s/', $airport->name)) {
                        $name = str_replace(' ', '-', strtolower($airport->name));
                    } else {
                        $name = trim(strtolower($airport->name));
                    }
                    $url = str_replace(' ', '-', $name) . '-airport-parking';
                @endphp

                <div class="pz-airport-item {{ $index < 9 ? 'show' : '' }}">
                    <article class="pz-airport-card">
                        <div class="pz-airport-card__img">
                            <img src="{{ url('https://www.dashboard.jetseekergroup.com/storage/' . str_replace('public/', '', $airport->profile_image)) }}"
                                alt="{{ $airport->name }}" loading="lazy">
                            <span class="pz-airport-card__badge">{{ $airport->name }}</span>
                        </div>
                        <div class="pz-airport-card__body">
                            <h3 class="pz-airport-card__name">{{ $airport->name }} Parking</h3>
                            <div class="pz-airport-card__stars">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="pz-airport-card__features">
                                <span class="pz-airport-card__feature" title="24/7 CCTV">
                                    <img src="{{ asset('theme/images/CCTV.png') }}" alt="CCTV">
                                </span>
                                <span class="pz-airport-card__feature" title="Disability Access">
                                    <img src="{{ asset('theme/images/disability.png') }}" alt="Disability">
                                </span>
                                <span class="pz-airport-card__feature" title="Security Barriers">
                                    <img src="{{ asset('theme/images/barrier.png') }}" alt="Barrier">
                                </span>
                                <span class="pz-airport-card__feature" title="24 Hour Service">
                                    <img src="{{ asset('theme/images/24_hours.png') }}" alt="24 Hours">
                                </span>
                            </div>
                            <div class="pz-airport-card__footer">
                                <div>
                                    <span class="pz-airport-card__price-label">{{ crm('airports.card_price_label', 'Starting from') }}</span>
                                    <span class="pz-airport-card__price">£{{ $p }}</span>
                                </div>
                                <a href="{{ route('page', ['slug' => $url]) }}" class="pz-airport-card__link">
                                    {{ crm('airports.card_cta', 'View Deals') }} <i class="fa fa-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
                @php $p = $p + 2; @endphp
            @endforeach
        </div>

        @if(count($airports) > 9)
            <div class="pz-btn-more-wrap">
                <button type="button" id="showMoreBtn" class="pz-btn-more">{{ crm('airports.show_more', 'Show More') }}</button>
            </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('.pz-airport-item');
    var btn = document.getElementById('showMoreBtn');
    var limit = 9;

    if (!btn || items.length <= limit) {
        if (btn) btn.style.display = 'none';
        return;
    }

    btn.addEventListener('click', function () {
        var hidden = Array.prototype.filter.call(items, function (el) {
            return !el.classList.contains('show');
        });

        if (hidden.length) {
            hidden.forEach(function (el) { el.classList.add('show'); });
            btn.textContent = 'Show Less';
        } else {
            Array.prototype.forEach.call(items, function (el, i) {
                if (i >= limit) el.classList.remove('show');
            });
            btn.textContent = 'Show More';
            document.querySelector('.pz-airports-intro').scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>

@include('layouts.footer')
