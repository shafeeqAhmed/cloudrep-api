<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use DateTime;
use DateTimeZone;
use FFMpeg\Filters\Audio\CustomFilter;

class CampaignReporting extends Model
{
    use HasFactory;
    use SoftDeletes;

    // protected $fillable = [
    //     'campaign_id','call_date','profit','campaign','publisher',
    //     'caller_id','dialed','time_to_call','duplicate','hangup','time_to_connect','target',
    //     'revenue','payout','duration','recording', 'client_uuid','hangup_reason','longitude','latitude'
    // ];
    protected $guarded =  ['id'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public static function getIdByUuid($uuid)
    {
        return self::where('id', $uuid)->value('id');
    }

    public static function getCampaignReportingByUuid($col, $val)
    {
        $record =  CampaignReporting::where($col, $val)->first();
        return $record;
    }

    // public static function getReportingByUser(Request $request) {
    //     $campaignReporting = CampaignReporting::
    //         when($request->role == 'admin', function ($query) {
    //             return $query->where('publisher_id', request()->user()->id);
    //         })
    //         ->when($request->soryBy, function ($query, $sortBy) {
    //             return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
    //         })
    //         ->when($request->page, function ($query, $page) {
    //             return $query->offset($page - 1);
    //         })
    //         ->with('tags')
    //         ->select('twillio_numbers.*', 'campaigns.campaign_name', 'users.user_uuid as publisher_uuid', 'users.name as publisher_name')->orderBy('id', 'DESC')->paginate($request->perPage);

    //     return $twillioNumber;
    // }

    public static function getCampaignClients()
    {
        $clients = CampaignReporting::join('users as cl', 'cl.id', '=', 'campaign_reportings.client_id')->select(
            'cl.name as name',
            'cl.user_uuid as uuid'
        )->groupBy('client_id')->orderBy('campaign_reportings.id', 'DESC')->get();


        $publishers = CampaignReporting::join('users as p', 'p.id', '=', 'campaign_reportings.publisher_id')->select(
            'p.name as name',
            'p.user_uuid as uuid'
        )->groupBy('publisher_id')->orderBy('campaign_reportings.id', 'DESC')->get();

        $campaigns = CampaignReporting::join('campaigns as cm', 'cm.id', '=', 'campaign_reportings.campaign_id')->select(
            'cm.campaign_name as name',
            'cm.uuid as uuid'
        )->when(request()->page, function ($query) {
            return self::getDataByUser($query);
        })->groupBy('campaign_id')->orderBy('campaign_reportings.id', 'DESC')->get();

        $targets = CampaignReporting::join('target_listings as tg', 'tg.id', '=', 'campaign_reportings.target_id')->select(
            'tg.name as name',
            'tg.uuid as uuid',
            'tg.destination as destination'
        )->when(request()->page, function ($query) {
            return self::getDataByUser($query);
        })->groupBy('target_id')->orderBy('campaign_reportings.id', 'DESC')->get();

        $caller_ids = CampaignReporting::groupBy('caller_id')
            ->select('caller_id')
            ->when(request()->page, function ($query) {
                return self::getDataByUser($query);
            })->orderBy('id', 'desc')->get();

        $dialed_numbers = CampaignReporting::groupBy('dialed')
            ->select('dialed')
            ->when(request()->page, function ($query) {
                return self::getDataByUser($query);
            })->orderBy('id', 'desc')->get();

        $call_durations = CampaignReporting::where('duration', '!=', null)
            ->select('duration')
            ->when(request()->page, function ($query) {
                return self::getDataByUser($query);
            })->groupBy('duration')->orderBy('id', 'desc')->get();

        $time_to_connect = CampaignReporting::where('time_to_connect', '!=', null)
            ->select('time_to_connect')
            ->when(request()->page, function ($query) {
                return self::getDataByUser($query);
            })->groupBy('time_to_connect')->orderBy('id', 'desc')->get();

        $time_to_call = CampaignReporting::where('time_to_call', '!=', null)
            ->select('time_to_call')
            ->when(request()->page, function ($query) {
                return self::getDataByUser($query);
            })->groupBy('time_to_call')->orderBy('id', 'desc')->get();

        $revenue = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null]])
            ->select('revenue')
            ->when(request()->page, function ($query) {
                return self::getDataByUser($query);
            })->groupBy('revenue')->orderBy('id', 'desc')->get();

        $payout = CampaignReporting::where([['payout', '!=', 0.0], ['payout', '!=', null]])
            ->select('payout')
            ->when(request()->page, function ($query) {
                return self::getDataByUser($query);
            })->groupBy('payout')->orderBy('id', 'desc')->get();

        $profit = CampaignReporting::where([['profit', '!=', 0.0], ['profit', '!=', null]])->select('profit')->groupBy('profit')->orderBy('id', 'desc')->get();


        $data['clients'] = $clients;
        $data['publishers'] = $publishers;
        $data['campaigns'] = $campaigns;
        $data['targets'] = $targets;
        $data['caller_ids'] = $caller_ids;
        $data['dialed_numbers'] = $dialed_numbers;
        $data['call_durations'] = $call_durations;
        $data['time_to_connect'] = $time_to_connect;
        $data['time_to_call'] = $time_to_call;
        $data['revenue'] = $revenue;
        $data['payout'] = $payout;
        $data['profit'] = $profit;
        return $data;
    }

    public static function getUserDashboardRecord($request)
    {
        $total_converted = 0;
        $total_leads = 0;
        $total_expenditure = 0;
        $total_payout = 0;
        $total_profit = 0;
        $total_duplicate = 0;
        $total_completed = 0;
        $total_incomming = 0;
        $total_goal = 0;
        $payout = [0, 0, 0, 0, 0];
        $revenue = [0, 0, 0, 0, 0];
        $profit = [0, 0, 0, 0, 0];

        if ($request->user_uuid) {
            $user = User::where('user_uuid', $request->user_uuid)->first();
            $role = $user->getRoleNames();
            $user_id = $user->id;
        } else {
            $user = User::where('user_uuid', $request->user()->user_uuid)->first();
            $role = $user->getRoleNames();
            $user_id = $user->id;
        }

        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);

        if ($role[0] == 'client') {

            $total_converted = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null], ['call_status', 'completed'], ['client_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();
            $total_leads = CampaignReporting::where('client_id', $user_id)
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();
            $total_expenditure = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null], ['call_status', 'completed'], ['client_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->sum('revenue');


            $total_completed = CampaignReporting::where([['call_status', 'completed'], ['client_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $total_incomming = CampaignReporting::where([['call_status', '!=', 'completed'], ['client_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $revenue = CampaignReporting::selectRaw("sum(revenue) as revenue")
                ->where('client_id', $user_id)
                ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endofDay()])
                ->groupBy('campaign_reportings.created_at')
                ->pluck('revenue');


            if ($total_completed != 0) {
                $total_goal = ($total_converted / $total_completed) * 100;
                $total_goal = round($total_goal, 2);
            }
        } else if ($role[0] == 'publisher') {

            $total_converted = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null], ['call_status', 'completed'], ['publisher_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $total_leads = CampaignReporting::where('publisher_id', $user_id)
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $total_payout = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null], ['call_status', 'completed'], ['publisher_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->sum('payout');


            $total_completed = CampaignReporting::where([['call_status', 'completed'], ['publisher_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $total_incomming = CampaignReporting::where([['call_status', '!=', 'completed'], ['publisher_id', $user_id]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $payout = CampaignReporting::selectRaw("sum(payout) as payout")
                ->where('publisher_id', $user_id)
                ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endofDay()])
                ->groupBy('campaign_reportings.created_at')
                ->pluck('payout');


            if ($total_completed != 0) {
                $total_goal = ($total_converted / $total_completed) * 100;
                $total_goal = round($total_goal, 2);
            }
        } else if ($role[0] == 'admin') {

            $total_converted = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null], ['call_status', 'completed']])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $total_leads = CampaignReporting::when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                $query->where('campaign_id', $campaignId);
            })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                return self::getTimeRangeRecord($query);
            })->count();

            $total_payout = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null], ['call_status', 'completed']])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->sum('payout');


            $total_expenditure = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null], ['call_status', 'completed']])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->sum('revenue');


            $total_profit = CampaignReporting::where([['revenue', '!=', 0.0], ['revenue', '!=', null]])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->sum('profit');


            $total_duplicate = CampaignReporting::where('duplicate', 1)
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();


            $total_completed = CampaignReporting::where([['call_status', 'completed']])
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();


            $total_incomming = CampaignReporting::where('call_status', '!=', 'completed')
                ->when($request->get('campaign_uuid'), function ($query) use ($campaignId) {
                    $query->where('campaign_id', $campaignId);
                })->when($request->get('dateRange'), function ($query) use ($campaignId) {
                    return self::getTimeRangeRecord($query);
                })->count();

            $payout = CampaignReporting::selectRaw("sum(payout) as payout")
                ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endofDay()])
                ->groupBy('campaign_reportings.created_at')
                ->pluck('payout');

            $revenue = CampaignReporting::selectRaw("sum(revenue) as revenue")
                ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endofDay()])
                ->groupBy('campaign_reportings.created_at')
                ->pluck('revenue');

            $profit = CampaignReporting::selectRaw("sum(profit) as profit")
                ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endofDay()])
                ->groupBy('campaign_reportings.created_at')
                ->pluck('profit');


            if ($total_completed != 0) {
                $total_goal = ($total_converted / $total_completed) * 100;
                $total_goal = round($total_goal, 2);
            }
        }

        $data['total_expenditure'] = $total_expenditure;
        $data['total_completed'] = $total_completed;
        $data['total_goal'] = $total_goal;
        $data['total_converted'] = $total_converted;
        $data['total_leads'] = $total_leads;
        $data['total_payout'] = $total_payout;
        $data['total_incomming'] = $total_incomming;
        $data['total_duplicate'] = $total_duplicate;
        $data['total_profit'] = $total_profit;
        $data['profit_trend'] = $profit;
        $data['payout_trend'] = $payout;
        $data['revenue_trend'] = $revenue;

        return $data;
    }

    public static function getCampaignReporting(Request $request)
    {

        //$customFilters = json_decode($request->customFilters, true);
        $campaignReporting = CampaignReporting::join('campaigns as c', 'c.id', '=', 'campaign_reportings.campaign_id')
            ->join('users as p', 'p.id', '=', 'campaign_reportings.publisher_id')
            ->join('users as cl', 'cl.id', '=', 'campaign_reportings.client_id')
            ->leftJoin('target_listings as tl', 'tl.id', '=', 'campaign_reportings.target_id')
            ->when($request->q, function ($query, $q) {
                return $query->where('c.campaign_name', 'LIKE', "%{$q}%");
            })
            // ->when($request->soryBy, function ($query, $sortBy) {
            //     return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            // })
            ->select(
                'campaign_reportings.call_date',
                'campaign_reportings.profit',
                'c.campaign_name',
                'p.name as publisher_name',
                'campaign_reportings.caller_id',
                'campaign_reportings.dialed',
                'campaign_reportings.time_to_call',
                'campaign_reportings.duplicate',
                'campaign_reportings.hangup',
                'campaign_reportings.time_to_connect',
                'tl.name as target_name',
                'campaign_reportings.revenue',
                'campaign_reportings.payout',
                'campaign_reportings.profit',
                'campaign_reportings.duration',
                'c.currency',
                'campaign_reportings.recording',
                'campaign_reportings.call_status'
            )
            ->when($request->page, function ($query, $page) {
                $query->offset($page - 1);
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

                    $query->whereBetween('campaign_reportings.created_at', [$startNewDate, $endNewDate])
                        ->orderBy('campaign_reportings.created_at', 'asc');
                }
                self::getDataByUser($query);
                return $query;
            })
            ->when($request->user_uuid, function ($query, $user_uuid) {
                $user = User::where('user_uuid', $user_uuid)->first();
                $role = $user->getRoleNames();
                if ($role[0] == 'publisher') {
                    return $query->where('campaign_reportings.publisher_id', $user->id);
                } else if ($role[0] == 'client') {
                    return $query->where('campaign_reportings.client_id', $user->id);
                } else if ($role[0] == 'admin') {
                    return $query;
                }
            })
            ->when($request->customFilters, function ($query) use ($request) {
                return getFilterandTags($query);
            })
            ->when($request->type, function ($query) {
                self::getDataByUser($query);
            })
            ->orderBy('campaign_reportings.id', 'DESC')->paginate($request->perPage);

        return $campaignReporting;
    }

    public static function getTimeline(Request $request)
    {
        $range = $request->value;
        // $customFilters = json_decode($request->customFilters, true);
        $timeline = CampaignReporting::join('campaigns as c', 'c.id', '=', 'campaign_reportings.campaign_id')
            ->when($request->type == 'today', function ($query) {

                // Get Data for today
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->groupBy('Date');
            })
            // Get Yesterday Data
            ->when($request->type == 'yesterday', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereDate('campaign_reportings.created_at', '=', Carbon::yesterday())
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Two Days Data
            ->when($request->type == 'lastTwoDays', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(2)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Seven Days Data
            ->when($request->type == 'lastSevenDays', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get This (Current) Week Data
            ->when($request->type == 'thisWeek', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Week Data
            ->when($request->type == 'lastWeek', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last 30 Days Data
            ->when($request->type == 'last30Days', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get This Month Data
            ->when($request->type == 'thisMonth', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Month Data
            ->when($request->type == 'lastMonth', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last 6 Month Data
            ->when($request->type == 'last6Month', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subMonth(6)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Year Data
            ->when($request->type == 'lastYear', function ($query) {
                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereYear('campaign_reportings.created_at', Carbon::now()->subYear()->year)
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            ->when($request->type == 'weekly', function ($query) {
                // Laravel Get Week Wise Current Month Data
                return $query->select(DB::raw("(COUNT(*)) as count"))
                    ->whereMonth('campaign_reportings.created_at', date('m'))
                    ->whereYear('campaign_reportings.created_at', date('Y'));
            })
            ->when($request->type == 'range', function ($query) use ($range) {

                $dateRange = json_decode($range, true);

                $startNewDate = $dateRange['startDate'];
                $startNewDate =  Carbon::parse($startNewDate)->format('Y-m-d H:i:s');

                $endNewDate = $dateRange['endDate'];
                $endNewDate =  Carbon::parse($endNewDate)->format('Y-m-d H:i:s');

                return $query->select(DB::raw("(COUNT(*)) as count"), DB::raw("DATE(campaign_reportings.created_at) as Date"))
                    ->whereDate('campaign_reportings.created_at', '<=', $endNewDate)
                    ->whereDate('campaign_reportings.created_at', '>=', $startNewDate)
                    ->groupBy('date')
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            ->when($request->user_uuid, function ($query, $user_uuid) {
                $user = User::where('user_uuid', $user_uuid)->first();
                $role = $user->getRoleNames();
                if ($role[0] == 'publisher') {
                    return $query->where('campaign_reportings.publisher_id', $user->id);
                } else if ($role[0] == 'client') {
                    return $query->where('campaign_reportings.client_id', $user->id);
                } else if ($role[0] == 'admin') {
                    return $query;
                }
            })
            ->when($request->customFilters, function ($query) use ($request) {
                return getFilterandTags($query);
            })
            ->when($request->type, function ($query) {
                self::getDataByUser($query);
            })
            ->get();
        return $timeline;
    }
    public static function getPerformanceSumary(Request $request)
    {

        $range = $request->value;
        $timeline = CampaignReporting::join('campaigns as c', 'c.id', '=', 'campaign_reportings.campaign_id')
            ->when($request->type == 'today', function ($query) {

                // Get Data for today
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->groupBy(['date', 'call_status']);
            })
            // Get Yesterday Data
            ->when($request->type == 'yesterday', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereDate('campaign_reportings.created_at', '=', Carbon::yesterday())
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Two Days Data
            ->when($request->type == 'lastTwoDays', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(2)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Seven Days Data
            ->when($request->type == 'lastSevenDays', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(campaign_reportings.id)) as count"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get This (Current) Week Data
            ->when($request->type == 'thisWeek', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Week Data
            ->when($request->type == 'lastWeek', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last 30 Days Data
            ->when($request->type == 'last30Days', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get This Month Data
            ->when($request->type == 'thisMonth', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Month Data
            ->when($request->type == 'lastMonth', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last 6 Month Data
            ->when($request->type == 'last6Month', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereBetween('campaign_reportings.created_at', [Carbon::now()->subMonth(6)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            // Get Last Year Data
            ->when($request->type == 'lastYear', function ($query) {
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereYear('campaign_reportings.created_at', Carbon::now()->subYear()->year)
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            ->when($request->type == 'weekly', function ($query) {
                // Laravel Get Week Wise Current Month Data
                self::getDataByUser($query);
                return $query->select(
                    DB::raw("(COUNT(*)) as count")
                )
                    ->whereMonth('campaign_reportings.created_at', date('m'))
                    ->whereYear('campaign_reportings.created_at', date('Y'));
            })
            ->when($request->type == 'range', function ($query) use ($range) {
                self::getDataByUser($query);
                $date_range = explode('to', $range);
                return $query->select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("DATE(campaign_reportings.created_at) as Date"),
                    DB::raw("(Sum(payout)) as payout"),
                    DB::raw("(Sum(revenue)) as revenue"),
                    DB::raw("c.currency as currency"),
                    'call_status'
                )
                    ->whereDate('campaign_reportings.created_at', '<=', $date_range[1])
                    ->whereDate('campaign_reportings.created_at', '>=', $date_range[0])
                    ->groupBy(['date', 'call_status'])
                    ->orderBy('campaign_reportings.created_at', 'asc');
            })
            ->when($request->customFilters, function ($query) use ($request) {
                return getFilterandTags($query);
            })
            ->get();
        return $timeline;
    }
    public static function getPerformanceReport(Request $request)
    {

        $report = CampaignReporting::join('campaigns as c', 'c.id', '=', 'campaign_reportings.campaign_id')
            ->selectRaw('c.currency as currency, count(campaign_reportings.id) as total ,sum(payout) as payout , sum(revenue) as revenue,  DATE(campaign_reportings.created_at) as Date, CONCAT(DATE(campaign_reportings.created_at),"/", DAYNAME(campaign_reportings.created_at)) AS Day,call_status')->whereBetween('campaign_reportings.created_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endofDay()])
            ->groupBy('date')
            ->when($request->sortDesc, function ($query) {
                self::getDataByUser($query);
            })
            ->orderBy('campaign_reportings.created_at', 'desc')
            ->get();
        return $report;
    }

    public static function getTimeLineSummary(Request $request)
    {
        $summary = CampaignReporting::join('campaigns as c', 'c.id', '=', 'campaign_reportings.campaign_id')
            ->when($request->type == 'campaign', function ($query) {
                self::getDataByUser($query);
                return $query
                    ->selectRaw('c.campaign_name as name, c.currency as currency, sum(payout) as payout, sum(revenue) as revenue, sum(profit) as profit, campaign_reportings.campaign_id')
                    ->groupBy('campaign_reportings.campaign_id')
                    ->orderBy('c.id', 'asc');
            })
            ->when($request->type == 'publisher', function ($query) {
                self::getDataByUser($query);
                return $query->join('users as p', 'p.id', '=', 'campaign_reportings.publisher_id')
                    ->selectRaw('p.name as name, sum(payout) as payout, c.currency as currency,sum(revenue) as revenue, sum(profit) as profit, publisher_id')
                    ->groupBy('campaign_reportings.publisher_id')
                    ->orderBy('p.id', 'asc');
            })
            ->when($request->type == 'client', function ($query) {
                self::getDataByUser($query);
                return $query->join('users as cl', 'cl.id', '=', 'campaign_reportings.client_id')
                    ->selectRaw('cl.name as name, sum(payout) as payout, c.currency as currency,sum(revenue) as revenue, sum(profit) as profit, campaign_reportings.client_id')
                    ->groupBy('campaign_reportings.client_id')
                    ->orderBy('cl.id', 'asc');
            })
            ->when($request->type == 'target', function ($query) {
                self::getDataByUser($query);
                return $query->join('target_listings as tl', 'tl.id', '=', 'campaign_reportings.target_id')
                    ->selectRaw('tl.name as name, sum(payout) as payout,  c.currency as currency,sum(revenue) as revenue, sum(profit) as profit, target_id')
                    ->groupBy('campaign_reportings.target_id')
                    ->orderBy('tl.id', 'asc');
            })
            ->when($request->type == 'dialed', function ($query) {
                self::getDataByUser($query);
                return $query->selectRaw('dialed as dialed, sum(payout) as payout,  c.currency as currency, sum(revenue) as revenue, sum(profit) as profit')
                    ->groupBy('dialed')
                    ->orderBy('dialed', 'asc');
            })
            ->when($request->type == 'duplicate', function ($query) {
                self::getDataByUser($query);
                return $query->selectRaw('duplicate as duplicated, sum(payout) as payout,  c.currency as currency, sum(revenue) as revenue, sum(profit) as profit')
                    ->groupBy('duplicate')
                    ->orderBy('duplicate', 'desc');
            })
            ->when($request->type == 'date', function ($query) {
                self::getDataByUser($query);
                return $query->selectRaw('campaign_reportings.created_at, sum(payout) as payout, c.currency as currency, sum(revenue) as revenue, sum(profit) as profit')
                    ->groupBy(DB::raw("DATE_FORMAT(campaign_reportings.created_at,'%d-%m-%Y')"))
                    ->orderBy('campaign_reportings.created_at', 'desc');
            })
            ->when($request->user_uuid, function ($query, $user_uuid) {
                $user = User::where('user_uuid', $user_uuid)->first();
                $role = $user->getRoleNames();
                if ($role[0] == 'publisher') {
                    return $query->where('campaign_reportings.publisher_id', $user->id);
                } else if ($role[0] == 'client') {
                    return $query->where('campaign_reportings.client_id', $user->id);
                } else if ($role[0] == 'admin') {
                    return $query;
                }
            })
            ->when($request->type, function ($query) {
                return getTimeRangeRecord($query);
            })
            ->when($request->customFilters, function ($query) use ($request) {
                return getFilterandTags($query);
            })
            ->orderBy('campaign_reportings.id', 'DESC')->paginate($request->perPage);

        return $summary;
    }

    public static function getTopPerformers(Request $request)
    {
        $topPerformers = CampaignReporting::join('campaigns as c', 'c.id', '=', 'campaign_reportings.campaign_id')
            ->when($request->type == 'publisher', function ($query) {
                self::getDataByUser($query);
                return $query->join('users as u', 'u.id', '=', 'campaign_reportings.publisher_id')
                    ->selectRaw('u.name as name, sum(payout) as payout, sum(revenue) as revenue, sum(profit) as profit, c.currency as currency, publisher_id')
                    ->where('call_status', 'completed')
                    ->groupBy('campaign_reportings.publisher_id');
            })->when($request->type == 'campaign', function ($query) {
                self::getDataByUser($query);
                return $query->selectRaw('c.campaign_name as name, sum(payout) as payout, sum(revenue) as revenue, sum(profit) as profit, c.currency as currency, campaign_id')
                    ->where('call_status', 'completed')
                    ->groupBy('campaign_reportings.campaign_id');
            })->when($request->type == 'target', function ($query) {
                return $query->join('target_listings as tl', 'tl.id', '=', 'campaign_reportings.target_id')
                    ->selectRaw('tl.name as name, sum(payout) as payout, sum(revenue) as revenue, sum(profit) as profit, c.currency as currency, target_id')
                    ->where('call_status', 'completed')
                    ->groupBy('campaign_reportings.target_id');
            })
            ->get();

        return $topPerformers;
    }

    protected function callDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  request('time_zone')  ? convertDateTimeToYearDayTime($value, 'UTC', request('time_zone'))   : Carbon::parse($value)->format('M d H:i:s A'),
        );
    }
    public static function getCallCountOfCountries($request)
    {
        return self::when($request->user_uuid, function ($query, $user_uuid) {
            $user = User::where('user_uuid', $user_uuid)->first();
            $role = $user->getRoleNames();
            if ($role[0] == 'publisher') {
                return $query->where('campaign_reportings.publisher_id', $user->id);
            } else if ($role[0] == 'client') {
                return $query->where('campaign_reportings.client_id', $user->id);
            } else if ($role[0] == 'admin') {
                return $query;
            }
        })
            ->when($request->page, function ($query) {
                return getTimeRangeRecord($query);
            })
            ->when($request->customFilters, function ($query) use ($request) {
                return getFilterandTags($query);
            })
            ->groupBy('caller_country')
            ->selectRaw('count(*) as total, caller_country')
            ->pluck('total', 'caller_country');
    }

    public static function getDataByUser($query)
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

    public static function getTimeRangeRecord($query)
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
    }
}
