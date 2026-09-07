<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Lounges extends Model
{
    protected $table = 'lounges';

    public $timestamps = false;

    protected $fillable = [
        'lounge_code', 'company_code', 'email', 'profile_image', 'tripappimages', 'title',
        'airport_id', 'admin_id', 'terminal_id', 'price_per_person', 'price_per_child',
        'banner_images', 'address', 'city', 'post_code', 'telephone', 'information',
        'menu_drinks', 'menu_extras', 'menu_food', 'overview', 'luggageleftonsite',
        'facilities', 'food_beverage', 'unique_features', 'dresscode', 'facilitiesdisabled',
        'directions', 'checkintime', 'terms', 'introduction',
        'why_bookone', 'why_booktwo', 'why_bookthree', 'why_bookfour',
        'groups', 'children_permitted', 'share_percentage', 'max_discount', 'special_features',
        'featured', 'recommended', 'cancelable', 'editable',
        'free_drinks', 'free_food', 'suitable_for_int', 'landside', 'magazines',
        'noncancellable_nonrefundable', 'personal_lounge', 'phone', 'tv', 'wifi',
        'infant_age', 'child_age', 'adult_age', 'check_in_up_to',
        'children_allowed', 'disabled_access', 'flight_announcements', 'snacks',
        'upgrade_lounge_text', 'whats_included_drinks', 'whats_included_extras',
        'whats_included_food', 'business_facilities', 'smoking_permitted_txt',
        'flight_announce', 'smoking_permitted',
        'opening_time', 'closing_time', 'processtime', 'awards',
        'added_by', 'status', 'removed', 'removed_by', 'company_email',
        'created_at', 'updated_at', 'added_on', 'update_on',
    ];

    public static function catalogueLookup(): array
    {
        $query = static::query();

        if (Schema::hasColumn('lounges', 'status')) {
            $query->where('status', 'Yes');
        }

        if (Schema::hasColumn('lounges', 'removed')) {
            $query->where(function ($inner) {
                $inner->where('removed', '!=', 'Yes')->orWhereNull('removed');
            });
        }

        $map = [];

        foreach ($query->get() as $lounge) {
            foreach (['lounge_code', 'company_code'] as $field) {
                if (!isset($lounge->{$field})) {
                    continue;
                }

                $code = strtoupper(trim((string) $lounge->{$field}));
                if ($code !== '') {
                    $map[$code] = $lounge;
                }
            }
        }

        return $map;
    }

    public function awards()
    {
        return $this->hasMany(\App\Models\companies_assign_awards::class);
    }

    public function airport()
    {
        return $this->belongsTo(\App\Models\airport::class, 'airport_id');
    }

    public function getImageUrl(?string $fallback = null): string
    {
        foreach ([
            $this->profile_image ?? null,
            $this->logo ?? null,
            $this->banner_images ?? null,
        ] as $candidate) {
            $url = self::formatImageUrl(is_string($candidate) ? $candidate : null);
            if ($url !== '') {
                return $url;
            }
        }

        if (!empty($this->tripappimages)) {
            foreach (array_filter(array_map('trim', explode(',', (string) $this->tripappimages))) as $tripImage) {
                $url = self::formatImageUrl($tripImage);
                if ($url !== '') {
                    return $url;
                }
            }
        }

        if ($fallback !== null && $fallback !== '') {
            $fallbackUrl = self::formatImageUrl($fallback);
            if ($fallbackUrl !== '') {
                return $fallbackUrl;
            }
        }

        return asset('favicon.ico');
    }

    public static function formatImageUrl(?string $image): string
    {
        $image = trim((string) $image);

        if ($image === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $image)) {
            return $image;
        }

        if (str_starts_with($image, '//')) {
            return 'https:' . $image;
        }

        if (str_contains($image, 'libraryimages') || str_contains($image, 'imageLibrary')) {
            if (str_contains($image, 'cloudfront.net') || str_contains($image, 'imgix.net')) {
                return str_starts_with($image, 'http') ? $image : 'https://' . ltrim($image, '/');
            }

            $image = str_replace('imageLibrary', 'libraryimages', $image);

            return 'https://holidayextras.imgix.net/' . ltrim($image, '/');
        }

        if (str_starts_with($image, '/storage/') || str_starts_with($image, 'storage/')) {
            return asset(ltrim($image, '/'));
        }

        return 'https://dashboard.jetseekergroup.com/storage/app/' . ltrim($image, '/');
    }

    public static function normalizeImageList($images): array
    {
        if (is_string($images)) {
            $images = array_filter(array_map('trim', preg_split('/[;,]/', $images)));
        }

        $out = [];

        foreach ((array) $images as $image) {
            if (is_array($image)) {
                $image = $image['url'] ?? $image['src'] ?? $image['href'] ?? $image['link'] ?? reset($image);
            } elseif (is_object($image)) {
                $image = $image->url ?? $image->src ?? $image->href ?? null;
            }

            $url = self::formatImageUrl(is_string($image) ? $image : null);
            if ($url !== '' && !in_array($url, $out, true)) {
                $out[] = $url;
            }
        }

        return $out;
    }
}
