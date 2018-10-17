<?php
namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportType;
use Illuminate\Http\Request;
use Input;
use Validator;

class ReportController extends Controller
{
    /**
     * Get all report
     *
     * @return Response
     */
    public function getAllReport(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        $page       = $request->page ? : 1;
        $limit      = $request->limit ? : 10;
        $offset     = 0;
        $order      = $request->order ? : 'created_at';
        $direction  = $request->direction ? : 'desc';

        try {
            $getReport = Report::with(['type', 'images']);
            if ($limit && $page > 0) {
                $offset = $limit * ($page - 1);
                $getReport = $getReport->offset($offset)->limit($limit);
            }

            $totalRow = $getReport->count();
            $getReport = $getReport->orderBy($order, $direction)->get();

            $response = [];
            $response['data'] = $getReport;
            $response['status'] = 200;
            $response['total'] = $totalRow;
            $response['current_page'] = $page;
            $response['total_page'] = ceil($totalRow/$limit);
        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }
        return response()->json($response);
    } 

    /**
     * Get my report
     *
     * @return Response
     */
    public function getMyReport(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        $page       = $request->page ? : 1;
        $limit      = $request->limit ? : 10;
        $offset     = 0;
        $order      = $request->order ? : 'created_at';
        $direction  = $request->direction ? : 'desc';

        try {
            $getReport = Report::with(['type', 'images'])->where('created_by', Auth::guard('api')->id());
            if ($limit && $page > 0) {
                $offset = $limit * ($page - 1);
                $getReport = $getReport->offset($offset)->limit($limit);
            }

            $totalRow = $getReport->count();
            $getReport = $getReport->orderBy($order, $direction)->get();

            $response = [];
            $response['status'] = 200;
            $response['data'] = $getReport;
            $response['total'] = $totalRow;
            $response['current_page'] = $page;
            $response['total_page'] = ceil($totalRow/$limit);
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
            $response['data'] = Report::with(['type', 'images'])->find($id);
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

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'long' => 'required',
            'lat' => 'required'
        ]);

        if ($validator->fails()) {
            $response['status'] = 400;
            $response['errorMessage'] = implode(" ", $validator->errors()->all());
            return response()->json($response);
        }

        \DB::beginTransaction();

        try {
            $checkReportType = ReportType::find($request->type_id);
            if (!$checkReportType)
                throw new \Exception('data type not found');

            $dataReport = [];
            $dataReport['type_id'] = $request->type_id;
            $dataReport['description'] = $request->description;
            $dataReport['location'] = $request->location;
            $dataReport['long'] = $request->long;
            $dataReport['lat'] = $request->lat;
            $dataReport['created_by'] = Auth::guard('api')->user()->id;
            $dataReport['updated_by'] = Auth::guard('api')->user()->id;
            $createReport = Report::create($dataReport);

            $response = [];
            $response['data'] = $createReport;
            $response['status'] = 200;

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
    public function updateReport(Request $request, $id)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        $rules = [];
        if ($request->type_id)
            $rules['type_id'] = 'integer';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response['status'] = 400;
            $response['errorMessage'] = implode(" ", $validator->errors()->all());
            return response()->json($response);
        }

        try {
            $dataReport = Report::where('created_by', Auth::guard('api')->id())->where('id', $id)->first();
            if (!$dataReport)
                throw new \Exception("Data report not found");
            
            if ($request->type_id)
                $dataReport->type_id = $request->type_id;
            
            if ($request->description)
                $dataReport->description = $request->description;
            
            if ($request->location)
                $dataReport->location = $request->location;
            
            if ($request->long)
                $dataReport->long = $request->long;
            
            if ($request->lat)
                $dataReport->lat = $request->lat;
            
            $dataReport->updated_by = Auth::guard('api')->user()->id;
            $dataReport->save();

            $response = [];
            $response['data'] = $dataReport;
            $response['status'] = 200;

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
    public function deleteReport(Request $request, $id)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        try {
            $dataReport = Report::where('created_by', Auth::guard('api')->id())->where('id', $id)->first();
            if (!$dataReport)
                throw new \Exception("Data report not found or already deleted");

            $dataReport->delete();

            $response = [];
            $response['status'] = 200;
            
        } catch (\Exception $e) {
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
