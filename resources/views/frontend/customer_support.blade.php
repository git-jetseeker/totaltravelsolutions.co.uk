@section('title', $page->meta_title)
@section('meta_keyword', $page->meta_keyword)
@section('meta_description', $page->meta_description)
@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-support.css?v=20251002') }}">

@php
    $site_settings_main = [];
    $settingsAll = App\Models\settings::all()->where('agent_id', '9');
    foreach ($settingsAll as $setting) {
        $site_settings_main[$setting->field_name] = $setting->field_value;
    }
    $supportEmail = $site_settings_main['footer_email'] ?? 'support@totaltravelsolutions.co.uk';
    $supportPhone = $site_settings_main['footer_phone_no'] ?? '';
@endphp

<section class="js-page-hero js-page-hero--enhanced js-page-hero--support">
    <div class="js-container">
        <span class="js-page-hero__eyebrow">Need help?</span>
        <h1 class="js-page-hero__title">Customer Support</h1>
        <p class="js-page-hero__subtitle">Create a support ticket or search an existing one. Our team is here to help.</p>
        <p class="js-page-hero__lead">If you prefer not to create a ticket, email <a href="mailto:{{ $supportEmail }}" style="color:#F9A8D4;text-decoration:underline;">{{ $supportEmail }}</a>@if($supportPhone) or call <a href="tel:{{ $supportPhone }}" style="color:#F9A8D4;text-decoration:underline;">{{ $supportPhone }}</a>@endif.</p>
    </div>
</section>

<main class="js-support-page">
    <section class="js-support-body">
        <div class="js-container">

            <div class="js-support-contact">
                <span>Email: <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a></span>
                @if($supportPhone)
                    <span class="js-support-contact__divider">|</span>
                    <span>Helpline: <a href="tel:{{ $supportPhone }}">{{ $supportPhone }}</a></span>
                @endif
            </div>

            <div class="js-support-notices">
                <div class="js-support-notice js-support-notice--info">
                    <strong>Important:</strong> JET SEEKER is a booking agent for the advertised car park. We do not collect, store or drive customers vehicle and we do not own a car park.
                </div>
                <div class="js-support-notice js-support-notice--warn">
                    If you are struggling with this procedure and don't want to create a support ticket, please send your query to <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a> and we will get back to you promptly.
                </div>
            </div>

            <div class="js-support-intro">
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
                <article class="js-support-card">
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
                                <input type="text" class="form-control{{ $ticketHasError('ref_no') ? ' is-invalid' : '' }}" id="ref_no" name="ref_no" placeholder="JSXXXXXXXXX" required value="{{ Request::old('ref_no') }}" autofocus @if($ticketHasError('ref_no')) aria-invalid="true" @endif>
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
                                {{ Form::select('department', $departements_list, Request::old('department'), ['class' => 'form-control' . ($ticketHasError('department') ? ' is-invalid' : ''), 'id' => 'department', 'required' => 'required', 'aria-invalid' => $ticketHasError('department') ? 'true' : 'false']) }}
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
                <article class="js-support-card js-support-search">
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

                {{-- Knowledge Base --}}
                <section class="js-support-kb">
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
</main>

@include('layouts.footer')
