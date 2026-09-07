<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Hotel extends Model
{
    protected $table = 'hotels';

    public $timestamps = false;

    protected $fillable = [
        'aph_id',
        'holiday_id',
        'edin_id',
        'name',
        'priority',
        'company_code',
        'admin_id',
        'parent_id',
        'company_email',
        'airport_id',
        'terminal',
        'parking_type',
        'logo',
        'overview',
        'ShortDescription',
        'arival',
        'ArrivalTelephoneNumber',
        'arivalfront',
        'return_proc',
        'returnfront',
        'ReturnTelephoneNumber',
        'TransferDetails',
        'ParkingOptionTitle',
        'ParkingOptionDesc',
        'term_condition',
        'terms',
        'miles_from_airport',
        'ParkingRestrictions',
        'ImportantInformation',
        'processtime',
        'opening_time',
        'closing_time',
        'travel_time',
        'message',
        'bookingspace',
        'bookingmsg',
        'special_features',
        'featured',
        'recommended',
        'cancelable',
        'editable',
        'admin_edit',
        'share_percentage',
        'extra',
        'MinimumStayCharges',
        'DisabledFacilities',
        'address',
        'address2',
        'address3',
        'town',
        'post_code',
        'Latitude',
        'Longitude',
        'added_by',
        'parking_per_day_price',
        'awards',
        'extra_charges',
        'is_active',
        'is_flex',
        'api_code',
        'supplier_id',
        'added_on',
        'update_on',
        'removed',
        'removed_by',
        'max_discount',
        'sort_by',
    ];

    public function scopeActive(Builder $query): Builder
    {
        $query->where(function (Builder $inner) {
            if (Schema::hasColumn($this->getTable(), 'is_active')) {
                $inner->orWhere('is_active', 'Yes');
            }

            if (Schema::hasColumn($this->getTable(), 'status')) {
                $inner->orWhere('status', 'Yes');
            }
        });

        if (Schema::hasColumn($this->getTable(), 'removed')) {
            $query->where(function (Builder $inner) {
                $inner->where('removed', '!=', 'Yes')->orWhereNull('removed');
            });
        }

        return $query;
    }

    public static function activeByCompanyCode()
    {
        if (!Schema::hasColumn('hotels', 'company_code')) {
            return collect();
        }

        return static::query()
            ->active()
            ->whereNotNull('company_code')
            ->where('company_code', '!=', '')
            ->get()
            ->keyBy(fn ($hotel) => strtoupper(trim((string) $hotel->company_code)));
    }

    public static function catalogueLookup(): array
    {
        $map = [];

        foreach (static::activeByCompanyCode() as $hotel) {
            $code = strtoupper(trim((string) $hotel->company_code));
            if ($code !== '') {
                $map[$code] = $hotel;
            }
        }

        return $map;
    }

    public static function activeDiscountMap(): array
    {
        if (!Schema::hasColumn('hotels', 'company_code') || !Schema::hasColumn('hotels', 'max_discount')) {
            return [];
        }

        return static::query()
            ->active()
            ->whereNotNull('company_code')
            ->pluck('max_discount', 'company_code')
            ->toArray();
    }

    public function getLogoUrlAttribute(): ?string
    {
        $url = $this->getImageUrl('');

        return $url !== '' ? $url : null;
    }

    public function getImageUrl(?string $fallback = null): string
    {
        foreach ([$this->logo ?? null, $this->profile_image ?? null, $this->banner_image ?? null] as $candidate) {
            $url = self::formatImageUrl(is_string($candidate) ? $candidate : null);
            if ($url !== '') {
                return $url;
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

    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->hotel_name)) {
            return $this->hotel_name;
        }

        if (!empty($this->name)) {
            return $this->name;
        }

        return '';
    }

    public function airport()
    {
        return $this->belongsTo('App\Models\airport', 'airport_id');
    }
}
