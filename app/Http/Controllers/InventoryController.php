<?php

namespace App\Http\Controllers;

use App\BatteryInventory;
use App\DroneInventory;
use App\DroneModel;
use App\PayloadModel;
use App\BatteryModel;
use App\PayloadInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function indexDrone()
    {
        $drones = [];
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ], Response::HTTP_UNAUTHORIZED);
        } else if (Auth::user()->role_id == 2) { // if user is Center



            foreach (DroneInventory::latest()->get() as $drone) {

                $drone->image = asset($drone->image);
                // return $drone->image;

                $droneModel = DroneModel::find($drone->drone_model_id, [
                    'brand_name', 'model_name', 'has_built_in_camera', 'image', 'wind_speed',
                    'temprature', 'weight', 'max_flight_time', 'range'
                ]);

                $mergedDrone = array_merge($drone->toArray(), $droneModel->toArray());
                array_push($drones, $mergedDrone);
            }
        } else if (Auth::user()->role_id == 3) { //if user is Pilot

            foreach (DroneInventory::where('user_id', Auth::user()->id)->latest()->get() as $drone) {

                $drone->image = asset($drone->image);

                $droneModel = DroneModel::find($drone->drone_model_id, [
                    'brand_name', 'model_name', 'has_built_in_camera', 'wind_speed',
                    'temprature', 'weight', 'max_flight_time', 'range', 'image'
                ]);

                $mergedDrone = array_merge($drone->toArray(), $droneModel->toArray());
                array_push($drones, $mergedDrone);
            }
        }

        return response()->json($drones, 200);
    }

    public function showDrone($id)
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        } else if (DroneInventory::find($id)) {
            if (DroneInventory::find($id)->user_id == Auth::user()->id) {

                $drone = DroneInventory::find($id);

                $droneModel = DroneModel::find($drone->drone_model_id, [
                    'brand_name', 'model_name', 'has_built_in_camera', 'wind_speed',
                    'temprature', 'weight', 'max_flight_time', 'range', 'image'
                ]);

                return array_merge($drone->toArray(), $droneModel->toArray());
            } else {
                return response()->json(['message' => 'Not Accessible by you.']);
            }
        } else {
            return response()->json(['message' => 'Drone Not Found']);
        }
    }

    public function addDrone(Request $request)
    {

        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $request->validate([
            'drone_model_id' => 'required',
            'name' => 'required',
            'serial_number' => 'required|unique:drone_inventories',
            'purchase_date' => 'required|date',
            'registration_date' => 'required|date',
            'insurance_status' => 'required',
            'physical_status' => 'required',
            // 'activation' => 'required'
        ]);

        return response()->json([
            'message' => 'Drone Added successfully',
            'Drone' => DroneInventory::create([
                "drone_model_id" => $request->input('drone_model_id'),
                // "user_id" => Auth::user()->id,
                "user_id" => $request->input('user_id'),
                "name" => $request->input('name'),
                "serial_number" => $request->input('serial_number'),
                "purchase_date" => $request->input('purchase_date'),
                "registration_date" => $request->input('registration_date'),
                "insurance_status" => $request->input('insurance_status'),
                "physical_status" => $request->input('physical_status'),
                "activation" => $request->input('physical_status') == 'Airworthy' && $request->input('insurance_status'),
            ])
        ], 201);
    }

    public function updateDrone(Request $request, $id)
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $drone = DroneInventory::find($id);
        if (!$drone) {
            return response()->json([
                'message' => 'Drone Not Found.'
            ]);
        } else {

            $unique = '';
            if (DroneInventory::find($id)->serial_number != $request->serial_number) {
                $unique = '|unique:drone_inventories';
            }

            $request->validate([
                'drone_model_id' => 'required',
                'name' => 'required',
                'serial_number' => 'required' . $unique,
                'purchase_date' => 'required|date',
                'registration_date' => 'required|date',
                'insurance_status' => 'required',
                'physical_status' => 'required',
            ]);

            $data = $request->all();

            foreach ($data as $key => $value) {
                $drone->$key = $request->$key;
            }
            $drone->activation = $request->input('physical_status') == 'Airworthy' && $request->input('insurance_status');

            $drone->user_id = Auth::user()->id;

            $drone->update();

            return response()->json([
                'message' => 'Drone Model Updated Successfully.'
            ]);
        }
    }

    public function deleteDrone($id)
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $battery = DroneInventory::find($id);

        if (!$battery) {

            return response()->json([
                'message' => 'Drone Not Found.'
            ]);
        } else if (DroneInventory::find($id)->user_id == Auth::user()->id) {
            $battery->delete();

            return response()->json([
                'message' => 'Drone Deleted Successfully.'
            ]);
        } else {
            return response()->json(['message' => 'Not Accessible by you.']);
        }
    }

    public function indexPayload()
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        } else if (Auth::user()->role_id == 2) {
            $payloads = [];

            foreach (PayloadInventory::latest()->get() as $payload) {

                $payloadModel = PayloadModel::find($payload->payload_model_id, [
                    'brand_name', 'model_name', 'type', 'image'
                ]);

                $mergedPayload = array_merge($payload->toArray(), $payloadModel->toArray());
                array_push($payloads, $mergedPayload);
            }

            return response()->json($payloads, 200);
        } else if (Auth::user()->role_id == 3) {
            $payloads = [];

            foreach (PayloadInventory::where('user_id', Auth::user()->id)->latest()->get() as $payload) {

                $payloadModel = PayloadModel::find($payload->payload_model_id, [
                    'brand_name', 'model_name', 'type', 'image'
                ]);

                $mergedPayload = array_merge($payload->toArray(), $payloadModel->toArray());
                array_push($payloads, $mergedPayload);
            }
            return response()->json($payloads, 200);
        }
    }

    public function showPayload($id)
    {
        $payload = PayloadInventory::find($id);

        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        } else if ($payload) {

            if ($payload->user_id == Auth::user()->id) {

                $payloadModel = PayloadModel::find($payload->payload_model_id, [
                    'brand_name', 'model_name', 'type', 'image'
                ]);

                return array_merge($payload->toArray(), $payloadModel->toArray());
            } else {
                return response()->json(['message' => 'Not Accessible by you.']);
            }
        } else {
            return response()->json(['message' => 'Payload Not Found']);
        }
    }

    public function addPayload(Request $request)
    {

        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $request->validate([
            'payload_model_id' => 'required',
            'name' => 'required',
            'serial_number' => 'required|unique:payload_inventories',
            'purchase_date' => 'required|date',
            'registration_date' => 'required|date',
            'insurance_status' => 'required',
            'physical_status' => 'required',
        ]);

        return response()->json([
            'message' => 'Payload Added successfully',
            'Drone' => PayloadInventory::create([
                "payload_model_id" => $request->input('payload_model_id'),
                // "user_id" => Auth::user()->id,
                "user_id" => $request->input('user_id'),
                "name" => $request->input('name'),
                "serial_number" => $request->input('serial_number'),
                "purchase_date" => $request->input('purchase_date'),
                "registration_date" => $request->input('registration_date'),
                "insurance_status" => $request->input('insurance_status'),
                "physical_status" => $request->input('physical_status'),
                "activation" => $request->input('physical_status') == 'Airworthy' && $request->input('insurance_status'),
            ])
        ], 201);
    }

    public function updatePayload(Request $request, $id)
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $payload = PayloadInventory::find($id);
        if (!$payload) {
            return response()->json([
                'message' => 'Payload Not Found.'
            ]);
        } else {

            $unique = '';
            if (PayloadInventory::find($id)->serial_number != $request->serial_number) {
                $unique = '|unique:payload_inventories';
            }

            $request->validate([
                'payload_model_id' => 'required',
                'user_id' => 'required',
                'name' => 'required',
                'serial_number' => 'required' . $unique,
                'purchase_date' => 'required|date',
                'registration_date' => 'required|date',
                'insurance_status' => 'required',
                'physical_status' => 'required',
            ]);

            $data = $request->all();

            foreach ($data as $key => $value) {
                $payload->$key = $request->$key;
            }

            $payload->user_id = Auth::user()->id;
            $payload->activation = $request->input('physical_status') == 'Airworthy' && $request->input('insurance_status');

            $payload->update();

            return response()->json([
                'message' => 'Payload Model Updated Successfully.'
            ]);
        }
    }

    public function deletePayload($id)
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $payload = PayloadInventory::find($id);
        if (!$payload) {
            return response()->json([
                'message' => 'Payload Not Found.'
            ]);
        } else if (PayloadInventory::find($id)->user_id == Auth::user()->id) {
            $payload->delete();

            return response()->json([
                'message' => 'Payload Deleted Successfully.'
            ]);
        } else {
            return response()->json(['message' => 'Not Accessible by you.']);
        }
    }

    public function indexBatteries()
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        } else if (Auth::user()->role_id == 2) {
            $batteries = [];

            foreach (BatteryInventory::latest()->get() as $battery) {

                $batteryModel = BatteryModel::find($battery->battery_model_id, [
                    'brand_name', 'model_name', 'drone_model_id', 'maximum_num_of_cycles', 'image'
                ]);

                $mergedBattery = array_merge($battery->toArray(), $batteryModel->toArray());
                array_push($batteries, $mergedBattery);
            }

            return response()->json($batteries, 200);
        } else if (Auth::user()->role_id == 3) {
            $batteries = [];

            foreach (BatteryInventory::where('user_id', Auth::user()->id)->latest()->get() as $battery) {

                $batteryModel = BatteryModel::find($battery->battery_model_id, [
                    'brand_name', 'model_name', 'drone_model_id', 'maximum_num_of_cycles', 'image'
                ]);

                $mergedBattery = array_merge($battery->toArray(), $batteryModel->toArray());
                array_push($batteries, $mergedBattery);
            }

            return response()->json($batteries, 200);
        }
    }

    public function showBattery($id)
    {
        $battery = BatteryInventory::find($id);

        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        } else if ($battery) {
            if ($battery->user_id == Auth::user()->id) {

                $batteryModel = BatteryModel::find($battery->battery_model_id, [
                    'brand_name', 'model_name', 'drone_model_id', 'maximum_num_of_cycles', 'image'
                ]);

                return array_merge($battery->toArray(), $batteryModel->toArray());
            } else {
                return response()->json(['message' => 'Not Accessible by you.']);
            }
        } else {
            return response()->json(['message' => 'Battery Not Found']);
        }
    }

    public function addBattery(Request $request)
    {

        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $request->validate([
            'battery_model_id' => 'required',
            'name' => 'required',
            'serial_number' => 'required|unique:battery_inventories',
            'purchase_date' => 'required|date',
            'registration_date' => 'required|date',
            'physical_status' => 'required',
            'number_of_cycles' => 'required|integer',
            'activation' => 'required'
        ]);

        return response()->json([
            'message' => 'Battery Added successfully',
            'Battery' => BatteryInventory::create([
                "battery_model_id" => $request->input('battery_model_id'),
                // "user_id" => Auth::user()->id,
                "user_id" => $request->input('user_id'),
                "name" => $request->input('name'),
                "serial_number" => $request->input('serial_number'),
                "purchase_date" => $request->input('purchase_date'),
                "registration_date" => $request->input('registration_date'),
                "physical_status" => $request->input('physical_status'),
                'number_of_cycles' => $request->input('number_of_cycles'),
                "activation" => $request->input('activation'),
            ])
        ], 201);
    }

    public function updateBattery(Request $request, $id)
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $battery = BatteryInventory::find($id);
        if (!$battery) {
            return response()->json([
                'message' => 'Battery Not Found.'
            ]);
        } else {

            $unique = '';
            if (BatteryInventory::find($id)->serial_number != $request->serial_number) {
                $unique = '|unique:battery_inventories';
            }

            $request->validate([
                'battery_model_id' => 'required',
                'user_id' => 'required',
                'name' => 'required',
                'serial_number' => 'required' . $unique,
                'purchase_date' => 'required|date',
                'registration_date' => 'required|date',
                'physical_status' => 'required',
                'number_of_cycles' => 'required|integer',
                'activation' => 'required'
            ]);

            $data = $request->all();

            foreach ($data as $key => $value) {
                $battery->$key = $request->$key;
            }


            if ($battery->number_of_cycles > BatteryModel::find($battery->battery_model_id)->value('maximum_num_of_cycles')) { // maximum_num_of_cycles
                $battery->physical_status = 'Retired';
            }

            $battery->user_id = Auth::user()->id;

            $battery->update();

            return response()->json([
                'message' => 'Battery Model Updated Successfully.'
            ]);
        }
    }

    public function deleteBattery($id)
    {
        if (Auth::user()->role_id == 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $battery = BatteryInventory::find($id);
        if (!$battery) {
            return response()->json([
                'message' => 'Battery Not Found.'
            ]);
        } else if (BatteryInventory::find($id)->user_id == Auth::user()->id) {
            $battery->delete();

            return response()->json([
                'message' => 'Battery Deleted Successfully.'
            ]);
        } else {
            return response()->json(['message' => 'Not Accessible by you.']);
        }
    }
}
