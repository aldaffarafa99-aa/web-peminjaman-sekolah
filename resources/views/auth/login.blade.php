<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Masuk | {{ config('app.name', 'Peminjaman Barang') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
        <div class="min-h-screen lg:grid lg:grid-cols-2">
            <aside class="relative hidden min-h-screen overflow-hidden bg-gradient-to-br from-indigo-950 via-indigo-800 to-violet-700 px-12 py-12 text-white lg:flex lg:flex-col lg:justify-between xl:px-20">
                <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full border border-white/10 bg-white/5"></div>
                <div class="absolute -bottom-40 -left-24 h-[30rem] w-[30rem] rounded-full border border-violet-200/10 bg-violet-300/10 blur-sm"></div>
                <div class="absolute right-24 top-1/2 h-24 w-24 rounded-full bg-indigo-300/10 blur-2xl"></div>

                <div class="relative z-10">
                    <a href="/" class="inline-flex items-center gap-3" aria-label="Beranda Peminjaman Barang">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 shadow-lg ring-1 ring-white/20">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo sekolah" class="h-8 w-8 rounded-xl object-cover">
                        </span>
                        <span class="text-lg font-semibold tracking-tight">Peminjaman Barang</span>
                    </a>
                </div>

                <div class="relative z-10 max-w-lg py-16">
                    <p class="mb-5 text-sm font-semibold uppercase tracking-[0.24em] text-indigo-200">Ruang kerja sekolah yang lebih rapi</p>
                    <h1 class="text-4xl font-semibold leading-tight tracking-tight xl:text-5xl">Kelola barang sekolah dengan lebih mudah.</h1>
                    <p class="mt-6 max-w-md text-lg leading-8 text-indigo-100">Satu tempat untuk mencatat, memantau, dan menelusuri setiap peminjaman barang.</p>

                    <div class="mt-10 space-y-5">
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-indigo-100 ring-1 ring-white/10">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 3.75h9.5L19 7.25v13H6v-16.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3.75v4h4M9 12h6M9 15.5h6" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-indigo-50">Catat peminjaman dengan cepat</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-indigo-100 ring-1 ring-white/10">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.25V9.75l8-4 8 4v9.5M7.5 17v-4M12 17v-6M16.5 17v-2" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-indigo-50">Pantau stok barang secara teratur</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-indigo-100 ring-1 ring-white/10">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5.75h14v13H5v-13ZM8.5 3.75v4M15.5 3.75v4M5 10h14" />
                                    <path stroke-linecap="round" d="M8.5 14h.01M12 14h.01M15.5 14h.01M8.5 17h.01M12 17h.01" />
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-indigo-50">Akses riwayat lengkap kapan saja</span>
                        </div>
                    </div>
                </div>

                <p class="relative z-10 text-sm text-indigo-200">Sistem inventaris dan peminjaman sekolah</p>
            </aside>

            <main class="flex min-h-screen items-center justify-center px-5 py-8 sm:px-8 lg:px-12 xl:px-20" x-data="{ showPassword: false, isSubmitting: false }">
                <div class="w-full max-w-md">
                    <div class="mb-8 flex items-center gap-3 lg:hidden">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo sekolah" class="h-8 w-8 rounded-xl object-cover">
                        </span>
                        <span class="text-lg font-semibold tracking-tight text-slate-800">Peminjaman Barang</span>
                    </div>

                    <div class="rounded-3xl bg-white p-7 shadow-xl shadow-slate-200/70 ring-1 ring-slate-200/80 sm:p-10">
                        <div class="mb-8">
                            <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Selamat datang</p>
                            <h2 class="text-3xl font-semibold tracking-tight text-slate-900">Selamat datang kembali</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-500">Masuk untuk melanjutkan pengelolaan peminjaman barang sekolah.</p>
                        </div>

                        <x-auth-session-status class="mb-5" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" @submit="isSubmitting = true">
                            @csrf

                            <div>
                                <x-input-label for="email" value="Email" class="mb-2 text-sm font-medium text-slate-700" />
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.75h16v10.5H4V6.75Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 7.25 7.5 5.5 7.5-5.5" />
                                        </svg>
                                    </div>
                                    <x-text-input id="email" class="block w-full rounded-xl border-slate-200 py-3 pl-11 pr-4 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@sekolah.sch.id" />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-5">
                                <div class="mb-2 flex items-center justify-between">
                                    <x-input-label for="password" value="Kata sandi" class="text-sm font-medium text-slate-700" />
                                    @if (Route::has('password.request'))
                                        <a class="text-xs font-medium text-indigo-600 transition hover:text-indigo-800" href="{{ route('password.request') }}">
                                            Lupa kata sandi?
                                        </a>
                                    @endif
                                </div>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <rect x="5" y="10" width="14" height="10" rx="2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10V7.5a4 4 0 0 1 8 0V10" />
                                        </svg>
                                    </div>
                                    <x-text-input id="password" class="block w-full rounded-xl border-slate-200 py-3 pl-11 pr-12 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10" x-bind:type="showPassword ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" />
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" @click="showPassword = !showPassword" :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                        <svg x-show="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.75 12s3.25-5 9.25-5 9.25 5 9.25 5-3.25 5-9.25 5-9.25-5-9.25-5Z" />
                                            <circle cx="12" cy="12" r="2.25" />
                                        </svg>
                                        <svg x-cloak x-show="showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 6.98A10.7 10.7 0 0 1 12 7c6 0 9.25 5 9.25 5a15.8 15.8 0 0 1-3.1 3.3M6.15 6.2C3.9 7.55 2.75 12 2.75 12s3.25 5 9.25 5c1.17 0 2.22-.2 3.15-.5" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 0 0 4.24 4.24" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="mt-5 flex items-center">
                                <label for="remember_me" class="inline-flex cursor-pointer items-center">
                                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm transition focus:ring-2 focus:ring-indigo-500/30" name="remember">
                                    <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
                                </label>
                            </div>

                            <button type="submit" class="mt-7 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition duration-200 hover:-translate-y-0.5 hover:from-indigo-700 hover:to-violet-700 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/25 disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0" x-bind:disabled="isSubmitting">
                                <svg x-cloak x-show="isSubmitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"></circle>
                                    <path class="opacity-90" fill="currentColor" d="M21 12a9 9 0 0 1-9 9v-3a6 6 0 0 0 6-6h3Z"></path>
                                </svg>
                                <span x-show="!isSubmitting">Masuk</span>
                                <span x-cloak x-show="isSubmitting">Memproses...</span>
                            </button>

                            <p class="mt-6 text-center text-sm text-slate-500">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-800">Daftar di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>