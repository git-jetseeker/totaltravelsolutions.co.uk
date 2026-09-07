<?php

namespace App\Support;

class HotelRoomFacilities
{
    /**
     * Build room facility labels from BookFHR option payload + hotel amenities.
     */
    public static function fromOption(array $option, array $hotelAmenities = []): array
    {
        $facilities = [];

        foreach ($option['description'] ?? [] as $item) {
            if (is_string($item) && trim($item) !== '') {
                $facilities[] = self::normalizeLabel($item);
            } elseif (is_array($item)) {
                $label = $item['name'] ?? $item['label'] ?? $item['text'] ?? null;
                if (!empty($label)) {
                    $facilities[] = self::normalizeLabel((string) $label);
                }
            }
        }

        $details = $option['details'] ?? [];

        foreach ($details['facilities'] ?? $details['amenities'] ?? [] as $item) {
            if (is_string($item) && trim($item) !== '') {
                $facilities[] = self::normalizeLabel($item);
            } elseif (is_array($item)) {
                $label = $item['name'] ?? $item['label'] ?? $item['text'] ?? null;
                if (!empty($label)) {
                    $facilities[] = self::normalizeLabel((string) $label);
                }
            }
        }

        if (!empty($details['accessible'])) {
            $facilities[] = 'Accessible';
        }

        if (!empty($details['type'])) {
            $facilities[] = self::normalizeLabel((string) $details['type']);
        }

        if (!empty($option['additionalInfo'])) {
            $parts = preg_split('/[,|•\n\r]+/', strip_tags((string) $option['additionalInfo'])) ?: [];
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part !== '') {
                    $facilities[] = self::normalizeLabel($part);
                }
            }
        }

        $hasOptionFacilities = !empty(array_filter($facilities));

        if (!$hasOptionFacilities && !empty($hotelAmenities)) {
            $facilities = array_merge($facilities, self::filterRoomRelevant($hotelAmenities));
        } elseif (!empty($hotelAmenities)) {
            $facilities = array_merge($facilities, self::filterRoomRelevant($hotelAmenities));
        }

        $facilities = array_values(array_unique(array_filter($facilities)));

        return array_slice($facilities, 0, 10);
    }

    public static function resolveOptionName(array $option): string
    {
        foreach (['name', 'displayName', 'title', 'roomName'] as $field) {
            $value = trim((string) ($option[$field] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        $details = $option['details'] ?? [];
        foreach (['name', 'roomName', 'type'] as $field) {
            $value = trim((string) ($details[$field] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    public static function parseRoomTitle(string $optionName): string
    {
        $name = trim($optionName);
        if ($name === '') {
            return '';
        }

        if (preg_match('/^(.+?)\s*-\s*(ROOM ONLY|BED\s*(?:&|AND)\s*BREAKFAST|HALF\s*BOARD|FULL\s*BOARD|ALL\s*INCLUSIVE)/i', $name, $matches)) {
            return ucwords(strtolower(trim($matches[1])));
        }

        if (preg_match('/^(.+?)\s*-\s*(.+)$/i', $name, $matches)) {
            return ucwords(strtolower(trim($matches[1])));
        }

        return ucwords(strtolower($name));
    }

    public static function parsePlanType(string $optionName): string
    {
        if (preg_match('/-\s*(ROOM ONLY|BED\s*(?:&|AND)\s*BREAKFAST|HALF\s*BOARD|FULL\s*BOARD|ALL\s*INCLUSIVE)\s*$/i', $optionName, $matches)) {
            return ucwords(strtolower(trim($matches[1])));
        }

        if (preg_match('/-\s*(.+)$/i', $optionName, $matches)) {
            return ucwords(strtolower(trim($matches[1])));
        }

        return 'Room Only';
    }

    public static function parseMaxGuests(string $roomTitle, int $fallback = 2): int
    {
        $title = strtoupper($roomTitle);

        if (str_contains($title, 'SINGLE')) {
            return 1;
        }
        if (str_contains($title, 'TRIPLE')) {
            return 3;
        }
        if (str_contains($title, 'QUAD')) {
            return 4;
        }
        if (str_contains($title, 'FAMILY')) {
            return 4;
        }
        if (str_contains($title, 'TWIN') || str_contains($title, 'DOUBLE')) {
            return 2;
        }

        return max(1, $fallback);
    }

    public static function isNonSmoking(array $option): bool
    {
        $details = $option['details'] ?? [];

        return array_key_exists('smoking', $details) && !$details['smoking'];
    }

    public static function optionPayload(array $option): array
    {
        return [
            'name'           => self::resolveOptionName($option),
            'description'    => $option['description'] ?? [],
            'details'        => $option['details'] ?? [],
            'additionalInfo' => $option['additionalInfo'] ?? '',
        ];
    }

    public static function iconClass(string $name): string
    {
        $key = strtolower(trim($name));

        $map = [
            'wi-fi' => 'fa-wifi',
            'wifi' => 'fa-wifi',
            'internet' => 'fa-wifi',
            'a/c' => 'fa-snowflake',
            'air conditioning' => 'fa-snowflake',
            'heating' => 'fa-temperature-high',
            'tv' => 'fa-tv',
            'television' => 'fa-tv',
            'shower' => 'fa-shower',
            'toiletries' => 'fa-pump-soap',
            'accessible' => 'fa-wheelchair',
            'non-smoking' => 'fa-smoking-ban',
            'smoking allowed' => 'fa-smoking',
            'parking' => 'fa-parking',
            'safe' => 'fa-lock',
            'kettle' => 'fa-coffee',
            'minibar' => 'fa-wine-bottle',
            'phone' => 'fa-phone',
            'hairdryer' => 'fa-wind',
            'housekeeping' => 'fa-broom',
            'smoke detector' => 'fa-bell',
        ];

        if (isset($map[$key])) {
            return 'fas ' . $map[$key];
        }

        foreach ($map as $label => $icon) {
            if (str_contains($key, $label)) {
                return 'fas ' . $icon;
            }
        }

        if (str_contains($key, 'wifi') || str_contains($key, 'wi-fi')) {
            return 'fas fa-wifi';
        }
        if (str_contains($key, 'air') && str_contains($key, 'condition')) {
            return 'fas fa-snowflake';
        }
        if (str_contains($key, 'heat')) {
            return 'fas fa-temperature-high';
        }
        if (str_contains($key, 'shower') || str_contains($key, 'bath')) {
            return 'fas fa-shower';
        }
        if (str_contains($key, 'toilet') || str_contains($key, 'soap')) {
            return 'fas fa-pump-soap';
        }
        if (str_contains($key, 'tv') || str_contains($key, 'television')) {
            return 'fas fa-tv';
        }
        if (str_contains($key, 'smoke')) {
            return 'fas fa-bell';
        }
        if (str_contains($key, 'towel') || str_contains($key, 'linen')) {
            return 'fas fa-broom';
        }

        return 'fas fa-check';
    }

    private static function filterRoomRelevant(array $amenities): array
    {
        $skipPatterns = [
            'year of construction',
            'year of most recent renovation',
            'total number of rooms',
            'number of floors',
            'single rooms',
            'double rooms',
            'executive rooms',
            'family rooms',
            'hotel',
            'bus/train',
            'nearest bus',
            'airport',
            '24-hour reception',
            '24-hour security',
            'lift access',
            'green tourism',
            'non-smoking establishment',
        ];

        $keywords = [
            'wifi', 'wi-fi', 'internet', 'television', 'tv', 'air', 'heat', 'shower',
            'toilet', 'bath', 'kettle', 'safe', 'minibar', 'desk', 'phone', 'hairdryer',
            'conditioning', 'towel', 'wheelchair', 'smoke detector', 'housekeeping',
            'linen', 'mobile phone',
        ];

        $matched = [];
        foreach ($amenities as $amenity) {
            $text = strtolower((string) $amenity);

            foreach ($skipPatterns as $skip) {
                if (str_contains($text, $skip)) {
                    continue 2;
                }
            }

            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $matched[] = self::normalizeLabel((string) $amenity);
                    break;
                }
            }
        }

        return $matched;
    }

    private static function normalizeLabel(string $label): string
    {
        $label = trim(strip_tags(html_entity_decode($label, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        $lower = strtolower($label);

        if ($lower === '') {
            return '';
        }

        if (str_contains($lower, 'wi-fi') || str_contains($lower, 'wifi')) {
            return 'Wi-Fi';
        }
        if (str_contains($lower, 'air conditioning') || str_contains($lower, 'air-condition')) {
            return 'A/C';
        }
        if (str_contains($lower, 'heating') || str_contains($lower, 'heater')) {
            return 'Heating';
        }
        if (preg_match('/\b(tv|television)\b/', $lower)) {
            return 'TV';
        }
        if (str_contains($lower, 'shower')) {
            return 'Shower';
        }
        if (str_contains($lower, 'toilet')) {
            return 'Toiletries';
        }
        if (str_contains($lower, 'hairdryer') || str_contains($lower, 'hair dryer')) {
            return 'Hairdryer';
        }
        if (str_contains($lower, 'smoke detector')) {
            return 'Smoke detector';
        }
        if (str_contains($lower, 'towel') || str_contains($lower, 'bed linen') || str_contains($lower, 'housekeeping')) {
            return 'Housekeeping';
        }
        if (str_contains($lower, 'wheelchair') || str_contains($lower, 'accessible')) {
            return 'Accessible';
        }
        if (str_contains($lower, 'mobile phone')) {
            return 'Phone';
        }

        if (strlen($label) > 28) {
            return substr($label, 0, 25) . '...';
        }

        return $label;
    }
}
