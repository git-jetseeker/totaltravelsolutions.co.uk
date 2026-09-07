{{-- Reusable page hero partial
     Usage: @include('partials.page-hero', ['title' => 'About Us', 'subtitle' => 'Optional subtitle', 'lead' => 'Optional paragraph'])
     With booking widget: pass withBookingWidget => true, selectedAirportId => $id (airport pages)
--}}
<section class="js-page-hero {{ $compact ?? false ? 'js-page-hero--compact' : '' }} {{ $heroClass ?? '' }}">
    <div class="js-container">
        @if (!empty($eyebrow))
            <span class="js-page-hero__eyebrow">{{ $eyebrow }}</span>
        @endif
        <h1 class="js-page-hero__title">{{ $title }}</h1>
        @if (!empty($subtitle))
            <p class="js-page-hero__subtitle">{{ $subtitle }}</p>
        @endif
        @if (!empty($lead))
            <p class="js-page-hero__lead">{{ $lead }}</p>
        @endif

        @if (!empty($withBookingWidget))
            @include('partials.booking-widget', [
                'selectedAirportId' => $selectedAirportId ?? null,
                'bookingCardId' => $bookingCardId ?? 'airport_search_form',
                'bookingCardClass' => 'js-booking-card--hero',
                'skipRefTracking' => $skipRefTracking ?? false,
            ])
        @endif
    </div>
</section>
