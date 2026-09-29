@include('layouts.header')
@include('layouts.nav')

<link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-manage-booking.css?v=20260929detail') }}">
<style>
    .js-manage-detail {
        --js-primary: #111111;
        --js-primary-dark: #000000;
        --js-accent: #C2185B;
        --js-accent-dark: #9c1249;
    }
    .js-manage-detail__amount {
        background: linear-gradient(135deg, #111 0%, #2a2a2a 100%);
    }
    .js-manage-detail__section-title {
        border-bottom-color: #C2185B;
    }
    .js-manage-detail__note {
        background: rgba(194, 24, 91, 0.08);
        border-left-color: #C2185B;
    }
</style>

@php
    $settings = function_exists('site_settings') ? site_settings() : [];
    $supportEmail = $settings['footer_email'] ?? 'bookings@totaltravelsolutions.co.uk';
    $supportPhone = $settings['footer_phone_no'] ?? '020 4511 4171';
    $brandName = 'Total Travel Solutions';
    $logoSrc = asset('theme/images/logo-black.png') . '?v=20260915';
    $pdfName = 'Total_Travel_Solutions_Booking_' . ($booking->referenceNo ?? 'booking') . '.pdf';
    $companyLine = 'Total Travel Solutions — Registered in England · No. 11502152';
@endphp

@include('partials.page-hero', [
    'title' => 'Booking Confirmation',
    'subtitle' => 'Reference ' . ($booking->referenceNo ?? ''),
    'lead' => 'Your parking booking details are below. Download a PDF copy for your trip.',
    'heroClass' => 'js-page-hero--enhanced js-page-hero--compact',
    'eyebrow' => 'Manage booking',
])

<div class="js-manage-booking-page js-manage-detail">
    <div class="js-container">
        <div class="js-manage-detail__layout">
            <article class="js-manage-detail__card" id="print">
                <header class="js-manage-detail__header">
                    <div class="js-manage-detail__brand">
                        <img src="{{ $logoSrc }}" alt="{{ $brandName }}" class="js-manage-detail__logo" width="140" height="40">
                        <p class="js-manage-detail__doc-label">Booking confirmation</p>
                    </div>
                    <div class="js-manage-detail__ref-block">
                        <span class="js-manage-detail__ref-label">Reference</span>
                        <strong class="js-manage-detail__ref">{{ $booking->referenceNo }}</strong>
                        <span class="js-manage-detail__date">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </header>

                <div class="js-manage-detail__amount">
                    <span>Total paid</span>
                    <strong>{{ is_numeric($booking->total_amount) ? '£' . number_format((float) $booking->total_amount, 2) : $booking->total_amount }}</strong>
                </div>

                <section class="js-manage-detail__section">
                    <h2 class="js-manage-detail__section-title">Customer</h2>
                    <dl class="js-manage-detail__grid">
                        <div>
                            <dt>Name</dt>
                            <dd>{{ $booking->first_name }} {{ $booking->last_name }}</dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $booking->email }}</dd>
                        </div>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $booking->phone_number }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="js-manage-detail__section">
                    <h2 class="js-manage-detail__section-title">Parking details</h2>
                    <dl class="js-manage-detail__grid">
                        @if ($airport_detail)
                            <div>
                                <dt>Airport</dt>
                                <dd>{{ $airport_detail->name }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt>Provider</dt>
                            <dd>{{ $booking->name ?? $booking->company_name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Parking type</dt>
                            <dd>{{ $booking->booked_type }}</dd>
                        </div>
                        <div>
                            <dt>Drop-off</dt>
                            <dd>{{ $booking->departDate }}</dd>
                        </div>
                        <div>
                            <dt>Return</dt>
                            <dd>{{ $booking->returnDate }}</dd>
                        </div>
                        <div>
                            <dt>Duration</dt>
                            <dd>{{ $booking->no_of_days }} {{ (int) $booking->no_of_days === 1 ? 'day' : 'days' }}</dd>
                        </div>
                        <div>
                            <dt>Outbound terminal</dt>
                            <dd>
                                @php $dTerm = $booking->dterminal ?? null; @endphp
                                {{ ($dTerm && isset($dTerm->name)) ? $dTerm->name : ($booking->deprTerminal ?: '—') }}
                            </dd>
                        </div>
                        <div>
                            <dt>Inbound terminal</dt>
                            <dd>
                                @php $rTerm = $booking->rterminal ?? null; @endphp
                                {{ ($rTerm && isset($rTerm->name)) ? $rTerm->name : ($booking->returnTerminal ?: '—') }}
                            </dd>
                        </div>
                    </dl>
                </section>

                <section class="js-manage-detail__section">
                    <h2 class="js-manage-detail__section-title">Vehicle</h2>
                    <dl class="js-manage-detail__grid">
                        <div>
                            <dt>Registration</dt>
                            <dd>{{ $booking->registration ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Make</dt>
                            <dd>{{ $booking->make ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Model</dt>
                            <dd>{{ $booking->model ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Colour</dt>
                            <dd>{{ $booking->color ?: '—' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="js-manage-detail__section">
                    <h2 class="js-manage-detail__section-title">Payment &amp; status</h2>
                    <dl class="js-manage-detail__grid">
                        <div>
                            <dt>Payment method</dt>
                            <dd>{{ $booking->payment_method }}</dd>
                        </div>
                        <div>
                            <dt>Payment status</dt>
                            <dd><span class="js-manage-detail__badge">{{ $booking->payment_status }}</span></dd>
                        </div>
                        <div>
                            <dt>Booking status</dt>
                            <dd><span class="js-manage-detail__badge js-manage-detail__badge--navy">{{ $booking->booking_status }}</span></dd>
                        </div>
                    </dl>
                </section>

                @if (!empty($booking->arival))
                    <section class="js-manage-detail__section">
                        <h2 class="js-manage-detail__section-title">Arrival instructions</h2>
                        <div class="js-manage-detail__prose">{!! $booking->arival !!}</div>
                    </section>
                @endif

                @if (!empty($booking->return_proc))
                    <section class="js-manage-detail__section">
                        <h2 class="js-manage-detail__section-title">Departure instructions</h2>
                        <div class="js-manage-detail__prose">{!! $booking->return_proc !!}</div>
                    </section>
                @endif

                <aside class="js-manage-detail__note">
                    <p><strong>Important:</strong> Bring a printed or digital copy of this confirmation when dropping off and collecting your vehicle.</p>
                    <p>Customer Services:
                        @if ($supportPhone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $supportPhone) }}">{{ $supportPhone }}</a>
                        @endif
                        @if ($supportEmail)
                            · <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                        @endif
                    </p>
                </aside>

                <footer class="js-manage-detail__footer">
                    <p>{{ $companyLine }}</p>
                    <p>Thank you for booking with {{ $brandName }}</p>
                </footer>
            </article>

            <aside class="js-manage-detail__actions no-print">
                <h2 class="js-manage-detail__actions-title">Booking actions</h2>
                <button type="button" class="js-manage-detail__btn" id="downloadPdf">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <span class="js-manage-detail__btn-label">Download PDF</span>
                </button>
                @if (\Illuminate\Support\Facades\Route::has('reSendEmailBooking'))
                    <button type="button" class="js-manage-detail__btn js-manage-detail__btn--accent" id="RButton" style="margin-top:10px;background:var(--js-accent,#C2185B);">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                        <span class="js-manage-detail__btn-label">Re-send Email</span>
                    </button>
                    <form id="RForm" style="display:none;">
                        @csrf
                        <input type="hidden" name="id" value="{{ $booking->bookingid ?? $booking->id }}">
                        <input type="hidden" name="email" value="{{ $booking->email }}">
                        <input type="hidden" name="action" value="resend">
                    </form>
                    <p class="alert alert-info" id="msg" style="display:none;margin-top:12px;font-size:13px;"></p>
                @endif
                <a href="{{ route('manage_booking') }}" class="js-manage-detail__link">
                    <i class="fa fa-search" aria-hidden="true"></i> Search another booking
                </a>
            </aside>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    (function () {
        var btn = document.getElementById('downloadPdf');
        if (!btn || typeof html2pdf === 'undefined') return;

        btn.addEventListener('click', function () {
            var source = document.getElementById('print');
            var label = btn.querySelector('.js-manage-detail__btn-label');
            if (!source) return;

            btn.disabled = true;
            if (label) label.textContent = 'Generating PDF…';

            var host = document.createElement('div');
            host.setAttribute('aria-hidden', 'true');
            host.style.cssText = 'position:fixed;left:-10000px;top:0;width:794px;background:#ffffff;z-index:-1;';
            var clone = source.cloneNode(true);
            clone.id = 'print-pdf-clone';
            clone.style.cssText = 'width:100%;max-width:794px;background:#ffffff;color:#0a1f3d;box-shadow:none;border:1px solid #dfe3ea;';
            host.appendChild(clone);
            document.body.appendChild(host);

            clone.querySelectorAll('*').forEach(function (el) {
                var style = window.getComputedStyle(el);
                if (style.color && style.color.indexOf('rgba(0, 0, 0, 0)') === -1) {
                    el.style.color = style.color;
                }
                if (style.backgroundColor && style.backgroundColor !== 'rgba(0, 0, 0, 0)') {
                    el.style.backgroundImage = 'none';
                    el.style.backgroundColor = style.backgroundColor;
                }
            });

            var amount = clone.querySelector('.js-manage-detail__amount');
            if (amount) {
                amount.style.background = '#0A1F3D';
                amount.style.color = '#ffffff';
                amount.querySelectorAll('*').forEach(function (el) {
                    el.style.color = '#ffffff';
                });
            }

            clone.querySelectorAll('img').forEach(function (img) {
                img.crossOrigin = 'anonymous';
                if (!img.complete || img.naturalWidth === 0) {
                    img.style.display = 'none';
                }
            });

            var opt = {
                margin: [10, 10, 10, 10],
                filename: @json($pdfName),
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: clone.scrollWidth,
                    windowHeight: clone.scrollHeight
                },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['css', 'legacy'] }
            };

            html2pdf()
                .set(opt)
                .from(clone)
                .save()
                .then(function () {
                    host.remove();
                    btn.disabled = false;
                    if (label) label.textContent = 'Download PDF';
                })
                .catch(function (err) {
                    console.error('PDF export failed', err);
                    host.remove();
                    btn.disabled = false;
                    if (label) label.textContent = 'Download PDF';
                    alert('Unable to generate PDF. Please try again or use Print.');
                });
        });

        var resendBtn = document.getElementById('RButton');
        var resendForm = document.getElementById('RForm');
        if (resendBtn && resendForm && window.jQuery) {
            resendBtn.addEventListener('click', function () {
                var label = resendBtn.querySelector('.js-manage-detail__btn-label');
                resendBtn.disabled = true;
                if (label) label.textContent = 'Sending…';
                window.jQuery.post('{{ route('reSendEmailBooking') }}', window.jQuery(resendForm).serialize())
                    .done(function (data) {
                        var msg = document.getElementById('msg');
                        if (msg) {
                            msg.style.display = 'block';
                            msg.textContent = (data && data.message) ? data.message : 'Confirmation email re-sent.';
                        }
                    })
                    .fail(function () {
                        alert('Unable to re-send email. Please try again.');
                    })
                    .always(function () {
                        resendBtn.disabled = false;
                        if (label) label.textContent = 'Re-send Email';
                    });
            });
        }
    })();
</script>

@include('layouts.footer')
