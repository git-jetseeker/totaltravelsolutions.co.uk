<aside class="tts-account-sidebar" aria-label="Customer account navigation">
    <a class="tts-account-brand" href="{{ route('main') }}">
        <img src="{{ asset('theme/images/logo-white.png') }}" alt="Total Travel Solutions" width="170" height="50">
        <span>Customer account</span>
    </a>

    <nav class="tts-account-sidebar__nav">
        <a href="{{ route('customer.dashboard') }}" class="{{ Route::currentRouteName() === 'customer.dashboard' ? 'is-active' : '' }}">
            <i class="fa fa-th-large" aria-hidden="true"></i> Dashboard
        </a>
        <a href="{{ route('customer.bookings') }}" class="{{ Route::currentRouteName() === 'customer.bookings' ? 'is-active' : '' }}">
            <i class="fa fa-calendar-check-o" aria-hidden="true"></i> Booking history
        </a>
        <a href="{{ route('customer.tickets') }}" class="{{ Route::currentRouteName() === 'customer.tickets' ? 'is-active' : '' }}">
            <i class="fa fa-life-ring" aria-hidden="true"></i> Support tickets
        </a>
    </nav>

    <div class="tts-account-sidebar__bottom">
        <strong>{{ trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')) ?: 'Customer' }}</strong>
        <span>{{ $customer['email'] }}</span>
        <a href="{{ route('main') }}" class="tts-account-sidebar__site-link">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to site
        </a>
        <form method="post" action="{{ route('customer.dashboard.logout') }}">
            @csrf
            <button type="submit" class="tts-account-sidebar__logout">
                <i class="fa fa-sign-out" aria-hidden="true"></i> Log out
            </button>
        </form>
    </div>
</aside>
