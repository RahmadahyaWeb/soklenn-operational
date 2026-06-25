{{-- HEADER --}}
<header class="sticky top-0 z-50 border-b border-white/10 bg-[#0b3d2c]/95 backdrop-blur">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6">

        {{-- Logo --}}
        <a href="/" class="text-2xl font-black tracking-tight text-white md:text-3xl">
            Soklenn
        </a>

        {{-- Desktop Menu --}}
        <nav class="hidden items-center gap-10 font-medium text-white lg:flex">

            <a href="/#services" class="transition hover:opacity-70">
                Services
            </a>

            <a href="/#about" class="transition hover:opacity-70">
                About
            </a>

            <a href="/#testimonials" class="transition hover:opacity-70">
                Testimonials
            </a>

            <a href="/#location" class="transition hover:opacity-70">
                Location
            </a>

            <a href="/#faq" class="transition hover:opacity-70">
                FAQ
            </a>

            <a href="{{ route('membership.lookup') }}" class="transition hover:opacity-70">
                Membership
            </a>

            <a href="/#contact" class="transition hover:opacity-70">
                Contact
            </a>

        </nav>

        {{-- Right --}}
        <div class="flex items-center gap-3">

            <a href="/#contact"
                class="inline-flex items-center rounded-full bg-white px-4 py-2.5 text-xs font-bold text-[#0b3d2c] transition hover:scale-105 md:px-6 md:py-3 md:text-sm">
                Book Now
            </a>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-button"
                class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/20 text-white transition hover:bg-white/10 lg:hidden">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />

                </svg>

            </button>

        </div>

    </div>

</header>

{{-- Overlay --}}
<div id="mobile-menu-overlay" class="fixed inset-0 z-[60] hidden bg-black/50 backdrop-blur-sm lg:hidden">
</div>

{{-- Mobile Menu --}}
<div id="mobile-menu"
    class="fixed right-0 top-0 z-[70] h-full w-[300px] translate-x-full bg-[#0b3d2c] shadow-2xl transition-transform duration-300 lg:hidden">

    <div class="flex items-center justify-between border-b border-white/10 p-6">

        <div class="text-2xl font-black text-white">
            Soklenn
        </div>

        <button id="mobile-menu-close" class="text-white">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />

            </svg>

        </button>

    </div>

    <nav class="flex flex-col p-6 text-lg font-medium text-white">

        <a href="/#services" class="rounded-xl px-4 py-3 transition hover:bg-white/10">
            Services
        </a>

        <a href="/#about" class="rounded-xl px-4 py-3 transition hover:bg-white/10">
            About
        </a>

        <a href="/#testimonials" class="rounded-xl px-4 py-3 transition hover:bg-white/10">
            Testimonials
        </a>

        <a href="/#location" class="rounded-xl px-4 py-3 transition hover:bg-white/10">
            Location
        </a>

        <a href="/#faq" class="rounded-xl px-4 py-3 transition hover:bg-white/10">
            FAQ
        </a>

        <a href="{{ route('membership.lookup') }}" class="rounded-xl px-4 py-3 transition hover:bg-white/10">
            Membership
        </a>

        <a href="/#contact" class="rounded-xl px-4 py-3 transition hover:bg-white/10">
            Contact
        </a>

        <a href="/#contact" class="mt-6 rounded-xl bg-white px-5 py-3 text-center font-bold text-[#0b3d2c]">
            Book Now
        </a>

    </nav>

</div>

<script>
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');

    function openMobileMenu() {

        mobileMenu.classList.remove('translate-x-full');
        mobileOverlay.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');

    }

    function closeMobileMenu() {

        mobileMenu.classList.add('translate-x-full');
        mobileOverlay.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }

    document
        .getElementById('mobile-menu-button')
        .addEventListener('click', openMobileMenu);

    document
        .getElementById('mobile-menu-close')
        .addEventListener('click', closeMobileMenu);

    mobileOverlay.addEventListener('click', closeMobileMenu);

    document.querySelectorAll('#mobile-menu a').forEach(link => {

        link.addEventListener('click', closeMobileMenu);

    });
</script>
