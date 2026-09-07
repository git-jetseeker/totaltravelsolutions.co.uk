@php
    use Illuminate\Support\Str;

    $checkinRaw = $request->input('hotel_checkin_date');
    $checkoutRaw = $request->input('hotel_checkout_date');
    $fromTs = strtotime(str_replace('/', '-', (string) $checkinRaw));
    $toTs = strtotime(str_replace('/', '-', (string) $checkoutRaw));
    $stayNights = ($fromTs && $toTs && $toTs > $fromTs)
        ? max(1, (int) round(($toTs - $fromTs) / 86400))
        : 1;
    $searchMeta = $hotel_search_meta ?? [];
@endphp

<section id="lounge-sec" class="js-results-cards-section">
    <div class="js-apb-results-list" id="skip-b">
        @if (!empty($companies) && count($companies) > 0)
            @foreach ($companies as $index => $company)
                @include('partials.results-lounge-deal-card', [
                    'company' => $company,
                    'request' => $request,
                    'promo' => $promo ?? '',
                    'index' => $index,
                ])
            @endforeach
        @else
            <div class="js-results-empty">
                <img src="{{ asset('theme/images/norecord.png') }}" alt="No results" class="js-results-empty__img">
                <h3 class="js-results-empty__title">No lounge results found</h3>
                <p class="js-results-empty__text">Try different dates, another terminal, or amend your search above.</p>
            </div>
        @endif
    </div>
</section>
