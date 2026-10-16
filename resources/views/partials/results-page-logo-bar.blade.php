{{-- Results page: logo only (no main nav / topbar) --}}
<header class="js-results-logo-bar">
    <div class="container js-results-logo-bar__inner">
        <a href="{{ url('/') }}" class="js-results-logo-bar__link" aria-label="Total Travel Solutions Home">
            <picture>
                <source type="image/webp" srcset="{{ asset('theme/images/logo-black-280.webp') }}?v=20261012">
                <img src="{{ asset('theme/images/logo-black.png') }}?v=20261012" alt="Total Travel Solutions" width="160" height="64">
            </picture>
        </a>
    </div>
</header>
