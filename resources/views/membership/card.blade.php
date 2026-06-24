@extends('layouts.landing')

@section('content')
    @include('sections.header')

    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mx-auto max-w-[420px]">

            <input type="checkbox" id="flip-card" class="peer hidden">

            <label for="flip-card" class="flip-card cursor-pointer">

                <div class="flip-card-inner">

                    {{-- FRONT --}}
                    <div id="membership-card" class="flip-card-front">

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

                                    <div class="mt-2 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">

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

            <div class="mt-5 flex flex-wrap justify-center gap-3">

                <button onclick="downloadMembershipCard()"
                    class="rounded-xl bg-[#05643b] px-5 py-3 text-sm font-medium text-white hover:bg-[#045533]">

                    Download Card

                </button>

                <button onclick="downloadStoryCard()"
                    class="rounded-xl border border-[#05643b] px-5 py-3 text-sm font-medium text-[#05643b] hover:bg-[#05643b]/5">

                    Download Story

                </button>

            </div>

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

    <div style="
        position:absolute;
        width:0;
        height:0;
        overflow:hidden;
    ">

        <div id="membership-story" style="
            width:1080px;
            height:1920px;
        ">

            <div class="relative h-full w-full overflow-hidden bg-[#05643b]">

                {{-- Background --}}
                <div class="absolute inset-0 bg-gradient-to-b from-[#05643b] via-[#0b3d2c] to-[#04281d]">
                </div>

                <div class="absolute -top-64 -right-64 h-[900px] w-[900px] rounded-full border border-white/10">
                </div>

                <div class="absolute -bottom-64 -left-64 h-[1000px] w-[1000px] rounded-full border border-white/10">
                </div>

                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_35%)]">
                </div>

                {{-- Header --}}
                <div class="absolute top-20 left-0 right-0 text-center text-white">

                    <img src="{{ asset('logo-soklenn-real-putih.png') }}" alt="Soklenn" class="mx-auto w-72">

                    <div class="mt-12 text-4xl uppercase tracking-[0.4em] text-white/60">

                        THIS CARD BELONGS TO

                    </div>

                    <div class="mt-6 text-8xl font-black">

                        {{ strtoupper($membership->customer->name) }}

                    </div>

                    <div class="mt-6 text-3xl text-white/75">

                        Proud Member of Soklenn

                    </div>

                </div>

                {{-- Card --}}
                <div class="absolute left-1/2 top-[740px] -translate-x-1/2">

                    <div
                        class="relative h-[540px] w-[856px] overflow-hidden rounded-[48px] bg-gradient-to-br from-[#05643b] via-[#0b3d2c] to-[#04281d] shadow-2xl">

                        <div class="absolute -top-32 -right-32 h-[400px] w-[400px] rounded-full border border-white/10">
                        </div>

                        <div class="absolute -bottom-40 -left-20 h-[450px] w-[450px] rounded-full border border-white/10">
                        </div>

                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_30%)]">
                        </div>

                        <div class="relative flex h-full flex-col p-12 text-white">

                            <div>

                                <div class="text-xl uppercase tracking-[0.4em] text-white/70">

                                    Soklenn Membership

                                </div>

                                <div class="mt-4 text-5xl font-black">

                                    @if ($membership->isFamily())
                                        ⭐ SOKLENN FAMILY
                                    @else
                                        SOKLENN MEMBER
                                    @endif

                                </div>

                            </div>

                            <div class="mt-auto">

                                <div class="text-5xl font-bold">
                                    {{ strtoupper($membership->customer->name) }}
                                </div>

                                <div class="mt-3 text-2xl text-white/70">
                                    {{ $membership->member_code }}
                                </div>

                            </div>

                            <div class="mt-8 flex items-end justify-between">

                                <div>

                                    <div class="text-lg uppercase tracking-wider text-white/60">

                                        Member Since

                                    </div>

                                    <div class="mt-2 text-2xl font-medium">
                                        {{ $membership->member_since?->format('d M Y') }}
                                    </div>

                                    <div class="mt-4 inline-flex rounded-full bg-white/15 px-5 py-2 text-lg font-semibold">

                                        {{ strtoupper($membership->tier) }}

                                    </div>

                                </div>

                                <div class="rounded-2xl bg-white p-4">

                                    <img crossorigin="anonymous"
                                        src="https://quickchart.io/qr?size=300&margin=1&text={{ urlencode($membershipUrl) }}"
                                        alt="QR Membership" class="h-40 w-40">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="absolute bottom-40 left-0 right-0 text-center text-white">

                    <div class="text-5xl font-light italic">

                        Every step tells a story.

                    </div>

                    <div class="mt-8 text-3xl text-white/75">

                        Thank you for being part of our journey.

                    </div>

                    <div class="mt-10 text-3xl font-black tracking-wide text-white/60">

                        #CUCISEPATUDISOKLENN

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        async function downloadMembershipCard() {
            const button = event.currentTarget;

            const originalText = button.innerHTML;

            button.disabled = true;

            button.innerHTML = `
        <svg class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <span>Generating...</span>
    `;

            try {

                const node = document.getElementById('membership-card');

                const dataUrl = await window.htmlToImage.toPng(node, {
                    pixelRatio: 4,
                    cacheBust: true,
                });

                const link = document.createElement('a');

                link.download = 'membership-{{ $membership->member_code }}.png';

                link.href = dataUrl;

                link.click();

                button.innerHTML = `
            ✓ Downloaded
        `;

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);

            } catch (error) {

                console.error(error);

                button.innerHTML = 'Failed';

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 2000);

            }
        }
    </script>

    <script>
        async function downloadStoryCard() {
            const node = document.getElementById('membership-story');

            const dataUrl = await window.htmlToImage.toPng(
                node, {
                    pixelRatio: 2,
                    cacheBust: true,
                }
            );

            const link = document.createElement('a');

            link.download =
                'membership-story-{{ $membership->member_code }}.png';

            link.href = dataUrl;

            link.click();
        }
    </script>
@endsection
