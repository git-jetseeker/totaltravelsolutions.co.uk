@php $meta = function_exists('crm_page_meta') ? crm_page_meta('manage-booking') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: ($page->meta_title ?? 'Manage Booking - Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: ($page->meta_keyword ?? 'manage booking, booking reference, total travel solutions'))
@section('meta_description', ($meta->meta_description ?? null) ?: ($page->meta_description ?? 'Find and manage your Total Travel Solutions booking using your reference number, last name and email.'))
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20261001mobileform') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-manage-booking.css?v=20261001manage2') }}">

@include('layouts.search_form', [
    'heroEyebrow' => crm('manage-booking.hero_eyebrow', 'Your trip, in one place'),
    'heroTitle' => crm('manage-booking.hero_title', 'Manage Booking'),
    'heroSubtitle' => crm('manage-booking.hero_subtitle', 'Retrieve your airport parking reservation securely'),
    'hideBookingWidget' => true,
])

<main class="js-manage-page">

    <section class="js-manage-body section-spacing">
        <div class="container">
            <div class="js-manage-main">

                <header class="js-section-head js-reveal">
                    <span class="js-section-head__badge">{{ crm('manage-booking.intro_badge', 'Booking lookup') }}</span>
                    <h2 class="js-section-title" id="booking-summary-title">{{ crm('manage-booking.form_title', 'Find Your Booking') }}</h2>
                    <p class="js-section-subtitle">{{ crm('manage-booking.form_lead', 'Enter the details exactly as they appear on your booking confirmation email.') }}</p>
                </header>

                <div class="js-manage-panel js-reveal">
                    <p class="js-manage-hint">{{ crm('manage-booking.hint', 'You’ll need your booking reference, surname, and checkout email') }}</p>

                    @php $input = $input ?? []; @endphp
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

                        <div class="js-manage-form__group">
                            <label for="ref_no">Booking reference number <span class="required-field">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('ref_no') is-invalid @enderror"
                                id="ref_no"
                                name="ref_no"
                                placeholder="TTS-XXXXXX"
                                required
                                value="{{ old('ref_no', $input['ref_no'] ?? '') }}"
                                autocomplete="off"
                                aria-describedby="ref_no_error"
                                autofocus
                            >
                            <span class="js-manage-field-error" id="ref_no_error" aria-live="polite">@error('ref_no'){{ $message }}@enderror</span>
                        </div>

                        <div class="js-manage-form__group">
                            <label for="last_name">Last name <span class="required-field">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name"
                                name="last_name"
                                placeholder="Last name"
                                required
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
                                <span>Find your booking reference (TTS-…)</span>
                            </li>
                            <li>
                                <span class="js-manage-steps__num">2</span>
                                <span>Enter the lead passenger’s surname</span>
                            </li>
                            <li>
                                <span class="js-manage-steps__num">3</span>
                                <span>Use the email used at checkout</span>
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

    var messages = {
        ref_no: 'Please enter your booking reference number.',
        last_name: 'Please enter the last name on the booking.',
        email: 'Please enter the email address used for the booking.'
    };

    function validateField(field) {
        var error = document.getElementById(field.id + '_error');
        var message = '';

        if (field.validity.valueMissing) {
            message = messages[field.id];
        } else if (field.type === 'email' && field.validity.typeMismatch) {
            message = 'Please enter a valid email address.';
        }

        field.classList.toggle('is-invalid', Boolean(message));
        if (error) error.textContent = message;
        return !message;
    }

    Array.prototype.forEach.call(form.querySelectorAll('[required]'), function (field) {
        field.addEventListener('blur', function () { validateField(field); });
        field.addEventListener('input', function () { validateField(field); });
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
});
</script>

@include('layouts.footer')
