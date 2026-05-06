<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\DamageReport;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date 
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date 
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfMonth();

        $topDamaged = DamageReport::whereBetween('created_at', [$startDate, $endDate])
            ->select('item_name', DB::raw('count(*) as total'))
            ->groupBy('item_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topLabs = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->select('area_id', DB::raw('count(*) as total'))
            ->with('area')
            ->groupBy('area_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topMaterials = DB::table('equipment_voucher')
            ->join('vouchers', 'equipment_voucher.voucher_id', '=', 'vouchers.id')
            ->join('equipment', 'equipment_voucher.equipment_id', '=', 'equipment.id')

            ->whereBetween('vouchers.created_at', [$startDate, $endDate])

            ->select(
                'equipment.product',
                DB::raw('SUM(equipment_voucher.quantity) as total')
            )

            ->groupBy('equipment.product')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $totalReports = DamageReport::whereBetween('created_at', [$startDate, $endDate])->count();

        $pending = DamageReport::where('status', 'Pendiente')
            ->whereBetween('created_at', [$startDate, $endDate])->count();

        $inProgress = DamageReport::where('status', 'En proceso')
            ->whereBetween('created_at', [$startDate, $endDate])->count();

        $resolved = DamageReport::where('status', 'Resuelto')
            ->whereBetween('created_at', [$startDate, $endDate])->count();

        $cancelled = DamageReport::where('status', 'Cancelado')
            ->whereBetween('created_at', [$startDate, $endDate])->count();

        $reportsByDay = DamageReport::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $reportsByDay->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m'));
        $totals = $reportsByDay->pluck('total');

        $types = DamageReport::whereBetween('created_at', [$startDate, $endDate])
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        $typesLabels = $types->pluck('type');
        $typesData = $types->pluck('total');

        $criticalArea = DamageReport::whereBetween('created_at', [$startDate, $endDate])
            ->select('build', 'area', DB::raw('count(*) as total'))
            ->groupBy('build', 'area')
            ->orderByDesc('total')
            ->first();

        $recentReports = DamageReport::whereBetween('created_at', [$startDate, $endDate])
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('admin.estadisticas.index', compact(
            'topDamaged',
            'topLabs',
            'topMaterials',

            'totalReports',
            'pending',
            'inProgress',
            'resolved',
            'cancelled',

            'dates',
            'totals',

            'typesLabels',
            'typesData',

            'criticalArea',
            'recentReports',

            'startDate',
            'endDate'
        ));
    }
}
