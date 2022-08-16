<?php

namespace App\Http\Controllers;

use App\BatteryModel;
use App\DroneModel;
use App\PayloadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    private function imgUpload($request, $dir)
    {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $file->move('images/' . $dir . '/', $fileName);
        return $fileName;
    }

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

        $droneModel = new DroneModel;

        $data = $request->all();

        foreach ($data as $key => $value) {
            $droneModel->$key = $request->$key;
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move('images/drones/', $fileName);
            $droneModel->image = $fileName;
            // $droneModel->image = imgUpload($request, 'drones');
        }

        $droneModel->save();

        return response()->json([
            'message' => 'Drone Model Added successfully',
            'Drone' => $droneModel
        ], 201);
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
            $unique = '';
            if (DroneModel::find($id)->model_name != $request->model_name) {
                $unique = '|unique:drone_models';
            }
            $request->validate([
                'brand_name' => 'required',
                'model_name' => 'required' . $unique,
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
            ], 200);
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

        $payloadModel = new PayloadModel;

        $data = $request->all();

        foreach ($data as $key => $value) {
            if ($key == 'drone_id') {
                continue;
            }
            $payloadModel->$key = $request->$key;
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move('images/payloads/', $fileName);
            $payloadModel->image = $fileName;
        }

        $payloadModel->save();

        $string = "";

        foreach ($request->input('drone_id') as $value) {
            if (!DroneModel::find($value)->has_built_in_camera) {
                DroneModel::find($value)->attachPayloadModel($payloadModel);
            } else {
                $string .= $value . ',';
            }
        }

        return response()->json([
            'message' => 'Payload Model Added successfully',
            'Payload' => $payloadModel,
            'unattachableDrones' => 'These Drones => ' . $string . ' can\'t handle attachment'
        ], 201);
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
            $unique = '';
            if (PayloadModel::find($id)->model_name != $request->model_name) {
                $unique = '|unique:payload_models';
            }
            $request->validate([
                'brand_name' => 'required',
                'model_name' => 'required' . $unique,
                'type' => 'required',
                'drone_id' => 'required'
            ]);

            //change relationship table drone_id

            $payloadDroneAttachment = PayloadModel::find($id)->droneModel;

            for ($i = 0; $i < count($payloadDroneAttachment); $i++) {
                $payloadDroneAttachment[$i]->pivot->delete();
            }

            foreach ($request->input('drone_id') as $key => $value) {
                DroneModel::find($value)->attachPayloadModel($payload);
            }

            $data = $request->all();

            foreach ($data as $key => $value) {
                if ($key == 'drone_id') {
                    continue;
                }
                $payload->$key = $request->$key;
            }


            $payload->update();

            return response()->json([
                'message' => 'Payload Model Updated Successfully.',
                $payload
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

        $batteryModel = new BatteryModel;

        $data = $request->all();

        foreach ($data as $key => $value) {
            $batteryModel->$key = $request->$key;
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move('images/batteries/', $fileName);
            $batteryModel->image = $fileName;
        }

        $batteryModel->save();

        return response()->json([
            'message' => 'Battery Model Added successfully',
            'Battery' => $batteryModel
        ], 201);
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

            $unique = '';
            if (BatteryModel::find($id)->drone_model_id != $request->drone_model_id) {
                $unique = '|unique:battery_models';
            }
            $request->validate([
                'drone_model_id' => 'required' . $unique,
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
