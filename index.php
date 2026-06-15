<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Experience premium beauty treatments, threading, and world-class spa facials at Rupini's Spa. Book your appointment online today.">
    <title>Rupini's Spa - Premium Beauty Experience</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- Open Graph SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Rupini's Spa - Premium Beauty Experience">
    <meta property="og:description" content="Experience premium beauty treatments, threading, and world-class spa facials at Rupini's Spa. Book your appointment online today.">
    <meta property="og:image" content="assets/images/logo-dark.png">


    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6F2C91',
                        dark: '#4B145F',
                        lavender: '#F7F0FA',
                        light: '#E6D8EF',
                        gold: '#C9A646',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-[#faf7fc] text-gray-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- 1. Premium Immersive Hero Section -->
    <section class="relative min-h-[calc(100vh-80px)] flex items-center justify-center overflow-hidden bg-dark py-20">
        <!-- Background Auto Slider -->
        <div class="absolute inset-0 w-full h-full" id="hero-slider">
            <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=2070&auto=format&fit=crop"
                alt="Rupinis Spa Hero"
                class="w-full h-full object-cover absolute top-0 left-0 transition-opacity duration-1000 ease-in-out opacity-100 slide-img">
            <img src="assets/images/hero_2.png" alt="Rupinis Spa Detail"
                class="w-full h-full object-cover absolute top-0 left-0 transition-opacity duration-1000 ease-in-out opacity-0 slide-img">
            <img src="assets/images/hero_3.png" alt="Rupinis Spa Ambience"
                class="w-full h-full object-cover absolute top-0 left-0 transition-opacity duration-1000 ease-in-out opacity-0 slide-img">
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const slides = document.querySelectorAll('.slide-img');
                if (slides.length > 0) {
                    let current = 0;
                    setInterval(() => {
                        slides[current].classList.remove('opacity-100');
                        slides[current].classList.add('opacity-0');
                        current = (current + 1) % slides.length;
                        slides[current].classList.remove('opacity-0');
                        slides[current].classList.add('opacity-100');
                    }, 4000); // Change image every 4 seconds
                }
            });
        </script>

        <!-- Natural Dark Overlay -->
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-dark/95 via-dark/50 to-dark/20"></div>

        <!-- Ambient Glow -->
        <div class="absolute inset-0 opacity-40 mix-blend-screen pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-primary blur-[100px]"></div>
            <div class="absolute top-20 -left-20 w-72 h-72 rounded-full bg-[#8c3ab8] blur-[80px]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10 text-center">
            <div class="flex items-center justify-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="h-[1px] w-8 sm:w-12 md:w-24 bg-gradient-to-r from-transparent to-white/60"></div>
                <span
                    class="text-white/90 text-xs sm:text-sm font-bold tracking-[0.2em] sm:tracking-[0.3em] uppercase drop-shadow-sm">Welcome
                    to Tranquility</span>
                <div class="h-[1px] w-8 sm:w-12 md:w-24 bg-gradient-to-l from-transparent to-white/60"></div>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[4.5rem] font-black text-white tracking-tight mb-4 sm:mb-6 drop-shadow-2xl leading-[1.1]"
                style="text-shadow: 0 4px 16px rgba(0,0,0,0.4);">
                Elevate Your <br><span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-white to-light">Natural Beauty</span>
            </h1>
            <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/90 max-w-2xl mx-auto leading-relaxed font-light mb-8 sm:mb-10 px-4 sm:px-0"
                style="text-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                Experience world-class spa treatments, threading, and facials designed to rejuvenate your body and soul.
            </p>
            <div class="flex flex-row justify-center items-center gap-2 sm:gap-4 w-full">
                <a href="book.php"
                    class="inline-flex items-center justify-center px-4 sm:px-8 py-3 sm:py-3.5 text-xs sm:text-sm md:text-base font-bold text-dark bg-white rounded-full hover:bg-gray-100 shadow-xl shadow-black/20 transform hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto text-center whitespace-nowrap">
                    <span class="sm:hidden">Book Now</span>
                    <span class="hidden sm:inline">Book an Appointment</span>
                </a>
                <a href="services.php"
                    class="inline-flex items-center justify-center px-4 sm:px-8 py-3 sm:py-3.5 text-xs sm:text-sm md:text-base font-bold text-white bg-white/10 border border-white/30 rounded-full hover:bg-white/20 backdrop-blur-sm transition-all duration-300 w-full sm:w-auto text-center whitespace-nowrap">
                    Explore Services
                </a>
            </div>
        </div>
    </section>

    <!-- 1.5 First-Time Trial Promo -->
    <section class="bg-primary overflow-hidden relative border-y border-dark/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4 text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg md:text-xl">First-Time Customer? Get 20% Off!</h3>
                        <p class="text-white/80 text-sm">Experience the Rupini's standard with a special discount on
                            your first facial or threading service.</p>
                    </div>
                </div>
                <a href="book.php"
                    class="shrink-0 px-8 py-3 bg-white text-primary font-bold rounded-full hover:bg-gray-100 transition-colors shadow-lg shadow-black/10 transform hover:-translate-y-0.5">
                    Claim Offer
                </a>
            </div>
        </div>
    </section>

    <!-- 2. Features / Why Choose Us -->
    <section class="py-24 bg-[#faf7fc]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">The Rupini's Standard</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">We blend traditional techniques with modern luxury to
                    provide an unmatched spa experience.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-light/50 hover:shadow-float transition-all duration-300 transform hover:-translate-y-1 group">
                    <div
                        class="w-14 h-14 bg-lavender rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Expert Beauticians</h3>
                    <p class="text-gray-500 leading-relaxed">Highly trained professionals dedicated to perfecting your
                        personal care.</p>
                </div>
                <!-- Feature 2 -->
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-light/50 hover:shadow-float transition-all duration-300 transform hover:-translate-y-1 group">
                    <div
                        class="w-14 h-14 bg-lavender rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Premium Products</h3>
                    <p class="text-gray-500 leading-relaxed">We use only the finest organic and luxury products for all
                        our treatments.</p>
                </div>
                <!-- Feature 3 -->
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-light/50 hover:shadow-float transition-all duration-300 transform hover:-translate-y-1 group">
                    <div
                        class="w-14 h-14 bg-lavender rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Instant Booking</h3>
                    <p class="text-gray-500 leading-relaxed">Seamlessly book your appointments online with real-time
                        availability.</p>
                </div>
                <!-- Feature 4 -->
                <div
                    class="bg-white p-8 rounded-3xl shadow-sm border border-light/50 hover:shadow-float transition-all duration-300 transform hover:-translate-y-1 group">
                    <div
                        class="w-14 h-14 bg-lavender rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Tranquil Environment</h3>
                    <p class="text-gray-500 leading-relaxed">Escape the city hustle in our perfectly designed relaxation
                        zones.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Popular Services -->
    <section class="py-24 bg-white border-y border-light/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">Popular Services</h2>
                    <p class="text-gray-600 max-w-xl text-lg">Discover our most requested treatments, carefully curated
                        for your ultimate relaxation.</p>
                </div>
                <a href="services.php"
                    class="flex items-center justify-center px-6 py-2.5 rounded-full border border-primary text-primary font-bold hover:bg-primary hover:text-white transition-colors w-full md:w-auto">
                    View All Services
                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-[#faf7fc] border border-light/50 hover:shadow-float transition-all duration-300">
                    <div class="h-64 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?q=80&w=2070&auto=format&fit=crop"
                            alt="Spa Facial"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div
                            class="absolute bottom-4 left-4 text-white font-bold px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-sm">
                            Most Popular</div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-dark mb-3">Signature Facial</h3>
                        <p class="text-gray-600 mb-6 line-clamp-2">A deep-cleansing facial tailored to your skin type,
                            leaving you with a radiant, youthful glow.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-black text-primary">$85.00</span>
                            <a href="book.php" aria-label="Book Signature Facial"
                                class="text-primary font-bold hover:text-dark flex items-center">
                                Book Now <svg class="w-4 h-4 ml-1" aria-hidden="true" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Service 2 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-[#faf7fc] border border-light/50 hover:shadow-float transition-all duration-300">
                    <div class="h-64 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=2070&auto=format&fit=crop"
                            alt="Massage"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-dark mb-3">Aromatherapy Massage</h3>
                        <p class="text-gray-600 mb-6 line-clamp-2">Melt away stress with a full-body massage using our
                            custom-blended essential oils.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-black text-primary">$120.00</span>
                            <a href="book.php" aria-label="Book Aromatherapy Massage"
                                class="text-primary font-bold hover:text-dark flex items-center">
                                Book Now <svg class="w-4 h-4 ml-1" aria-hidden="true" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Service 3 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-[#faf7fc] border border-light/50 hover:shadow-float transition-all duration-300">
                    <div class="h-64 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?q=80&w=2070&auto=format&fit=crop"
                            alt="Threading"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-dark mb-3">Precision Threading</h3>
                        <p class="text-gray-600 mb-6 line-clamp-2">Expert eyebrow shaping and facial hair removal for
                            perfectly defined features.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-black text-primary">$15.00</span>
                            <a href="book.php" aria-label="Book Precision Threading"
                                class="text-primary font-bold hover:text-dark flex items-center">
                                Book Now <svg class="w-4 h-4 ml-1" aria-hidden="true" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3.5 Testimonials -->
    <section class="py-24 bg-white border-y border-light/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">Loved by Singaporeans</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Don't just take our word for it. See what our
                    community has to say about their Rupini's experience.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Review 1 -->
                <div
                    class="bg-[#faf7fc] p-8 rounded-3xl border border-light/50 shadow-sm hover:shadow-float transition-all duration-300">
                    <div class="flex items-center gap-2 mb-4 text-gold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 italic mb-6 leading-relaxed">"Absolutely the best threading experience I've
                        had in Singapore. Practically painless and the staff are so meticulous. The environment is
                        extremely clean and relaxing."</p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                            SM</div>
                        <div>
                            <div class="font-bold text-dark text-sm">Sarah M.</div>
                            <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Verified
                                Client via Google</div>
                        </div>
                    </div>
                </div>
                <!-- Review 2 -->
                <div
                    class="bg-[#faf7fc] p-8 rounded-3xl border border-light/50 shadow-sm hover:shadow-float transition-all duration-300">
                    <div class="flex items-center gap-2 mb-4 text-gold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 italic mb-6 leading-relaxed">"I tried their Signature Facial and my skin has
                        never looked better. No hard selling, just pure professional service. I've found my regular
                        spa!"</p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                            JL</div>
                        <div>
                            <div class="font-bold text-dark text-sm">Jessica L.</div>
                            <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Verified
                                Client via Google</div>
                        </div>
                    </div>
                </div>
                <!-- Review 3 -->
                <div
                    class="bg-[#faf7fc] p-8 rounded-3xl border border-light/50 shadow-sm hover:shadow-float transition-all duration-300">
                    <div class="flex items-center gap-2 mb-4 text-gold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 italic mb-6 leading-relaxed">"Booking online was a breeze. I arrived and was
                        immediately ushered into a tranquil room. The aromatherapy massage was divine. Highly
                        recommend!"</p>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                            AT</div>
                        <div>
                            <div class="font-bold text-dark text-sm">Amanda T.</div>
                            <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Verified
                                Client via Google</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. The Experience / About -->
    <section class="py-24 bg-[#faf7fc] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-1/2 relative">
                    <div
                        class="relative rounded-[2rem] overflow-hidden shadow-2xl z-10 aspect-[4/5] border-8 border-white">
                        <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?q=80&w=2070&auto=format&fit=crop"
                            alt="Relaxing Spa Environment" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -top-8 -left-8 w-48 h-48 bg-primary rounded-full blur-3xl opacity-30"></div>
                    <div class="absolute -bottom-8 -right-8 w-64 h-64 bg-dark rounded-full blur-3xl opacity-20"></div>
                </div>
                <div class="w-full lg:w-1/2">
                    <h2 class="text-4xl md:text-5xl font-black text-dark mb-6 leading-tight">More than just a treatment,
                        it's an <span class="text-primary">experience.</span></h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        At Rupini's Spa, we believe that true beauty radiates from within. Our philosophy centers on
                        creating a holistic sanctuary where modern stress melts away, replaced by profound tranquility
                        and rejuvenation.
                    </p>
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed">
                        With over two decades of experience in the wellness industry, our expert team provides
                        personalized care using only premium, carefully selected products to ensure your time with us is
                        nothing short of transformative.
                    </p>
                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div>
                            <div class="text-4xl font-black text-primary mb-2">200+</div>
                            <div class="text-gray-500 font-medium">Premium Services</div>
                        </div>
                        <div>
                            <div class="text-4xl font-black text-primary mb-2">15k+</div>
                            <div class="text-gray-500 font-medium">Happy Clients</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4.5 Our Locations -->
    <section class="py-24 bg-white border-t border-light/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-dark mb-4">Conveniently Located in SG</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto text-lg">Easily accessible via MRT, our branches are
                        designed to be your quick escape from the city hustle.</p>
                </div>
                <a href="branches.php"
                    class="flex items-center justify-center px-6 py-2.5 rounded-full border border-primary text-primary font-bold hover:bg-primary hover:text-white transition-colors w-full md:w-auto">
                    View All Branches
                    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 max-w-xl mx-auto">
                <!-- Location 1 -->
                <div
                    class="bg-[#faf7fc] rounded-3xl p-8 border border-light/50 hover:shadow-float transition-all flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-primary mb-6 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-dark mb-2">Little India Branch</h3>
                    <p class="text-gray-600 mb-4">24/26 2nd Floor Buffalo Road, Singapore 219791</p>
                    <div
                        class="inline-flex items-center text-sm font-semibold text-primary bg-primary/10 px-4 py-2 rounded-full mb-6">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        3 mins walk from Farrer Park MRT
                    </div>
                    <a href="book.php"
                        class="w-full block py-3 border-2 border-primary text-primary font-bold rounded-xl hover:bg-primary hover:text-white transition-colors">Book
                        at Little India</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Final CTA -->
    <section class="py-24 relative overflow-hidden bg-fixed bg-center bg-cover"
        style="background-image: url('https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=2070&auto=format&fit=crop');">
        <!-- Natural Dark overlay -->
        <div class="absolute inset-0 bg-dark/80 mix-blend-multiply"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary rounded-full blur-[120px] opacity-40">
        </div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <h2 class="text-4xl md:text-6xl font-black text-white mb-8 tracking-tight">Ready to refresh your glow?</h2>
            <p class="text-xl text-white/80 mb-12 max-w-2xl mx-auto font-light leading-relaxed">Join thousands of
                satisfied clients and discover the Rupini's difference today. Booking your sanctuary takes less than a
                minute.</p>
            <a href="book.php"
                class="inline-flex items-center justify-center px-12 py-5 text-xl font-bold text-dark bg-white rounded-full hover:bg-gray-100 shadow-xl shadow-black/20 transform hover:-translate-y-1 transition-all duration-300">
                Book Your Appointment Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>