<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\ReportImage;
use App\Models\UserImage;

class FileUploadController extends Controller
{
    
  /**
     * Upload Image on folder report
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeImageReport(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        
        $validator = Validator::make($request->all(), [
            'report_id' => 'required|integer',
            'image' => 'required|image',
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
          $image = $request->file('image');
          $extension = $image->getClientOriginalExtension();
          Storage::disk('reportImage')->put($image->getFilename().'.'.$extension,  File::get($image));

          $dataReport = [];
          $dataReport['mime'] = $image->getClientMimeType();
          $dataReport['original_filename'] = $image->getClientOriginalName();
          $dataReport['filename'] = $image->getFilename().'.'.$extension;
          $dataReport['report_id'] = $request->report_id;
          $dataReport['created_by'] = Auth::guard('api')->user()->id;
          $dataReport['updated_by'] = Auth::guard('api')->user()->id;

          $imageUpload = ReportImage::create($dataReport);

          $response = [];
          $response['status'] = 201;
          $response['filename'] = $dataReport['filename'];
          $response['report_id'] = $dataReport['report_id'];

          \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Upload Image on folder user
     *
     * @param  Request  $request
     * @return Response
     */
    public function storeImageUser(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'image' => 'required|image',
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
          $image = $request->file('image');
          $extension = $image->getClientOriginalExtension();
          Storage::disk('userImage')->put($image->getFilename().'.'.$extension,  File::get($image));

          $dataReport = [];
          $dataReport['mime'] = $image->getClientMimeType();
          $dataReport['original_filename'] = $image->getClientOriginalName();
          $dataReport['filename'] = $image->getFilename().'.'.$extension;
          $dataReport['user_id'] = $request->user_id;
          $dataReport['created_by'] = Auth::guard('api')->user()->id;
          $dataReport['updated_by'] = Auth::guard('api')->user()->id;

          $imageUpload = UserImage::create($dataReport);

          $response = [];
          $response['status'] = 201;
          $response['filename'] = $dataReport['filename'];
          $response['user_id'] = $dataReport['user_id'];

          \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
