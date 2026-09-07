<?php

namespace App\Support;

class LoungeFacilities
{
    /**
     * Parse lounge facilities HTML/text into clean facility names.
     */
    public static function parse(string $raw): array
    {
        if (trim($raw) === '') {
            return [];
        }

        $items = preg_split('/<br\s*\/?>/i', $raw) ?: [];

        return array_values(array_filter(array_map(function ($item) {
            return trim(strip_tags(html_entity_decode($item, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        }, $items)));
    }

    /**
     * Font Awesome icon class for a facility label.
     */
    public static function iconClass(string $name): string
    {
        $key = strtolower(trim($name));

        $map = [
            'air conditioning' => 'fa-snowflake',
            'refreshments (alcoholic)' => 'fa-wine-glass-alt',
            'refreshments (soft drinks)' => 'fa-glass-whiskey',
            'disabled access' => 'fa-wheelchair',
            'flight information monitor' => 'fa-plane-arrival',
            'no smoking' => 'fa-smoking-ban',
            'television' => 'fa-tv',
            'wi-fi' => 'fa-wifi',
            'wifi' => 'fa-wifi',
            'internet' => 'fa-wifi',
            'conference facilities' => 'fa-users',
            'business facilities' => 'fa-briefcase',
            'phone' => 'fa-phone',
            'magazines' => 'fa-newspaper',
            'newspapers' => 'fa-newspaper',
            'snacks' => 'fa-cookie-bite',
            'shower' => 'fa-shower',
            'flight announcements' => 'fa-bullhorn',
            'free drinks' => 'fa-coffee',
            'free food' => 'fa-utensils',
            'personal lounge' => 'fa-couch',
            'landside' => 'fa-door-open',
            'children allowed' => 'fa-child',
        ];

        if (isset($map[$key])) {
            return 'fas ' . $map[$key];
        }

        foreach ($map as $label => $icon) {
            if (str_contains($key, $label) || str_contains($label, $key)) {
                return 'fas ' . $icon;
            }
        }

        if (str_contains($key, 'wifi') || str_contains($key, 'wi-fi')) {
            return 'fas fa-wifi';
        }
        if (str_contains($key, 'wheelchair') || str_contains($key, 'disabled')) {
            return 'fas fa-wheelchair';
        }
        if (str_contains($key, 'alcohol') || str_contains($key, 'wine') || str_contains($key, 'drink')) {
            return 'fas fa-wine-glass-alt';
        }
        if (str_contains($key, 'flight') || str_contains($key, 'plane')) {
            return 'fas fa-plane-arrival';
        }
        if (str_contains($key, 'smok')) {
            return 'fas fa-smoking-ban';
        }
        if (str_contains($key, 'tv') || str_contains($key, 'television')) {
            return 'fas fa-tv';
        }

        return 'fas fa-check';
    }
}
