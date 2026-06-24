<div class="membership-card relative overflow-hidden rounded-[48px]">

    @php
        $membershipUrl = route('membership.card', $membership->public_token);
    @endphp

    <div class="absolute inset-0 bg-gradient-to-br from-[#05643b] via-[#0b3d2c] to-[#04281d]"></div>

    <div class="absolute -top-[15%] -right-[15%] h-[320px] w-[320px] rounded-full border border-white/10">
    </div>

    <div class="absolute -bottom-[20%] -left-[10%] h-[380px] w-[380px] rounded-full border border-white/10">
    </div>

    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_30%)]">
    </div>

    <div class="relative flex h-full flex-col p-[42px] text-white">

        {{-- Header --}}
        <div>

            <div class="text-[18px] font-semibold uppercase tracking-[0.4em] text-white/60">

                SOKLENN MEMBERSHIP

            </div>

        </div>

        {{-- Member --}}
        <div class="mt-auto">

            <div class="text-[52px] font-black leading-none tracking-tight">

                {{ strtoupper($membership->customer->name) }}

            </div>

            <div class="mt-3 text-[24px] font-medium tracking-wide text-white/70">

                {{ $membership->member_code }}

            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-8 flex items-end justify-between">

            <div
                class="inline-flex items-center rounded-full bg-white/15 px-5 py-2.5 text-[20px] font-bold tracking-wide backdrop-blur">

                {{ strtoupper($membership->tier) }}

            </div>

            <div class="rounded-[20px] bg-white p-4">

                <img src="https://quickchart.io/qr?size=300&margin=1&text={{ urlencode($membershipUrl) }}"
                    alt="QR Membership" class="h-[150px] w-[150px]">

            </div>

        </div>

    </div>

</div>
