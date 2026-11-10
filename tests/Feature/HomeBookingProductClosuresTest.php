<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;
use Tests\TestCase;

class HomeBookingProductClosuresTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'products.lounge_close' => false,
            'products.hotel_close' => false,
            'products.car_hire_close' => false,
            'services.enjoytravel.enabled' => true,
        ]);
    }

    public function test_closed_products_show_coming_soon_in_the_home_booking_widget(): void
    {
        config([
            'products.lounge_close' => true,
            'products.hotel_close' => true,
            'products.car_hire_close' => true,
        ]);

        $html = $this->homeSearchForm()->render();

        $this->assertStringContainsString('Airport lounges are on the way', $html);
        $this->assertStringContainsString('Airport hotels are on the way', $html);
        $this->assertStringContainsString('Airport car hire is on the way', $html);
        $this->assertStringNotContainsString('id="search_form_lounge"', $html);
        $this->assertStringNotContainsString('id="search_form_hotel"', $html);
        $this->assertStringNotContainsString('id="search_form_carhire"', $html);
    }

    public function test_open_products_keep_their_existing_booking_forms(): void
    {
        $html = $this->homeSearchForm()->render();

        $this->assertStringContainsString('id="search_form_lounge"', $html);
        $this->assertStringContainsString('id="search_form_hotel"', $html);
        $this->assertStringContainsString('id="search_form_carhire"', $html);
        $this->assertStringNotContainsString('Airport lounges are on the way', $html);
        $this->assertStringNotContainsString('Airport hotels are on the way', $html);
    }

    public function test_direct_product_form_includes_obey_close_flags(): void
    {
        config([
            'products.lounge_close' => true,
            'products.hotel_close' => true,
            'products.car_hire_close' => true,
        ]);

        $loungeHtml = view('layouts.lounges_form')->render();
        $hotelHtml = view('layouts.hotels_form', ['errors' => new ViewErrorBag()])->render();
        $carHireHtml = view('layouts.carhire_form')->render();
        $legacyLoungeHtml = view('layouts.search_form_result_lounge', [
            'airports' => new Collection(),
        ])->render();

        $this->assertStringContainsString('Airport lounges are on the way', $loungeHtml);
        $this->assertStringContainsString('Airport hotels are on the way', $hotelHtml);
        $this->assertStringContainsString('Airport lounges are on the way', $legacyLoungeHtml);
        $this->assertStringContainsString('Airport car hire is on the way', $carHireHtml);
        $this->assertStringNotContainsString('id="search_form_lounge"', $loungeHtml);
        $this->assertStringNotContainsString('id="search_form_hotel"', $hotelHtml);
        $this->assertStringNotContainsString('id="search_form_carhire"', $carHireHtml);
        $this->assertStringNotContainsString('id="search_form_1"', $legacyLoungeHtml);
    }

    private function homeSearchForm(): View
    {
        return view('layouts.search_form', [
            'airports' => new Collection([(object) ['id' => 20, 'name' => 'Test Airport']]),
            'errors' => new ViewErrorBag(),
            'skipRefTracking' => true,
            'showClosedProductsComingSoon' => true,
        ]);
    }
}
