@include('layouts.header')
<link rel="stylesheet" href="{{ asset('theme/styles/jetseeker-customer-dashboard.css?v=20261109portal-pages') }}">

<div class="tts-account-shell">
    @include('frontend.customer.partials.sidebar')

    <main class="tts-account-main">
        <header class="tts-account-topbar">
            <div>
                <strong>Hi, {{ $customer['first_name'] ?: 'there' }}</strong>
                <span>Your Total Travel Solutions customer account</span>
            </div>
            <div class="tts-account-topbar__actions">
                <a href="{{ route('support') }}" class="tts-account-button tts-account-button--light">
                    <i class="fa fa-headphones" aria-hidden="true"></i> Support
                </a>
                <a href="{{ route('main') }}#search" class="tts-account-button">
                    <i class="fa fa-car" aria-hidden="true"></i> Book parking
                </a>
            </div>
        </header>

        <div class="tts-account-content">
            @yield('customer_content')
        </div>
    </main>
</div>

@include('layouts.footer')
