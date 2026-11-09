@php $meta = function_exists('crm_page_meta') ? crm_page_meta('manage-booking') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: ($page->meta_title ?? 'Manage Booking - Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: ($page->meta_keyword ?? 'manage booking, booking reference, total travel solutions'))
@section('meta_description', ($meta->meta_description ?? null) ?: ($page->meta_description ?? 'Find and manage your Total Travel Solutions booking using your email and booking details.'))
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20261012min') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-manage-booking.css?v=20261109searchselect') }}">

@include('layouts.search_form', [
    'heroEyebrow' => crm('manage-booking.hero_eyebrow', 'Your trip, in one place'),
    'heroTitle' => crm('manage-booking.hero_title', 'Manage Booking'),
    'heroSubtitle' => crm('manage-booking.hero_subtitle', 'Retrieve your reservation securely'),
    'hideBookingWidget' => true,
])

<main class="js-manage-page">
    @php
        $input = $input ?? [];
        $oldProduct = old('product_type', $input['product_type'] ?? 'parking');
        $oldSearchBy = old('search_by', $input['search_by'] ?? 'booking_number');
        $oldSearchValue = old('search_value', $input['search_value'] ?? $input['ref_no'] ?? '');
    @endphp

    <section class="js-manage-body section-spacing">
        <div class="container">
            <div class="js-manage-main">

                <header class="js-section-head js-reveal">
                    <span class="js-section-head__badge">{{ crm('manage-booking.intro_badge', 'Booking lookup') }}</span>
                    <h2 class="js-section-title" id="booking-summary-title">{{ crm('manage-booking.form_title', 'Find Your Booking') }}</h2>
                    <p class="js-section-subtitle">{{ crm('manage-booking.form_lead', 'Enter the details exactly as they appear on your booking confirmation email.') }}</p>
                </header>

                <div class="js-manage-panel js-reveal">
                    <p class="js-manage-hint">{{ crm('manage-booking.hint', 'Choose your booking type and enter the email used at checkout.') }}</p>

                    @if (!$errors->isEmpty())
                        <div class="js-manage-alert js-manage-alert--error" role="alert">
                            <strong>We couldn’t retrieve your booking.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="js-manage-booking-form" action="{{ route('booking_search') }}" class="js-manage-form" method="post" novalidate>
                        @csrf
                        <input type="hidden" name="product_type" id="product_type" value="{{ $oldProduct }}">

                        <div class="js-manage-product-tabs" role="tablist" aria-label="Booking type">
                            <button type="button" class="js-manage-product-tab {{ $oldProduct === 'parking' ? 'is-active' : '' }}" data-product="parking" role="tab" aria-selected="{{ $oldProduct === 'parking' ? 'true' : 'false' }}">Parking</button>
                            <button type="button" class="js-manage-product-tab {{ $oldProduct === 'hotel' ? 'is-active' : '' }}" data-product="hotel" role="tab" aria-selected="{{ $oldProduct === 'hotel' ? 'true' : 'false' }}">Hotel</button>
                            <button type="button" class="js-manage-product-tab {{ $oldProduct === 'carhire' ? 'is-active' : '' }}" data-product="carhire" role="tab" aria-selected="{{ $oldProduct === 'carhire' ? 'true' : 'false' }}">Car Hire</button>
                            <button type="button" class="js-manage-product-tab {{ $oldProduct === 'lounge' ? 'is-active' : '' }}" data-product="lounge" role="tab" aria-selected="{{ $oldProduct === 'lounge' ? 'true' : 'false' }}">Lounge</button>
                        </div>

                        <div class="js-manage-form__group">
                            <label for="search_by">Search by <span class="required-field">*</span></label>
                            <select class="form-control @error('search_by') is-invalid @enderror" id="search_by" name="search_by" required aria-describedby="search_by_error">
                                <option value="booking_number" {{ $oldSearchBy === 'booking_number' ? 'selected' : '' }}>Booking number</option>
                                <option value="car_registration" {{ $oldSearchBy === 'car_registration' ? 'selected' : '' }}>Car registration</option>
                                <option value="mobile" {{ $oldSearchBy === 'mobile' ? 'selected' : '' }}>Mobile number</option>
                            </select>
                            <span class="js-manage-field-error" id="search_by_error" aria-live="polite">@error('search_by'){{ $message }}@enderror</span>
                        </div>

                        <div class="js-manage-form__group">
                            <label for="search_value" id="search_value_label">Booking number <span class="required-field">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('search_value') is-invalid @enderror"
                                id="search_value"
                                name="search_value"
                                placeholder="TTS-XXXXXX"
                                required
                                value="{{ $oldSearchValue }}"
                                autocomplete="off"
                                aria-describedby="search_value_error"
                                autofocus
                            >
                            <span class="js-manage-field-error" id="search_value_error" aria-live="polite">@error('search_value'){{ $message }}@enderror</span>
                        </div>

                        <div class="js-manage-form__group" id="last_name_group">
                            <label for="last_name">Last name <span class="required-field">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name"
                                name="last_name"
                                placeholder="Last name"
                                value="{{ old('last_name', $input['last_name'] ?? '') }}"
                                autocomplete="family-name"
                                aria-describedby="last_name_error"
                            >
                            <span class="js-manage-field-error" id="last_name_error" aria-live="polite">@error('last_name'){{ $message }}@enderror</span>
                        </div>

                        <div class="js-manage-form__group">
                            <label for="email">Email address <span class="required-field">*</span></label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                placeholder="you@example.com"
                                required
                                value="{{ old('email', $input['email'] ?? '') }}"
                                autocomplete="email"
                                aria-describedby="email_error"
                            >
                            <span class="js-manage-field-error" id="email_error" aria-live="polite">@error('email'){{ $message }}@enderror</span>
                        </div>

                        <div class="js-manage-form__actions">
                            <button type="submit" name="submit" class="js-btn js-btn--primary">
                                Find my booking
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </form>

                    <div class="js-manage-steps-block">
                        <h3 class="js-manage-steps-block__label">{{ crm('manage-booking.guide_title', 'Have your confirmation ready') }}</h3>
                        <ol class="js-manage-steps">
                            <li>
                                <span class="js-manage-steps__num">1</span>
                                <span>Choose your product and search type</span>
                            </li>
                            <li>
                                <span class="js-manage-steps__num">2</span>
                                <span>Enter the email used at checkout</span>
                            </li>
                            <li>
                                <span class="js-manage-steps__num">3</span>
                                <span>For parking bookings, provide the lead passenger’s surname when searching by booking number</span>
                            </li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="js-manage-banner">
        <div class="container">
            <div class="js-manage-banner__inner js-reveal">
                <div class="js-manage-banner__copy">
                    <h2 class="js-manage-banner__title">{{ crm('manage-booking.help_title', 'Still need help?') }}</h2>
                    <p class="js-manage-banner__text">{{ crm('manage-booking.help_text', 'If you can’t find your booking, our support team is available Mon–Fri, 9AM–5PM.') }}</p>
                </div>
                <a href="{{ route('support') }}" class="js-btn js-btn--accent">{{ crm('manage-booking.help_button', 'Contact support') }}</a>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('js-manage-booking-form');
    if (!form) return;

    var productInput = document.getElementById('product_type');
    var searchBy = document.getElementById('search_by');
    var searchValue = document.getElementById('search_value');
    var searchLabel = document.getElementById('search_value_label');
    var lastNameGroup = document.getElementById('last_name_group');
    var lastName = document.getElementById('last_name');
    var tabs = form.querySelectorAll('.js-manage-product-tab');
    var labels = {
        booking_number: { label: 'Booking number', placeholder: 'TTS-XXXXXX' },
        car_registration: { label: 'Car registration', placeholder: 'Enter car registration' },
        mobile: { label: 'Mobile number', placeholder: 'Enter mobile number' }
    };

    function syncSearchForm() {
        var product = productInput.value;
        var search = searchBy.value;
        var meta = labels[search] || labels.booking_number;
        var registrationOption = searchBy.querySelector('option[value="car_registration"]');

        if (registrationOption) registrationOption.disabled = product !== 'parking';
        if (product !== 'parking' && search === 'car_registration') {
            searchBy.value = 'booking_number';
            search = 'booking_number';
            meta = labels[search];
        }

        searchLabel.innerHTML = meta.label + ' <span class="required-field">*</span>';
        searchValue.placeholder = meta.placeholder;
        searchValue.setAttribute('aria-label', meta.label);
        lastNameGroup.hidden = product !== 'parking' || search !== 'booking_number';
        lastName.required = !lastNameGroup.hidden;
        lastName.setAttribute('aria-required', lastName.required ? 'true' : 'false');
    }

    function setProduct(product) {
        productInput.value = product;
        Array.prototype.forEach.call(tabs, function (tab) {
            var active = tab.getAttribute('data-product') === product;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        syncSearchForm();
    }

    function validateField(field) {
        var error = document.getElementById(field.id + '_error');
        var message = '';

        if (field.validity.valueMissing) {
            message = field.id === 'search_value'
                ? 'Please enter the ' + searchBy.value.replace('_', ' ') + '.'
                : field.id === 'last_name'
                    ? 'Please enter the last name on the booking.'
                    : 'Please enter the email address used for the booking.';
        } else if (field.type === 'email' && field.validity.typeMismatch) {
            message = 'Please enter a valid email address.';
        }

        field.classList.toggle('is-invalid', Boolean(message));
        if (error) error.textContent = message;
        return !message;
    }

    Array.prototype.forEach.call(form.querySelectorAll('input'), function (field) {
        field.addEventListener('blur', function () { validateField(field); });
        field.addEventListener('input', function () { validateField(field); });
    });
    searchBy.addEventListener('change', syncSearchForm);
    Array.prototype.forEach.call(tabs, function (tab) {
        tab.addEventListener('click', function () {
            setProduct(tab.getAttribute('data-product'));
        });
    });

    form.addEventListener('submit', function (event) {
        var valid = true;
        Array.prototype.forEach.call(form.querySelectorAll('[required]'), function (field) {
            if (!validateField(field)) valid = false;
        });
        if (!valid) {
            event.preventDefault();
            var firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) firstInvalid.focus();
        }
    });

    syncSearchForm();
});
</script>

@include('layouts.footer')
