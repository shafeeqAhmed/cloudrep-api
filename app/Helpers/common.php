<?php

use App\Models\Campaign;
use App\Models\SystemSetting;
use App\Models\CampaignReporting;
use App\Models\TargetListing;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//CONTROLLER

if (!function_exists('uploadBase64Image')) {
    function uploadBase64Image($key, $directory)
    {

        if (request($key)) {

            $base64_encoded_string = request($key);
            $type = explode('/', mime_content_type($base64_encoded_string))[1];
            $img = preg_replace('/^data:image\/\w+;base64,/', '', $base64_encoded_string);
            $imageName = Str::random(30) . '.' . $type;
            $path = $directory . '/' . $imageName;
            Storage::disk('s3')->put($path, base64_decode($img));
            return Storage::disk('s3')->url($path);
        } else {
            return '';
        }
    }
}
if (!function_exists('uploadImage')) {
    function uploadImage($key, $directory, int $width, int $height)
    {
        if (request($key)) {
            $path = request()->file($key)->store($directory, 's3');
            return  Storage::disk('s3')->url($path);
        } else {
            return '';
        }
    }
}
if (!function_exists('uploadAppLogo')) {
    function uploadAppLogo($key, $directory, $width, $height)
    {
        if (request($key)) {
            $path = request()->file($key)->store($directory, 's3', $width, $height);
            return  Storage::disk('s3')->url($path);
        } else {
            return '';
        }
    }
}
if (!function_exists('uploadAudio')) {
    function uploadAudio($key, $directory)
    {
        if (request($key)) {
            $path = request()->file($key)->store($directory, 's3');
            return  Storage::disk('s3')->url($path);
        } else {
            return '';
        }
    }
}
if (!function_exists('getVideoThumbnail')) {
    function getVideoThumbnail($key, $directory)
    {
        if (request($key)) {
            $thumbnailName = Str::random(30) . '.png';
            $url = "$directory/$thumbnailName";
            FFMpeg::open(request()->file($key))
                ->getFrameFromSeconds(1)
                ->export()
                ->toDisk('public')
                ->save($url);
            return "storage/$url";
        } else {
            return '';
        }
    }
}
if (!function_exists('getVideoDuration')) {
    function getVideoDuration($key)
    {
        if (request($key)) {
            $duration = FFMpeg::open(request()->file($key))->getDurationInSeconds();
            return $duration;
        } else {
            return 0;
        }
    }
}


if (!function_exists('uploadVideo')) {
    function uploadVideo($key, $directory)
    {
        if (request($key)) {

            $path =  request()->file($key)->store($directory, 's3');
            return Storage::disk('s3')->url($path);
        } else {
            return '';
        }
    }
}

if (!function_exists('removeImage')) {
    function removeImage($directory, $old_img_url)
    {
        $arr = explode('/', $old_img_url);
        $path =  $directory . '/' . end($arr);
        // $awsUrl =  config('filesystems.disks.s3.url');
        // str_replace($awsUrl, '', $old_img_url);
        Storage::disk('s3')->delete($path);
    }
}
if (!function_exists('removeLogo')) {
    function removeLogo($directory, $old_img_url)
    {
        $arr = explode('/', $old_img_url);
        $path =  $directory . '/' . end($arr);
        // $awsUrl =  config('filesystems.disks.s3.url');
        // str_replace($awsUrl, '', $old_img_url);
        return Storage::disk('s3')->delete($path);
        // dd($test);

    }
}
if (!function_exists('removeFile')) {
    function removeFile($directory, $file_url)
    {
        $arr = explode('/', $file_url);
        $path =  $directory . '/' . end($arr);
        return Storage::disk('s3')->delete($path);
    }
}
if (!function_exists('removeVideo')) {
    function removeVideo($directory, $old_img_url)
    {
        $arr = explode('/', $old_img_url);
        $path = 'public/' . $directory . '/' . end($arr);
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
if (!function_exists('isFileExist')) {
    function isFileExist($directory, $url)
    {
        $arr = explode('/', $url);
        $path = 'public/' . $directory . '/' . end($arr);
        return Storage::exists($path);
    }
}
if (!function_exists('generateUuid')) {
    function generateUuid()
    {
        return Str::uuid()->toString();
    }
}
if (!function_exists('getRecordResponseArray')) {
    function getRecordResponseArray($data = [])
    {
        $message = 'Successfully fetched Records!';

        if (empty($data)) {
            $message = 'There is no Record Found!';
        }

        return  [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
    }
}
if (!function_exists('saveRecordResponseArray')) {
    function saveRecordResponseArray($isSaved, $data = [])
    {
        if ($isSaved) {
            return  [
                'status' => true,
                'message' => 'Created Record Successfully!',
                'data' => $data
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Something is going wrong please try again!',
                'data' => []
            ];
        }
    }
}
if (!function_exists('updateRecordResponseArray')) {
    function updateRecordResponseArray($isUpdate)
    {
        if ($isUpdate) {
            return  [
                'status' => true,
                'message' => 'Updated Successfully!',
                'data' => []
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Something is going wrong please try again!',
                'data' => []
            ];
        }
    }
}

//   function getBooleanStatus($request, $key, $default = false) {

//     if($request->has($key)) {
//         $value = $request->get($key);
//         return $value === true || $value === 'true' || $value === 1;
//     }
//     return $default;

// }


if (!function_exists('getSystemSetting')) {
    function getSystemSetting($name)
    {
        $record =  SystemSetting::whereIn('name', $name)->pluck('value');
        return $record ?? null;
    }
}
if (!function_exists('getNumber')) {
    function getNumber($digit)
    {
        $arr = [
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
        ];
        return $arr[$digit];
    }
}

if (!function_exists('getSetting')) {
    function getSetting($name)
    {
        return SystemSetting::where('name', $name)->value('value');
    }
}
if (!function_exists('getStandarDateTime')) {
    function getStandarDateTime($dateTime)
    {
        return date('Y-m-d H:i:s', strtotime($dateTime));
    }
}

if (!function_exists('countCampaignReportingRecord')) {
    function countCampaignReportingRecord($dateTime, $status, $userId)
    {
        $user = User::where('id', $userId)->first();
        $role = $user->getRoleNames();
        if ($status == 'completed') {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where([['call_status', $status], ['revenue', '0.00'], ['publisher_id', $userId]])->whereDate('created_at', $dateTime)->count();
            } else if ($role[0] == 'client') {
                return CampaignReporting::where([['call_status', $status], ['revenue', '0.00'], ['client_id', $userId]])->whereDate('created_at', $dateTime)->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where([['call_status', $status], ['revenue', '0.00']])->whereDate('created_at', $dateTime)->count();
            }
        } else if ($status == 'converted') {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where([['call_status', $status], ['revenue', '!=', '0.00'], ['publisher_id', $userId]])->whereDate('created_at', $dateTime)->count();
            } else if ($role[0] == 'client') {
                return CampaignReporting::where([['call_status', $status], ['revenue', '!=', '0.00'], ['client_id', $userId]])->whereDate('created_at', $dateTime)->count();
            } else if ($role[0] == 'admin') {

                return CampaignReporting::where([['call_status', $status], ['revenue', '!=', '0.00']])->whereDate('created_at', $dateTime)->count();
            }
        } else {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where([['call_status', $status], ['publisher_id', $userId]])->whereDate('created_at', $dateTime)->count();
            } else if ($role[0] == 'client') {
                return CampaignReporting::where([['call_status', $status], ['client_id', $userId]])->whereDate('created_at', $dateTime)->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where([['call_status', $status]])->whereDate('created_at', $dateTime)->count();
            }
        }
    }
}

if (!function_exists('countCallStatus')) {
    function countCallStatus($status, $publisherId, $clientId, $campaignId, $targetId, $userId, $dialed, $duplicated, $date, $selectedUser, $dateRange, $customFilter)
    {
        $user = User::where('id', $userId)->first();
        $role = $user->getRoleNames();
        $query = CampaignReporting::query();
        if ($status == 'completed') {
            if ($campaignId != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['revenue', '0.00']]);
                        }
                    }
                    $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['revenue', '0.00']]);
                }
            } else if ($publisherId != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['call_status', $status], ['revenue', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'publisher') {
                            $query = $query->where([['publisher', $selectedUser->id], ['call_status', $status], ['revenue', '0.00']]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['publisher_id', $publisherId], ['call_status', $status], ['revenue', '0.00']]);
                        }
                    }
                    $query = $query->where([['publisher_id', $publisherId], ['call_status', $status], ['revenue', '0.00']]);
                }
            } else if ($clientId != null) {
                if ($role[0] == 'client') {
                    $query = $query->where([['campaign_reportings.client_id', $userId], ['call_status', $status], ['revenue', '0.00']]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['campaign_reportings.client_id', $selectedUser->id], ['call_status', $status], ['revenue', '0.00']]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['campaign_reportings.client_id', $clientId], ['call_status', $status], ['revenue', '0.00']]);
                        }
                    }
                    $query = $query->where([['campaign_reportings.client_id', $clientId], ['call_status', $status], ['revenue', '0.00']]);
                }
            } else if ($targetId != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['target_id', $targetId], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['target_id', $targetId], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['target_id', $targetId], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['target_id', $targetId], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['target_id', $targetId], ['call_status', $status], ['revenue', '0.00']]);
                        }
                    }
                    $query = $query->where([['target_id', $targetId], ['call_status', $status], ['revenue', '0.00']]);
                }
            } else if ($dialed != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['dialed', $dialed], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['dialed', $dialed], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['dialed', $dialed], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['dialed', $dialed], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['dialed', $dialed], ['call_status', $status], ['revenue', '0.00']]);
                        }
                    }
                    $query = $query->where([['dialed', $dialed], ['call_status', $status], ['revenue', '0.00']]);
                }
            } else if ($duplicated !== null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['revenue', '0.00']]);
                        }
                    }
                    $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['revenue', '0.00']]);
                }
            } else if ($date !== null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['revenue', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['revenue', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['revenue', '0.00']]);
                        }
                    }
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['revenue', '0.00']]);
                }
            }
        } else if ($status == 'converted') {
            if ($campaignId != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        }
                    }
                    $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                }
            } else if ($publisherId != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'publisher') {
                            $query = $query->where([['publisher_id', $selectedUser->id], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['publisher_id', $publisherId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        }
                    }
                    $query = $query->where([['publisher_id', $publisherId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                }
            } else if ($clientId != null) {
                if ($role[0] == 'client') {
                    $query = $query->where([['campaign_reportings.client_id', $userId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['campaign_reportings.client_id', $selectedUser->id], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['campaign_reportings.client_id', $clientId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        }
                    }
                    $query = $query->where([['campaign_reportings.client_id', $clientId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                }
            } else if ($targetId != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        }
                    }
                    $query = $query->where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                }
            } else if ($dialed != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['dialed', $dialed], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['dialed', $dialed], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['dialed', $dialed], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['dialed', $dialed], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['dialed', $dialed], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        }
                    }
                    $query = $query->where([['dialed', $dialed], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                }
            } else if ($duplicated !== null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['duplicate', $duplicated], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['duplicate', $duplicated], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        }
                    }
                    $query = $query->where([['duplicate', $duplicated], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                }
            } else if ($date !== null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', 'completed'], ['revenue', '!=', '0.00'], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                        }
                    }
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', 'completed'], ['revenue', '!=', '0.00']]);
                }
            }
        } else {
            if ($campaignId != null) {
                if ($role[0] == 'publisher') {
                    $query =  $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query =  $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query =  $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query =  $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query =  $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status]]);
                        }
                    }
                    $query =  $query->where([['campaign_reportings.campaign_id', $campaignId], ['call_status', $status]]);
                }
            } else if ($publisherId != null) {
                if ($role[0] == 'publisher') {
                    $query =  $query->where([['call_status', $status], ['publisher_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'publisher') {
                            $query =  $query->where([['publisher_id', $selectedUser->id], ['call_status', $status]]);
                        } else if ($role[0] == 'admin') {
                            $query =  $query->where([['publisher_id', $publisherId], ['call_status', $status]]);
                        }
                    }
                    $query =  $query->where([['publisher_id', $publisherId], ['call_status', $status]]);
                }
            } else if ($clientId != null) {
                if ($role[0] == 'client') {
                    $query =  $query->where([['campaign_reportings.client_id', $userId], ['call_status', $status]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query =  $query->where([['campaign_reportings.client_id', $selectedUser->id], ['call_status', $status]]);
                        } else if ($role[0] == 'admin') {
                            $query =  $query->where([['campaign_reportings.client_id', $clientId], ['call_status', $status]]);
                        }
                    }
                    $query =  $query->where([['campaign_reportings.client_id', $clientId], ['call_status', $status]]);
                }
            } else if ($targetId != null) {
                if ($role[0] == 'publisher') {
                    $query =  $query->where([['target_id', $targetId], ['call_status', $status], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query =  $query->where([['target_id', $targetId], ['call_status', $status], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query =  $query->where([['target_id', $targetId], ['call_status', $status], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query =  $query->where([['target_id', $targetId], ['call_status', $status], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query =  $query->where([['target_id', $targetId], ['call_status', $status]]);
                        }
                    }
                    $query =  $query->where([['target_id', $targetId], ['call_status', $status]]);
                }
            } else if ($dialed != null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['dialed', $dialed], ['call_status', $status], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['dialed', $dialed], ['call_status', $status], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['dialed', $dialed], ['call_status', $status], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['dialed', $dialed], ['call_status', $status], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['dialed', $dialed], ['call_status', $status]]);
                        }
                    }
                    $query = $query->where([['dialed', $dialed], ['call_status', $status]]);
                }
            } else if ($duplicated !== null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', $status], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['duplicate', $duplicated], ['call_status', $status]]);
                        }
                    }
                    $query = $query->where([['duplicate', $duplicated], ['call_status', $status]]);
                }
            } else if ($date !== null) {
                if ($role[0] == 'publisher') {
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['publisher_id', $userId]]);
                } else if ($role[0] == 'client') {
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['campaign_reportings.client_id', $userId]]);
                } else if ($role[0] == 'admin') {
                    if (!empty($selectedUser)) {
                        $role = $selectedUser->getRoleNames();
                        if ($role[0] == 'client') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['campaign_reportings.client_id', $selectedUser->id]]);
                        } else if ($role[0] == 'publisher') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status], ['publisher_id', $selectedUser->id]]);
                        } else if ($role[0] == 'admin') {
                            $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status]]);
                        }
                    }
                    $query = $query->where([['campaign_reportings.created_at', $date], ['call_status', $status]]);
                }
            }
        }
        if ($dateRange != null) {
            getTimeRangeRecord($query);
        }
        if ($customFilter != null) {
            getFilterandTags($query);
        }
        $query = $query->count();
        return $query;
    }
}


if (!function_exists('countTotalCallsRecord')) {
    function countTotalCallsRecord($publisherId, $clientId, $campaignId, $targetId, $userId)
    {
        $user = User::where('id', $userId)->first();
        $role = $user->getRoleNames();
        if ($publisherId != null) {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where('publisher_id', $userId)->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where('publisher_id', $publisherId)->count();
            }
        } else if ($clientId != null) {
            if ($role[0] == 'client') {
                return CampaignReporting::where('client_id', $userId)->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where('client_id', $clientId)->count();
            }
        } else if ($campaignId != null) {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where('campaign_id', $campaignId)->where('publisher_id', $userId)->count();
            } else if ($role[0] == 'client') {
                return CampaignReporting::where('campaign_id', $campaignId)->where('client_id', $userId)->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where('campaign_id', $campaignId)->count();
            }
        } else if ($targetId != null) {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where('target_id', $targetId)->where('publisher_id', $userId)->count();
            } else if ($role[0] == 'client') {
                return CampaignReporting::where('target_id', $targetId)->where('client_id', $userId)->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where('target_id', $targetId)->count();
            }
        }
    }
}

if (!function_exists('countConvertedCallsRecord')) {
    function countConvertedCallsRecord($publisherId, $clientId, $campaignId, $targetId, $userId)
    {
        $user = User::where('id', $userId)->first();
        $role = $user->getRoleNames();
        if ($publisherId != null) {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where([['publisher_id', $userId], ['call_status', 'completed'], ['revenue', '!=', 0.0]])->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where([['publisher_id', $publisherId], ['call_status', 'completed'], ['revenue', '!=', 0.0]])->count();
            }
        } else if ($clientId != null) {
            if ($role[0] == 'client') {
                return CampaignReporting::where([['client_id', $userId], ['call_status', 'completed'], ['revenue', '!=', 0.0]])->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where([['client_id', $publisherId], ['call_status', 'completed'], ['revenue', '!=', 0.0]])->count();
            }
        } else if ($campaignId != null) {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where([['campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', 0.0], ['publisher_id', $userId]])->count();
            } else if ($role[0] == 'client') {
                return CampaignReporting::where([['campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', 0.0], ['client_id', $userId]])->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where([['campaign_id', $campaignId], ['call_status', 'completed'], ['revenue', '!=', 0.0]])->count();
            }
        } else if ($targetId != null) {
            if ($role[0] == 'publisher') {
                return CampaignReporting::where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', 0.0], ['publisher_id', $userId]])->count();
            } else if ($role[0] == 'client') {
                return CampaignReporting::where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', 0.0], ['client_id', $userId]])->count();
            } else if ($role[0] == 'admin') {
                return CampaignReporting::where([['target_id', $targetId], ['call_status', 'completed'], ['revenue', '!=', 0.0]])->count();
            }
        }
    }
}

// if (!function_exists('countDuplicateCallsRecord')) {
//     function countDuplicateCallsRecord($publisherId, $clientId, $campaignId, $targetId, $userId, $dialed, $duplicated, $date, $selectedUser)
//     {
//         $user = User::where('id', $userId)->first();
//         $role = $user->getRoleNames();
//         if ($publisherId != null) {
//             if ($role[0] == 'publisher') {
//                 return CampaignReporting::where([['publisher_id', $userId],['duplicate', 1]])->count();
//             } else if ($role[0] == 'admin') {
//                 if(!empty($selectedUser)) {
//                     $role = $selectedUser->getRoleNames();
//                     if ($role[0] == 'publisher') {
//                         return CampaignReporting::where([['publisher_id', $selectedUser->id], ['duplicate', 1]])->count();
//                     } else if ($role[0] == 'admin') {
//                         return CampaignReporting::where([['publisher_id', $publisherId], ['duplicate', 1]])->count();
//                     }
//                 }
//                 return CampaignReporting::where([['publisher_id', $publisherId],['duplicate', 1]])->count();
//             }
//         } else if ($clientId != null) {
//             if ($role[0] == 'client') {
//                 return CampaignReporting::where([['client_id', $userId],['duplicate', 1]])->count();
//             } else if ($role[0] == 'admin') {
//                 if(!empty($selectedUser)) {
//                     $role = $selectedUser->getRoleNames();
//                     if($role[0] == 'client') {
//                         return CampaignReporting::where([['client_id', $selectedUser->id], ['duplicate', 1]])->count();
//                     } else if ($role[0] == 'admin') {
//                         return CampaignReporting::where([['client_id', $clientId], ['duplicate', 1]])->count();
//                     }
//                 }
//                 return CampaignReporting::where([['client_id', $clientId],['duplicate', 1]])->count();
//             }
//         }else if ($campaignId != null) {
//             if ($role[0] == 'publisher') {
//                 return CampaignReporting::where([['campaign_id', $campaignId], ['duplicate', 1], ['publisher_id',$userId]])->count();
//             } else if ($role[0] == 'client') {
//                 return CampaignReporting::where([['campaign_id', $campaignId], ['duplicate', 1],['client_id',$userId]])->count();
//             } else if ($role[0] == 'admin') {
//                 if(!empty($selectedUser)) {
//                     $role = $selectedUser->getRoleNames();
//                     if($role[0] == 'client') {
//                         return CampaignReporting::where([['campaign_id', $campaignId], ['duplicate', 1], ['client_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'publisher') {
//                         return CampaignReporting::where([['campaign_id', $campaignId], ['duplicate', 1], ['publisher_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'admin') {
//                         return CampaignReporting::where([['campaign_id', $campaignId], ['duplicate', 1]])->count();
//                     }
//                 }
//                 return CampaignReporting::where([['campaign_id', $campaignId], ['duplicate', 1]])->count();
//             }
//         } else if ($targetId != null) {
//             if ($role[0] == 'publisher') {
//                 return CampaignReporting::where([['target_id', $targetId], ['duplicate', 1],['publisher_id',$userId]])->count();
//             } else if ($role[0] == 'client') {
//                 return CampaignReporting::where([['target_id', $targetId], ['duplicate', 1],['client_id',$userId]])->count();
//             } else if ($role[0] == 'admin') {
//                 if(!empty($selectedUser)) {
//                     $role = $selectedUser->getRoleNames();
//                     if($role[0] == 'client') {
//                         return CampaignReporting::where([['target_id', $targetId], ['duplicate', 1], ['client_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'publisher') {
//                         return CampaignReporting::where([['target_id', $targetId], ['duplicate', 1], ['publisher_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'admin') {
//                         return CampaignReporting::where([['target_id', $targetId], ['duplicate', 1]])->count();
//                     }
//                 }
//                 return CampaignReporting::where([['target_id', $targetId], ['duplicate', 1]])->count();
//             }
//         } else if ($dialed != null) {
//             if ($role[0] == 'publisher') {
//                 return CampaignReporting::where([['dialed', $dialed], ['duplicate', 1],['publisher_id',$userId]])->count();
//             } else if ($role[0] == 'client') {
//                 return CampaignReporting::where([['dialed', $dialed], ['duplicate', 1],['client_id',$userId]])->count();
//             } else if ($role[0] == 'admin') {
//                 if(!empty($selectedUser)) {
//                     $role = $selectedUser->getRoleNames();
//                     if($role[0] == 'client') {
//                         return CampaignReporting::where([['dialed', $dialed], ['duplicate', 1], ['client_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'publisher') {
//                         return CampaignReporting::where([['dialed', $dialed], ['duplicate', 1], ['publisher_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'admin') {
//                         return CampaignReporting::where([['dialed', $dialed], ['duplicate', 1]])->count();
//                     }
//                 }
//                 return CampaignReporting::where([['dialed', $dialed], ['duplicate', 1]])->count();
//             }
//         } else if ($duplicated !== null) {
//             if ($role[0] == 'publisher') {
//                 return CampaignReporting::where([['duplicate', $duplicated],['publisher_id',$userId]])->count();
//             } else if ($role[0] == 'client') {
//                 return CampaignReporting::where([['duplicate', $duplicated], ['client_id',$userId]])->count();
//             } else if ($role[0] == 'admin') {
//                 if(!empty($selectedUser)) {
//                     $role = $selectedUser->getRoleNames();
//                     if($role[0] == 'client') {
//                         return CampaignReporting::where([['duplicate', $duplicated], ['client_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'publisher') {
//                         return CampaignReporting::where([['duplicate', $duplicated], ['publisher_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'admin') {
//                         return CampaignReporting::where([['duplicate', $duplicated]])->count();
//                     }
//                 }
//                 return CampaignReporting::where([['duplicate', $duplicated]])->count();
//             }
//         } else if ($date != null) {
//             if ($role[0] == 'publisher') {
//                 return CampaignReporting::where([['created_at', $date], ['duplicate', 1], ['publisher_id',$userId]])->count();
//             } else if ($role[0] == 'client') {
//                 return CampaignReporting::where([['created_at', $date], ['duplicate', 1], ['client_id',$userId]])->count();
//             } else if ($role[0] == 'admin') {
//                 if(!empty($selectedUser)) {
//                     $role = $selectedUser->getRoleNames();
//                     if($role[0] == 'client') {
//                         return CampaignReporting::where([['created_at', $date], ['duplicate', 1], ['client_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'publisher') {
//                         return CampaignReporting::where([['created_at', $date], ['duplicate', 1], ['publisher_id',$selectedUser->id]])->count();
//                     } else if ($role[0] == 'admin') {
//                         return CampaignReporting::where([['created_at', $date], ['duplicate', 1]])->count();
//                     }
//                 }
//                 return CampaignReporting::where([['created_at', $date], ['duplicate', 1]])->count();
//             }
//         }
//     }
// }

if (!function_exists('countDuplicateCallsRecord')) {
    function countDuplicateCallsRecord($publisherId, $clientId, $campaignId, $targetId, $userId, $dialed, $duplicated, $date, $selectedUser, $dateRange, $customFilter)
    {
        $user = User::where('id', $userId)->first();
        $role = $user->getRoleNames();
        $query = CampaignReporting::query();
        if ($publisherId != null) {
            if ($role[0] == 'publisher') {
                $query = $query->where([['publisher_id', $userId], ['duplicate', 1]]);
            } else if ($role[0] == 'admin') {
                if (!empty($selectedUser)) {
                    $role = $selectedUser->getRoleNames();
                    if ($role[0] == 'publisher') {
                        $query = $query->where([['publisher_id', $selectedUser->id], ['duplicate', 1]]);
                    } else if ($role[0] == 'admin') {
                        $query = $query->where([['publisher_id', $publisherId], ['duplicate', 1]]);
                    }
                }
                $query = $query->where([['publisher_id', $publisherId], ['duplicate', 1]]);
            }
        } else if ($clientId != null) {
            if ($role[0] == 'client') {
                $query = $query->where([['campaign_reportings.client_id', $userId], ['duplicate', 1]]);
            } else if ($role[0] == 'admin') {
                if (!empty($selectedUser)) {
                    $role = $selectedUser->getRoleNames();
                    if ($role[0] == 'client') {
                        $query = $query->where([['campaign_reportings.client_id', $selectedUser->id], ['duplicate', 1]]);
                    } else if ($role[0] == 'admin') {
                        $query = $query->where([['campaign_reportings.client_id', $clientId], ['duplicate', 1]]);
                    }
                }
                $query = $query->where([['campaign_reportings.client_id', $clientId], ['duplicate', 1]]);
            }
        } else if ($campaignId != null) {
            if ($role[0] == 'publisher') {
                $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['duplicate', 1], ['publisher_id', $userId]]);
            } else if ($role[0] == 'client') {
                $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['duplicate', 1], ['campaign_reportings.client_id', $userId]]);
            } else if ($role[0] == 'admin') {
                if (!empty($selectedUser)) {
                    $role = $selectedUser->getRoleNames();
                    if ($role[0] == 'client') {
                        $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['duplicate', 1], ['campaign_reportings.client_id', $selectedUser->id]]);
                    } else if ($role[0] == 'publisher') {
                        $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['duplicate', 1], ['publisher_id', $selectedUser->id]]);
                    } else if ($role[0] == 'admin') {
                        $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['duplicate', 1]]);
                    }
                }
                $query = $query->where([['campaign_reportings.campaign_id', $campaignId], ['duplicate', 1]]);
            }
        } else if ($targetId != null) {
            if ($role[0] == 'publisher') {
                $query = $query->where([['target_id', $targetId], ['duplicate', 1], ['publisher_id', $userId]]);
            } else if ($role[0] == 'client') {
                $query = $query->where([['target_id', $targetId], ['duplicate', 1], ['campaign_reportings.client_id', $userId]]);
            } else if ($role[0] == 'admin') {
                if (!empty($selectedUser)) {
                    $role = $selectedUser->getRoleNames();
                    if ($role[0] == 'client') {
                        $query = $query->where([['target_id', $targetId], ['duplicate', 1], ['campaign_reportings.client_id', $selectedUser->id]]);
                    } else if ($role[0] == 'publisher') {
                        $query = $query->where([['target_id', $targetId], ['duplicate', 1], ['publisher_id', $selectedUser->id]]);
                    } else if ($role[0] == 'admin') {
                        $query = $query->where([['target_id', $targetId], ['duplicate', 1]]);
                    }
                }
                $query = $query->where([['target_id', $targetId], ['duplicate', 1]]);
            }
        } else if ($dialed != null) {
            if ($role[0] == 'publisher') {
                $query = $query->where([['dialed', $dialed], ['duplicate', 1], ['publisher_id', $userId]]);
            } else if ($role[0] == 'client') {
                $query = $query->where([['dialed', $dialed], ['duplicate', 1], ['client_id', $userId]]);
            } else if ($role[0] == 'admin') {
                if (!empty($selectedUser)) {
                    $role = $selectedUser->getRoleNames();
                    if ($role[0] == 'client') {
                        $query = $query->where([['dialed', $dialed], ['duplicate', 1], ['client_id', $selectedUser->id]]);
                    } else if ($role[0] == 'publisher') {
                        $query = $query->where([['dialed', $dialed], ['duplicate', 1], ['publisher_id', $selectedUser->id]]);
                    } else if ($role[0] == 'admin') {
                        $query = $query->where([['dialed', $dialed], ['duplicate', 1]]);
                    }
                }
                $query = $query->where([['dialed', $dialed], ['duplicate', 1]]);
            }
        } else if ($duplicated !== null) {
            if ($role[0] == 'publisher') {
                $query = $query->where([['duplicate', $duplicated], ['publisher_id', $userId]]);
            } else if ($role[0] == 'client') {
                $query = $query->where([['duplicate', $duplicated], ['campaign_reportings.client_id', $userId]]);
            } else if ($role[0] == 'admin') {
                if (!empty($selectedUser)) {
                    $role = $selectedUser->getRoleNames();
                    if ($role[0] == 'client') {
                        $query = $query->where([['duplicate', $duplicated], ['campaign_reportings.client_id', $selectedUser->id]]);
                    } else if ($role[0] == 'publisher') {
                        $query = $query->where([['duplicate', $duplicated], ['publisher_id', $selectedUser->id]]);
                    } else if ($role[0] == 'admin') {
                        $query = $query->where([['duplicate', $duplicated]]);
                    }
                }
                $query = $query->where([['duplicate', $duplicated]]);
            }
        } else if ($date != null) {
            $date = Carbon::parse($date)->format('Y-m-d');
            if ($role[0] == 'publisher') {
                $query = $query->where([['campaign_reportings.created_at', $date], ['duplicate', 1], ['publisher_id', $userId]]);
            } else if ($role[0] == 'client') {
                $query = $query->where([['campaign_reportings.created_at', $date], ['duplicate', 1], ['campaign_reportings.client_id', $userId]]);
            } else if ($role[0] == 'admin') {
                if (!empty($selectedUser)) {
                    $role = $selectedUser->getRoleNames();
                    if ($role[0] == 'client') {
                        $query = $query->where([[DB::raw("DATE(campaign_reportings.created_at)"), $date], ['duplicate', 1], ['campaign_reportings.client_id', $selectedUser->id]]);
                    } else if ($role[0] == 'publisher') {
                        $query = $query->where([[DB::raw("DATE(campaign_reportings.created_at)"), $date], ['duplicate', 1], ['publisher_id', $selectedUser->id]]);
                    } else if ($role[0] == 'admin') {
                        $query = $query->where([[DB::raw("DATE(campaign_reportings.created_at)"), $date], ['duplicate', 1]]);
                    }
                }

                $query = $query->where([[DB::raw("DATE(campaign_reportings.created_at)"), $date], ['duplicate', 1]]);
            }
        }

        if ($dateRange != null) {

            getTimeRangeRecord($query);
        }
        if ($customFilter != null) {
            getFilterandTags($query);
        }
        $query = $query->count();
        return $query;
    }
}


if (!function_exists('convertDateTimeToTimezone')) {
    function convertDateTimeToTimezone($time, $from_timezone = 'UTC', $to_timezone = 'UTC')
    {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        $date = $date->format('Y-m-d H:i:s');
        return $date;
    }
}


if (!function_exists('convertDateTimeToYearDayTime')) {
    function convertDateTimeToYearDayTime($time, $from_timezone = 'UTC', $to_timezone = 'UTC')
    {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        $date = $date->format('M d H:i:s A');
        return $date;
    }
}

if (!function_exists('convertTimeToTimezone')) {
    function convertTimeToTimezone($time, $from_timezone = 'UTC', $to_timezone = 'UTC')
    {
        $date = new DateTime($time, new DateTimeZone($from_timezone));
        $date->setTimezone(new DateTimeZone($to_timezone));
        $date = $date->format('H:i');
        return $date;
    }
}


if (!function_exists('getOperator')) {
    function getOperator($operator)
    {
        switch ($operator) {
            case 'contains':
                return 'like';
                break;
            case 'not_contains':
                return 'not like';
                break;
            case 'begins_with':
                return 'like';
                break;
            case 'greater_than':
                return '>';
                break;
            case 'less_than':
                return '<';
                break;
            case 'equal_single_value':
                return '=';
                break;
            case 'not_equal_single_value':
                return '!=';
                break;
        }
    }
}

if (!function_exists('checkSearchFilters')) {
    function checkSearchFilters($key, $operator, $value, $query)
    {
        switch ($operator) {
            case 'contains':
                $query->where($key, 'like', "%{$value}");
                break;
            case 'not_contains':
                $query->where($key, 'not like', "%{$value}");
                break;
            case 'begins_with':
                $query->where($key, 'like', "%{$value}%");
                break;
        }
    }
}

if (!function_exists('getFilterandTags')) {
    function getFilterandTags($query)
    {

        foreach (request()->customFilters as $index => $record) {

            $data = json_decode($record, true);
            $operator = $data['operator'] ?? null;
            $operation = $data['operation'] ?? null;
            $filter_value = $data['filter_value'] ?? null;
            $filter_key = $data['filter_key'] ?? null;
            if ($operator == 'contains' || $operator == 'not_contains') {
                passFilteredData($filter_key, $query, $operator, $filter_value, $operation);
            } else if ($operator == 'begins_with') {
                passFilteredData($filter_key, $query, $operator, $filter_value, $operation);
            } else if ($operator) {
                passFilteredData($filter_key, $query, $operator, $filter_value, $operation);
            }
        }
        return $query;
    }
}

if (!function_exists('passFilteredData')) {
    function passFilteredData($filter_key, $query, $operator, $filter_value, $operation)
    {
        $nameFilterColumns = array('client_name', 'publisher_name',  'campaign_name',  'target_name', 'target_number');
        $idFilterColumns = array('client_id', 'publisher_id',  'campaign_id',  'target_id');
        $callFilterColumns = array('call_status_connected', 'call_status_converted', 'recording', 'duplicate');
        if (!$operation && $operator == 'contains' || $operator == "not_contains") {
            if (in_array($filter_key, $idFilterColumns)) {
                return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
            } else if (in_array($filter_key, $nameFilterColumns)) {
                return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
            } else if (in_array($filter_key, $callFilterColumns)) {
                return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
            } else {
                $query->where('campaign_reportings.' . $filter_key, getOperator($operator), "%{$filter_value}%");
            }
        } else if (!$operation && $operator == 'begins_with') {
            if (in_array($filter_key, $idFilterColumns)) {
                return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
            } else if (in_array($filter_key, $nameFilterColumns)) {
                return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
            } else if (in_array($filter_key, $callFilterColumns)) {
                return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
            } else {
                $query->where('campaign_reportings.' . $filter_key, getOperator($operator), "{$filter_value}%");
            }
        } else if (!$operation && $operator) {
            if (in_array($filter_key, $idFilterColumns)) {
                return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
            } else if (in_array($filter_key, $nameFilterColumns)) {
                return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
            } else if (in_array($filter_key, $callFilterColumns)) {
                return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
            } else {
                $query->where('campaign_reportings.' . $filter_key, getOperator($operator), $filter_value);
            }
        }
        if ($operation && $operation == "and") {
            if ($operator == 'contains' || $operator == "not_contains") {
                if (in_array($filter_key, $nameFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $idFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $callFilterColumns)) {
                    return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
                } else {
                    $query->where('campaign_reportings.' . $filter_key, getOperator($operator), "%{$filter_value}%");
                }
            } else if ($operator == 'begins_with') {
                if (in_array($filter_key, $nameFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $idFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $callFilterColumns)) {
                    return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
                } else {
                    $query->where('campaign_reportings.' . $filter_key, getOperator($operator), "{$filter_value}%");
                }
            } else if ($operator) {
                if (in_array($filter_key, $nameFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $idFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $callFilterColumns)) {
                    return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
                } else {
                    $query->where('campaign_reportings.' . $filter_key, getOperator($operator), $filter_value);
                }
            }
        }
        if ($operation && $operation == "or") {
            if ($operator == 'contains' || $operator == "not_contains") {
                if (in_array($filter_key, $nameFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $idFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $callFilterColumns)) {
                    return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
                } else {
                    $query->orWhere('campaign_reportings.' . $filter_key, getOperator($operator), "%{$filter_value}%");
                }
            } else if ($operator == 'begins_with') {
                if (in_array($filter_key, $nameFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $idFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $callFilterColumns)) {
                    return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
                } else {
                    $query->where('campaign_reportings.' . $filter_key, getOperator($operator), "{$filter_value}%");
                }
            } else if ($operator) {
                if (in_array($filter_key, $nameFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $idFilterColumns)) {
                    return getFiltersData($filter_key, $query, $operator, $filter_value, $operation);
                } else if (in_array($filter_key, $callFilterColumns)) {
                    return getCallStatusFilters($query, $filter_key, $operator, $filter_value, $operation);
                } else {
                    $query->orWhere('campaign_reportings.' . $filter_key, getOperator($operator), $filter_value);
                }
            }
        }
    }
}

if (!function_exists('getFiltersData')) {
    function getFiltersData($filterKey, $query, $operator, $filterValue, $operation)
    {
        $count = rand(1, 500);
        if ($filterKey == 'client_name') {

            $query->join('users as u' . $count, 'u' . $count . '.id', '=', 'campaign_reportings.client_id');
            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('u' . $count . '.name', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('u' . $count . '.name', getOperator($operator), "%{$filterValue}");
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $query->where('campaign_reportings.client_id', getOperator($operator), User::getIdByUuid($filterValue));
            } else if (!$operation && $operator) {
                $query->where('u' . $count . '.name', getOperator($operator), $filterValue);
            }
            if ($operation && $operation == "and") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('u' . $count . '.name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('u' . $count . '.name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->where('campaign_reportings.client_id', getOperator($operator), User::getIdByUuid($filterValue));
                } else {
                    $query->where('u' . $count . '.name', getOperator($operator), $filterValue);
                }
            }
            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('u' . $count . '.name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('u' . $count . '.name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->orWhere('campaign_reportings.client_id', getOperator($operator), User::getIdByUuid($filterValue));
                } else {
                    $query->orWhere('u' . $count . '.name', getOperator($operator), $filterValue);
                }
            }
        }


        if ($filterKey == 'client_id') {

            $query->join('users as uc' . $count, 'uc' . $count . '.id', '=', 'campaign_reportings.client_id');

            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('uc' . $count . '.user_uuid', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('uc' . $count . '.user_uuid', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator == 'greater_than' || $operator == 'less_than') {
                $query->where('uc' . $count . '.user_uuid', getOperator($operator), $filterValue);
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $clientId = User::getIdByUuid($filterValue);
                $query->where('campaign_reportings.client_id', getOperator($operator), $clientId);
            }



            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('uc' . $count . '.user_uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('uc' . $count . '.user_uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->orWhere('uc' . $count . '.user_uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $clientId = User::getIdByUuid($filterValue);
                    $query->orWhere('campaign_reportings.client_id', getOperator($operator), $clientId);
                }
            }


            if ($operation && $operation == "and") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('uc' . $count . '.user_uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('uc' . $count . '.user_uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->where('uc' . $count . '.user_uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $clientId = User::getIdByUuid($filterValue);
                    $query->where('campaign_reportings.client_id', getOperator($operator), $clientId);
                }
            }
        }


        if ($filterKey == 'publisher_name') {
            $query->join('users as pb_us' . $count, 'pb_us' . $count . '.id', '=', 'campaign_reportings.publisher_id');
            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('pb_us' . $count . '.name', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation &&  $operator == 'begins_with') {
                $query->where('pb_us' . $count . '.name', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $query->where('campaign_reportings.publisher_id', getOperator($operator), User::getIdByUuid($filterValue));
            } else if (!$operation && $operator) {
                $query->where('pb_us' . $count . '.name', getOperator($operator), $filterValue);
            }
            if ($operation && $operation == "and") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('pb_us' . $count . '.name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('pb_us' . $count . '.name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->where('campaign_reportings.publisher_id', getOperator($operator), User::getIdByUuid($filterValue));
                } else {
                    $query->where('pb_us' . $count . '.name', getOperator($operator), $filterValue);
                }
            }
            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('pb_us' . $count . '.name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('pb_us' . $count . '.name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->orWhere('campaign_reportings.publisher_id', getOperator($operator), User::getIdByUuid($filterValue));
                } else {
                    $query->orWhere('pb_us' . $count . '.name', getOperator($operator), $filterValue);
                }
            }
        }



        if ($filterKey == 'publisher_id') {

            $query->join('users as pbi_us' . $count, 'pbi_us' . $count . '.id', '=', 'campaign_reportings.publisher_id');

            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('pbi_us' . $count . '.user_uuid', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('pbi_us' . $count . '.user_uuid', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator == 'greater_than' || $operator == 'less_than') {
                $query->where('pbi_us' . $count . '.user_uuid', getOperator($operator), $filterValue);
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $publisherId = User::getIdByUuid($filterValue);
                $query->where('campaign_reportings.publisher_id', getOperator($operator), $publisherId);
            }

            if ($operation && $operation == "and") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('pbi_us' . $count . '.user_uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('pbi_us' . $count . '.user_uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->where('pbi_us' . $count . '.user_uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $publisherId = User::getIdByUuid($filterValue);
                    $query->where('campaign_reportings.publisher_id', getOperator($operator), $publisherId);
                }
            }


            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('pbi_us' . $count . '.user_uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('pbi_us' . $count . '.user_uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->orWhere('pbi_us' . $count . '.user_uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $publisherId = User::getIdByUuid($filterValue);
                    $query->orWhere('campaign_reportings.publisher_id', getOperator($operator), $publisherId);
                }
            }
        }



        if ($filterKey == 'campaign_name') {
            $query->join('campaigns as cm' . $count, 'cm' . $count . '.id', '=', 'campaign_reportings.campaign_id');
            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('cm' . $count . '.campaign_name', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('cm' . $count . '.campaign_name', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $query->where('campaign_reportings.campaign_id', getOperator($operator), Campaign::getIdByUuid($filterValue));
            } else if (!$operation && $operator) {
                $query->where('cm' . $count . '.campaign_name', getOperator($operator), $filterValue);
            }
            if ($operation && $operation == "and") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('cm' . $count . '.campaign_name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('cm' . $count . '.campaign_name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->where('campaign_reportings.campaign_id', getOperator($operator), Campaign::getIdByUuid($filterValue));
                } else {
                    $query->where('cm' . $count . '.campaign_name', getOperator($operator), $filterValue);
                }
            }
            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('cm' . $count . '.campaign_name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('cm' . $count . '.campaign_name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->orWhere('campaign_reportings.campaign_id', getOperator($operator), Campaign::getIdByUuid($filterValue));
                } else {
                    $query->orWhere('cm' . $count . '.campaign_name', getOperator($operator), $filterValue);
                }
            }
        }


        if ($filterKey == 'campaign_id') {

            $query->join('campaigns as cm_i' . $count, 'cm_i' . $count . '.id', '=', 'campaign_reportings.campaign_id');

            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('cm_i' . $count . '.uuid', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('cm_i' . $count . '.uuid', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator == 'greater_than' || $operator == 'less_than') {
                $query->where('cm_i' . $count . '.uuid', getOperator($operator), $filterValue);
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $campaignId = Campaign::getIdByUuid($filterValue);
                $query->where('campaign_reportings.campaign_id', getOperator($operator), $campaignId);
            }


            if ($operation && $operation == "and") {

                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('cm_i' . $count . '.uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('cm_i' . $count . '.uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->where('cm_i' . $count . '.uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $campaignId = Campaign::getIdByUuid($filterValue);
                    $query->where('campaign_reportings.campaign_id', getOperator($operator), $campaignId);
                }
            }


            if ($operation && $operation == "or") {

                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('cm_i' . $count . '.uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('cm_i' . $count . '.uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->orWhere('cm_i' . $count . '.uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $campaignId = Campaign::getIdByUuid($filterValue);
                    $query->orWhere('campaign_reportings.campaign_id', getOperator($operator), $campaignId);
                }
            }
        }



        if ($filterKey == 'target_name') {
            $query->join('target_listings as t' . $count, 't' . $count . '.id', '=', 'campaign_reportings.target_id');
            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('t' . $count . '.name', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('t' . $count . '.name', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $query->where('campaign_reportings.target_id', getOperator($operator), TargetListing::getIdByUuid($filterValue));
            } else if (!$operation && $operator) {
                $query->where('t' . $count . '.name', getOperator($operator), $filterValue);
            }
            if ($operation && $operation == "and") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('t' . $count . '.name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('t' . $count . '.name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->where('campaign_reportings.target_id', getOperator($operator), TargetListing::getIdByUuid($filterValue));
                } else {
                    $query->where('t' . $count . '.name', getOperator($operator), $filterValue);
                }
            }
            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('t' . $count . '.name', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('t' . $count . '.name', getOperator($operator), "{$filterValue}%");
                } else if ($operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $query->orWhere('campaign_reportings.target_id', getOperator($operator), TargetListing::getIdByUuid($filterValue));
                } else {
                    $query->orWhere('t' . $count . '.name', getOperator($operator), $filterValue);
                }
            }
        }


        if ($filterKey == 'target_id') {
            $query->join('target_listings as ti' . $count, 'ti' . $count . '.id', '=', 'campaign_reportings.target_id');

            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('ti' . $count . '.uuid', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('ti' . $count . '.uuid', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator == 'greater_than' || $operator == 'less_than') {
                $query->where('ti' . $count . '.uuid', getOperator($operator), $filterValue);
            } else if (!$operation && $operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                $targetId = TargetListing::getIdByUuid($filterValue);
                $query->where('campaign_reportings.target_id', getOperator($operator), $targetId);
            }



            if ($operation && $operation == "and") {

                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('ti' . $count . '.uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('ti' . $count . '.uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->where('ti' . $count . '.uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $targetId = TargetListing::getIdByUuid($filterValue);
                    $query->where('campaign_reportings.target_id', getOperator($operator), $targetId);
                }
            }

            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('ti' . $count . '.uuid', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('ti' . $count . '.uuid', getOperator($operator), "{$filterValue}%");
                } else if ($operator == 'greater_than' || $operator == 'less_than') {
                    $query->orWhere('ti' . $count . '.uuid', getOperator($operator), $filterValue);
                } else if ($operator == 'equal_single_value' || $operator == 'not_equal_single_value') {
                    $targetId = TargetListing::getIdByUuid($filterValue);
                    $query->orWhere('campaign_reportings.target_id', getOperator($operator), $targetId);
                }
            }
        }


        if ($filterKey == 'target_number') {
            $query->join('target_listings as tn' . $count, 'tn' . $count . '.id', '=', 'campaign_reportings.target_id');

            if (!$operation && $operator == 'contains' || $operator == 'not_contains') {
                $query->where('tn' . $count . '.destination', getOperator($operator), "%{$filterValue}%");
            } else if (!$operation && $operator == 'begins_with') {
                $query->where('tn' . $count . '.destination', getOperator($operator), "{$filterValue}%");
            } else if (!$operation && $operator) {
                $query->where('tn' . $count . '.destination', getOperator($operator), $filterValue);
            }



            if ($operation && $operation == "and") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->where('tn' . $count . '.destination', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->where('tn' . $count . '.destination', getOperator($operator), "{$filterValue}%");
                } else if ($operator) {
                    $query->where('tn' . $count . '.destination', getOperator($operator), $filterValue);
                }
            }

            if ($operation && $operation == "or") {
                if ($operator == 'contains' || $operator == 'not_contains') {
                    $query->orWhere('tn' . $count . '.destination', getOperator($operator), "%{$filterValue}%");
                } else if ($operator == 'begins_with') {
                    $query->orWhere('tn' . $count . '.destination', getOperator($operator), "{$filterValue}%");
                } else if ($operator) {
                    $query->orWhere('tn' . $count . '.destination', getOperator($operator), $filterValue);
                }
            }
        }
        return $query;
    }
}

if (!function_exists('getCallStatusFilters')) {
    function getCallStatusFilters($query, $key, $operator, $value, $operation)
    {
        if (!$operation && $operator == 'equal_single_value') {
            //Call Connected
            if ($key == 'call_status_connected' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            } else if ($key == 'call_status_connected' && $value == 'no') {
                $query->where('campaign_reportings.call_status', '!=', 'completed');
            }

            //Call Converted
            else if ($key == 'call_status_converted' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', '!=', 0.0);
            } else if ($key == 'call_status_converted' && $value == 'no') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            }

            // Call Recording
            else if ($key == 'recording' && $value == 'yes') {
                $query->where('campaign_reportings.recording', '!=', null);
            } else if ($key == 'recording' && $value == 'no') {
                $query->where('campaign_reportings.recording', null);
            }

            // Call Duplicate
            else if ($key == 'duplicate' && $value == 'yes') {
                $query->where('campaign_reportings.duplicate', 1);
            } else if ($key == 'duplicate' && $value == 'no') {
                $query->where('campaign_reportings.duplicate', 0);
            }
        } else if (!$operation && $operator == 'not_equal_single_value') {
            // Call Connected
            if ($key == 'call_status_connected' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed');
            } else if ($key == 'call_status_connected' && $value == 'no') {
                $query->where('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            }

            // Call Converted
            else if ($key == 'call_status_converted' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            } else if ($key == 'call_status_converted' && $value == 'no') {
                $query->where('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', getOperator($operator), 0.0);
            }

            // Call Recording
            else if ($key == 'recording' && $value == 'yes') {
                $query->where('campaign_reportings.recording', null);
            } else if ($key == 'recording' && $value == 'no') {
                $query->where('campaign_reportings.recording', getOperator($operator), null);
            }

            // Call Duplicate
            else if ($key == 'duplicate' && $value == 'yes') {
                $query->where('campaign_reportings.duplicate', getOperator($operator), 1);
            } else if ($key == 'duplicate' && $value == 'no') {
                $query->where('campaign_reportings.duplicate', getOperator($operator), 0);
            }
        }
        if ($operation && $operation == 'and' && $operator == 'equal_single_value') {
            //Call Connected
            if ($key == 'call_status_connected' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            } else if ($key == 'call_status_connected' && $value == 'no') {
                $query->where('campaign_reportings.call_status', '!=', 'completed');
            }

            //Call Converted
            else if ($key == 'call_status_converted' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', '!=', 0.0);
            } else if ($key == 'call_status_converted' && $value == 'no') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            }

            // Call Recording
            else if ($key == 'recording' && $value == 'yes') {
                $query->where('campaign_reportings.recording', '!=', null);
            } else if ($key == 'recording' && $value == 'no') {
                $query->where('campaign_reportings.recording', null);
            }

            // Call Duplicate
            else if ($key == 'duplicate' && $value == 'yes') {
                $query->where('campaign_reportings.duplicate', 1);
            } else if ($key == 'duplicate' && $value == 'no') {
                $query->where('campaign_reportings.duplicate', 0);
            }
        } else if ($operation && $operation == 'and' && $operator == 'not_equal_single_value') {
            // Call Connected
            if ($key == 'call_status_connected' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', getOperator($operator), 'completed');
            } else if ($key == 'call_status_connected' && $value == 'no') {
                $query->where('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            }

            // Call Converted
            else if ($key == 'call_status_converted' && $value == 'yes') {
                $query->where('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            } else if ($key == 'call_status_converted' && $value == 'no') {
                $query->where('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', getOperator($operator), 0.0);
            }

            // Call Recording
            else if ($key == 'recording' && $value == 'yes') {
                $query->where('campaign_reportings.recording', null);
            } else if ($key == 'recording' && $value == 'no') {
                $query->where('campaign_reportings.recording', getOperator($operator), null);
            }

            // Call Duplicate
            else if ($key == 'duplicate' && $value == 'yes') {
                $query->where('campaign_reportings.duplicate', getOperator($operator), 1);
            } else if ($key == 'duplicate' && $value == 'no') {
                $query->where('campaign_reportings.duplicate', getOperator($operator), 0);
            }
        }
        if ($operation && $operation == 'or' && $operator == 'equal_single_value') {
            //Call Connected
            if ($key == 'call_status_connected' && $value == 'yes') {
                $query->orWhere('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            } else if ($key == 'call_status_connected' && $value == 'no') {
                $query->orWhere('campaign_reportings.call_status', '!=', 'completed');
            }

            //Call Converted
            else if ($key == 'call_status_converted' && $value == 'yes') {
                $query->orWhere('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', '!=', 0.0);
            } else if ($key == 'call_status_converted' && $value == 'no') {
                $query->orWhere('campaign_reportings.call_status', getOperator($operator), 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            }

            // Call Recording
            else if ($key == 'recording' && $value == 'yes') {
                $query->orWhere('campaign_reportings.recording', '!=', null);
            } else if ($key == 'recording' && $value == 'no') {
                $query->orWhere('campaign_reportings.recording', null);
            }

            // Call Duplicate
            else if ($key == 'duplicate' && $value == 'yes') {
                $query->orWhere('campaign_reportings.duplicate', 1);
            } else if ($key == 'duplicate' && $value == 'no') {
                $query->orWhere('campaign_reportings.duplicate', 0);
            }
        } else if ($operation && $operation == 'or' && $operator == 'not_equal_single_value') {
            // Call Connected
            if ($key == 'call_status_connected' && $value == 'yes') {
                $query->orWhere('campaign_reportings.call_status', getOperator($operator), 'completed');
            } else if ($key == 'call_status_connected' && $value == 'no') {
                $query->orWhere('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            }

            // Call Converted
            else if ($key == 'call_status_converted' && $value == 'yes') {
                $query->orWhere('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', 0.0);
            } else if ($key == 'call_status_converted' && $value == 'no') {
                $query->orWhere('campaign_reportings.call_status', 'completed')
                    ->where('campaign_reportings.revenue', getOperator($operator), 0.0);
            }

            // Call Recording
            else if ($key == 'recording' && $value == 'yes') {
                $query->orWhere('campaign_reportings.recording', null);
            } else if ($key == 'recording' && $value == 'no') {
                $query->orWhere('campaign_reportings.recording', getOperator($operator), null);
            }

            // Call Duplicate
            else if ($key == 'duplicate' && $value == 'yes') {
                $query->orWhere('campaign_reportings.duplicate', getOperator($operator), 1);
            } else if ($key == 'duplicate' && $value == 'no') {
                $query->orWhere('campaign_reportings.duplicate', getOperator($operator), 0);
            }
        }
        return $query;
    }
}

if (!function_exists('getTimeRangeRecord')) {
    function getTimeRangeRecord($query)
    {
        // $query->offset($page - 1);
        $to_timezone = 'UTC';

        if (!is_null(request('dateRange')) && strlen(request('dateRange')) > 2   &&   request('time_zone')) {

            $from_timezone = request('time_zone');
            $dateRange = json_decode(request('dateRange'), true);
            $startDate = $dateRange['startDate'];
            $endDate = $dateRange['endDate'];
            $startNewDate = convertDateTimeToTimezone($startDate, $from_timezone, $to_timezone);
            $endNewDate = convertDateTimeToTimezone($endDate, $from_timezone, $to_timezone);
        } else if (request('time_zone')  &&    strlen(request('dateRange')) == 2) {
            $from_timezone = request('time_zone');
            $endDate = Carbon::now()->endofDay();
            $startDate = Carbon::now()->subDays(7)->startOfDay();

            $startNewDate = convertDateTimeToTimezone($startDate, $from_timezone, $to_timezone);

            $endNewDate = convertDateTimeToTimezone($endDate, $from_timezone, $to_timezone);
        } else if (!is_null(request('dateRange')) &&   strlen(request('dateRange')) > 2) {
            $dateRange = json_decode(request('dateRange'), true);
            $startNewDate = $dateRange['startDate'];
            $startNewDate =  Carbon::parse($startNewDate)->format('Y-m-d H:i:s');

            $endNewDate = $dateRange['endDate'];
            $endNewDate =  Carbon::parse($endNewDate)->format('Y-m-d H:i:s');
        }

        if (!is_null(request('dateRange')) && strlen(request('dateRange')) > 2   || request('time_zone')) {
            return $query->whereBetween('campaign_reportings.created_at', [$startNewDate, $endNewDate])
                ->orderBy('campaign_reportings.created_at', 'asc');
        }
        getDataByUser($query);
    }
}

if (!function_exists('getDataByUser')) {
    function getDataByUser($query)
    {
        $user = User::where('id', request()->user()->id)->first();
        $role = $user->getRoleNames();
        if ($role[0] == 'publisher') {
            $query->where('campaign_reportings.publisher_id', $user->id);
        } else if ($role[0] == 'client') {
            $query->where('campaign_reportings.client_id', $user->id);
        } else if ($role[0] == 'admin') {
            $query;
        }
        return $query;
    }
}
