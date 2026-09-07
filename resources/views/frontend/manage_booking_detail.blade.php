@extends('layouts.main')

@include('layouts.header')
@include('layouts.nav')

@section('content')
    <style type="text/css">
        .booking-result p strong {
            color: #000 !important;
        }

        .btn-black {
            background-color: #000;
            color: white;
        }

        .btn-black:hover {
            background-color: #000;
            color: white;
        }

        div {
            color: #000;
        }

        p {
            color: #000;
        }

        span {
            color: #000;
        }

        /* PDF-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #print, #print * {
                visibility: visible;
            }
            #print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }

        .receipt-header {
            text-align: center;
            padding: 20px;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
        }

        .receipt-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .receipt-section h2 {
            color: #000;
            border-bottom: 2px solid #C2185B;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 30%;
        }

        .amount-highlight {
            background-color: #f0f0f0;
            padding: 15px;
            text-align: center;
            border: 2px solid #000;
            border-radius: 8px;
            margin: 20px 0;
        }

        .amount-highlight h2 {
            color: #000;
            margin: 0;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset('theme/styles/jetseeker-manage-booking.css?v=20250901') }}">

    <div class="home-container home-background">
        @include('frontend.header')
    </div>

    <div class="js-manage-booking-page">
    <section class="section" style="margin-top: 44px;padding: 40px;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="booking-result">
                    <div class="row" style="margin: 20px auto;">
                        <div class="col-md-9" id="myfont" style="margin:20px auto;">
                            <div class="row" id="print">
                                <div class="main-area" style="background: url(//assets/images/banner18.jpg); border: 1px solid #777777; float: left; width: 100%; border-radius: 23px; background-color: #fff; padding: 30px;">
                                    
                                    <!-- Receipt Header -->
                                    <div class="receipt-header">
                                        <h1 style="color: #000; font-weight: 600; margin: 0;">Total Travel Solutions</h1>
                                        <h2 style="color: #000; font-weight: 600; margin: 10px 0;">Booking Confirmation</h2>
                                        <p style="margin: 5px 0;"><strong>Reference:</strong> {{ $booking->referenceNo }}</p>
                                        <p style="margin: 5px 0; font-size: 14px;">{{ now()->format('d/m/Y H:i') }}</p>
                                    </div>

                                    <!-- Customer Information -->
                                    <div class="receipt-section">
                                        <h2>Customer Information</h2>
                                        <table class="info-table">
                                            <tr>
                                                <td>Name:</td>
                                                <td>{{ $booking->first_name }} {{ $booking->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td>{{ $booking->email }}</td>
                                            </tr>
                                            <tr>
                                                <td>Phone:</td>
                                                <td>{{ $booking->phone_number }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- Booking Details -->
                                    <div class="receipt-section">
                                        <h2>Booking Details</h2>
                                        <table class="info-table">
                                            @if ($airport_detail)
                                            <tr>
                                                <td>Airport:</td>
                                                <td>{{ $airport_detail->name }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>Booking Start Date:</td>
                                                <td>{{ $booking->departDate }}</td>
                                            </tr>
                                            <tr>
                                                <td>Booking End Date:</td>
                                                <td>{{ $booking->returnDate }}</td>
                                            </tr>
                                            <tr>
                                                <td>Number of Days:</td>
                                                <td>{{ $booking->no_of_days }}</td>
                                            </tr>
                                            <tr>
                                                <td>Company Booked with:</td>
                                                <td><strong>{{ $booking->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Parking Type:</td>
                                                <td>{{ $booking->booked_type }}</td>
                                            </tr>
                                            <tr>
                                                <td>Outbound Terminal:</td>
                                                <td>@if ($booking->dterminal) {{ $booking->dterminal->name }} @endif</td>
                                            </tr>
                                            <tr>
                                                <td>Inbound Terminal:</td>
                                                <td>@if ($booking->rterminal) {{ $booking->rterminal->name }} @endif</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- Vehicle Information -->
                                    <div class="receipt-section">
                                        <h2>Vehicle Information</h2>
                                        <table class="info-table">
                                            <tr>
                                                <td>Registration:</td>
                                                <td>{{ $booking->registration }}</td>
                                            </tr>
                                            <tr>
                                                <td>Make:</td>
                                                <td>{{ $booking->make }}</td>
                                            </tr>
                                            <tr>
                                                <td>Model:</td>
                                                <td>{{ $booking->model }}</td>
                                            </tr>
                                            <tr>
                                                <td>Color:</td>
                                                <td>{{ $booking->color }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <!-- Payment Information -->
                                    <div class="receipt-section">
                                        <h2>Payment Information</h2>
                                        <table class="info-table">
                                            <tr>
                                                <td>Payment Method:</td>
                                                <td>{{ $booking->payment_method }}</td>
                                            </tr>
                                            <tr>
                                                <td>Payment Status:</td>
                                                <td>{{ $booking->payment_status }}</td>
                                            </tr>
                                            <tr>
                                                <td>Booking Status:</td>
                                                <td>{{ $booking->booking_status }}</td>
                                            </tr>
                                        </table>
                                        
                                        <div class="amount-highlight">
                                            <h2>Total Amount: {{ $booking->total_amount }}</h2>
                                        </div>
                                    </div>

                                    <!-- Arrival Instructions -->
                                    @if($booking->arival)
                                    <div class="receipt-section">
                                        <h2>Arrival Instructions</h2>
                                        <div style="padding: 10px; background: #f9f9f9; border-radius: 5px;">
                                            <?php echo $booking->arival; ?>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Departure Instructions -->
                                    @if($booking->return_proc)
                                    <div class="receipt-section">
                                        <h2>Departure Instructions</h2>
                                        <div style="padding: 10px; background: #f9f9f9; border-radius: 5px;">
                                            <?php echo $booking->return_proc; ?>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Important Note -->
                                    <div class="receipt-section" style="background: #fff9e6; padding: 15px; border-left: 4px solid #C2185B;">
                                        <p><strong>Important:</strong> Please bring a printed or digital copy of this booking confirmation when dropping off and collecting your vehicle.</p>
                                        <p style="margin-top: 10px;"><strong>Contact:</strong> Customer Services: 020 8178 6133 | Email: bookings@totaltravelsolutions.co.uk</p>
                                    </div>

                                    <!-- Footer -->
                                    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0; font-size: 12px; color: #666;">
                                        <p>Total Travel Solutions - Registered in England Registration Number 11502152</p>
                                        <p>Thank you for booking with Total Travel Solutions</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 no-print" style="background: #fff; padding-top: 33px;">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <h3 style="color: #000; font-weight: 600;">Booking Actions</h3>
                                </div>
                                <div class="col-md-12">
                                    <p class="alert alert-info" id="msg" style="display:none;"></p>
                                    <ul style="padding: 0px; list-style: none;">
                                        <li style="margin-bottom: 15px;">
                                            <button type="button" class="btn btn-black" id="downloadPdf" style="width: 88%;">
                                                <i class="entypo-download"></i> Download PDF
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-yellow" id="RButton" style="background: #C2185B; width: 88%;">
                                                <i class="entypo-mail"></i> Re-send Email
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form id="RForm">
                            <input type="hidden" name="id" value="{{ $booking->bookingid }}">
                            <input type="hidden" name="email" value="{{ $booking->email }}">
                            <input type="hidden" name="action" value="resend">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection

@section('footer-script')
    <!-- Include html2pdf.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <script>
        // Download PDF functionality
        $('#downloadPdf').click(function() {
            const element = document.getElementById('print');
            const opt = {
                margin: 10,
                filename: 'Total_Travel_Solutions_Booking_{{ $booking->referenceNo }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Show loading message
            $(this).html('<i class="entypo-hourglass"></i> Generating PDF...').prop('disabled', true);
            
            html2pdf().set(opt).from(element).save().then(() => {
                $('#downloadPdf').html('<i class="entypo-download"></i> Download PDF').prop('disabled', false);
            });
        });

        // Re-send email functionality
        $('#RButton').click(function() {
            $.post('{{ route('reSendEmailBooking') }}', $("#RForm").serialize(), function(data, textStatus, xhr) {
                if (data == "success") {
                    alert('Email Sent Successfully!');
                } else {
                    alert('Email Not Sent. Please Try Again!');
                }
            });
            $('#msg').hide();
        });
    </script>
@endsection