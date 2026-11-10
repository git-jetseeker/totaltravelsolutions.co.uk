<?php

namespace Tests\Feature;

use App\Http\Controllers\front\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CustomerPortalRoutesTest extends TestCase
{
    public function test_customer_portal_routes_are_registered_and_resolve_to_the_expected_actions(): void
    {
        $expectedRoutes = [
            'customer.dashboard' => ['GET', 'customer-dashboard', 'customerDashboard'],
            'customer.bookings' => ['GET', 'customer-dashboard/bookings', 'customerBookingHistory'],
            'customer.tickets' => ['GET', 'customer-dashboard/tickets', 'customerSupportTickets'],
            'customer.dashboard.logout' => ['POST', 'customer-dashboard/logout', 'customerDashboardLogout'],
            'manage_booking' => ['GET', 'manage-booking', 'manage_booking'],
            'booking_search' => ['POST', 'booking-search', 'booking_search'],
        ];

        foreach ($expectedRoutes as $name => [$method, $uri, $action]) {
            $route = Route::getRoutes()->getByName($name);

            $this->assertNotNull($route, "Route [{$name}] is missing.");
            $this->assertSame($uri, $route->uri());
            $this->assertContains($method, $route->methods());
            $this->assertSame(BookingController::class . '@' . $action, $route->getActionName());

            $matchedRoute = Route::getRoutes()->match(Request::create('/' . $uri, $method));
            $this->assertSame($name, $matchedRoute->getName());
        }
    }
    public function test_portal_pages_redirect_to_booking_lookup_without_a_verified_customer(): void
    {
        foreach ([
            'http://localhost/customer-dashboard',
            'http://localhost/customer-dashboard/bookings',
            'http://localhost/customer-dashboard/tickets',
        ] as $url) {
            $this->get($url)->assertRedirect(route('manage_booking'));
        }
    }

    public function test_customer_can_log_out_of_the_portal(): void
    {
        $this->withSession([
            'manage_booking_customer' => [
                'email' => 'customer@example.com',
                'verified_reference' => 'TTS-123456',
            ],
        ])->post('http://localhost/customer-dashboard/logout')
            ->assertRedirect(route('manage_booking'))
            ->assertSessionMissing('manage_booking_customer');
    }
}