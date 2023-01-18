<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use Illuminate\support\Str;
use Illuminate\Http\Request;

class CampaignFilterReports extends Model
{
    use HasFactory;
    protected $fillable = ['filter_user_uuid', 'filter_time_zone', 'filter_report_name', 'filter_date_range', 'custom_filters', 'user_uuid'];
    protected $table = 'campaign_filter_reports';
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
            $model->user_uuid = request()->user()->user_uuid;
        });
    }

    public static function saveCampaignFilterReport(Request $request)
    {
        $report = new CampaignFilterReports($request->all());
        $report->filter_date_range = serialize($request['filter_date_range']);
        $report->save();
        return $report;
    }

    public static function getCampaignFilterReports(Request $request)
    {
        $reports = CampaignFilterReports::where('user_uuid', request()->user()->user_uuid)->get();
        return $reports;
    }

    public  function updateCampaignFilterReport(Request $request)
    {
        $data = $request->all();
        $data['filter_date_range'] = serialize($request->filter_date_range);
        return  self::where('uuid', $request->uuid)->update($this->fillableFromArray($data));
    }

    public function deleteFilterReport(Request $request)
    {
        $data =  $request->validate([
            'uuid' => 'required',
        ]);
        CampaignFilterReports::where('uuid', $request->uuid)->delete($data);
        return $this->respond([
            'status' => true,
            'message' => 'Filter Report has been deleted successfully!',
            'data' => []
        ]);
    }
}
