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
