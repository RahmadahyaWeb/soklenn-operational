<div id="membership-card" class="membership-card">

    @php
        $membershipUrl = route('membership.card', $membership->public_token);
    @endphp

    <div class="absolute inset-0 bg-gradient-to-br from-[#05643b] via-[#0b3d2c] to-[#04281d]"></div>

    <div class="absolute -top-[15%] -right-[15%] h-[60%] w-[60%] rounded-full border border-white/10">
    </div>

    <div class="absolute -bottom-[20%] -left-[10%] h-[70%] w-[70%] rounded-full border border-white/10">
    </div>

    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,.15),transparent_30%)]">
    </div>

    <div class="relative flex h-full flex-col p-5 sm:p-6 text-white">

        {{-- Header --}}
        <div>

            <div class="text-[10px] sm:text-xs font-semibold uppercase tracking-[0.4em] text-white/60">

                SOKLENN MEMBERSHIP

            </div>

        </div>

        {{-- Member --}}
        <div class="mt-auto">

            <div class="text-2xl sm:text-3xl font-black leading-none tracking-tight">

                {{ strtoupper($membership->customer->name) }}

            </div>

            <div class="mt-2 text-sm sm:text-base font-medium tracking-wide text-white/70">

                {{ $membership->member_code }}

            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-5 flex items-end justify-between">

            <div
                class="mt-3 inline-flex items-center rounded-full bg-white/20 px-3 py-1.5 text-[11px] font-bold tracking-wide">

                {{ strtoupper($membership->tier) }}

            </div>

            <div class="rounded-xl bg-white p-2">

                <img src="https://quickchart.io/qr?size=120&margin=1&text={{ urlencode($membershipUrl) }}"
                    alt="QR Membership" class="h-14 w-14 sm:h-16 sm:w-16">

                {{-- <div class="w-16 h-16 bg-white"></div> --}}

            </div>

        </div>

    </div>

</div>
