<?php

namespace App\Http\Controllers;

use App\BatteryModel;
use App\DroneModel;
use App\PayloadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

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

    public function listDroneModels()
    {
        // if (!Auth::user()->isAdmin) {
        //     return response()->json([
        //         'message' => 'Unauthenticated user.'
        //     ]);
        // } else { // if user is Admin
        //     $drones = DroneModel::all();
        //     return $drones ? $drones : response()->json(['message' => 'No Drones Were Found']);
        // }
        $drones = DroneModel::orderBy('id')->get();
        return $drones ? $drones : response()->json(['message' => 'No Drones Were Found']);
    }

    public function addDroneModel(Request $request)
    {

        if (!Auth::user()->isAdmin) {
            return response()->json([
                'message' => 'Unauthenticated user.'
            ], Response::HTTP_UNAUTHORIZED);
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
            $droneModel->image = 'images/drones/' . $fileName;
        }

        $droneModel->save();

        return response()->json([
            'message' => 'Drone Model Added successfully',
            'Drone' => $droneModel
        ], 201);
    }

    public function updateDroneModel(Request $request, $id)
    {
        if (!Auth::user()->isAdmin) {
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
        // if (!Auth::user()->isAdmin) {
        //     return response()->json([
        //         'message' => 'Unauthenticated user.'
        //     ]);
        // }

        if (!Auth::user()->isAdmin) {
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

    public function listPayloadModels()
    {
        // if (!Auth::user()->isAdmin) {
        //     return response()->json([
        //         'message' => 'Unauthenticated user.'
        //     ]);
        // } else { // if user is Admin
        //     $payload = PayloadModel::all();
        //     return $payload ? $payload : response()->json(['message' => 'No Drones Were Found']);
        // }


        $payload = PayloadModel::orderBy('id')->get();
        return $payload ? $payload : response()->json(['message' => 'No Drones Were Found']);
    }

    public function addPayloadModel(Request $request)
    {

        if (!Auth::user()->isAdmin) {
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
            $payloadModel->image = 'images/payloads/' . $fileName;
        }

        $payloadModel->save();

        $string = "";
        $unattachableDrones = false;

        foreach ($request->input('drone_id') as $value) {
            // return DroneModel::find($value)->payloadModel();
            if (!DroneModel::find($value)->has_built_in_camera) {
                DroneModel::find($value)->attachPayloadModel($payloadModel);
            } else {
                $string .= $value . ',';
                $unattachableDrones = true;
            }
        }

        return response()->json([
            'message' => 'Payload Model Added successfully',
            'Payload' => $payloadModel,
            'isUnattachableDrones' => $unattachableDrones,
            'unattachableDrones' => strlen($string) ? 'These Drones => ' . $string . ' can\'t handle attachment' : 'All selected drones can handle attachment',
            'drone_id' => $request->input('drone_id')
        ], 201);
    }

    public function updatePayloadModel(Request $request, $id)
    {
        if (!Auth::user()->isAdmin) {
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
        if (!Auth::user()->isAdmin) {
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

    public function listBatteryModels()
    {
        // if (!Auth::user()->isAdmin) {
        //     return response()->json([
        //         'message' => 'Unauthenticated user.'
        //     ]);
        // } else { // if user is Admin
        //     $battery = BatteryModel::all();
        //     return $battery ? $battery : response()->json(['message' => 'No Drones Were Found']);
        // }
        $battery = BatteryModel::orderBy('id')->get();
        return $battery ? $battery : response()->json(['message' => 'No Drones Were Found']);
    }

    public function addBatteryModel(Request $request)
    {

        if (!Auth::user()->isAdmin) {
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
            $batteryModel->image = 'images/batteries/' . $fileName;
        }

        $batteryModel->save();

        return response()->json([
            'message' => 'Battery Model Added successfully',
            'Battery' => $batteryModel
        ], 201);
    }

    public function updateBatteryModel(Request $request, $id)
    {
        if (!Auth::user()->isAdmin) {
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
        if (!Auth::user()->isAdmin) {
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
