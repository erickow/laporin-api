<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ReportController extends Controller
{
    /**
     * Get all report
     *
     * @return Response
     */
    public function getAllReport()
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        try {
            $response = Report::all();
            $response['status'] = 200;

        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Get report By Id
     *
     * @param  Request  $request
     * @return Response
     */
    public function getReportById($id)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        try {
            $response = [];
            $response = Report::find($id);
            $response['status'] = 200;

        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }
    
    /**
     * Create a new report
     *
     * @param  Request  $request
     * @return Response
     */
    public function createReport(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'long' => 'required',
            'lat' => 'required',
            'created_by' => 'required|integer',
            'updated_by' => 'required|integer',
        ]);

        if(!Auth::guard('api')->check()) {
          $response['status'] = 400;
          $response['errorMessage'] = "Token is wrong, please try again later";
          return response()->json($response);
        }elseif ($validator->fails()) {
          $response['status'] = 400;
          $response['errorMessage'] = implode(" ", $validator->errors()->all());
          return response()->json($response);
        }

        \DB::beginTransaction();

        try {

            $dataReport = [];
            $dataReport['type_id'] = $request->type_id;
            $dataReport['description'] = $request->description;
            $dataReport['location'] = $request->location;
            $dataReport['long'] = $request->long;
            $dataReport['lat'] = $request->lat;
            $dataReport['created_by'] = $request->created_by;
            $dataReport['updated_by'] = $request->updated_by;

            $createUser = Report::create($dataReport);

            $response = [];
            $response['status'] = 201;
            $response['type_id'] = $dataReport['type_id'];
            $response['description'] = $dataReport['description'];
            $response['long'] = $dataReport['long'];
            $response['lat'] = $dataReport['lat'];

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Create a new report
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateReport(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'long' => 'required',
            'lat' => 'required',
            'created_by' => 'required|integer',
            'updated_by' => 'required|integer',
        ]);

        if(!Auth::guard('api')->check()) {
          $response['status'] = 400;
          $response['errorMessage'] = "Token is wrong, please try again later";
          return response()->json($response);
        }elseif ($validator->fails()) {
          $response['status'] = 400;
          $response['errorMessage'] = implode(" ", $validator->errors()->all());
          return response()->json($response);
        }

        try {

            $dataReport = Report::find($request->id);
            $dataReport->type_id = $request->type_id;
            $dataReport->description = $request->description;
            $dataReport->location = $request->location;
            $dataReport->long = $request->long;
            $dataReport->lat = $request->lat;
            $dataReport->created_by = $request->created_by;
            $dataReport->updated_by = $request->updated_by;

            $dataReport->save();

            $response = $dataReport;
            $response['status'] = 201;

        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Delete report by Id
     *
     * @param Request
     * @return Response
     */
    public function deleteReport(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        try {
            $response = [];
            $deleteData = Report::find($request->id);
            if(!$deleteData == null){
              $deleteData->delete();
              $response['status'] = 200;
            }else{
              $response['errorMessage'] = "id not found, please try again later";
              $response['status'] = 400;
            }
            
            

        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
