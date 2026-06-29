@extends('layouts.landing')

@section('content')
    @include('sections.header')

    <div class="max-w-[420px] mx-auto px-4 py-8 space-y-6">
        <div class="mx-auto w-full max-w-[420px]">

            <div id="flip-card" class="flip-card cursor-pointer">

                <div id="flip-card-inner" class="flip-card-inner">

                    {{-- FRONT --}}
                    <div class="flip-card-front">

                        @include('membership.partials.card-front')

                    </div>

                    {{-- BACK --}}
                    <div class="flip-card-back">

                        <div class="absolute inset-0 bg-gradient-to-br from-[#05643b] via-[#0b3d2c] to-[#04281d]"></div>

                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,.15),transparent_30%)]">
                        </div>

                        <div class="relative flex h-full flex-col justify-center px-4 sm:px-6 text-white">

                            <div class="text-center">

                                <div class="text-[9px] sm:text-[11px] uppercase tracking-[0.35em] text-white/70">
                                    Stamp
                                </div>

                            </div>

                            <div class="mt-5 flex justify-center">

                                <div class="grid grid-cols-5 gap-2 sm:gap-3">

                                    @for ($i = 1; $i <= 15; $i++)
                                        <div
                                            class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full border-2 text-[10px] sm:text-xs font-bold
            {{ $membership->stamp >= $i
                ? 'border-white bg-white text-[#05643b]'
                : 'border-white/30 bg-white/10 text-white/70' }}">

                                            {{ $rewardMap[$i] ?? $i }}

                                        </div>
                                    @endfor

                                </div>

                            </div>

                            @if ($membership->stamp >= 15)
                                <div class="mt-5 flex justify-center">

                                    <div
                                        class="rounded-full bg-emerald-500/20 px-4 py-2 text-xs sm:text-sm font-semibold text-emerald-200">

                                        ⭐ Family Member

                                    </div>

                                </div>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">

                <button onclick="downloadMembershipCard(event)"
                    class="w-full rounded-xl bg-[#05643b] px-4 py-3 text-sm font-medium text-white hover:bg-[#045533]">

                    Download Card

                </button>

                @if ($membership->card_image)
                    <button id="download-story-btn" onclick="downloadStoryCard()"
                        class="w-full rounded-xl border border-[#05643b] px-4 py-3 text-sm font-medium text-[#05643b] hover:bg-[#05643b]/5">

                        Download Story

                    </button>
                @endif

            </div>

            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">

                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 flex-shrink-0 text-amber-600" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14m-6 0l-4.553 2.276A1 1 0 013 15.382V8.618a1 1 0 011.447-.894L9 10m6 0V6a3 3 0 10-6 0v4m6 0H9" />

                </svg>

                <p class="text-sm leading-6 text-amber-800">
                    <span class="font-semibold">Tips:</span>
                    Ketuk kartu membership untuk membalik kartu dan melihat bagian belakang yang berisi
                    progress stamp kamu.
                </p>

            </div>

        </div>


        {{-- Membership Information --}}
        <div class="space-y-6">

            {{-- Next Reward --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">
                        Reward Berikutnya
                    </h2>

                    @if ($nextReward)
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                            {{ $membership->stamp }} Stamp
                        </span>
                    @endif
                </div>

                @if ($nextReward)
                    @php
                        $remaining = max($nextReward->required_stamp - $membership->stamp, 0);
                        $percent = min(($membership->stamp / $nextReward->required_stamp) * 100, 100);
                    @endphp

                    <div class="mt-6">

                        <h3 class="text-2xl font-bold text-[#05643b]">
                            {{ $nextReward->name }}
                        </h3>

                        <p class="mt-2 text-gray-500">
                            Tinggal <strong>{{ $remaining }} stamp</strong> lagi untuk mendapatkan reward ini.
                        </p>

                        <div class="mt-5 h-3 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full rounded-full bg-[#05643b]" style="width: {{ $percent }}%">
                            </div>
                        </div>

                        <div class="mt-2 flex justify-between text-xs text-gray-500">
                            <span>{{ $membership->stamp }} Stamp</span>
                            <span>{{ $nextReward->required_stamp }} Stamp</span>
                        </div>

                    </div>
                @else
                    <div class="mt-6 rounded-2xl bg-green-50 border border-green-200 p-5 text-green-700 font-semibold">
                        🎉 Selamat! Semua reward membership sudah berhasil kamu capai.
                    </div>
                @endif

            </div>

            {{-- Available Rewards --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-gray-900">
                            Reward Siap Dipakai
                        </h2>

                        <p class="text-sm text-gray-500">
                            Gunakan reward kapan saja saat melakukan treatment.
                        </p>
                    </div>

                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        {{ $availableRewards->count() }}
                    </span>

                </div>

                <div class="mt-5 space-y-4">

                    @forelse ($availableRewards as $claim)
                        <div class="rounded-2xl border border-green-200 bg-green-50 p-5">

                            <div class="flex items-start justify-between">

                                <div>

                                    <h3 class="font-semibold text-green-800">
                                        {{ $claim->reward->name }}
                                    </h3>

                                    <p class="mt-1 text-sm text-green-700">
                                        Didapat pada {{ $claim->claimed_at?->format('d M Y') }}
                                    </p>

                                </div>

                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-green-700">
                                    Siap Digunakan
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="rounded-2xl border border-dashed p-8 text-center text-gray-500">
                            Belum ada reward yang bisa digunakan.
                        </div>
                    @endforelse

                </div>

            </div>

            {{-- Reward History --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm">

                <h2 class="text-lg font-bold text-gray-900">
                    Riwayat Reward
                </h2>

                <div class="mt-6 space-y-5">

                    @forelse ($usedRewards as $claim)
                        <div class="flex gap-4">

                            <div class="mt-1 h-3 w-3 rounded-full bg-[#05643b]"></div>

                            <div class="flex-1 rounded-2xl border p-4">

                                <div class="flex justify-between items-start">

                                    <div>

                                        <h3 class="font-semibold text-gray-900">
                                            {{ $claim->reward->name }}
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Digunakan pada {{ $claim->used_at?->format('d M Y') }}
                                        </p>

                                        @if ($claim->order)
                                            <p class="mt-1 text-sm text-gray-500">
                                                Invoice : {{ $claim->order->invoice_number }}
                                            </p>
                                        @endif

                                    </div>

                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                        Selesai
                                    </span>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="rounded-2xl border border-dashed p-8 text-center text-gray-500">
                            Belum ada riwayat reward.
                        </div>
                    @endforelse

                </div>

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
                <div class="absolute top-2 left-0 right-0 text-center text-white">

                    <img id="story-logo" src="{{ url('logo-soklenn-real-putih.png') }}" alt="Soklenn" loading="eager"
                        decoding="sync" fetchpriority="high" crossorigin="anonymous"
                        style="width:400px; display:block; margin:auto;">

                    <div class="mt-4 text-4xl uppercase tracking-[0.4em] text-white/60">

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
                <div class="absolute left-1/2 top-[800px] -translate-x-1/2">

                    <img src="{{ asset('storage/' . $membership->card_image) }}" crossorigin="anonymous" loading="eager"
                        decoding="sync" fetchpriority="high" alt="Membership Card"
                        style="
        width:960px;
        max-width:none;
        border-radius:60px;
        display:block;
    ">

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

    @if ($membership->stamp > 0)
        @php
            $currentReward = \App\Models\MembershipReward::where('required_stamp', $membership->stamp)->first();
        @endphp

        <div id="reward-popup"
            class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-5">

            <div class="relative w-full max-w-sm overflow-hidden rounded-[32px] bg-white shadow-2xl animate-popup">

                {{-- Background --}}
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(5,100,59,.08),transparent_45%),radial-gradient(circle_at_bottom_right,rgba(255,200,0,.08),transparent_45%)]">
                </div>
                <div class="relative p-6">

                    {{-- Stamp --}}
                    <div class="flex justify-center">

                        <div class="relative">

                            <div class="absolute inset-0 scale-110 rounded-full bg-[#05643b]/20 blur-lg"></div>

                            <div
                                class="relative flex h-20 w-20 items-center justify-center rounded-full border-4 border-white bg-[#05643b] text-4xl font-black text-white shadow-lg">

                                {{ $membership->stamp }}

                            </div>

                        </div>

                    </div>

                    {{-- Title --}}
                    <h2 class="mt-5 text-center">
                        <span class="block text-lg font-medium text-gray-500">
                            Selamat,
                        </span>

                        <span class="mt-1 block text-3xl font-black text-[#05643b]">
                            {{ $membership->customer->name }}!
                        </span>
                    </h2>
                    <p class="mt-3 text-center text-lg font-bold text-gray-900">
                        Kamu berhasil mengumpulkan
                        <span class="text-[#05643b]">
                            {{ $membership->stamp }} stamp
                        </span>
                    </p>

                    <p class="mt-3 text-center text-sm leading-7 text-gray-500">
                        Terima kasih telah mempercayakan perawatan sepatumu kepada
                        <span class="font-semibold text-[#05643b]">Soklenn</span>.
                    </p>

                    {{-- Reward Baru --}}
                    @if ($currentReward)
                        <div class="mt-6 rounded-2xl border border-green-200 bg-gradient-to-br from-green-50 to-white p-5">

                            <div class="flex items-center gap-4">

                                <div
                                    class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-2xl bg-[#05643b] text-3xl text-white">

                                    🎁

                                </div>

                                <div>

                                    <div class="text-xs font-bold uppercase tracking-widest text-[#05643b]">
                                        Reward Berhasil Didapat
                                    </div>

                                    <div class="mt-1 text-xl font-black text-gray-900">
                                        {{ $currentReward->name }}
                                    </div>

                                </div>

                            </div>

                            <div class="mt-4 rounded-xl bg-white/80 p-3">

                                <p class="text-sm leading-6 text-gray-600">
                                    Reward ini sudah aktif dan dapat digunakan
                                    <span class="font-semibold text-[#05643b]">
                                        pada order berikutnya.
                                    </span>
                                </p>

                            </div>

                        </div>
                    @endif

                    {{-- Reward Selanjutnya --}}
                    @if ($nextReward)
                        <div class="mt-4 flex items-center justify-between rounded-2xl bg-gray-100 px-4 py-3">

                            <div>

                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Target Berikutnya
                                </div>

                                <div class="font-bold text-gray-900">
                                    {{ $nextReward->name }}
                                </div>

                            </div>

                            <div class="rounded-full bg-[#05643b] px-3 py-1 text-sm font-bold text-white">

                                {{ max($nextReward->required_stamp - $membership->stamp, 0) }}
                                Stamp

                            </div>

                        </div>
                    @elseif(!$currentReward)
                        <div class="mt-4 rounded-2xl bg-green-50 p-4 text-center">

                            <div class="text-3xl">
                                👑
                            </div>

                            <div class="mt-2 font-bold text-[#05643b]">
                                Semua Reward Telah Terbuka
                            </div>

                            <div class="mt-1 text-sm text-gray-500">
                                Terima kasih telah menjadi bagian dari keluarga Soklenn.
                            </div>

                        </div>
                    @endif

                    {{-- Button --}}
                    <button onclick="closeRewardPopup()"
                        class="mt-6 w-full rounded-2xl bg-[#05643b] py-3.5 text-base font-semibold text-white transition hover:bg-[#045533]">

                        Lanjutkan

                    </button>

                </div>

            </div>

        </div>
    @endif

    <script>
        async function downloadMembershipCard(event) {

            const button = event.currentTarget;

            // Jika tombol sudah menjadi Refresh Page
            if (button.dataset.mode === 'refresh') {
                location.reload();
                return;
            }

            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = 'Generating Card...';

            try {

                const node = document.getElementById('membership-card');

                await document.fonts.ready;

                await Promise.all(
                    Array.from(node.querySelectorAll('img')).map(img => {

                        if (img.complete && img.naturalWidth > 0) {
                            return Promise.resolve();
                        }

                        return new Promise((resolve, reject) => {

                            img.onload = resolve;
                            img.onerror = reject;

                        });

                    })
                );

                const dataUrl = await window.htmlToImage.toPng(node, {
                    pixelRatio: 4,
                    cacheBust: true,
                });

                button.innerHTML = 'Saving to Server...';

                const response = await fetch(
                    "{{ route('membership.save-card', $membership) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            image: dataUrl
                        })
                    }
                );

                const responseText = await response.text();

                let result;

                try {

                    result = JSON.parse(responseText);

                } catch (e) {

                    throw new Error(
                        'Server tidak mengembalikan JSON.\n\n' +
                        responseText.substring(0, 300)
                    );

                }

                if (!response.ok) {
                    throw new Error(result.message ?? 'HTTP Error');
                }

                if (!result.success) {
                    throw new Error(result.message ?? 'Save failed');
                }

                button.innerHTML = 'Downloading...';

                const link = document.createElement('a');

                link.download = 'membership-{{ $membership->member_code }}.png';
                link.href = dataUrl;

                document.body.appendChild(link);

                link.click();

                document.body.removeChild(link);

                // Ubah tombol menjadi Refresh
                button.dataset.mode = 'refresh';
                button.disabled = false;
                button.innerHTML = '🔄 Refresh Page';

            } catch (error) {

                console.error(error);

                button.innerHTML = 'Failed';

                alert(error.message ?? 'Something went wrong.');

                setTimeout(() => {

                    button.innerHTML = originalText;
                    button.disabled = false;

                }, 2500);

            }

        }
    </script>

    <script>
        async function downloadStoryCard() {

            const button = event.currentTarget;
            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = 'Generating Story...';

            try {

                const node = document.getElementById('membership-story');

                await document.fonts.ready;

                const images = Array.from(node.querySelectorAll('img'));

                await Promise.all(
                    images.map(async (img) => {

                        if (!img.complete || img.naturalWidth === 0) {

                            await new Promise((resolve, reject) => {

                                img.onload = resolve;
                                img.onerror = reject;

                            });

                        }

                        if (img.decode) {

                            try {
                                await img.decode();
                            } catch (e) {
                                console.warn('Decode failed:', img.src);
                            }

                        }

                    })
                );

                // Safari membutuhkan waktu untuk melakukan compositing layer
                await new Promise(resolve => setTimeout(resolve, 300));

                const logo = document.getElementById('story-logo');

                if (logo) {

                    if (!logo.complete || logo.naturalWidth === 0) {

                        await new Promise((resolve, reject) => {

                            logo.onload = resolve;
                            logo.onerror = reject;

                        });

                    }

                    if (logo.decode) {
                        try {
                            await logo.decode();
                        } catch (e) {}
                    }

                }

                const dataUrl = await window.htmlToImage.toPng(node, {
                    pixelRatio: 2,
                    cacheBust: true,
                });

                button.innerHTML = 'Downloading...';

                const link = document.createElement('a');

                link.download =
                    'membership-story-{{ $membership->member_code }}.png';

                link.href = dataUrl;

                document.body.appendChild(link);

                // window.open(dataUrl, '_blank');

                link.click();

                document.body.removeChild(link);

                button.innerHTML = '✓ Story Downloaded';

                setTimeout(() => {

                    button.innerHTML = originalText;
                    button.disabled = false;

                }, 2000);

            } catch (error) {

                console.error(error);

                button.innerHTML = 'Failed';

                alert(error.message ?? 'Failed to generate story.');

                setTimeout(() => {

                    button.innerHTML = originalText;
                    button.disabled = false;

                }, 2000);

            }

        }
    </script>

    <script>
        const flipCard = document.getElementById('flip-card');

        flipCard.addEventListener('click', function() {

            flipCard.classList.toggle('flipped');

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const popup = document.getElementById('reward-popup');

            if (!popup) return;

            setTimeout(() => {

                popup.classList.remove('hidden');
                popup.classList.add('flex');

            }, 500);

        });

        function closeRewardPopup() {

            const popup = document.getElementById('reward-popup');

            popup.classList.remove('flex');
            popup.classList.add('hidden');

        }
    </script>
@endsection
