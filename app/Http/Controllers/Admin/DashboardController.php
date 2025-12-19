<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan Tanggal (Default Hari Ini atau dari URL)
        $queryDate = $request->input('date'); 
        $currentDate = $queryDate ? Carbon::parse($queryDate) : Carbon::now();

        // 2. Navigasi Kalender
        $year = $currentDate->year;
        $month = $currentDate->month;
        $monthName = $currentDate->translatedFormat('F Y');
        
        $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);
        $startDayIndex = ($firstDayOfMonth->dayOfWeek + 6) % 7;

        $daysInMonth = $firstDayOfMonth->daysInMonth;

        $prevMonthLink = route('admin.dashboard', ['date' => $currentDate->copy()->subMonth()->format('Y-m-d')]);
        $nextMonthLink = route('admin.dashboard', ['date' => $currentDate->copy()->addMonth()->format('Y-m-d')]);

        // 3. LOGIKA TITIK MERAH (Marked Dates)
        // Cari tanggal yang memiliki pesanan berstatus 'pending' di bulan ini
        $markedDates = Order::whereYear('pickup_date', $year)
                            ->whereMonth('pickup_date', $month)
                            ->where('status', 'pending') // Hanya tandai yang pending/butuh aksi
                            ->pluck('pickup_date')
                            ->map(fn($date) => Carbon::parse($date)->day)
                            ->unique()
                            ->toArray();

        // 4. AMBIL PESANAN UNTUK TANGGAL YANG DIPILIH
        $selectedDateDisplay = $currentDate->translatedFormat('d F Y');
        
        $orders = Order::with(['user', 'orderItems.product'])
                       ->whereDate('pickup_date', $currentDate)
                       ->latest()
                       ->get();

        return view('admin.dashboard', compact(
            'currentDate', 'monthName', 'startDayIndex', 'daysInMonth', 
            'prevMonthLink', 'nextMonthLink', 'selectedDateDisplay', 
            'orders', 'markedDates'
        ));
    }
}