<?php

namespace App\Http\Controllers\Api;

use App\Models\BussinesCategory;
use App\Models\Campaign;
use App\Models\CampaignGeoLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadCsvController extends APiController
{
    private $rows = [];
    public function uploadCsv(Request $request) {
        $request->validate([
            'campaign_id' => 'required',
            'step' => 'required',
            'address_type' => 'required'
        ]);

        $campaign_id = Campaign::getIdByUuid($request->campaign_id);

        //update step in campaign against this step
        $campaign = Campaign::where('id', $campaign_id)->first();
        // $campaign->step = 12;
        // $campaign->update();
        // $campaign = Campaign::where('uuid', $request->campaign_id)->first();
        if($request->step > $campaign->step){
            $campaign->step = $request->step;
        }
        $campaign->update();

        $fileName = $request->fileName;
        $filePath = request()->file('file')->store('uploads/file', 's3');
        $fileUrl =  Storage::disk('s3')->url($filePath);
        $path = $request->file('file')->getRealPath();
        $records = array_map('str_getcsv', file($path));
        // return response()->json($records);
        $user_id = $request->user()->id;

        if (! count($records) > 0) {
        //    return 'Error...';
            return $this->respond([
                'status' => false,
                'message' => 'Please Upload zipcode only!'
            ]);
        }

        // Get field names from header column
        $fields = array_map('strtolower', $records[0]);
        // Remove the header column
        array_shift($records);


        foreach ($records as $record) {
            if (count($fields) != count($record)) {
                // return 'csv_upload_invalid_data';
                return $this->respond([
                    'status' => false,
                    'message' => 'Csv upload invalid data!'
                ]);
            }
            // Decode unwanted html entities
            $record =  array_map("html_entity_decode", $record);
            // Set the field name as key
            $record = array_combine($fields, $record);
            // Get the clean data
            $this->rows[] = str_replace(' ', '-', $record);
        }

        // for geo location
        foreach ($this->rows as $data) {
            CampaignGeoLocation::insert([
               'uuid' => generateUuid(),
               'zipcode' => $data['zip'],
               'campaign_id'=> $campaign_id,
               'file_name'=> $fileName,
               'file_url'=> $fileUrl,
               'address_type'=> $request->address_type,
            ]);
        }
        // For Business Category
        // foreach ($this->rows as $data) {
        //     BussinesCategory::create([
        //        'uuid' => generateUuid(),
        //        'name' => $data['name'],
        //        'user_id'=> $request->user()->id,
        //     ]);
        // }
        return $this->respond([
            'status' => true,
            'message' => 'Zipcodes has been uploaded successfully!'
        ]);
    }
    public function downloadFile()
    {
        // https://cloudrepbucket.s3.amazonaws.com/uploads/file/l6ys6S0D21oyBLR9DjrJybLiilfKYIT4HyyCy4vB.txt
        $filePath =  Storage::disk('s3')->get("/uploads/file/l6ys6S0D21oyBLR9DjrJybLiilfKYIT4HyyCy4vB.txt");
        return $this->respond([
            'status' => true,
            'message' => 'CSV file download successfully!',
            'data' => $filePath
        ]);
    }
    public function removeCsv(Request $request)
    {
        $campaign_id = Campaign::getIdByUuid($request->campaign_id);
        $deleteCsvFileFromS3 = removeFile('uploads/file', $request->file_url);
        if($deleteCsvFileFromS3){
            $camGeoLocation = CampaignGeoLocation::where('campaign_id', $campaign_id)->delete();
            if($camGeoLocation){
                return $this->respond([
                    'status' => true,
                    'message' => 'Campaign Geo Location deleted successfully!'
                ]);
            }else{
                return $this->respond([
                    'status' => false,
                    'message' => 'Campaign Geo Location not deleted!'
                ]);
            }
        }else{
            return $this->respond([
                'status' => false,
                'message' => 'AWS giving error!'
            ]);
        }

    }
}
