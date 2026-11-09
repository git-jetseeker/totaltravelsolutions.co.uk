@extends('frontend.customer.layouts.app')

@section('title', 'Customer dashboard - Total Travel Solutions')
@section('meta_description', 'View your bookings and support tickets with Total Travel Solutions.')

@section('customer_content')
    @php
        $lastBooking = $portalStats['last_booking'];
        $formatDate = function ($value, $format = 'd M Y') {
            if (empty($value)) {
                return '—';
            }
            try {
                return \Carbon\Carbon::parse($value)->format($format);
            } catch (\Exception $exception) {
                return (string) $value;
            }
        };
    @endphp

    <section class="tts-account-welcome">
        <div>
            <h1>Welcome back, {{ $customer['first_name'] ?: 'Customer' }}</h1>
            <p>Track bookings and support tickets in one place. Need a space? Book airport parking in a few clicks.</p>
        </div>
        <div class="tts-account-welcome__badges">
            <span>{{ $portalStats['bookings'] }} {{ \Illuminate\Support\Str::plural('booking', $portalStats['bookings']) }}</span>
            <span>Customer since {{ $portalStats['customer_since'] ? $formatDate($portalStats['customer_since']) : 'today' }}</span>
            <span>{{ $portalStats['open_tickets'] }} open {{ \Illuminate\Support\Str::plural('ticket', $portalStats['open_tickets']) }}</span>
        </div>
    </section>

    <section class="tts-account-stats" aria-label="Account summary">
        <article class="tts-account-stat">
            <span class="tts-account-stat__icon tts-account-stat__icon--blue"><i class="fa fa-calendar" aria-hidden="true"></i></span>
            <span class="tts-account-stat__label">Last booking</span>
            <strong>{{ $lastBooking['reference'] ?? '—' }}</strong>
            <small>{{ $lastBooking ? ($lastBooking['place'] . ' · ' . $formatDate($lastBooking['start_date'])) : 'No booking yet' }}</small>
        </article>
        <article class="tts-account-stat">
            <span class="tts-account-stat__icon tts-account-stat__icon--amber"><i class="fa fa-check-circle" aria-hidden="true"></i></span>
            <span class="tts-account-stat__label">This month</span>
            <strong>{{ $portalStats['this_month'] }}</strong>
            <small>Bookings created this month</small>
        </article>
        <article class="tts-account-stat">
            <span class="tts-account-stat__icon tts-account-stat__icon--green"><i class="fa fa-ticket" aria-hidden="true"></i></span>
            <span class="tts-account-stat__label">Total bookings</span>
            <strong>{{ $portalStats['bookings'] }}</strong>
            <small>All-time with Total Travel Solutions</small>
        </article>
        <article class="tts-account-stat">
            <span class="tts-account-stat__icon tts-account-stat__icon--rose"><i class="fa fa-heart-o" aria-hidden="true"></i></span>
            <span class="tts-account-stat__label">With us</span>
            <strong>{{ $portalStats['membership_years'] }} {{ \Illuminate\Support\Str::plural('yr', $portalStats['membership_years']) }}</strong>
            <small>Since {{ $portalStats['customer_since'] ? $formatDate($portalStats['customer_since']) : 'your first booking' }}</small>
        </article>
    </section>

    <section class="tts-account-shortcuts" aria-label="Account shortcuts">
        <a href="{{ route('customer.bookings') }}">
            <span class="tts-account-shortcuts__icon"><i class="fa fa-history" aria-hidden="true"></i></span>
            <strong>Booking history</strong>
            <span>View, print or manage all {{ $portalStats['bookings'] }} bookings.</span>
        </a>
        <a href="{{ route('customer.tickets') }}">
            <span class="tts-account-shortcuts__icon"><i class="fa fa-ticket" aria-hidden="true"></i></span>
            <strong>Support tickets</strong>
            <span>Check your open and closed support tickets.</span>
        </a>
        <a href="{{ route('support') }}">
            <span class="tts-account-shortcuts__icon"><i class="fa fa-plus-square-o" aria-hidden="true"></i></span>
            <strong>Create ticket</strong>
            <span>Need help with a booking? Contact our team.</span>
        </a>
        <a href="{{ route('main') }}#search">
            <span class="tts-account-shortcuts__icon"><i class="fa fa-car" aria-hidden="true"></i></span>
            <strong>Book parking</strong>
            <span>Search airport parking and make a reservation.</span>
        </a>
    </section>

@endsection
