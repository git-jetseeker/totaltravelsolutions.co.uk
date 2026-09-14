<?php

if (! function_exists('normalize_site_host')) {
    function normalize_site_host($value)
    {
        $value = trim(strtolower((string) $value));
        if ($value === '') {
            return '';
        }

        if (! preg_match('#^https?://#', $value)) {
            $value = 'http://' . $value;
        }

        $host = parse_url($value, PHP_URL_HOST);
        if (! $host) {
            return '';
        }

        return preg_replace('/^www\./', '', $host);
    }
}

if (! function_exists('current_agent_id')) {
    /**
     * Prefer APP_AGENT_ID, else match partners.url host, else CRM/app default.
     */
    function current_agent_id()
    {
        $forced = config('app.agent_id');
        if ($forced !== null && $forced !== '') {
            return (int) $forced;
        }

        $requestHost = normalize_site_host(request()->getHost());
        if ($requestHost !== '') {
            static $hostMap = null;

            if ($hostMap === null) {
                $hostMap = [];
                try {
                    if (class_exists(\App\Models\partners::class)) {
                        $partners = \App\Models\partners::query()
                            ->select(['id', 'url'])
                            ->whereNotNull('url')
                            ->where('url', '!=', '')
                            ->get();

                        foreach ($partners as $partner) {
                            $host = normalize_site_host($partner->url);
                            if ($host !== '' && ! isset($hostMap[$host])) {
                                $hostMap[$host] = (int) $partner->id;
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    $hostMap = [];
                }
            }

            if (isset($hostMap[$requestHost])) {
                return $hostMap[$requestHost];
            }
        }

        return (int) config('app.default_agent_id', config('crm.default_agent_id', 1));
    }
}

if (! function_exists('crm')) {
    function crm(string $key, $default = '', ?int $agentId = null)
    {
        try {
            $value = app(\App\Services\CrmContentService::class)->get($key, $default, $agentId);
        } catch (\Throwable $e) {
            return $default;
        }

        return $value === null || $value === '' ? $default : $value;
    }
}

if (! function_exists('crm_html')) {
    function crm_html(string $key, $default = '', ?int $agentId = null)
    {
        return crm($key, $default, $agentId);
    }
}

if (! function_exists('crm_page_meta')) {
    function crm_page_meta(string $slug, ?int $agentId = null): object
    {
        try {
            return app(\App\Services\CrmContentService::class)->pageMeta($slug, $agentId);
        } catch (\Throwable $e) {
            return (object) [
                'title' => '',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'path' => '',
            ];
        }
    }
}

if (! function_exists('apply_crm_meta')) {
    function apply_crm_meta($page, string $slug)
    {
        if (!$page) {
            $page = (object) [
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'page_title' => '',
            ];
        }

        $meta = crm_page_meta($slug);

        if ($meta->meta_title !== '') {
            $page->meta_title = $meta->meta_title;
        }
        if ($meta->meta_keyword !== '') {
            $page->meta_keyword = $meta->meta_keyword;
        }
        if ($meta->meta_description !== '') {
            $page->meta_description = $meta->meta_description;
        }
        if ($meta->title !== '' && empty($page->page_title)) {
            $page->page_title = $meta->title;
        }

        return $page;
    }
}

if (! function_exists('normalize_site_settings')) {
    /**
     * Ensure common settings keys exist so PHP 8+ does not throw on missing array keys.
     */
    function normalize_site_settings(array $settings = []): array
    {
        $defaults = [
            'site_title' => '',
            'meta_description' => '',
            'meta_keyword' => '',
            'site_twitter_title' => '',
            'site_og_title' => '',
            'site_og_type' => 'website',
            'site_og_image' => '',
            'site_og_url' => '',
            'site_author' => '',
            'site_schema' => '',
            'site_header_analytics' => '',
            'site_body_analytics' => '',
            'site_confirm_page_analytics' => '',
            'footer_phone_no' => '',
            'footer_email' => '',
            'footer_catch_line' => '',
            'footer_copyright' => '',
            'footer_company_reg_no' => '',
            'footer_address' => '',
            'facebook' => '',
            'facebook_status' => '',
            'twitter' => '',
            'twitter_status' => '',
            'instagram' => '',
            'instagram_status' => '',
            'youtube' => '',
            'youtube_status' => '',
            'linkedin' => '',
            'linkedin_status' => '',
            'pinterest' => '',
            'pinterest_status' => '',
            'google_plus' => '',
            'google_plus_status' => '',
            'sliders' => 'a:0:{}',
            'payment_type' => 'stripe',
            'booking_fee' => '0',
            'homepage_tagline' => '',
            'homepage_tagline_heading' => '',
            'homepage_joinus_heading' => '',
            'homepage_joinus_subheading' => '',
            'homepage_joinus_text' => '',
            'services_page_parking_heading' => '',
            'services_page_parking_descp' => '',
            'services_page_parking_sec1_heading' => '',
            'services_page_parking_sec1_descp' => '',
            'services_page_parking_sec1_meetandgreet' => '',
            'services_page_parking_sec1_parkandride' => '',
            'services_page_parking_sec1_onairport' => '',
            'services_page_parking_sec2_heading' => '',
            'services_page_parking_sec2_descp' => '',
            'services_page_parking_sec2_step1' => '',
            'services_page_parking_sec2_step2' => '',
            'services_page_parking_sec2_step3' => '',
            'services_page_lounges_heading' => '',
            'services_page_lounges_descp' => '',
            'services_page_lounges_sec1_heading' => '',
            'services_page_lounges_sec1_descp' => '',
            'services_page_lounges_sec1_grid1' => '',
            'services_page_lounges_sec1_grid2' => '',
            'services_page_lounges_sec1_grid3' => '',
            'services_page_lounges_sec1_grid4' => '',
            'services_page_lounges_sec2_heading' => '',
            'services_page_lounges_sec2_descp' => '',
        ];

        return array_merge($defaults, $settings);
    }
}

if (!function_exists('ttss_dashboard_asset_url')) {
    /**
     * Magr uploads are served from /storage/app/... on dashboard.ttssgroup.com.
     * DB values are usually public/pagebanners/x, companies/x, or bare filenames.
     */
    function ttss_dashboard_asset_url($path, $default = '')
    {
        $base = 'https://www.dashboard.ttssgroup.com/';
        $path = trim((string) $path);
        if ($path === '') {
            return $default;
        }

        $path = preg_replace('#^(https?:)+#i', 'https:', $path) ?: $path;
        if (stripos($path, 'https://') === 0 || stripos($path, 'http://') === 0) {
            return preg_replace('#^https://https://#i', 'https://', $path);
        }
        if (str_starts_with($path, '//')) {
            return 'https:' . $path;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, strlen('public/'));
        }

        if (str_starts_with($path, 'storage/app/')) {
            return $base . $path;
        }

        // Legacy JetSeeker-style /storage/pagebanners → Magr /storage/app/pagebanners
        if (str_starts_with($path, 'storage/')) {
            $rest = substr($path, strlen('storage/'));
            if (str_starts_with($rest, 'app/')) {
                return $base . $path;
            }

            return $base . 'storage/app/' . $rest;
        }

        if (str_starts_with($path, 'companies/') || str_starts_with($path, 'pagebanners/')) {
            return $base . 'storage/app/' . $path;
        }

        // Bare filenames historically lived at Magr public root / theme assets.
        if (! str_contains($path, '/')) {
            return $base . $path;
        }

        return $base . 'storage/app/' . $path;
    }
}

if (!function_exists('ttss_company_logo_url')) {
    function ttss_company_logo_url($path, $default = '')
    {
        return ttss_dashboard_asset_url($path, $default);
    }
}

