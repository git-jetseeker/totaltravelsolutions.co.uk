@php $meta = function_exists('crm_page_meta') ? crm_page_meta('manage-booking') : null; @endphp
@section('title', $page->meta_title ?? 'Manage Booking - Total Travel Solutions')
@section('meta_keyword', $page->meta_keyword ?? 'manage booking, booking reference, total travel solutions')
@section('meta_description', $page->meta_description ?? 'Find and manage your Total Travel Solutions booking using your reference number, last name and email.')
@include('layouts.header')
@include('layouts.nav')
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-manage-booking.css?v=20260925tts') }}">

<main class="pz-manage tts-manage">
    <section class="pz-manage-hero" aria-labelledby="manage-title">
        <div class="container">
            <span class="pz-page-kicker"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Your trip, in one place</span>
            <h1 id="manage-title">{{ crm('manage-booking.hero_title', 'Manage your airport parking booking') }}</h1>
            <p>{{ crm('manage-booking.hero_lead', 'Retrieve your booking securely to review the details and get the information you need for your journey.') }}</p>
            <div class="pz-page-trust">
                <span><i class="fa fa-shield" aria-hidden="true"></i> Secure lookup</span>
                <span><i class="fa fa-clock-o" aria-hidden="true"></i> Takes under a minute</span>
                <span><i class="fa fa-headphones" aria-hidden="true"></i> Support when needed</span>
            </div>
        </div>
    </section>

    <section class="pz-manage-form-section" aria-labelledby="booking-summary-title">
        <div class="container">
            <div class="pz-manage-layout">
                <aside class="pz-manage-guide">
                    <h2>Have your confirmation ready</h2>
                    <p>Enter the details exactly as they appear on your booking confirmation email.</p>
                    <ul>
                        <li><span class="pz-step">1</span> Find your booking reference (TTS-…)</li>
                        <li><span class="pz-step">2</span> Enter the lead passenger’s surname</li>
                        <li><span class="pz-step">3</span> Use the email used at checkout</li>
                    </ul>
                    <a href="{{ route('support') }}" class="pz-manage-guide__link">Need help? Customer Support</a>
                </aside>

                <div class="pz-manage-card">
                    <span class="pz-page-kicker">Booking lookup</span>
                    <h2 id="booking-summary-title">Booking summary</h2>

                    @if (!$errors->isEmpty())
                        <div class="alert alert-danger pz-form-summary" role="alert">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                            <div>
                                <strong>We couldn’t retrieve your booking.</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form id="js-manage-booking-form" action="{{ route('booking_search') }}" method="post" novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="ref_no">Booking reference number <span class="required-field">*</span></label>
                            <div class="pz-input-wrap">
                                <i class="fa fa-ticket" aria-hidden="true"></i>
                                <input type="text" class="form-control @error('ref_no') is-invalid @enderror" id="ref_no" name="ref_no" placeholder="TTS-XXXXXX" required value="{{ old('ref_no') }}" autocomplete="off" aria-describedby="ref_no_error" autofocus>
                            </div>
                            <span class="pz-field-error" id="ref_no_error" aria-live="polite">@error('ref_no'){{ $message }}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last name <span class="required-field">*</span></label>
                            <div class="pz-input-wrap">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" placeholder="Last name" required value="{{ old('last_name') }}" autocomplete="family-name" aria-describedby="last_name_error">
                            </div>
                            <span class="pz-field-error" id="last_name_error" aria-live="polite">@error('last_name'){{ $message }}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email address <span class="required-field">*</span></label>
                            <div class="pz-input-wrap">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="you@example.com" required value="{{ old('email') }}" autocomplete="email" aria-describedby="email_error">
                            </div>
                            <span class="pz-field-error" id="email_error" aria-live="polite">@error('email'){{ $message }}@enderror</span>
                        </div>

                        <button type="submit" name="submit" class="btn pz-primary-btn">
                            Find my booking <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
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
