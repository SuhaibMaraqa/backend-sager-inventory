<?php

namespace App\Http\Controllers;

use App\BatteryModel;
use App\DroneModel;
use App\PayloadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function addDroneModel(Request $request)
    {

        if (Auth::user()->role_id != 1) {
            return ([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $request->validate([
            'brand_name' => 'required',
            'model_name' => 'required|unique:drone_models',
            'has_built_in_camera' => 'required|boolean',
            'wind_speed' => 'required',
            'temprature' => 'required',
            'weight' => 'required',
            'max_flight_time' => 'required',
            'max_height' => 'required',
            'range' => 'required'
        ]);

        return DroneModel::create([
            "brand_name" => $request->input('brand_name'),
            "model_name" => $request->input('model_name'),
            "has_built_in_camera" => $request->input('has_built_in_camera'),
            "wind_speed" => $request->input('wind_speed'),
            "temprature" => $request->input('temprature'),
            "weight" => $request->input('weight'),
            "max_flight_time" => $request->input('max_flight_time'),
            "max_height" => $request->input('max_height'),
            "range" => $request->input('range')
        ]);
    }

    public function updateDroneModel(Request $request, $id)
    {
        if (Auth::user()->role_id != 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $drone = DroneModel::find($id);
        if (!$drone) {
            return response()->json([
                'message' => 'Drone Model Not Found.'
            ]);
        } else {
            $request->validate([
                'brand_name' => 'required',
                'model_name' => 'required|unique:drone_models',
                'has_built_in_camera' => 'required|boolean',
                'wind_speed' => 'required',
                'temprature' => 'required',
                'weight' => 'required',
                'max_flight_time' => 'required',
                'max_height' => 'required',
                'range' => 'required'
            ]);

            $data = $request->all();

            foreach ($data as $key => $value) {
                $drone->$key = $request->$key;
            }

            $drone->update();

            return response()->json([
                'message' => 'Drone Model Updated Successfully.'
            ]);
        }
    }

    public function deleteDroneModel($id)
    {
        if (Auth::user()->role_id != 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $drone = DroneModel::find($id);
        if (!$drone) {
            return response()->json([
                'message' => 'Drone Model Not Found.'
            ]);
        } else {

            $drone->delete();

            return response()->json([
                'message' => 'Drone Model Deleted Successfully.'
            ]);
        }
    }

    public function addPayloadModel(Request $request)
    {

        if (Auth::user()->role_id != 1) {
            return ([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $request->validate([
            'brand_name' => 'required',
            'model_name' => 'required|unique:payload_models',
            'type' => 'required',
            'drone_id' => 'required'
        ]);

        $payloadModel = PayloadModel::create([
            "brand_name" => $request->input('brand_name'),
            "model_name" => $request->input('model_name'),
            "type" => $request->input('type'),
        ]);

        $drone_id = $request->input('drone_id');

        return DroneModel::find($drone_id)->attachPayloadModel($payloadModel);
    }

    public function updatePayloadModel(Request $request, $id)
    {
        if (Auth::user()->role_id != 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $payload = PayloadModel::find($id);
        if (!$payload) {
            return response()->json([
                'message' => 'Payload Model Not Found.'
            ]);
        } else {
            $request->validate([
                'brand_name' => 'required',
                'model_name' => 'required|unique:payload_models',
                'type' => 'required',
                // 'drone_id' => 'required'
            ]);

            //change relationship table drone_id

            // if ($request->drone_id != ) {
            //     # code...
            // }
            // return DroneModel::find($request->drone_id)->payloadModel;

            $data = $request->all();

            foreach ($data as $key => $value) {
                $payload->$key = $request->$key;
            }

            $payload->update();

            return response()->json([
                'message' => 'Payload Model Updated Successfully.'
            ]);
        }
    }

    public function deletePayloadModel($id)
    {
        if (Auth::user()->role_id != 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $payload = PayloadModel::find($id);
        if (!$payload) {
            return response()->json([
                'message' => 'Payload Model Not Found.'
            ]);
        } else {

            $payload->delete();

            return response()->json([
                'message' => 'Payload Model Deleted Successfully.'
            ]);
        }
    }

    public function addBatteryModel(Request $request)
    {

        if (Auth::user()->role_id != 1) {
            return ([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $request->validate([
            'drone_model_id' => 'required|unique:battery_models',
            'brand_name' => 'required',
            'model_name' => 'required|unique:battery_models',
            'maximum_num_of_cycles' => 'required',
        ]);

        return BatteryModel::create([
            "brand_name" => $request->input('brand_name'),
            "model_name" => $request->input('model_name'),
            "drone_model_id" => $request->input('drone_model_id'),
            "maximum_num_of_cycles" => $request->input('maximum_num_of_cycles')
        ]);
    }

    public function updateBatteryModel(Request $request, $id)
    {
        if (Auth::user()->role_id != 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $battery = BatteryModel::find($id);
        if (!$battery) {
            return response()->json([
                'message' => 'Battery Model Not Found.'
            ]);
        } else {
            $request->validate([
                'drone_model_id' => 'required' . BatteryModel::find($id)->drone_model_id == $request->drone_model_id ? '' : '|unique:battery_models',
                'brand_name' => 'required',
                'model_name' => 'required|unique:battery_models',
                'maximum_num_of_cycles' => 'required',
            ]);

            $data = $request->all();

            foreach ($data as $key => $value) {
                $battery->$key = $request->$key;
            }

            $battery->update();

            return response()->json([
                'message' => 'Battery Model Updated Successfully.'
            ]);
        }
    }

    public function deleteBatteryModel($id)
    {
        if (Auth::user()->role_id != 1) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ]);
        }

        $battery = BatteryModel::find($id);
        if (!$battery) {
            return response()->json([
                'message' => 'Battery Model Not Found.'
            ]);
        } else {

            $battery->delete();

            return response()->json([
                'message' => 'Battery Model Deleted Successfully.'
            ]);
        }
    }
}
