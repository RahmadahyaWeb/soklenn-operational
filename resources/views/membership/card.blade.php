<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $membership->customer->name }} - Membership Card</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .flip-card {
            display: block;
            perspective: 1200px;
            width: 100%;
            aspect-ratio: 1.586;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform .7s;
            transform-style: preserve-3d;
        }

        .peer:checked+.flip-card .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            inset: 0;
            overflow: hidden;
            border-radius: 24px;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, .20);
        }

        .flip-card-back {
            transform: rotateY(180deg);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">

    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mx-auto max-w-[420px]">

            <input type="checkbox" id="flip-card" class="peer hidden">

            <label for="flip-card" class="flip-card cursor-pointer">

                <div class="flip-card-inner">

                    {{-- FRONT --}}
                    <div class="flip-card-front">

                        @php
                            $membershipUrl = route('membership.card', $membership->public_token);
                        @endphp

                        <div class="absolute inset-0 bg-gradient-to-br from-[#05643b] via-[#0b3d2c] to-[#04281d]">
                        </div>

                        <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full border border-white/10">
                        </div>

                        <div class="absolute -bottom-24 -left-10 h-72 w-72 rounded-full border border-white/10">
                        </div>

                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_30%)]">
                        </div>

                        <div class="relative flex h-full flex-col p-6 text-white">

                            <div class="flex items-start justify-between">

                                <div>

                                    <div class="text-[11px] uppercase tracking-[0.35em] text-white/70">

                                        Soklenn Membership

                                    </div>

                                    <h2 class="mt-2 text-xl font-bold">

                                        @if ($membership->isFamily())
                                            ⭐ SOKLENN FAMILY
                                        @else
                                            SOKLENN MEMBER
                                        @endif

                                    </h2>

                                </div>

                                <div class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium backdrop-blur">

                                    TAP CARD

                                </div>

                            </div>

                            <div class="mt-auto">

                                <div class="text-xl font-bold">
                                    {{ strtoupper($membership->customer->name) }}
                                </div>

                                <div class="mt-1 text-white/70">
                                    {{ $membership->member_code }}
                                </div>

                            </div>

                            <div class="mt-4 flex items-end justify-between">

                                <div>

                                    <div class="text-[10px] uppercase tracking-wider text-white/60">

                                        Member Since

                                    </div>

                                    <div class="mt-1 text-sm font-medium">
                                        {{ $membership->member_since?->format('d M Y') }}
                                    </div>

                                    <div
                                        class="mt-2 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">

                                        {{ strtoupper($membership->tier) }}

                                    </div>

                                </div>

                                <div class="rounded-xl bg-white p-2 shadow-lg">

                                    <img src="https://quickchart.io/qr?size=120&margin=1&text={{ urlencode($membershipUrl) }}"
                                        alt="QR Membership" class="h-16 w-16">

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- BACK --}}
                    <div class="flip-card-back">

                        <div class="absolute inset-0 bg-gradient-to-br from-[#05643b] via-[#0b3d2c] to-[#04281d]">
                        </div>

                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,.15),transparent_30%)]">
                        </div>

                        <div class="relative flex h-full flex-col justify-center px-6 text-white">

                            <div class="text-center">

                                <div class="text-[11px] uppercase tracking-[0.35em] text-white/70">

                                    Stamp Card

                                </div>

                            </div>

                            <div class="mt-6 flex justify-center">

                                <div class="grid grid-cols-5 gap-2">
                                    @php

                                        $rewardLabels = [
                                            3 => '10%',
                                            5 => '10K',
                                            7 => '15%',
                                            10 => '20K',
                                            12 => '20%',
                                            15 => 'FAM',
                                        ];

                                    @endphp

                                    @for ($i = 1; $i <= 15; $i++)
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full border-2 text-[10px] font-bold
        {{ $membership->stamp >= $i
            ? 'border-white bg-white text-[#05643b]'
            : 'border-white/30 bg-white/10 text-white/70' }}">

                                            {{ $rewardMap[$i] ?? $i }}

                                        </div>
                                    @endfor

                                </div>

                            </div>

                            @if ($membership->stamp >= 15)
                                <div class="mt-6 flex justify-center">

                                    <div
                                        class="rounded-full bg-emerald-500/20 px-4 py-2 text-sm font-semibold text-emerald-200">

                                        ⭐ Family Member

                                    </div>

                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            </label>

        </div>

        {{-- Next Reward --}}
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Next Reward
            </h2>

            @if ($nextReward)
                <div class="mt-4">

                    <div class="text-xl font-semibold text-[#05643b]">
                        {{ $nextReward->name }}
                    </div>

                    <div class="mt-2 text-sm text-gray-500">

                        Butuh
                        <span class="font-semibold">
                            {{ $nextReward->required_stamp - $membership->stamp }}
                        </span>
                        stamp lagi

                    </div>

                </div>
            @else
                <div class="mt-4 text-green-600 font-semibold">
                    🎉 Selamat! Anda telah mencapai reward tertinggi.
                </div>
            @endif

        </div>

        {{-- Available Rewards --}}
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <h2 class="text-lg font-bold text-gray-900">
                    🎁 Reward Saya
                </h2>

                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

                    {{ $availableRewards->count() }} Reward

                </span>

            </div>

            <div class="mt-4 space-y-3">

                @forelse ($availableRewards as $claim)
                    <div class="rounded-2xl border border-green-200 bg-green-50 p-4">

                        <div class="font-semibold text-green-800">
                            {{ $claim->reward->name }}
                        </div>

                        <div class="mt-1 text-sm text-green-700">
                            Diperoleh
                            {{ $claim->claimed_at?->format('d M Y') }}
                        </div>

                    </div>

                @empty

                    <div class="text-center text-gray-500 py-6">
                        Belum ada reward tersedia.
                    </div>
                @endforelse

            </div>

        </div>

        {{-- Reward History --}}
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Reward History
            </h2>

            <div class="mt-4 space-y-3">

                @forelse ($usedRewards as $claim)
                    <div class="rounded-2xl border p-4">

                        <div class="flex items-start justify-between">

                            <div>

                                <div class="font-semibold">
                                    {{ $claim->reward->name }}
                                </div>

                                <div class="mt-1 text-sm text-gray-500">

                                    Digunakan:
                                    {{ $claim->used_at?->format('d M Y') }}

                                </div>

                                @if ($claim->order)
                                    <div class="text-sm text-gray-500">

                                        Invoice:
                                        {{ $claim->order->invoice_number }}

                                    </div>
                                @endif

                            </div>

                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">

                                Used

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="text-center text-gray-500 py-6">
                        Belum ada riwayat reward.
                    </div>
                @endforelse

            </div>

        </div>

    </div>

</body>

</html>
