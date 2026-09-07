<!DOCTYPE html>
<html lang="en">
<head>
    <!-- SEO Meta Tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title & Description -->
    <title>404 - Page Not Found | JET SEEKER Airport Parking</title>
    <meta name="description" content="The page you're looking for doesn't exist. Find the best UK airport parking deals at JET SEEKER. Compare prices, read reviews, and book secure parking at major airports.">
    <meta name="keywords" content="404, page not found, airport parking, UK airports, jet seeker, parking deals">

    <!-- Robots Meta -->
    <meta name="robots" content="noindex, nofollow">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="404 - Page Not Found | JET SEEKER Airport Parking">
    <meta property="og:description" content="The page you're looking for doesn't exist. Find the best UK airport parking deals at JET SEEKER.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="JET SEEKER">
    <meta property="og:image" content="{{ asset('theme/images/logo-black.png') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="404 - Page Not Found | JET SEEKER Airport Parking">
    <meta name="twitter:description" content="The page you're looking for doesn't exist. Find the best UK airport parking deals at JET SEEKER.">
    <meta name="twitter:image" content="{{ asset('theme/images/logo-black.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('theme/images/logo-black.png') }}">

    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "404 - Page Not Found",
        "description": "The page you're looking for doesn't exist. Find the best UK airport parking deals at JET SEEKER.",
        "url": "{{ url()->current() }}",
        "publisher": {
            "@type": "Organization",
            "name": "JET SEEKER",
            "url": "{{ url('/') }}"
        }
    }
    </script>

    <style>
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1a1a1a;
            overflow-x: hidden;
            background: #ffffff;
            min-height: 100vh;
            position: relative;
        }

        /* Subtle Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(255, 140, 0, 0.02) 1.5px, transparent 1.5px),
                linear-gradient(90deg, rgba(255, 140, 0, 0.02) 1.5px, transparent 1.5px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Accent Gradient */
        .top-accent {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            /* background: linear-gradient(90deg, #C2185B 0%, #ffa500 50%, #C2185B 100%); */
            z-index: 1000;
        }

        /* Main Section */
        .error-404-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 80px 24px;
            z-index: 1;
        }

        .error-404-container {
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Layout Grid */
        .error-content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        /* Left Column - Visual */
        .error-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-number-wrapper {
            position: relative;
        }

        .error-404-number {
            font-size: 280px;
            font-weight: 800;
            line-height: 0.9;
            background: linear-gradient(135deg, #C2185B 0%, #C2185B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -12px;
            position: relative;
        }

        .error-plane-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 80px;
            color: #000000;
            opacity: 0.08;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 140, 0, 0.08);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 120px;
            height: 120px;
            top: -20px;
            right: -20px;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 80px;
            height: 80px;
            bottom: -10px;
            left: -10px;
            animation-delay: 2s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            50% {
                transform: translateY(-20px) scale(1.05);
            }
        }

        /* Right Column - Content */
        .error-content {
            text-align: left;
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #C2185B;
            border: 1px solid #C2185B;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 24px;
        }

        .error-404-title {
            font-size: 48px;
            font-weight: 800;
            color: #000000;
            margin-bottom: 20px;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .error-404-subtitle {
            font-size: 18px;
            color: #666666;
            margin-bottom: 40px;
            line-height: 1.7;
            max-width: 520px;
        }

        /* Buttons */
        .error-404-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 50px;
        }

        .error-404-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .error-404-btn-primary {
            background: #C2185B;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.25);
        }

        .error-404-btn-primary:hover {
            background: #000;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 140, 0, 0.35);
            text-decoration: none;
            color: #ffffff;
        }

        .error-404-btn-secondary {
            background: transparent;
            color: #1a1a1a;
            border: 2px solid #e0e0e0;
        }

        .error-404-btn-secondary:hover {
            border-color: #C2185B;
            background: rgba(255, 140, 0, 0.05);
            text-decoration: none;
        }

        /* Quick Links */
        .error-404-links-section {
            padding-top: 40px;
            border-top: 1px solid #e0e0e0;
        }

        .error-404-links-title {
            font-size: 14px;
            font-weight: 600;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }

        .error-404-links {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .error-404-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #666666;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 15px;
            background: #f8f8f8;
        }

        .error-404-link:hover {
            color: #C2185B;
            background: rgba(255, 140, 0, 0.08);
            text-decoration: none;
            transform: translateX(4px);
        }

        .error-404-link i {
            font-size: 14px;
            color: #C2185B;
            width: 20px;
        }

        /* Help Section */
        .error-help-box {
            margin-top: 50px;
            padding: 24px;
            background: #f8f8f8;
            border-left: 4px solid #C2185B;
            border-radius: 8px;
        }

        .error-help-box h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .error-help-box p {
            font-size: 14px;
            color: #666666;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .error-help-box a {
            color: #C2185B;
            text-decoration: none;
            font-weight: 600;
        }

        .error-help-box a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .error-content-grid {
                grid-template-columns: 1fr;
                gap: 60px;
                text-align: center;
            }

            .error-visual {
                order: -1;
            }

            .error-content {
                text-align: center;
            }

            .error-404-number {
                font-size: 200px;
                letter-spacing: -8px;
            }

            .error-plane-icon {
                font-size: 60px;
            }

            .error-404-title {
                font-size: 40px;
            }

            .error-404-subtitle {
                max-width: 100%;
                margin-left: auto;
                margin-right: auto;
            }

            .error-404-buttons {
                justify-content: center;
            }

            .error-404-links-section {
                max-width: 500px;
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media (max-width: 767px) {
            .error-404-section {
                padding: 60px 20px;
            }

            .error-404-number {
                font-size: 140px;
                letter-spacing: -6px;
            }

            .error-plane-icon {
                font-size: 40px;
            }

            .error-404-title {
                font-size: 32px;
            }

            .error-404-subtitle {
                font-size: 16px;
            }

            .floating-element {
                display: none;
            }

            .error-404-buttons {
                flex-direction: column;
                width: 100%;
            }

            .error-404-btn {
                width: 100%;
                justify-content: center;
            }

            .error-404-links {
                grid-template-columns: 1fr;
            }

            .error-content-grid {
                gap: 40px;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }

        /* Print Styles */
        @media print {
            .error-404-section {
                padding: 40px;
            }
            
            .floating-element,
            body::before {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="top-accent" role="presentation"></div>
    
    <main>
        <section class="error-404-section">
            <div class="error-404-container">
                <div class="error-content-grid">
                    <!-- Left Column - Visual -->
                    <div class="error-visual">
                        <div class="error-number-wrapper">
                            <div class="floating-element"></div>
                            <div class="floating-element"></div>
                            <div class="error-404-number" aria-label="Error 404">404</div>
                            <i class="fas fa-plane error-plane-icon" aria-hidden="true"></i>
                        </div>
                    </div>

                    <!-- Right Column - Content -->
                    <div class="error-content">
                        <div class="error-badge">
                            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                            <span>Page Not Found</span>
                        </div>

                        <h1 class="error-404-title">We've Lost This Page</h1>

                        <p class="error-404-subtitle">
                            The page you're looking for doesn't exist or has been moved. Let's get you back on track to finding the perfect airport parking spot.
                        </p>

                        <div class="error-404-buttons">
                            <a href="{{ url('/') }}" class="error-404-btn error-404-btn-primary" aria-label="Return to home page">
                                <i class="fas fa-home" aria-hidden="true"></i>
                                <span>Back to Home</span>
                            </a>
                           
                        </div>

                        <div class="error-404-links-section">
                            <div class="error-404-links-title">Popular Pages</div>
                            <div class="error-404-links">
                                <a href="{{ url('/airports') }}" class="error-404-link">
                                    <i class="fas fa-plane-departure"></i>
                                    <span>All Airports</span>
                                </a>
                                <a href="{{ url('/support') }}" class="error-404-link">
                                    <i class="fas fa-headset"></i>
                                    <span>Contact Support</span>
                                </a>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- JavaScript for Analytics -->
    <script>
        // Track 404 errors for analytics
        if (typeof gtag === 'function') {
            gtag('event', 'page_view', {
                page_title: '404 - Page Not Found',
                page_location: window.location.href,
                page_path: window.location.pathname
            });
        }

        // Log to console for debugging
        console.log('404 Error - Page not found:', window.location.href);

        // Optional: Send to error tracking service
        if (typeof Sentry !== 'undefined') {
            Sentry.captureMessage('404 Page Not Found', {
                level: 'info',
                extra: {
                    url: window.location.href,
                    referrer: document.referrer
                }
            });
        }
    </script>
</body>
</html>