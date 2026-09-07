<section class="js-results-header">
    <div class="container">
        <h1 class="js-results-header__airport">{{ $title ?? 'Search Results' }}</h1>
        @if (!empty($subtitle))
            <p class="js-results-header__subtitle" style="color:rgba(255,255,255,0.9);text-align:center;margin:8px 0 0;font-size:14px;">{{ $subtitle }}</p>
        @endif
    </div>
</section>
