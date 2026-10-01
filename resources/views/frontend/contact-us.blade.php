@php $meta = function_exists('crm_page_meta') ? crm_page_meta('contact-us') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: 'Contact Us - Total Travel Solutions')
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: 'contact, support, Total Travel Solutions')
@section('meta_description', ($meta->meta_description ?? null) ?: 'Get in touch with Total Travel Solutions. We are here to help with bookings, amendments, and general enquiries.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-contact.css?v=20261001contact') }}">

@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all();
    foreach ($settingsAll as $setting) {
        if (setting_agent_matches($setting)) {
            $site_settings_main[$setting->field_name] = $setting->field_value;
        }
    }
    $site_settings_main = normalize_site_settings($site_settings_main);
    $supportEmail = $site_settings_main['footer_email'] ?? 'support@totaltravelsolutions.co.uk';
    $supportPhone = $site_settings_main['footer_phone_no'] ?? '';
@endphp

@include('partials.page-hero', [
    'eyebrow' => crm('contact-us.hero_eyebrow', 'Get in touch'),
    'title' => crm('contact-us.hero_title', 'We are here to help'),
    'subtitle' => crm('contact-us.hero_subtitle', 'Send us a message and our team will get back to you'),
    'lead' => crm('contact-us.hero_lead', 'Whether you need help with a booking, an amendment, or a general enquiry — leave a message below.'),
    'heroClass' => 'js-page-hero--enhanced',
])

<main class="js-contact-page">
    <section class="js-contact-body">
        <div class="js-container">
            <div class="js-contact-layout">
                <article class="js-contact-card">
                    <header class="js-contact-card__head">
                        <h2>{{ crm('contact-us.form_title', 'Leave a message') }}</h2>
                        <p>{{ crm('contact-us.form_lead', 'Fields marked * are required. We aim to reply as quickly as possible.') }}</p>
                    </header>

                    @if(session()->has('success_message'))
                        <div class="js-contact-alert js-contact-alert--success" role="status">
                            {{ session()->get('success_message') }}
                        </div>
                    @endif

                    <form id="js_contact-form" action="{{ route('contact-us-submit') }}" class="js-contact-form contact-form" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="js-contact-form__row">
                            <div class="js-contact-form__group">
                                <label for="contact-firstname">First Name<span class="required-field">*</span></label>
                                <input type="text" id="contact-firstname" class="form-control" name="firstname" placeholder="First name" required autofocus>
                            </div>
                            <div class="js-contact-form__group">
                                <label for="contact-lastname">Last Name<span class="required-field">*</span></label>
                                <input type="text" id="contact-lastname" class="form-control" name="lastname" placeholder="Last name" required>
                            </div>
                        </div>

                        <div class="js-contact-form__row">
                            <div class="js-contact-form__group">
                                <label for="contact-email">Email<span class="required-field">*</span></label>
                                <input type="email" id="contact-email" class="form-control" name="email" placeholder="Email address" required>
                            </div>
                            <div class="js-contact-form__group">
                                <label for="contact-phone">Phone no.<span class="required-field">*</span></label>
                                <input type="text" id="contact-phone" class="form-control" name="phone" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="15" placeholder="Mobile number" required>
                            </div>
                        </div>

                        <div class="js-contact-form__row js-contact-form__row--full">
                            <div class="js-contact-form__group">
                                <label for="contact-subject">Message subject<span class="required-field">*</span></label>
                                <select id="contact-subject" class="form-control" name="subject" required>
                                    <option value="">Please select your message main subject</option>
                                    <option value="Amend or cancel a booking">Amend or cancel a booking</option>
                                    <option value="Re-send booking confirmation">Re-send booking confirmation</option>
                                    <option value="New booking enquiry">New booking enquiry</option>
                                    <option value="General enquiry">General enquiry</option>
                                    <option value="Change email or postal address details">Change email or postal address details</option>
                                    <option value="Marketing/PR enquiry">Marketing/PR enquiry</option>
                                    <option value="Make a complaint">Make a complaint</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="js-contact-form__row js-contact-form__row--full">
                            <div class="js-contact-form__group">
                                <label for="comment">Message<span class="required-field">*</span></label>
                                <textarea class="form-control" required rows="6" id="comment" name="message" placeholder="Type your message here..."></textarea>
                            </div>
                        </div>

                        <div class="js-contact-form__actions">
                            <button type="submit" class="js-btn js-btn--primary">
                                {{ crm('contact-us.submit', 'Submit message') }}
                            </button>
                        </div>
                    </form>
                </article>

                <aside class="js-contact-aside">
                    <div class="js-contact-info">
                        <h3>{{ crm('contact-us.info_title', 'Contact details') }}</h3>
                        <p>{{ crm('contact-us.info_text', 'Prefer not to use the form? Reach us directly using the details below.') }}</p>
                        <ul class="js-contact-info__list">
                            <li>
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></span>
                            </li>
                            @if($supportPhone)
                                <li>
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                    <span>Helpline: <a href="tel:{{ $supportPhone }}">{{ $supportPhone }}</a></span>
                                </li>
                            @endif
                            <li>
                                <i class="fa fa-life-ring" aria-hidden="true"></i>
                                <span>Or open a ticket via <a href="{{ route('support') }}">Customer Support</a></span>
                            </li>
                        </ul>
                    </div>

                    <div class="js-contact-tip">
                        <h3>{{ crm('contact-us.tip_title', 'Already booked?') }}</h3>
                        <p>{{ crm('contact-us.tip_text', 'Use Manage Booking to find your reservation quickly, or visit Customer Support with your booking reference.') }}
                            <a href="{{ route('manage_booking') }}">Manage booking</a>
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

@include('layouts.footer')
