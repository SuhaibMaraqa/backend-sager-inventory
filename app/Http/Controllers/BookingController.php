<?php

namespace App\Http\Controllers;

use App\BatteryInventory;
use App\Booking;
use App\DroneInventory;
use App\PayloadInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->all()) {
            $booking = Booking::where($request->all())->first();
            return $booking ? $booking : response()->json(['message' => 'Booking Not Found']);
        } else {
            $booking = Booking::all();
            return $booking ? $booking : response()->json(['message' => 'No Bookings Were Found']);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->id != 2) {
            return response()->json([
                'message' => 'Only center users can book.'
            ]);
        }

        $request->validate([
            'inventory' => 'required',
            'drone_id' => 'required',
            'payload_id' => 'required',
            'battery_id' => 'required',
            'booking_date' => 'required|date',
        ]);

        if (!DroneInventory::find($request->drone_id)->activation) {
            return response()->json(['message' => 'Drone is not activated.']);
        } else if (!PayloadInventory::find($request->payload_id)->activation) {
            return response()->json(['message' => 'Payload is not activated.']);
        } else if (!BatteryInventory::find($request->battery_id)->activation) {
            return response()->json(['message' => 'Battery is not activated.']);
        }

        foreach (Booking::where('booking_date', '=', $request->booking_date)->get() as $key => $value) {
            if ($request->drone_id == $value->drone_id) {
                return response()->json(['message' => 'Drone is booked in the selected date.']);
            } else if ($request->payload_id == $value->payload_id) {
                return response()->json(['message' => 'Payload is booked in the selected date.']);
            } else if ($request->battery_id == $value->battery_id) {
                return response()->json(['message' => 'Battery is booked in the selected date.']);
            }
        }

        return response()->json([
            'message' => Booking::create([
                "user_id" => Auth::user()->id,
                "drone_id" => $request->input('drone_id'),
                "payload_id" => $request->input('payload_id'),
                "battery_id" => $request->input('battery_id'),
                "booking_date" => $request->input('booking_date'),
            ])
        ]);
    }
}
