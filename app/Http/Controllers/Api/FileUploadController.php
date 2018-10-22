<?php
namespace App\Http\Controllers\Api;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\UserImage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Validator;

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

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        $validator = Validator::make($request->all(), [
            'report_id' => 'required',
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            $response['status'] = 400;
            $response['errorMessage'] = implode(" ", $validator->errors()->all());
            return response()->json($response);
        }

        \DB::beginTransaction();
        try {
            $dataReport = Report::where('created_by', Auth::guard('api')->id())->where('id', $request->report_id)->first();
            if (!$dataReport)
                throw new \Exception("Data report not found");

            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = date("Y/m/d/") . str_slug($image->getFilename()) . '.'. $extension;
            $fileimage = File::get($image);
            Storage::disk('public')->put($filename, $fileimage);

            $dataReport = [];
            $dataReport['mime'] = $image->getClientMimeType();
            $dataReport['original_filename'] = $image->getClientOriginalName();
            $dataReport['filename'] = $filename;
            $dataReport['report_id'] = $request->report_id;
            $dataReport['created_by'] = Auth::guard('api')->user()->id;
            $dataReport['updated_by'] = Auth::guard('api')->user()->id;
            $imageUpload = ReportImage::create($dataReport);

            $response = [];
            $response['data'] = $dataReport;
            $response['status'] = 200;

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
            $response['status'] = 200;
            $response['filename'] = $dataReport['filename'];
            $response['user_id'] = $dataReport['user_id'];

            \DB::commit();
            } catch (\Exception $e) {
            \DB::rollback();
            $response['errorMessage'] = $e->getMessage();
        }
        return response()->json($response);
    }

    /**
    * Upload Image Pic
    *
    * @param  Request  $request
    * @return Response
    */
    public function storeImageMe(Request $request)
    {
        $response = ['status' => 500, 'errorMessage' => 'Internal Server Error'];

        if (!Auth::guard('api')->check()) {
            $response['status'] = 400;
            $response['errorMessage'] = "Token is wrong, please try again later";
            return response()->json($response);
        }

        $getUser = User::find(Auth::guard('api')->id());
        if (!$getUser) {
            $response['status'] = 400;
            $response['errorMessage'] = "Data user not found";
            return response()->json($response);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            $response['status'] = 400;
            $response['errorMessage'] = implode(" ", $validator->errors()->all());
            return response()->json($response);
        }

        \DB::beginTransaction();
        try {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = date("Y/m/d/") . str_slug($image->getFilename()) . '.'. $extension;
            $fileimage = File::get($image);
            Storage::disk('public')->put($filename, $fileimage);

            $user = Auth::guard('api')->user();
            $user->pic = $filename;
            $user->save();
            $getUser->pic = $filename;
            $getUser->save();
            
            $response = [];
            $response['data'] = $user;
            $response['status'] = 200;

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $response['errorMessage'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
