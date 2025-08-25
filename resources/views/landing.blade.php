@extends('layouts.main')

@section('title', 'Solusi Digital Terbaik Anda')

@push('styles')
<style>
    .floating-image {
        animation: float 4s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
    }
    
    .cta-section {
        background-image: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
    }
    .fade-in-up {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.8s cubic-bezier(.4,0,.2,1), transform 0.8s cubic-bezier(.4,0,.2,1);
        will-change: opacity, transform;
    }
    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="bg-slate-800 text-white py-16 fade-in-up">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="text-center md:text-left">
                    <h1 class="text-4xl font-bold mb-4">Solusi Digital Terbaik untuk Bisnis Anda</h1>
                    <p class="text-lg mb-8">
                        DapurKode menyediakan berbagai solusi digital berkualitas tinggi untuk mengembangkan bisnis Anda. Dari website custom, source code aplikasi, hingga layanan instalasi dan pengembangan.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md inline-flex items-center">
                            Lihat Produk
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#about" class="border border-white text-white font-medium py-2 px-6 rounded-md hover:bg-white hover:text-gray-900">
                            Tentang Kami
                        </a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <img src="{{ asset('images/hero-illustration.svg') }}" alt="Hero Illustration" class="w-full max-w-md">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-12 bg-gray-100 fade-in-up">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Produk Unggulan</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan solusi digital terbaik untuk kebutuhan bisnis Anda dari berbagai produk unggulan kami.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:shadow-lg hover:-translate-y-1">
                    <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/product-placeholder.jpg') }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $product->name }}
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $product->type === 'paket' ? 'bg-blue-100 text-blue-800' : 
                                  ($product->type === 'jasa_pasang' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ $product->type === 'paket' ? 'Paket' : 
                                   ($product->type === 'jasa_pasang' ? 'Jasa Pasang' : 'Source Code') }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4">{{ Str::limit($product->short_description, 100) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            <a href="{{ route('products.show', $product) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-10">
                    <p class="text-gray-500">Belum ada produk unggulan saat ini.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Lihat Semua Produk
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Product Categories -->
    <section class="py-16 bg-white fade-in-up">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Kategori Produk</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Kami menawarkan berbagai kategori produk digital untuk memenuhi kebutuhan Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Paket Website -->
                <div class="service-card bg-white p-8 rounded-xl border border-gray-200 text-center hover:border-indigo-500 transition-all duration-300">
                    <div class="service-icon inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-6 mx-auto transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Paket Website</h3>
                    <p class="text-gray-600 mb-4">Solusi website lengkap siap pakai dengan design modern dan fitur lengkap.</p>
                    <div class="animated-counter text-2xl font-bold text-indigo-600 mb-4" x-data="{ count: 0, target: {{ $productStats['paket'] }} }" x-init="() => { let interval = setInterval(() => { count = Math.min(count + 1, target); if(count >= target) clearInterval(interval); }, 30); }" x-text="count"></div>
                    <a href="{{ route('products.index', ['category' => 'paket']) }}" class="inline-block text-indigo-600 hover:text-indigo-800 font-medium">
                        Lihat Paket Website
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                
                <!-- Jasa Pasang -->
                <div class="service-card bg-white p-8 rounded-xl border border-gray-200 text-center hover:border-indigo-500 transition-all duration-300">
                    <div class="service-icon inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-6 mx-auto transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Jasa Pasang</h3>
                    <p class="text-gray-600 mb-4">Layanan profesional untuk instalasi, konfigurasi, dan deployment aplikasi.</p>
                    <div class="animated-counter text-2xl font-bold text-indigo-600 mb-4" x-data="{ count: 0, target: {{ $productStats['jasa_pasang'] }} }" x-init="() => { let interval = setInterval(() => { count = Math.min(count + 1, target); if(count >= target) clearInterval(interval); }, 30); }" x-text="count"></div>
                    <a href="{{ route('products.index', ['category' => 'jasa_pasang']) }}" class="inline-block text-indigo-600 hover:text-indigo-800 font-medium">
                        Lihat Jasa Pasang
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                
                <!-- Source Code -->
                <div class="service-card bg-white p-8 rounded-xl border border-gray-200 text-center hover:border-indigo-500 transition-all duration-300">
                    <div class="service-icon inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 mb-6 mx-auto transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Source Code</h3>
                    <p class="text-gray-600 mb-4">Kode sumber aplikasi berkualitas dengan dokumentasi lengkap.</p>
                    <div class="animated-counter text-2xl font-bold text-indigo-600 mb-4" x-data="{ count: 0, target: {{ $productStats['lepas'] }} }" x-init="() => { let interval = setInterval(() => { count = Math.min(count + 1, target); if(count >= target) clearInterval(interval); }, 30); }" x-text="count"></div>
                    <a href="{{ route('products.index', ['category' => 'lepas']) }}" class="inline-block text-indigo-600 hover:text-indigo-800 font-medium">
                        Lihat Source Code
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50 fade-in-up" id="features">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Mengapa Memilih DapurKode?</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">Kami menawarkan solusi digital terbaik dengan berbagai keunggulan.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Produk Berkualitas</h3>
                    </div>
                    <p class="text-gray-600">Semua produk kami dikembangkan dengan standar kode yang tinggi, dokumentasi lengkap, dan desain yang modern.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Dukungan Tepat Waktu</h3>
                    </div>
                    <p class="text-gray-600">Tim dukungan kami siap membantu Anda dengan respons cepat dan solusi efektif untuk setiap kebutuhan.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Pembayaran Aman</h3>
                    </div>
                    <p class="text-gray-600">Metode pembayaran yang aman dan terverifikasi dengan sistem verifikasi manual oleh tim kami.</p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Garansi Produk</h3>
                    </div>
                    <p class="text-gray-600">Semua produk kami dilengkapi dengan garansi yang dapat diperpanjang untuk ketenangan pikiran Anda.</p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Customisasi Fleksibel</h3>
                    </div>
                    <p class="text-gray-600">Kami menawarkan opsi customisasi untuk menyesuaikan produk dengan kebutuhan spesifik bisnis Anda.</p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Performa Tinggi</h3>
                    </div>
                    <p class="text-gray-600">Produk kami dirancang dengan fokus pada performa, kecepatan, dan pengalaman pengguna yang optimal.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-white fade-in-up" id="about">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="md:w-1/2 mb-8 md:mb-0 md:pr-10">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Tentang DapurKode</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        DapurKode adalah perusahaan pengembangan software yang berfokus pada solusi digital berkualitas tinggi. Kami didirikan dengan visi untuk menyediakan produk digital yang inovatif, efisien, dan terjangkau bagi bisnis dari berbagai skala.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Tim kami terdiri dari para developer berpengalaman yang berkomitmen untuk memberikan solusi terbaik dengan standar kode yang tinggi dan desain yang modern. Setiap produk kami dikembangkan dengan memperhatikan detail, performa, dan keamanan.
                    </p>
                    <p class="text-lg text-gray-600 mb-8">
                        Kami percaya bahwa teknologi yang baik harus dapat diakses oleh semua orang. Itulah mengapa kami menawarkan berbagai pilihan produk dan layanan untuk memenuhi kebutuhan dan anggaran yang berbeda.
                    </p>
                    <a href="#" class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800">
                        Pelajari lebih lanjut
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                <div class="md:w-1/2">
                    <img src="{{ asset('images/about-illustration.svg') }}" alt="About Us Illustration" class="rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-16 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Siap untuk Tingkatkan Bisnis Anda?</h2>
            <p class="text-lg mb-8 max-w-3xl mx-auto">Dapatkan solusi digital terbaik dari DapurKode untuk membantu pertumbuhan bisnis Anda.</p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('products.index') }}" class="bg-white text-indigo-700 hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                    Jelajahi Produk
                </a>
                <a href="{{ route('register') }}" class="border-2 border-white text-white hover:bg-white hover:text-indigo-700 font-semibold py-3 px-8 rounded-lg transition-all duration-300 flex items-center justify-center">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Fade-in on scroll (repeated animation)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                } else {
                    entry.target.classList.remove('visible');
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.fade-in-up').forEach(el => {
            observer.observe(el);
        });
    });
</script>
@endpush
