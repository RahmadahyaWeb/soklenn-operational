<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cuci Sepatu Banjarbaru | Soklenn - Jasa Perawatan Sepatu Profesional</title>

    <meta name="description"
        content="Soklenn adalah jasa cuci sepatu profesional di Banjarbaru. Melayani Fast Clean, Deep Clean, dan Pick Up & Delivery untuk menjaga sepatu tetap bersih, fresh, dan nyaman digunakan.">

    <meta name="keywords"
        content="cuci sepatu banjarbaru, laundry sepatu banjarbaru, jasa cuci sepatu banjarbaru, shoe cleaning banjarbaru, deep clean sepatu, fast clean sepatu, perawatan sepatu banjarbaru">

    <meta name="robots" content="index,follow">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Cuci Sepatu Banjarbaru | Soklenn">
    <meta property="og:description"
        content="Jasa cuci sepatu profesional di Banjarbaru dengan layanan Fast Clean, Deep Clean, dan Pick Up & Delivery.">
    <meta property="og:image" content="{{ asset('logo-soklenn-putih.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Cuci Sepatu Banjarbaru | Soklenn">
    <meta name="twitter:description"
        content="Jasa cuci sepatu profesional di Banjarbaru dengan layanan Fast Clean, Deep Clean, dan Pick Up & Delivery.">

    <script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => 'Soklenn',
    'description' => 'Jasa cuci sepatu profesional di Banjarbaru',
    'image' => asset('logo-soklenn-putih.png'),
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => 'Banjarbaru',
        'addressRegion' => 'Kalimantan Selatan',
        'addressCountry' => 'ID',
    ],
    'areaServed' => 'Banjarbaru',
    'serviceType' => [
        'Cuci Sepatu',
        'Deep Clean',
        'Fast Clean',
        'Pick Up & Delivery',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance
</head>

<body class="bg-white text-[#0b3d2c] overflow-x-hidden">

    <div class="relative isolate">

        <div
            class="absolute top-0 left-0 right-0 h-[760px] md:h-[850px] bg-[#0b3d2c] rounded-b-[40px] md:rounded-b-[80px]">
        </div>

        <div class="relative isolate overflow-hidden">

            <div class="absolute inset-0 bg-[#0b3d2c] rounded-b-[40px] md:rounded-b-[80px]"></div>

            <header class="relative z-20">

                <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5">

                    <div class="flex items-center justify-between">

                        <div class="text-2xl md:text-3xl font-black tracking-tight text-white">
                            Soklenn
                        </div>

                        <div class="hidden lg:flex items-center gap-10 text-white font-medium">

                            <a href="#services" class="hover:opacity-70 transition">
                                Services
                            </a>

                            <a href="#about" class="hover:opacity-70 transition">
                                About
                            </a>

                            <a href="#contact" class="hover:opacity-70 transition">
                                Contact
                            </a>

                        </div>

                        <a href="#contact"
                            class="inline-flex items-center rounded-full bg-white px-5 py-2.5 md:px-6 md:py-3 text-xs md:text-sm font-bold text-[#0b3d2c] hover:scale-105 transition">
                            Book Now
                        </a>

                    </div>

                </div>

            </header>

            <section class="relative z-10">

                <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-10 md:pt-16 lg:pt-20 pb-16 md:pb-20">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">

                        <div class="space-y-7 text-white order-2 lg:order-1">

                            <div
                                class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-4 py-2 backdrop-blur">

                                <span class="text-xs sm:text-sm font-medium">
                                    Jasa Cuci Sepatu Profesional Banjarbaru
                                </span>

                            </div>

                            <div class="space-y-5">

                                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black leading-[1.05] tracking-tight">

                                    Clean, Fresh,
                                    Soklenn.

                                </h1>

                                <p class="max-w-xl text-sm sm:text-base lg:text-lg leading-relaxed text-white/75">
                                    Sepatu kotor, kusam, menguning, atau penuh noda? Soklenn hadir sebagai jasa cuci
                                    sepatu profesional di Banjarbaru dengan layanan Fast Clean, Deep Clean, dan
                                    Pick Up & Delivery untuk membantu menjaga sepatu tetap bersih, fresh, nyaman
                                    digunakan, dan siap menunjang aktivitas sehari-hari.
                                </p>

                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">

                                <a href="#contact"
                                    class="inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 font-bold text-[#0b3d2c] hover:-translate-y-1 transition">
                                    Booking Sekarang
                                </a>

                                <a href="#services"
                                    class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-8 py-4 font-bold backdrop-blur hover:bg-white/20 transition">
                                    Lihat Services
                                </a>

                            </div>

                            <div class="grid grid-cols-3 gap-3 sm:gap-4 pt-2">

                                <div
                                    class="rounded-2xl md:rounded-3xl border border-white/10 bg-white/10 p-4 md:p-5 backdrop-blur">

                                    <div class="text-2xl md:text-3xl font-black">
                                        1K+
                                    </div>

                                    <div class="mt-1 text-[11px] sm:text-sm text-white/60 leading-relaxed">
                                        Sepatu
                                        Dibersihkan
                                    </div>

                                </div>

                                <div
                                    class="rounded-2xl md:rounded-3xl border border-white/10 bg-white/10 p-4 md:p-5 backdrop-blur">

                                    <div class="text-2xl md:text-3xl font-black">
                                        4.9
                                    </div>

                                    <div class="mt-1 text-[11px] sm:text-sm text-white/60 leading-relaxed">
                                        Rating
                                        Pelanggan
                                    </div>

                                </div>

                                <div
                                    class="rounded-2xl md:rounded-3xl border border-white/10 bg-white/10 p-4 md:p-5 backdrop-blur">

                                    <div class="text-2xl md:text-3xl font-black">
                                        24H
                                    </div>

                                    <div class="mt-1 text-[11px] sm:text-sm text-white/60 leading-relaxed">
                                        Fast
                                        Process
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="relative flex justify-center lg:justify-end order-1 lg:order-2">

                            <div
                                class="absolute top-4 right-0 w-full max-w-[520px] h-full rounded-[30px] md:rounded-[40px] bg-white/10">
                            </div>

                            <div
                                class="relative w-full max-w-[520px] overflow-hidden rounded-[30px] md:rounded-[40px] border border-white/10 shadow-2xl bg-white flex items-center justify-center p-10 md:p-16">

                                <img src="{{ asset('logo-soklenn-putih.png') }}"
                                    alt="Soklenn Jasa Cuci Sepatu Profesional Banjarbaru"
                                    class="w-full max-w-[320px] object-contain">

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </div>

    <section id="services" class="py-16 md:py-24 bg-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            <div class="flex items-end justify-between flex-wrap gap-6 mb-12 md:mb-16">

                <div class="space-y-4">

                    <div
                        class="inline-flex rounded-full bg-[#0b3d2c]/5 px-4 py-2 text-xs sm:text-sm font-bold text-[#0b3d2c]">
                        SERVICES
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black leading-tight">

                        Layanan Cuci Sepatu
                        Profesional Banjarbaru

                    </h2>

                </div>

                <p class="max-w-xl text-sm sm:text-base text-zinc-600 leading-relaxed">

                    Mulai dari Fast Clean, Deep Clean, hingga layanan Pick Up & Delivery,
                    setiap layanan dirancang untuk memberikan kemudahan dan hasil perawatan terbaik bagi sepatu
                    kesayangan Anda.

                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

                <div
                    class="group rounded-[30px] md:rounded-[36px] border border-zinc-200 bg-white overflow-hidden hover:-translate-y-2 hover:shadow-2xl transition duration-300">

                    <div class="h-56 md:h-64 overflow-hidden">

                        <img src="https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?q=80&w=1400&auto=format&fit=crop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                    </div>

                    <div class="p-6 md:p-8 space-y-4">

                        <div class="flex items-center justify-between gap-4">

                            <div class="text-xl md:text-2xl font-black">
                                Deep Clean
                            </div>

                            <div class="rounded-full bg-[#0b3d2c] px-4 py-2 text-sm font-bold text-white">
                                40K
                            </div>

                        </div>

                        <p class="text-sm sm:text-base text-zinc-600 leading-relaxed">
                            Perawatan menyeluruh untuk mengangkat noda, debu, dan kotoran yang menempel hingga ke detail
                            sepatu.
                        </p>

                    </div>

                </div>

                <div
                    class="group rounded-[30px] md:rounded-[36px] bg-[#0b3d2c] text-white overflow-hidden shadow-2xl hover:-translate-y-2 transition duration-300">

                    <div class="h-56 md:h-64 overflow-hidden">

                        <img src="https://images.unsplash.com/photo-1460353581641-37baddab0fa2?q=80&w=1400&auto=format&fit=crop"
                            class="w-full h-full object-cover opacity-90 group-hover:scale-110 transition duration-500">

                    </div>

                    <div class="p-6 md:p-8 space-y-4">

                        <div class="flex items-center justify-between gap-4">

                            <div class="text-xl md:text-2xl font-black">
                                Fast Clean
                            </div>

                            <div class="rounded-full bg-white px-4 py-2 text-sm font-bold text-[#0b3d2c]">
                                Rp30K
                            </div>

                        </div>

                        <p class="text-sm sm:text-base leading-relaxed text-white/70">
                            Perawatan cepat untuk membersihkan kotoran ringan pada sepatu harian agar tetap bersih,
                            fresh, dan nyaman digunakan.
                        </p>

                    </div>

                </div>

                <div
                    class="group rounded-[30px] md:rounded-[36px] border border-zinc-200 bg-white overflow-hidden hover:-translate-y-2 hover:shadow-2xl transition duration-300">

                    <div class="h-56 md:h-64 overflow-hidden">

                        <img src="https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?q=80&w=1400&auto=format&fit=crop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">

                    </div>

                    <div class="p-6 md:p-8 space-y-4">

                        <div class="flex items-center justify-between gap-4">

                            <div class="text-xl md:text-2xl font-black">
                                Pick Up & Delivery
                            </div>

                            <div class="rounded-full bg-[#0b3d2c] px-4 py-2 text-sm font-bold text-white">
                                Gratis*
                            </div>

                        </div>


                        <p class="text-sm sm:text-base text-zinc-600 leading-relaxed">
                            Layanan antar jemput untuk memudahkan proses perawatan sepatu tanpa perlu datang langsung ke
                            toko.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>

    <section id="about" class="py-16 md:py-24 bg-[#0b3d2c]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

                <div class="relative">

                    <div
                        class="absolute inset-0 -translate-x-4 md:-translate-x-6 -translate-y-4 md:-translate-y-6 rounded-[30px] md:rounded-[40px] bg-white/10">
                    </div>

                    <div class="relative overflow-hidden rounded-[30px] md:rounded-[40px] shadow-2xl">

                        <img src="https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?q=80&w=1400&auto=format&fit=crop"
                            class="w-full h-[380px] sm:h-[500px] md:h-[700px] object-cover">

                    </div>

                </div>

                <div class="space-y-8 text-white">

                    <div class="space-y-4">

                        <div class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-bold">
                            WHY SOKLENN
                        </div>

                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black leading-tight">

                            Mengapa Memilih Soklenn
                            Untuk Perawatan Sepatu Anda?

                        </h2>

                    </div>

                    <div class="space-y-5">

                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">

                            <div class="text-lg md:text-xl font-bold">
                                Premium Product
                            </div>

                            <div class="mt-2 text-sm sm:text-base text-white/70 leading-relaxed">
                                Aman untuk suede, leather, canvas,
                                hingga knit tanpa merusak material.
                            </div>

                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">

                            <div class="text-lg md:text-xl font-bold">
                                Professional Handling
                            </div>

                            <div class="mt-2 text-sm sm:text-base text-white/70 leading-relaxed">
                                Dikerjakan detail oleh tim berpengalaman
                                dengan proses treatment yang rapi.
                            </div>

                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">

                            <div class="text-lg md:text-xl font-bold">
                                Fast Turnaround
                            </div>

                            <div class="mt-2 text-sm sm:text-base text-white/70 leading-relaxed">
                                Proses cepat dengan update pengerjaan
                                yang transparan dan jelas.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section id="contact" class="py-16 md:py-24 bg-white">

        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            <div
                class="rounded-[32px] md:rounded-[48px] bg-[#0b3d2c] p-8 sm:p-12 lg:p-20 text-center text-white shadow-2xl">

                <div class="space-y-6">

                    <div class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-bold">
                        CONTACT
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black leading-tight">

                        Sepatu Kotor,
                        Kusam, atau Menguning?
                        Percayakan Pada Soklenn.

                    </h2>

                    <p class="max-w-2xl mx-auto text-sm sm:text-base lg:text-lg leading-relaxed text-white/70">

                        Jangan biarkan sepatu favoritmu terlihat kusam.
                        Booking sekarang dan rasakan treatment premium
                        dengan hasil yang lebih bersih, fresh, dan nyaman dipakai.

                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">

                        <a href="#"
                            class="inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 font-bold text-[#0b3d2c] hover:scale-105 transition">
                            WhatsApp Booking
                        </a>

                        <a href="#"
                            class="inline-flex items-center justify-center rounded-2xl border border-white/10 px-8 py-4 font-bold hover:bg-white/10 transition">
                            Instagram
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <footer class="py-10 text-center text-sm text-zinc-500">
        Soklenn - Jasa Cuci Sepatu Profesional Banjarbaru | Fast Clean, Deep Clean,
        Pick Up & Delivery
    </footer>

</body>

</html>
