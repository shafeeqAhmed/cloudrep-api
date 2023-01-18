<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SingleFileUploadRequest;
use App\Models\SystemSetting;
// use FFMpeg\FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Str;

class FileController extends ApiController
{

    /**
     * @OA\Post(
     * path="/api/single-image-upload",
     * summary="Upload Single Image",
     * description="Upload Single Image",
     * operationId="uploadSingleImage",
     * tags={"Image"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass image data",
     *    @OA\JsonContent(
     *       required={"image","jwtToken"},
     *       @OA\Property(property="image", type="string", format="image", example="abc.png"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Image has been uploaded Successfully!',
     *       'data': 'http://127.0.0.1:8000/storage/user/profile/AvcIa0P5eHpPgr974WSXZi2yLgGzEa.png'
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Image Not Found")
     *        )
     *     ),
     * )
     */
    public function singleImageUpload(SingleFileUploadRequest $request)
    {

        // return response()->json($request->all());
        $request->validated();
        $image = uploadImage('image', $request->directory, 300, 300);
        return $this->respond([
            'status' => true,
            'message' => 'image has been upload successfully!',
            'data' => $image
        ]);
    }
    public function uploadAppLogo(Request $request)
    {
        if (request()->hasFile('image')) {
            $image = uploadAppLogo('image', 'uploads/app-logo', 200, 100);
            $record = SystemSetting::where('name', 'Logo')->first();
            if ($record) {
                $record->value = $image;
                $record->update();
                return $this->respond([
                    'status' => true,
                    'message' => 'Logo Update successfully!',
                    'data' => $image
                ]);
            } else {
                if ($image) {
                    $newSystemSetting = new SystemSetting();
                    $newSystemSetting->name = 'Logo';
                    $newSystemSetting->value = $image;
                    $newSystemSetting->save();
                    return $this->respond([
                        'status' => true,
                        'message' => 'Logo has been upload successfully!',
                        'data' => $image
                    ]);
                } else {
                    return $this->respond([
                        'status' => false,
                        'message' => 'Some thing going wrong!',
                        'data' => []
                    ]);
                }
            }
        }
    }

    public function getAppLogo(Request $request)
    {
        $record = SystemSetting::where('name', 'Logo')->first();
        if ($record) {
            return $this->respond([
                'status' => true,
                'message' => 'App Logo fetched successfully!',
                'data' => $record
            ]);
        } else {
            return 'no record found!';
        }
    }

    public function removeAppLogo(Request $request)
    {
        if ($request->has('logo_path')) {
            $s3 = removeLogo('uploads/app-logo', $request->logo_path);
            if ($s3) {
                $record = SystemSetting::where('name', 'Logo')->first();
                if ($record) {
                    $record->value = '';
                    $record->update();
                    return $this->respond([
                        'status' => true,
                        'message' => 'App Logo removed successfully!',
                        'data' => []
                    ]);
                }
            } else {
                return 'Something Wrong happened!';
            }
        }
    }

    public function audioUpload(Request $request)
    {
        $path = request()->file('audio')->store('uploads/audio', 's3');
        return  Storage::disk('s3')->url($path);
        // $audio = uploadAudio('audio', 'uploads/audio');
        // return $this->respond([
        //     'status' => true,
        //     'message' => 'Audio has been upload successfully!',
        //     'data' => $audio
        // ]);
    }

    public function videoUpload(Request $request)
    {

        $url = getVideoThumbnail('video', "uploads/lessons/thumbnails");
        $video = uploadVideo('video', 'uploads/lessons/videos');
        $duration = getVideoDuration('video');

        return $this->respond([
            'status' => true,
            'message' => 'video has been upload successfully!',
            'data' => [
                'videoUrl' => $video,
                'thumnail' => $url,
                'duration' => $duration
            ]
        ]);
    }
    public function removeVideo(Request $request)
    {
        $profile_photo_path = 'http://localhost/storage/videos/test.mp4';
        removeVideo('videos', $profile_photo_path);

        return response()->json('File removed');
    }

    public function removeSingleImage(Request $request)
    {
        removeImage('course', $request->old_path);
    }

    public function fileUpload()
    {
        $path = request()->file('file')->store('uploads/file', 's3');
        return  Storage::disk('s3')->url($path);
    }
}
