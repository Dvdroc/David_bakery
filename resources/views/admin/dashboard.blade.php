@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
        <p class="text-sm text-gray-500">Pantau jadwal produksi dan pesanan masuk.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Kalender Pesanan</h3>
            
            <div class="flex items-center space-x-4 bg-gray-50 px-4 py-2 rounded-lg shadow-sm">
                <a href="{{ $prevMonthLink }}" class="text-gray-400 hover:text-orange-600 font-bold px-2">&lt;</a>
                <span class="font-semibold text-gray-800 w-32 text-center select-none">{{ $monthName }}</span>
                <a href="{{ $nextMonthLink }}" class="text-gray-400 hover:text-orange-600 font-bold px-2">&gt;</a>
            </div>
        </div>

        <div class="grid grid-cols-7 gap-2 text-center">
            <div class="text-gray-400 text-xs font-bold uppercase py-2">Min</div>
            <div class="text-gray-400 text-xs font-bold uppercase py-2">Sen</div>
            <div class="text-gray-400 text-xs font-bold uppercase py-2">Sel</div>
            <div class="text-gray-400 text-xs font-bold uppercase py-2">Rab</div>
            <div class="text-gray-400 text-xs font-bold uppercase py-2">Kam</div>
            <div class="text-gray-400 text-xs font-bold uppercase py-2">Jum</div>
            <div class="text-gray-400 text-xs font-bold uppercase py-2">Sab</div>

            @for ($i = 0; $i < $startDayIndex; $i++) <div class="h-10"></div> @endfor

            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $isSelected = ($currentDate->day == $day);
                    $hasEvent = in_array($day, $markedDates);
                    $dateUrl = route('admin.dashboard', ['date' => $currentDate->copy()->setDay($day)->format('Y-m-d')]);
                @endphp

                <a href="{{ $dateUrl }}" class="flex flex-col items-center justify-center h-12 w-full rounded-lg transition relative group
                    {{ $isSelected ? 'bg-orange-50 border-2 border-orange-500 text-orange-700 font-bold' : 'hover:bg-gray-50 text-gray-600' }}">
                    
                    <span>{{ $day }}</span>

                    @if ($hasEvent)
                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full absolute bottom-2 animate-pulse"></span>
                    @endif
                </a>
            @endfor
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Pesanan</h3>
                <p class="text-sm text-gray-500">
                    Detail untuk tanggal <span class="font-bold text-orange-600">{{ $selectedDateDisplay }}</span>
                </p>
            </div>
            @if($orders->count() > 0)
                <span class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full font-bold">
                    {{ $orders->count() }} Pesanan
                </span>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
                <a href="{{ route('admin.notifications') }}" class="block group">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-orange-50 hover:border-orange-200 transition cursor-pointer relative overflow-hidden">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-400 opacity-0 group-hover:opacity-100 transition"></div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-orange-200 shadow-sm group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 group-hover:text-orange-700">
                                    {{ $order->user->name }} 
                                    <span class="text-xs font-normal text-gray-400 ml-2">#APM-{{ $order->id }}</span>
                                </h4>
                                <p class="text-xs text-gray-500">
                                    {{ $order->orderItems->first()->product->name ?? 'Kue' }} 
                                    @if($order->orderItems->count() > 1) 
                                        dan {{ $order->orderItems->count() - 1 }} lainnya
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <span class="block text-lg font-bold text-gray-700 group-hover:text-orange-600">
                                {{ $order->orderItems->sum('quantity') }} pcs
                            </span>
                            <span class="text-xs font-bold {{ $order->status == 'pending' ? 'text-yellow-600' : 'text-green-600' }}">
                                {{ ucfirst($order->status) }} &rarr;
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-10">
                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada pesanan di tanggal ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection