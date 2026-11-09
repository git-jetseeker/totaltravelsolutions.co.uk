@extends('frontend.customer.layouts.app')

@section('title', 'Support tickets - Total Travel Solutions')
@section('meta_description', 'View and manage your Total Travel Solutions support tickets.')

@section('customer_content')
    <section class="tts-account-page-heading">
        <div>
            <h1>Support tickets</h1>
            <p>Hi {{ $customer['first_name'] ?: 'there' }} — see support requests linked to {{ $customer['email'] }}.</p>
        </div>
        <a href="{{ route('support') }}" class="tts-account-button">
            <i class="fa fa-plus" aria-hidden="true"></i> Create ticket
        </a>
    </section>

    <section class="tts-account-stats tts-account-stats--tickets" aria-label="Ticket summary">
        <article class="tts-account-stat">
            <span class="tts-account-stat__icon tts-account-stat__icon--blue"><i class="fa fa-ticket" aria-hidden="true"></i></span>
            <span class="tts-account-stat__label">All tickets</span>
            <strong>{{ $portalStats['tickets'] }}</strong>
        </article>
        <article class="tts-account-stat">
            <span class="tts-account-stat__icon tts-account-stat__icon--amber"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
            <span class="tts-account-stat__label">Open tickets</span>
            <strong>{{ $portalStats['open_tickets'] }}</strong>
        </article>
    </section>

    <section class="tts-account-section">
        <div class="tts-account-section__heading">
            <div>
                <h2>Your support requests</h2>
                <p>Track replies and updates from our team.</p>
            </div>
            <span class="tts-account-count">{{ $tickets->count() }} total</span>
        </div>

        @if ($tickets->isEmpty())
            <div class="tts-account-empty">
                <h3>No support tickets yet</h3>
                <p>You have not raised any support tickets using this email address.</p>
                <a href="{{ route('support') }}" class="tts-account-button">Create a ticket</a>
            </div>
        @else
            <div class="tts-account-table-wrap">
                <table class="tts-account-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Ticket</th>
                            <th>Booking</th>
                            <th>Subject</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            @php
                                $status = trim((string) ($ticket->status ?? 'Open')) ?: 'Open';
                                $isOpen = in_array(strtolower($status), ['open', 'pending', 'in progress', 'new', 'awaiting', 'replied'], true);
                            @endphp
                            <tr>
                                <td data-label="Date">{{ $ticket->date ?: '—' }}</td>
                                <td data-label="Ticket"><strong>{{ $ticket->ticket_id ?: '—' }}</strong></td>
                                <td data-label="Booking">{{ $ticket->booking_ref ?: '—' }}</td>
                                <td data-label="Subject">{{ $ticket->title ?: 'Support request' }}</td>
                                <td data-label="Priority">{{ $ticket->urgency ?: '—' }}</td>
                                <td data-label="Status"><span class="tts-account-status {{ $isOpen ? '' : 'is-complete' }}">{{ $status }}</span></td>
                                <td data-label="Action">
                                    <form method="post" action="{{ route('search_ticket') }}">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $customer['email'] }}">
                                        <input type="hidden" name="ref_no" value="{{ $ticket->ticket_id ?: $ticket->booking_ref }}">
                                        <button class="tts-account-button tts-account-button--small" type="submit">View</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
