<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        $stats = [
            'today' => VisitorLog::where('visited_date', $today)->count(),
            'this_month' => VisitorLog::whereMonth('visited_date', $thisMonth)
                                      ->whereYear('visited_date', $thisYear)
                                      ->count(),
            'total' => VisitorLog::count(),
        ];

        // Ambil data pengunjung 7 hari terakhir untuk grafik (opsional)
        $chartData = VisitorLog::selectRaw('visited_date, count(*) as count')
            ->where('visited_date', '>=', Carbon::today()->subDays(7))
            ->groupBy('visited_date')
            ->orderBy('visited_date', 'asc')
            ->get();

        return view('master.visitor.index', compact('stats', 'chartData'));
    }
}
