<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\Request;
use App\Models\airport;
use App\Models\airports_bookings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'airport_id' => 'required|exists:airports,id',
        ]);

        // Retrieve the airport and booking details
        $airport = airport::find($request->airport_id);


        // Store the email, airport ID, airport name, and reference number in the emails table
        Email::create([
            'email' => $request->email,
            'airport' => $request->airport_id,
            'airport_name' => $airport->name,
        ]);

        // Return a success response
        return response()->json(['status' => 'success']);
    }
   
}
