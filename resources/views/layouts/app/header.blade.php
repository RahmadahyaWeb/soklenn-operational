<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.08),transparent_50%)]"> </div>
        <header class="relative z-10 border-b border-white/10">
            <div class="max-w-7xl mx-auto px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-2xl bg-white text-black flex items-center justify-center font-black text-lg">
                            S </div>
                        <div>
                            <div class="font-bold text-lg"> Soklenn </div>
                            <div class="text-sm text-zinc-400"> Premium Shoe Cleaning </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center gap-8 text-sm text-zinc-300"> <a href="#services"
                            class="hover:text-white transition"> Services </a> <a href="#why-us"
                            class="hover:text-white transition"> Why Us </a> <a href="#pricing"
                            class="hover:text-white transition"> Pricing </a> <a href="#contact"
                            class="hover:text-white transition"> Contact </a> </div>
                </div>
            </div>
        </header>
        <section class="relative z-10">
            <div class="max-w-7xl mx-auto px-6 py-24 lg:py-32">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="space-y-8">
                        <div
                            class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-zinc-300">
                            Trusted Shoe Cleaning Service </div>
                        <div class="space-y-6">
                            <h1 class="text-5xl lg:text-7xl font-black leading-tight"> Make Your Sneakers Look Fresh
                                Again. </h1>
                            <p class="text-lg text-zinc-400 leading-relaxed max-w-xl"> Professional shoe cleaning,
                                repaint, and treatment service for your favorite sneakers with premium care and detailed
                                handling. </p>
                        </div>
                        <div class="flex flex-wrap items-center gap-4"> <a href="#contact"
                                class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-4 text-black font-semibold hover:bg-zinc-200 transition">
                                Book Now </a> <a href="#services"
                                class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-6 py-4 font-semibold hover:bg-white/10 transition">
                                Explore Services </a> </div>
                        <div class="grid grid-cols-3 gap-6 pt-6">
                            <div>
                                <div class="text-3xl font-black"> 1000+ </div>
                                <div class="text-sm text-zinc-400"> Shoes Cleaned </div>
                            </div>
                            <div>
                                <div class="text-3xl font-black"> 4.9 </div>
                                <div class="text-sm text-zinc-400"> Customer Rating </div>
                            </div>
                            <div>
                                <div class="text-3xl font-black"> 24H </div>
                                <div class="text-sm text-zinc-400"> Fast Service </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute -top-10 -right-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"> </div>
                        <div
                            class="relative rounded-[32px] border border-white/10 bg-white/5 backdrop-blur overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=1400&auto=format&fit=crop"
                                class="w-full h-[650px] object-cover" /> </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <section id="services" class="py-24 border-t border-white/10 bg-zinc-900">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl space-y-4 mb-16">
                <div class="text-sm uppercase tracking-[0.3em] text-zinc-500"> Services </div>
                <h2 class="text-4xl font-black"> Professional Sneaker Treatment </h2>
                <p class="text-zinc-400 leading-relaxed"> Complete cleaning and treatment service handled with proper
                    tools and premium materials. </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-3xl border border-white/10 bg-zinc-950 p-8 space-y-5">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white text-black flex items-center justify-center font-black text-xl">
                        01 </div>
                    <div class="space-y-2">
                        <div class="text-2xl font-bold"> Deep Clean </div>
                        <div class="text-zinc-400"> Deep cleaning treatment for dirty sneakers and daily usage stains.
                        </div>
                    </div>
                    <div class="text-3xl font-black"> Rp50K </div>
                </div>
                <div class="rounded-3xl border border-white/10 bg-zinc-950 p-8 space-y-5">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white text-black flex items-center justify-center font-black text-xl">
                        02 </div>
                    <div class="space-y-2">
                        <div class="text-2xl font-bold"> Fast Clean </div>
                        <div class="text-zinc-400"> Quick cleaning service with same day handling for urgent usage.
                        </div>
                    </div>
                    <div class="text-3xl font-black"> Rp30K </div>
                </div>
                <div class="rounded-3xl border border-white/10 bg-zinc-950 p-8 space-y-5">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white text-black flex items-center justify-center font-black text-xl">
                        03 </div>
                    <div class="space-y-2">
                        <div class="text-2xl font-bold"> Repaint </div>
                        <div class="text-zinc-400"> Restore faded sneaker color with professional repaint treatment.
                        </div>
                    </div>
                    <div class="text-3xl font-black"> Rp150K </div>
                </div>
            </div>
        </div>
    </section>
    <section id="why-us" class="py-24 bg-zinc-950">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8">
                    <div class="space-y-4">
                        <div class="text-sm uppercase tracking-[0.3em] text-zinc-500"> Why Soklenn </div>
                        <h2 class="text-4xl font-black"> Detailed Care For Every Pair </h2>
                    </div>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-white text-black flex items-center justify-center font-bold">
                                ✓ </div>
                            <div>
                                <div class="font-bold text-lg"> Premium Cleaning Products </div>
                                <div class="text-zinc-400"> Safe treatment for leather, suede, canvas, and knit
                                    materials. </div>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-white text-black flex items-center justify-center font-bold">
                                ✓ </div>
                            <div>
                                <div class="font-bold text-lg"> Professional Handling </div>
                                <div class="text-zinc-400"> Detailed cleaning process with careful material treatment.
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-white text-black flex items-center justify-center font-bold">
                                ✓ </div>
                            <div>
                                <div class="font-bold text-lg"> Fast Turnaround </div>
                                <div class="text-zinc-400"> Efficient workflow with transparent progress updates. </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-[32px] overflow-hidden border border-white/10"> <img
                        src="https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?q=80&w=1400&auto=format&fit=crop"
                        class="w-full h-[600px] object-cover" /> </div>
            </div>
        </div>
    </section>
    <section id="contact" class="py-24 border-t border-white/10 bg-zinc-900">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="space-y-6">
                <div class="text-sm uppercase tracking-[0.3em] text-zinc-500"> Contact </div>
                <h2 class="text-5xl font-black"> Ready To Clean Your Sneakers? </h2>
                <p class="text-zinc-400 text-lg leading-relaxed"> Bring back the best look for your favorite shoes with
                    professional treatment from Soklenn. </p>
                <div class="flex flex-wrap items-center justify-center gap-4 pt-4"> <a href="#"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 text-black font-semibold hover:bg-zinc-200 transition">
                        WhatsApp Booking </a> <a href="#"
                        class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-8 py-4 font-semibold hover:bg-white/10 transition">
                        Instagram </a> </div>
            </div>
        </div>
    </section>
</body>

</html>
