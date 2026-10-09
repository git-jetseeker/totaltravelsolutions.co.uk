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
        $base = 'https://dashboard.ttssgroup.com/';
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
            $file = basename($path);
            if ($file !== '' && str_starts_with($path, 'pagebanners/')) {
                $local = public_path('assets/images/airports/' . $file);
                if (is_file($local)) {
                    return asset('assets/images/airports/' . $file);
                }
            }
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

if (!function_exists('ttss_award_image_url')) {
    /**
     * Award/reward images live under /storage/app/awards/ on dashboard.ttssgroup.com.
     * Backend may store awards/foo.png, storage/app/awards/foo.png, or a bare filename.
     */
    function ttss_award_image_url($path, $default = '')
    {
        $base = 'https://dashboard.ttssgroup.com/storage/app/awards/';
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
        if (str_starts_with($path, 'storage/app/awards/')) {
            $path = substr($path, strlen('storage/app/awards/'));
        } elseif (str_starts_with($path, 'storage/app/')) {
            $path = substr($path, strlen('storage/app/'));
        } elseif (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }
        if (str_starts_with($path, 'awards/')) {
            $path = substr($path, strlen('awards/'));
        }

        $path = ltrim($path, '/');
        if ($path === '') {
            return $default;
        }

        return $base . $path;
    }
}


if (! function_exists('setting_agent_matches')) {
    /**
     * Magr settings may store partner id in agent_id and/or agentID.
     */
    function setting_agent_matches($setting, $agentId = null): bool
    {
        $agentId = (string) ($agentId ?? (function_exists('current_agent_id') ? current_agent_id() : ''));
        $row = (string) (($setting->agent_id ?? null) ?: ($setting->agentID ?? ''));

        return $row !== '' && $row === $agentId;
    }
}

if (! function_exists('pz_brand_settings')) {
    /**
     * Rewrite legacy JetSeeker/ParkingZone branding to Total Travel Solutions.
     */
    function pz_brand_settings(array $settings = []): array
    {
        $brand = 'Total Travel Solutions';
        $email = 'support@totaltravelsolutions.co.uk';
        $defaults = [
            'site_title' => $brand . ' | Compare UK Airport Parking',
            'footer_email' => $email,
            'footer_catch_line' => $brand . ' helps travellers compare and book reliable Park Mark accredited parking at major UK airports — transparent pricing, quality service, and peace of mind every time you fly.',
            'footer_copyright' => $brand . '. All rights reserved.',
            'footer_company_reg_no' => 'Company Registration No 16770283.',
            'site_author' => $brand,
            'site_twitter_title' => $brand,
            'site_og_title' => $brand,
        ];

        $rewriteText = static function ($value) use ($brand) {
            $value = (string) $value;
            if ($value === '') {
                return $value;
            }

            $value = preg_replace('/\b(JETSEEKER|JET SEEKER|JetSeeker|Jetseeker)\b/i', $brand, $value) ?? $value;
            $value = preg_replace('/\b(Parking\s*Zone|ParkingZone)\b/i', $brand, $value) ?? $value;
            $value = preg_replace('/\bMeetandGreet\b/i', 'Meet & Greet', $value) ?? $value;
            $value = preg_replace('/@jetseeker\.co\.uk\b/i', '@totaltravelsolutions.co.uk', $value) ?? $value;
            $value = preg_replace('/https?:\/\/(?:www\.)?jetseeker\.co\.uk/i', 'https://www.totaltravelsolutions.co.uk', $value) ?? $value;

            return $value;
        };

        foreach ($settings as $key => $value) {
            if (! is_string($value) || $value === '') {
                continue;
            }

            if ($key === 'footer_email' && preg_match('/@jetseeker\.co\.uk$/i', $value)) {
                $settings[$key] = $email;
                continue;
            }

            if (in_array($key, ['footer_copyright', 'footer_company_reg_no', 'footer_catch_line', 'site_title', 'site_author', 'site_twitter_title', 'site_og_title', 'meta_description', 'meta_keyword'], true)) {
                if (preg_match('/jetseeker|parking\s*zone|meetandgreet|14989449/i', $value)) {
                    $settings[$key] = $defaults[$key] ?? $rewriteText($value);
                    continue;
                }
            }

            if (preg_match('/jetseeker|@jetseeker\.co\.uk/i', $value)) {
                $settings[$key] = $rewriteText($value);
            }
        }

        foreach (['footer_email', 'footer_catch_line', 'footer_copyright', 'footer_company_reg_no', 'site_title'] as $key) {
            if (empty($settings[$key])) {
                $settings[$key] = $defaults[$key];
            }
        }

        return $settings;
    }
}

if (! function_exists('site_settings')) {
    /**
     * Load Magr settings for the current (or given) partner agent.
     */
    function site_settings($agentId = null): array
    {
        $agentId = (string) ($agentId ?? (function_exists('current_agent_id') ? current_agent_id() : '1'));
        $out = [];

        try {
            foreach (\App\Models\settings::all() as $setting) {
                if (setting_agent_matches($setting, $agentId)) {
                    $out[$setting->field_name] = $setting->field_value;
                }
            }
        } catch (\Throwable $e) {
            // keep empty map
        }

        if (function_exists('normalize_site_settings')) {
            $out = normalize_site_settings($out);
        }

        if (function_exists('pz_brand_settings')) {
            $out = pz_brand_settings($out);
        }

        return $out;
    }
}


if (! function_exists('agent_column')) {
    /**
     * Resolve agent column name per table (live: agent_id, local: agentID).
     * JetSeeker parity.
     */
    function agent_column(string $table = 'settings'): string
    {
        static $cache = [];

        if (array_key_exists($table, $cache)) {
            return $cache[$table];
        }

        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'agent_id')) {
                return $cache[$table] = 'agent_id';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'agentID')) {
                return $cache[$table] = 'agentID';
            }
        } catch (\Throwable $e) {
            // Schema may be unavailable during early boot.
        }

        return $cache[$table] = 'agent_id';
    }
}

if (! function_exists('agent_columns')) {
    /**
     * @return array<int, string>
     */
    function agent_columns(string $table = 'settings'): array
    {
        static $cache = [];

        if (array_key_exists($table, $cache)) {
            return $cache[$table];
        }

        $columns = [];
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'agent_id')) {
                $columns[] = 'agent_id';
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'agentID')) {
                $columns[] = 'agentID';
            }
        } catch (\Throwable $e) {
            // ignore
        }

        if ($columns === []) {
            $columns = ['agent_id'];
        }

        return $cache[$table] = $columns;
    }
}

if (! function_exists('current_site_agent_id')) {
    /**
     * JetSeeker-compatible site agent id (string).
     */
    function current_site_agent_id(): string
    {
        if (function_exists('current_agent_id')) {
            return (string) current_agent_id();
        }

        return (string) config('app.agent_id', config('app.default_agent_id', '9'));
    }
}

if (! function_exists('set_model_agent_id')) {
    /**
     * Write agent id onto whichever agent columns exist for the model table.
     * JetSeeker parity.
     */
    function set_model_agent_id($model, $agentId): void
    {
        $agentId = (string) $agentId;
        $table = method_exists($model, 'getTable') ? $model->getTable() : 'settings';

        foreach (agent_columns($table) as $column) {
            $model->setAttribute($column, $agentId);
        }
    }
}

if (! function_exists('hard_redirect')) {
    /**
     * Proxy-safe redirect (meta refresh + JS + Location header).
     * Some edge caches strip Laravel 302 Location and leave a blank redirect page.
     */
    function hard_redirect(string $url, int $status = 302)
    {
        $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $jsUrl = json_encode($url, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">'
            . '<meta http-equiv="refresh" content="0;url=' . $safeUrl . '">'
            . '<script>window.location.replace(' . $jsUrl . ');</script>'
            . '<title>Redirecting</title></head><body>'
            . '<p>If you are not redirected automatically, <a href="' . $safeUrl . '">continue here</a>.</p>'
            . '</body></html>';

        return response($html, $status, [
            'Location' => $url,
            'Cache-Control' => 'no-store, no-cache, private, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}

