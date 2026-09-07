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

<section id="hotel-sec" class="js-results-cards-section">
    <div class="js-apb-results-list" id="skip-b">
        @if (!empty($companies) && count($companies) > 0)
            @foreach ($companies as $index => $company)
                @include('partials.results-hotel-deal-card', [
                    'company' => $company,
                    'request' => $request,
                    'searchId' => $searchId ?? null,
                    'stayNights' => $stayNights,
                    'index' => $index,
                ])
            @endforeach
        @else
            <div class="js-results-empty">
                <i class="fas fa-bed js-results-empty__icon" aria-hidden="true"></i>
                <h3 class="js-results-empty__title">No hotels found</h3>
                @php
                    $apiRawCount = (int) ($searchMeta['api_raw_count'] ?? 0);
                    $matchedCount = (int) ($searchMeta['matched_count'] ?? 0);
                    $activeDbCount = (int) ($searchMeta['active_db_count'] ?? 0);
                @endphp
                @if ($apiRawCount === 0)
                    <p class="js-results-empty__text">The hotel supplier returned no availability for these dates and guests. Try different dates or amend your search above.</p>
                @elseif ($matchedCount === 0 && $activeDbCount > 0)
                    <p class="js-results-empty__text">Hotels were returned by the API, but none match your active local hotel catalogue.</p>
                @else
                    <p class="js-results-empty__text">Try different dates, a different room type, or another airport location.</p>
                @endif
                <a href="{{ url('/airport-hotels') }}" class="js-apb-deal-card__btn js-apb-deal-card__btn--book">New Search</a>
            </div>
        @endif
    </div>
</section>
