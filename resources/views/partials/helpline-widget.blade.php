@if (!empty($phone))
    <a href="tel:{{ $phone }}"
        class="js-helpline-widget{{ !empty($modifier) ? ' js-helpline-widget--' . $modifier : '' }}"
        aria-label="Call helpline {{ $phone }}, Mon–Fri 9AM–5PM">
        <span class="js-helpline-widget__icon" aria-hidden="true">
            <i class="fa fa-phone"></i>
        </span>
        <span class="js-helpline-widget__text">
            <span class="js-helpline-widget__label">Helpline</span>
            <span class="js-helpline-widget__hours">Mon–Fri · 9AM–5PM</span>
            <span class="js-helpline-widget__number">{{ $phone }}</span>
        </span>
    </a>
@endif
