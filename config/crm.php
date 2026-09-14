<?php

return [
    'default_agent_id' => (int) env('CRM_DEFAULT_AGENT_ID', 9),
    'cache_bust_token' => env('CRM_CACHE_BUST_TOKEN', ''),
    'siteground_purge_enabled' => (bool) env('SITEGROUND_CACHE_PURGE', true),
    'siteground_purge_url' => env('SITEGROUND_PURGE_URL', 'http://127.0.0.1/*'),
    'siteground_purge_timeout' => (int) env('SITEGROUND_PURGE_TIMEOUT', 5),
    'siteground_purge_hosts' => env('SITEGROUND_PURGE_HOSTS', 'parkingzone.co.uk,www.parkingzone.co.uk'),
];
