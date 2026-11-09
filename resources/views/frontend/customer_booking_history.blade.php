@extends('frontend.customer.layouts.app')

@section('title', 'Booking history - Total Travel Solutions')
@section('meta_description', 'View your Total Travel Solutions booking history.')

@section('customer_content')
    @php
        $formatDate = function ($value) {
            if (empty($value)) {
                return '—';
            }
            try {
                return \Carbon\Carbon::parse($value)->format('D, d M Y H:i');
            } catch (\Exception $exception) {
                return (string) $value;
            }
        };
    @endphp

    <section class="tts-account-section">
        <div class="tts-account-section__heading">
            <h2>Your bookings</h2>
            <span class="tts-account-count">{{ $bookings->count() }} total</span>
        </div>

        @if ($bookings->isEmpty())
            <div class="tts-account-empty">
                <h3>No bookings found</h3>
                <p>Bookings made with this email address will appear here.</p>
                <a href="{{ route('manage_booking') }}" class="tts-account-button">Find a booking</a>
            </div>
        @else
            <div class="tts-account-table-wrap">
                <table class="tts-account-table">
                    <thead>
                        <tr>
                            <th>Book ref.</th>
                            <th>Product</th>
                            <th>Traveling</th>
                            <th>Drop-off / start</th>
                            <th>Return / end</th>
                            <th>Vehicle / details</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            @php
                                $status = trim((string) ($booking['status'] ?? '')) ?: 'Pending';
                                $isComplete = in_array(strtolower($status), ['completed', 'complete', 'success', 'paid'], true);
                            @endphp
                            <tr>
                                <td data-label="Book ref."><strong>{{ $booking['reference'] ?: '—' }}</strong></td>
                                <td data-label="Product">{{ $booking['product'] }}</td>
                                <td data-label="Traveling">{{ $booking['place'] ?: $booking['product'] }}</td>
                                <td data-label="Drop-off / start">{{ $formatDate($booking['start_date']) }}</td>
                                <td data-label="Return / end">{{ $formatDate($booking['end_date']) }}</td>
                                <td data-label="Vehicle / details">{{ $booking['vehicle'] ?: '—' }}</td>
                                <td data-label="Amount">{{ is_numeric($booking['amount']) ? '£' . number_format((float) $booking['amount'], 2) : ($booking['amount'] ?: '—') }}</td>
                                <td data-label="Status"><span class="tts-account-status {{ $isComplete ? 'is-complete' : '' }}">{{ $status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
