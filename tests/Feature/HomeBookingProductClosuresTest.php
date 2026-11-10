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
