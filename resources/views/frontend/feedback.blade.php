@section('title', 'Feedback | Total Travel Solutions')
@section('meta_keyword', 'feedback, review, Total Travel Solutions')
@section('meta_description', 'Share your feedback with Total Travel Solutions and help us improve your airport parking experience.')

@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-contact.css?v=20261001contact') }}">

@include('partials.page-hero', [
    'eyebrow' => 'Your opinion matters',
    'title' => 'Send Us Feedback',
    'subtitle' => 'Tell us about your experience with Total Travel Solutions',
    'lead' => 'We read every message and use your feedback to improve our service.',
    'heroClass' => 'js-page-hero--enhanced',
])

<main class="js-contact-page">
    <section class="js-contact-body">
        <div class="js-container">
            <div class="js-contact-layout">
                <article class="js-contact-card">
                    <header class="js-contact-card__head">
                        <h2>Feedback form</h2>
                        <p>Rate your experience and leave a comment. Fields marked * are required.</p>
                    </header>

                    <div id="result" style="display:none;">
                        <div class="js-contact-alert js-contact-alert--success" role="status">
                            Thank you for your feedback.
                        </div>
                    </div>

                    <form id="js_contact-form" action="{{ route('submit-feedback') }}" class="js-contact-form contact-form" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="js-contact-form__row js-contact-form__row--full">
                            <div class="js-contact-form__group">
                                <label for="name">Name<span class="required-field">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                            </div>
                        </div>

                        <div class="js-contact-form__row js-contact-form__row--full">
                            <div class="js-contact-form__group">
                                <label for="rating">Rate this<span class="required-field">*</span></label>
                                <select id="rating" name="rating" class="form-control" required>
                                    <option value="5">5 — Very Good</option>
                                    <option value="4">4 — Good</option>
                                    <option value="3">3 — Ok</option>
                                    <option value="2">2 — Poor</option>
                                    <option value="1">1 — Very Poor</option>
                                </select>
                            </div>
                        </div>

                        <div class="js-contact-form__row js-contact-form__row--full">
                            <div class="js-contact-form__group">
                                <label for="message">Comment<span class="required-field">*</span></label>
                                <textarea name="message" id="message" class="form-control" rows="8" required placeholder="Share your feedback..."></textarea>
                            </div>
                        </div>

                        <input type="hidden" id="type" name="type" value="">
                        <input type="hidden" id="company" name="company" value="">
                        <input type="hidden" id="reff" name="reff" value="">
                        <input type="hidden" id="email" name="email" value="">

                        <div class="js-contact-form__actions">
                            <button type="submit" name="submit" class="js-btn js-btn--primary" id="btnfeed">
                                Send Feedback
                            </button>
                        </div>
                    </form>
                </article>

                <aside class="js-contact-aside">
                    <div class="js-contact-info">
                        <h3>Our services</h3>
                        <p>Reliable, efficient, and customer-oriented services across major UK airports.</p>
                        <ul class="js-contact-info__list">
                            <li><i class="fa fa-car" aria-hidden="true"></i><span>Airport Parking</span></li>
                            <li><i class="fa fa-coffee" aria-hidden="true"></i><span>Airport Lounges</span></li>
                            <li><i class="fa fa-bed" aria-hidden="true"></i><span>Airport Hotels</span></li>
                            <li><i class="fa fa-taxi" aria-hidden="true"></i><span>Airport Transfers</span></li>
                        </ul>
                    </div>
                    <div class="js-contact-tip">
                        <h3>Need help with a booking?</h3>
                        <p>Visit <a href="{{ route('support') }}">Customer Support</a> or <a href="{{ route('manage_booking') }}">Manage Booking</a> for faster assistance.</p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

@include('layouts.footer')
