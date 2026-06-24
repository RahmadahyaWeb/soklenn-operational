@extends('layouts.landing')

@section('content')
    @include('sections.header')

    <section class="py-24">

        <div class="max-w-5xl mx-auto px-4 sm:px-6">

            <div class="text-center">

                <div
                    class="inline-flex items-center rounded-full bg-[#05643b]/10 px-4 py-2 text-sm font-medium text-[#05643b]">

                    Soklenn Membership

                </div>

                <h1 class="mt-6 text-4xl md:text-5xl font-black tracking-tight">

                    Cek Kartu Membership

                </h1>

                <p class="mt-4 max-w-2xl mx-auto text-zinc-600 leading-relaxed">

                    Masukkan kode member untuk melihat progress stamp,
                    reward yang tersedia, dan status membership Anda.

                </p>

            </div>

            <div class="mt-12 max-w-lg mx-auto">

                <div class="rounded-[32px] border border-zinc-200 bg-white p-8 shadow-sm">

                    <form method="POST" action="{{ route('membership.lookup.search') }}" class="space-y-6">

                        @csrf

                        <div>

                            <label class="block text-sm font-semibold text-zinc-800 mb-2">

                                Kode Member

                            </label>

                            <input type="text" name="member_code" value="{{ old('member_code') }}"
                                placeholder="SKL-XXXXX"
                                class="w-full rounded-2xl border border-zinc-200 px-4 py-4 outline-none focus:border-[#05643b] focus:ring-4 focus:ring-[#05643b]/10">

                            @error('member_code')
                                <p class="mt-2 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <button type="submit"
                            class="w-full rounded-2xl bg-[#05643b] px-6 py-4 font-bold text-white transition hover:opacity-90">

                            Lihat Membership

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </section>
@endsection
