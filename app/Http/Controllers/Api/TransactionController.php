<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TransactionResource;
use App\Models\Campaign;
use Bavix\Wallet\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends ApiController
{
    public function getTransaction(Request $request)
    {

        $transaction = Transaction::where('payable_id', $request->user()->id)
        ->when($request->q, function ($query, $q) {
            return $query->where('type', 'LIKE', "%{$q}%");
        })
        ->when($request->campaignUuid, function ($query) {
            return $query->whereJsonContains('meta->campaign_id',Campaign::getIdByUuid(request()->campaignUuid));
        })
        ->when($request->sortBy, function ($query, $sortBy) use ($request) {
            return $query->orderBy($sortBy, $request->sortDesc ? 'asc' : 'desc');
        })
        ->when($request->page, function ($query, $page) {
            return $query->offset($page - 1);
        })
        ->when($request->dateRange, function ($query) {
            return self::getTimeRangeRecord($query);
        })
        ->when($request->type, function ($query) {
            return self::getDateFilters($query);
        })
        ->with('wallet')
        ->paginate($request->perPage);


        if (!empty($transaction)) {
            return $this->respond([
                'status' => true,
                'message' => 'Transactions has been fetched successfully!',
                'data' => [
                    'transactions' => $transaction
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'No Transaction Found!',
                'transaction' => []
            ]);
        }
    }

    public function getSingleTransaction($uuid)
    {
        // return response()->json($uuid);
        $transaction = Transaction::where('uuid', $uuid)->first();
        if (!empty($transaction)) {
            return $this->respond([
                'status' => true,
                'message' => 'Transaction has been fetched successfully!',
                'data' => [
                    'transaction' => new TransactionResource($transaction)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'No Transaction Found!',
                'transaction' => []
            ]);
        }
    }

    public static function getTimeRangeRecord($query) {
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
            return $query->whereBetween('transactions.created_at', [$startNewDate, $endNewDate])
                ->orderBy('transactions.created_at', 'asc');

        }
    }

    public static function getDateFilters($query) {
            $range = request()->value;
            $query->when(request()->type == 'today', function ($query) {
                // Get Data for today
                $query->whereBetween('transactions.created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                    ->groupBy('transactions.created_at');
            })
            // Get Yesterday Data
            ->when(request()->type == 'yesterday', function ($query) {
                 $query->whereDate('transactions.created_at', '=', Carbon::yesterday())
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get Last Two Days Data
            ->when(request()->type == 'lastTwoDays', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->subDays(2)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get Last Seven Days Data
            ->when(request()->type == 'lastSevenDays', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get This (Current) Week Data
            ->when(request()->type == 'thisWeek', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get Last Week Data
            ->when(request()->type == 'lastWeek', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get Last 30 Days Data
            ->when(request()->type == 'last30Days', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->subDays(30)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get This Month Data
            ->when(request()->type == 'thisMonth', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get Last Month Data
            ->when(request()->type == 'lastMonth', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get Last 6 Month Data
            ->when(request()->type == 'last6Month', function ($query) {
                 $query->whereBetween('transactions.created_at', [Carbon::now()->subMonth(6)->startOfDay(), Carbon::now()->endofDay()])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            // Get Last Year Data
            ->when(request()->type == 'lastYear', function ($query) {
                 $query->whereYear('transactions.created_at', Carbon::now()->subYear()->year)
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            })
            ->when(request()->type == 'weekly', function ($query) {
                // Laravel Get Week Wise Current Month Data
                 $query->whereMonth('transactions.created_at', date('m'))
                    ->whereYear('transactions.created_at', date('Y'));
            })
            ->when(request()->type == 'range', function ($query) use ($range) {
                $date_range = explode('to', $range);
                 $query->whereDate('transactions.created_at', '<=', $date_range[1])
                    ->whereDate('transactions.created_at', '>=', $date_range[0])
                    ->groupBy('transactions.created_at')
                    ->orderBy('transactions.created_at', 'asc');
            });

            return $query;
    }
}
