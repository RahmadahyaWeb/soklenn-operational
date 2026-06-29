@extends('layouts.landing')

@section('content')
    @include('sections.header')

    @php
        $steps = [
            'pending' => ['title' => 'Order Diterima', 'description' => 'Sepatu telah diterima oleh tim Soklenn.'],
            'washing' => ['title' => 'Sedang Dicuci', 'description' => 'Sepatu sedang melalui proses pencucian.'],
            'drying' => ['title' => 'Sedang Dikeringkan', 'description' => 'Sepatu sedang dikeringkan.'],
            'finished' => ['title' => 'Selesai', 'description' => 'Sepatu sudah siap diambil.'],
            'picked_up' => ['title' => 'Sudah Diambil', 'description' => 'Order telah selesai.'],
        ];
        $keys = array_keys($steps);
        $current = array_search($order->status, $keys);
        $progress = (($current + 1) / count($steps)) * 100;
        $statusColor = match ($order->status) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'washing' => 'bg-sky-100 text-sky-700',
            'drying' => 'bg-indigo-100 text-indigo-700',
            'finished' => 'bg-green-100 text-green-700',
            'picked_up' => 'bg-zinc-900 text-white',
            default => 'bg-zinc-100 text-zinc-700',
        };
    @endphp

    <div class="min-h-screen bg-zinc-100 py-10">
        <div class="mx-auto max-w-4xl px-4 space-y-6">
            <div class="overflow-hidden rounded-[32px] bg-white shadow-xl">
                <div class="bg-[#05643b] p-6 sm:p-8 text-white">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="text-sm uppercase tracking-[.3em] text-white/70">Soklenn Invoice</div>
                            <h1 class="mt-2 text-3xl font-black">{{ $order->invoice_number }}</h1>
                        </div>
                        <span
                            class="rounded-full bg-white px-5 py-2 text-sm font-bold text-[#05643b]">{{ str($order->status)->replace('_', ' ')->title() }}</span>
                    </div>
                </div>

                <div class="space-y-8 p-6 sm:p-8">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border p-5">
                            <div class="text-xs uppercase text-zinc-500">Customer</div>
                            <div class="mt-2 font-bold">{{ $order->customer->name }}</div>
                        </div>
                        <div class="rounded-2xl border p-5">
                            <div class="text-xs uppercase text-zinc-500">Diterima</div>
                            <div class="mt-2 font-bold">{{ optional($order->received_at)->format('d M Y H:i') }}</div>
                        </div>
                        <div class="rounded-2xl border p-5">
                            <div class="text-xs uppercase text-zinc-500">Status</div>
                            <div class="mt-2"><span
                                    class="rounded-full px-3 py-1 text-sm font-semibold {{ $statusColor }}">{{ str($order->status)->replace('_', ' ')->title() }}</span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-[#05643b]/20 bg-[#05643b]/5 p-5">
                            <div class="text-xs uppercase text-[#05643b]">Grand Total</div>
                            <div class="mt-2 text-3xl font-black text-[#05643b]">Rp
                                {{ number_format($order->grand_total, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-black">Progress</h2><span
                                class="text-sm font-semibold text-[#05643b]">{{ round($progress) }}%</span>
                        </div>
                        <div class="mb-6 h-2 overflow-hidden rounded-full bg-zinc-200">
                            <div class="h-full bg-[#05643b]" style="width:{{ $progress }}%"></div>
                        </div>
                        <div class="overflow-x-auto">
                            <div class="flex min-w-[720px] items-start">
                                @foreach ($steps as $key => $step)
                                    @php($i = array_search($key, $keys))
                                    <div class="flex flex-1 flex-col items-center">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full {{ $i < $current ? 'bg-[#05643b] text-white' : ($i == $current ? 'bg-[#05643b] text-white' : 'bg-zinc-200 text-zinc-500') }}">
                                            {{ $i < $current ? '✓' : ($i == $current ? '●' : '○') }}</div>
                                        <div class="mt-3 text-center">
                                            <div class="font-bold {{ $i > $current ? 'text-zinc-400' : '' }}">
                                                {{ $step['title'] }}</div>
                                            <div class="text-xs {{ $i > $current ? 'text-zinc-400' : 'text-zinc-500' }}">
                                                {{ $step['description'] }}</div>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <div
                                            class="mt-5 h-0.5 flex-1 {{ $i < $current ? 'bg-[#05643b]' : 'bg-zinc-200' }}">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-xl font-black">Layanan</h2><span
                                class="rounded-full bg-zinc-100 px-3 py-1 text-sm font-semibold">{{ $order->details->count() }}
                                Layanan</span>
                        </div>
                        <div class="space-y-3">
                            @foreach ($order->details as $detail)
                                <div
                                    class="flex flex-col gap-4 rounded-2xl border p-5 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <div class="font-bold">{{ $detail->service->name }}</div>
                                        <div class="text-sm text-zinc-500">Qty {{ $detail->qty }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-zinc-500">Harga</div>
                                        <div class="font-black text-[#05643b]">Rp
                                            {{ number_format($detail->total, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-zinc-200 bg-zinc-50 p-5">

                        <div class="space-y-3">

                            <div class="flex items-center justify-between">
                                <span class="text-zinc-500">
                                    Subtotal
                                </span>

                                <span class="font-semibold">
                                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            @if ($order->discount > 0)
                                <div class="flex items-center justify-between text-red-600">
                                    <span>
                                        Diskon
                                    </span>

                                    <span class="font-semibold">
                                        - Rp {{ number_format($order->discount, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            @if ($order->delivery_fee > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-zinc-500">
                                        Ongkir
                                    </span>

                                    <span class="font-semibold">
                                        Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            <div class="border-t border-dashed pt-3">

                                <div class="flex items-center justify-between">

                                    <span class="text-lg font-bold">
                                        Grand Total
                                    </span>

                                    <span class="text-2xl font-black text-[#05643b]">
                                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>


            @if ($order->status != 'picked_up')
                <div class="rounded-3xl border border-[#05643b]/20 bg-white p-6 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>
                            <h2 class="text-xl font-black text-zinc-900">
                                Pembayaran QRIS
                            </h2>

                            <p class="mt-1 text-sm text-zinc-500">
                                Scan QRIS di bawah untuk menyelesaikan pembayaran.
                            </p>
                        </div>

                        <span class="rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-600">
                            Belum Dibayar
                        </span>

                    </div>

                    <div class="mt-8 grid gap-8 lg:grid-cols-[280px_1fr]">

                        <div class="flex flex-col items-center">

                            <img src="{{ asset('qris.png') }}" alt="QRIS"
                                class="w-64 rounded-2xl border bg-white p-3 shadow-sm">

                            <div class="mt-5 text-center">

                                <div class="text-sm text-zinc-500">
                                    Nominal Pembayaran
                                </div>

                                <div class="mt-2 text-3xl font-black text-[#05643b]">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </div>

                            </div>

                        </div>

                        <div>

                            <h3 class="font-bold text-zinc-900">
                                Cara Pembayaran
                            </h3>

                            <div class="mt-5 space-y-4">

                                <div class="flex gap-4">

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#05643b] text-sm font-bold text-white">
                                        1
                                    </div>

                                    <div>

                                        <div class="font-semibold">
                                            Scan QRIS
                                        </div>

                                        <div class="text-sm text-zinc-500">
                                            Buka aplikasi pembayaran seperti GoPay, OVO, DANA, ShopeePay, Livin', BRImo, BCA
                                            Mobile,
                                            atau aplikasi lain yang mendukung QRIS.
                                        </div>

                                    </div>

                                </div>

                                <div class="flex gap-4">

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#05643b] text-sm font-bold text-white">
                                        2
                                    </div>

                                    <div>

                                        <div class="font-semibold">
                                            Bayar Sesuai Nominal
                                        </div>

                                        <div class="text-sm text-zinc-500">
                                            Pastikan nominal yang dibayarkan sama dengan total tagihan.
                                        </div>

                                    </div>

                                </div>

                                <div class="flex gap-4">

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#05643b] text-sm font-bold text-white">
                                        3
                                    </div>

                                    <div>

                                        <div class="font-semibold">
                                            Kirim Bukti Pembayaran
                                        </div>

                                        <div class="text-sm text-zinc-500">
                                            Setelah pembayaran berhasil, kirim screenshot atau bukti transfer kepada admin
                                            Soklenn
                                            melalui WhatsApp.
                                        </div>

                                    </div>

                                </div>

                                <div class="flex gap-4">

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#05643b] text-sm font-bold text-white">
                                        4
                                    </div>

                                    <div>

                                        <div class="font-semibold">
                                            Verifikasi Pembayaran
                                        </div>

                                        <div class="text-sm text-zinc-500">
                                            Admin akan memverifikasi pembayaran dan status invoice akan diperbarui.
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-4">

                                <div class="font-semibold text-amber-700">
                                    Perhatian
                                </div>

                                <p class="mt-2 text-sm text-amber-700">
                                    Gunakan nominal yang sama dengan total tagihan agar pembayaran dapat diverifikasi dengan
                                    lebih
                                    mudah.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>
            @endif
        </div>
    </div>
@endsection
