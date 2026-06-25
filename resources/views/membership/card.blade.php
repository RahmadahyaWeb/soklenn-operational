@extends('layouts.landing')

@section('content')
    @include('sections.header')

    <div class="max-w-[420px] mx-auto px-4 py-8">
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

                    <img id="story-logo" src="{{ url('logo-soklenn-real-putih.png') }}" alt="Soklenn" class="mx-auto w-72"
                        loading="eager" decoding="sync" fetchpriority="high" crossorigin="anonymous">

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
                <div class="absolute left-1/2 top-[820px] -translate-x-1/2">

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
@endsection
