@php $meta = function_exists('crm_page_meta') ? crm_page_meta('support') : null; @endphp
@section('title', ($meta->meta_title ?? null) ?: ($page->meta_title ?? 'Customer Support | Total Travel Solutions'))
@section('meta_keyword', ($meta->meta_keyword ?? null) ?: ($page->meta_keyword ?? 'customer support, tickets, help'))
@section('meta_description', ($meta->meta_description ?? null) ?: ($page->meta_description ?? 'Create a support ticket or search an existing one with Total Travel Solutions.'))
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-home.css?v=20261001support') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-support.css?v=20261001support5') }}">

@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all()->where('agent_id', '9');
    foreach ($settingsAll as $setting) {
        $site_settings_main[$setting->field_name] = $setting->field_value;
    }
    $site_settings_main = normalize_site_settings($site_settings_main);

    $supportEmail = $site_settings_main['footer_email'] ?? 'support@totaltravelsolutions.co.uk';
    $supportPhone = $site_settings_main['footer_phone_no'] ?? '';
@endphp

@include('layouts.search_form', [
    'heroEyebrow' => crm('support.hero_eyebrow', 'Need help?'),
    'heroTitle' => crm('support.hero_title', 'Customer Support'),
    'heroSubtitle' => crm('support.hero_lead', 'Create a support ticket or search an existing one. Our team is here to help.'),
    'hideBookingWidget' => true,
])

<main class="js-support-page">

    {{-- Contact strip --}}
    <section class="js-support-contact-strip" aria-label="Contact details">
        <div class="container">
            <div class="js-support-contact js-reveal">
                <a class="js-support-contact__item" href="mailto:{{ $supportEmail }}">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <span>{{ $supportEmail }}</span>
                </a>
                @if($supportPhone)
                    <span class="js-support-contact__divider" aria-hidden="true"></span>
                    <a class="js-support-contact__item" href="tel:{{ $supportPhone }}">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <span>{{ $supportPhone }}</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="js-support-body">
        <div class="container">

            <div class="js-support-notices js-reveal">
                <div class="js-support-notice js-support-notice--info">
                    <strong>Important:</strong> Total Travel Solutions is a booking agent for the advertised car park. We do not collect, store or drive customers’ vehicles and we do not own a car park.
                </div>
                <div class="js-support-notice js-support-notice--warn">
                    If you are struggling with this procedure and don't want to create a support ticket, please send your query to <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a> and we will get back to you promptly.
                </div>
            </div>

            <div class="js-support-intro js-reveal">
                <ul>
                    <li>In order to streamline support requests and better serve you, we utilize a support ticket system.</li>
                    <li>Every support request is assigned a unique ticket number which you can use to track the progress and responses online.</li>
                    <li>For your reference we provide complete archives and history of all your support requests.</li>
                    <li>A valid <strong>Email Address &amp; Booking Reference</strong> is required to submit a support ticket.</li>
                </ul>
            </div>

            <div class="js-support-grid">
                @php
                    $ticketHasError = fn ($field) => $errors->ticket_store->has($field);
                    $searchHasError = fn ($field) => $errors->search_ticket->has($field);
                @endphp

                {{-- Create Support Ticket --}}
                <article class="js-support-card js-reveal">
                    <header class="js-support-card__head">
                        <h2>Create Support Ticket</h2>
                        <p>Fill in your booking details and message. Fields marked * are required.</p>
                    </header>

                    <form id="js_contact-form" action="{{ route('submit-ticket') }}" class="js-support-form contact-form" method="post" enctype="multipart/form-data" novalidate>
                        @csrf

                        @if ($errors->ticket_store->isNotEmpty())
                            <div class="js-support-form__alert js-support-form__alert--error" role="alert">
                                <strong>Unable to submit ticket.</strong>
                                <span>Please review the highlighted fields below and try again.</span>
                            </div>
                        @endif

                        <div class="js-support-form__row">
                            <div class="form-group">
                                <label for="ref_no">Booking Reference No.<span class="required-field">*</span></label>
                                <input type="text" class="form-control{{ $ticketHasError('ref_no') ? ' is-invalid' : '' }}" id="ref_no" name="ref_no" placeholder="TTS-XXXXXX" required value="{{ Request::old('ref_no') }}" @if($ticketHasError('ref_no')) aria-invalid="true" @endif>
                                @error('ref_no', 'ticket_store')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="full_name">Last Name <span class="required-field">*</span></label>
                                <input type="text" class="form-control{{ $ticketHasError('full_name') ? ' is-invalid' : '' }}" id="full_name" name="full_name" placeholder="Last Name" required value="{{ Request::old('full_name') }}" @if($ticketHasError('full_name')) aria-invalid="true" @endif>
                                @error('full_name', 'ticket_store')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="required-field">*</span></label>
                                <input type="email" class="form-control{{ $ticketHasError('email') ? ' is-invalid' : '' }}" id="email" name="email" placeholder="Email" required value="{{ Request::old('email') }}" @if($ticketHasError('email')) aria-invalid="true" @endif>
                                @error('email', 'ticket_store')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="js-support-form__row">
                            <div class="form-group">
                                <label for="contact">Contact No.<span class="required-field">*</span></label>
                                <input type="text" class="form-control{{ $ticketHasError('contact') ? ' is-invalid' : '' }}" id="contact" name="contact" value="{{ Request::old('contact') }}" placeholder="XXXXXXXXX" required @if($ticketHasError('contact')) aria-invalid="true" @endif>
                                @error('contact', 'ticket_store')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="department">Support Department <span class="required-field">*</span></label>
                                <select name="department" id="department" class="form-control" required>
                                    @foreach(($departements_list ?? ['' => 'Select Department']) as $value => $label)
                                        <option value="{{ $value }}" @selected((string) old('department') === (string) $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('department', 'ticket_store')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="priority">Ticket Priority <span class="required-field">*</span></label>
                                {{ Form::select('priority', ['' => 'Select priority', 'Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'], Request::old('priority'), ['class' => 'form-control' . ($ticketHasError('priority') ? ' is-invalid' : ''), 'id' => 'priority', 'required' => 'required', 'aria-invalid' => $ticketHasError('priority') ? 'true' : 'false']) }}
                                @error('priority', 'ticket_store')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject">Ticket Subject <span class="required-field">*</span></label>
                            <input type="text" class="form-control{{ $ticketHasError('subject') ? ' is-invalid' : '' }}" id="subject" name="subject" placeholder="Subject" value="{{ Request::old('subject') }}" required @if($ticketHasError('subject')) aria-invalid="true" @endif>
                            @error('subject', 'ticket_store')
                                <span class="js-support-field-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="message">Ticket Message <span class="required-field">*</span></label>
                            <textarea class="form-control textarea-contact ckeditor{{ $ticketHasError('message') ? ' is-invalid' : '' }}" required rows="10" id="message" name="message" placeholder="Type your message or feedback here..." @if($ticketHasError('message')) aria-invalid="true" @endif>{{ Request::old('message') }}</textarea>
                            @error('message', 'ticket_store')
                                <span class="js-support-field-error" role="alert">{{ $message }}</span>
                            @enderror
                            <input type="hidden" name="ticket_submit" value="yes">
                        </div>

                        <div class="form-group">
                            <label for="attatchment">Attachment (optional)</label>
                            <input type="file" class="form-control{{ $ticketHasError('attatchment') ? ' is-invalid' : '' }}" id="attatchment" name="attatchment" @if($ticketHasError('attatchment')) aria-invalid="true" @endif>
                            @error('attatchment', 'ticket_store')
                                <span class="js-support-field-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <label class="js-support-form__checkbox{{ $ticketHasError('supportdeskpolicy') ? ' is-invalid' : '' }}">
                            <input type="checkbox" name="supportdeskpolicy" id="supportdeskpolicy" value="1" required @if(Request::old('supportdeskpolicy')) checked @endif @if($ticketHasError('supportdeskpolicy')) aria-invalid="true" @endif>
                            <span>I agree to the Total Travel Solutions <a href="{{ route('static_page', ['page' => 'privacy-policy']) }}">Support Policy</a> &amp; <a href="{{ route('static_page', ['page' => 'terms-and-conditions']) }}">Terms of Service</a></span>
                        </label>
                        @error('supportdeskpolicy', 'ticket_store')
                            <span class="js-support-field-error" role="alert">{{ $message }}</span>
                        @enderror

                        <div class="js-support-form__actions">
                            <button type="submit" name="submit" class="js-support-btn">Create New Support Ticket</button>
                        </div>
                    </form>
                </article>

                <div class="js-support-sidebar">
                    {{-- Search Ticket --}}
                    <article class="js-support-card js-support-search js-reveal">
                        <header class="js-support-card__head">
                            <h2>Search Ticket</h2>
                            <p>Look up an existing support ticket.</p>
                        </header>

                        <form action="{{ route('search_ticket') }}" method="post" class="js-support-form support-form" novalidate>
                            @csrf

                            @if ($errors->search_ticket->isNotEmpty())
                                <div class="js-support-form__alert js-support-form__alert--error" role="alert">
                                    <strong>Unable to find ticket.</strong>
                                    <span>Please check the details below and try again.</span>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="tickrt_email">Email Address <span class="required-field">*</span></label>
                                <input type="email" value="{{ Request::old('email') }}" name="email" id="tickrt_email" placeholder="Email" class="form-control{{ $searchHasError('email') ? ' is-invalid' : '' }}" required @if($searchHasError('email')) aria-invalid="true" @endif>
                                @error('email', 'search_ticket')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="ticket_reference">Ticket Reference <span class="required-field">*</span></label>
                                <input type="text" name="ref_no" value="{{ Request::old('ref_no') }}" id="ticket_reference" placeholder="Ticket Ref" class="form-control{{ $searchHasError('ref_no') ? ' is-invalid' : '' }}" required @if($searchHasError('ref_no')) aria-invalid="true" @endif>
                                @error('ref_no', 'search_ticket')
                                    <span class="js-support-field-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" id="search_ticket" class="js-support-btn js-support-btn--block">
                                <i class="fa fa-ticket" aria-hidden="true"></i> Search Support Ticket
                            </button>
                        </form>
                    </article>

                    {{-- Quick help --}}
                    <aside class="js-support-info js-reveal" style="--reveal-delay: 80ms">
                        <h3>{{ crm('support.info_title', 'Prefer email or phone?') }}</h3>
                        <p>{{ crm('support.info_text', 'You can also reach us directly — we aim to reply as quickly as possible.') }}</p>
                        <ul class="js-support-info__list">
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
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <span>Or browse our <a href="{{ route('faqs') }}">FAQs</a></span>
                            </li>
                        </ul>
                    </aside>

                    {{-- Knowledge Base --}}
                    <section class="js-support-kb js-reveal" style="--reveal-delay: 120ms">
                        <header class="js-support-kb__head">
                            <h2>Knowledge Base</h2>
                            <p>Quick answers to common questions.</p>
                        </header>

                        <div class="js-support-kb__list">
                            @include('partials.faq-item', [
                                'id' => 'kb-1',
                                'question' => 'Are you having issues with creating a ticket?',
                                'answer' => 'If you are struggling with this procedure and don\'t want to create a support ticket. Please send us your query at "support@totaltravelsolutions.co.uk", we will get back to you promptly.',
                            ])
                            @include('partials.faq-item', [
                                'id' => 'kb-2',
                                'question' => 'Are online payment procedures secure?',
                                'answer' => 'Absolutely! Our online payment procedures are safeguarded with authorized protocols, ensuring your transactions are conducted with utmost privacy. Rest assured, your credit card details and payment information enjoy complete protection throughout your service usage. In addition, once the booking process is finalized, we promptly delete all your data from our servers for added security.',
                            ])
                            @include('partials.faq-item', [
                                'id' => 'kb-3',
                                'question' => 'Why should I pre-book airport parking?',
                                'answer' => 'By pre-booking, you unlock savings of up to 60% compared to gate prices, ensuring significant cost benefits. Beyond the financial advantage, securing a reservation provides peace of mind, guaranteeing a designated space for you and ensuring a seamless start to your holiday.',
                            ])
                            @include('partials.faq-item', [
                                'id' => 'kb-4',
                                'question' => 'What is the gate price?',
                                'answer' => 'The gate price refers to the cost incurred when paying on the day of departure or making an on-site payment. Notably, these prices can be up to 60% higher than the pre-booked rates, emphasizing the financial benefit of securing your reservation in advance.',
                            ])
                            @include('partials.faq-item', [
                                'id' => 'kb-5',
                                'question' => 'How will I get booking confirmation?',
                                'answer' => 'Upon completing your booking, a complimentary copy of your confirmation will be sent to you via email. If you prefer receiving it through sms, you have the option to do so for a small charge. Additionally, you can download a copy of your booking confirmation anytime from the website using the "my booking" option.',
                            ])
                            @include('partials.faq-item', [
                                'id' => 'kb-6',
                                'question' => 'Why is there a booking fee?',
                                'answer' => 'Our commitment is to deliver the finest and most convenient parking booking service to our customers. Achieving this involves significant resources, including staffing and investments. The booking fee plays a crucial role in allowing us to guarantee that when you book through us, you receive the best possible customer experience. It supports the continued improvement and maintenance of the high standards we aim to provide.',
                            ])
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="js-support-banner">
        <div class="container">
            <div class="js-support-banner__inner js-reveal">
                <div class="js-support-banner__copy">
                    <h2 class="js-support-banner__title">{{ crm('support.cta_title', 'Need parking instead?') }}</h2>
                    <p class="js-support-banner__text">{{ crm('support.cta_text', 'Compare trusted airport parking across major UK airports and reserve your space in minutes.') }}</p>
                </div>
                <a href="{{ url('/') }}" class="js-btn js-btn--accent">{{ crm('support.cta_button', 'Find Parking') }}</a>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-support-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var valid = true;
            var firstInvalid = null;
            form.querySelectorAll('.js-support-field-error--client').forEach(function (el) { el.remove(); });
            form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });

            form.querySelectorAll('[required]').forEach(function (field) {
                var message = '';
                if (field.type === 'checkbox' && !field.checked) {
                    message = 'This field is required.';
                } else if (!field.value || !String(field.value).trim()) {
                    message = 'This field is required.';
                } else if (field.type === 'email' && field.validity && field.validity.typeMismatch) {
                    message = 'Enter a valid email address.';
                }
                if (message) {
                    valid = false;
                    field.classList.add('is-invalid');
                    if (!firstInvalid) firstInvalid = field;
                    var span = document.createElement('span');
                    span.className = 'text-danger js-support-field-error js-support-field-error--client';
                    span.setAttribute('role', 'alert');
                    span.textContent = message;
                    var group = field.closest('.form-group') || field.closest('.pz-support-field') || field.parentElement;
                    if (group) group.appendChild(span);
                }
            });

            if (!valid) {
                event.preventDefault();
                if (firstInvalid) firstInvalid.focus();
            }
        });
    });
});
</script>

@include('layouts.footer')
