<?php
$pageTitle = "Terms & Conditions";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Terms and Conditions for Rupini's Spa. Please read our policies regarding bookings and services.">
    <title><?php echo $pageTitle; ?> - Rupini's Spa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- Open Graph SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $pageTitle; ?> - Rupini's Spa">
    <meta property="og:description" content="Terms and Conditions for Rupini's Spa. Please read our policies regarding bookings and services.">
    <meta property="og:image" content="assets/images/logo-dark.png">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (CDN for development) -->
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
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Smooth scroll for TOC -->
    <style>html { scroll-behavior: smooth; }</style>
</head>
<body class="bg-[#faf7fc] text-gray-800 antialiased font-sans flex flex-col min-h-screen selection:bg-primary selection:text-white">

    <?php include 'includes/header.php'; ?>

    <!-- Immersive Hero Section -->
    <section class="relative pt-32 pb-40 bg-dark overflow-hidden">
        <div class="absolute inset-0 w-full h-full">
            <img src="assets/images/hero_bg.png" alt="Terms and Conditions"
                class="w-full h-full object-cover opacity-20 mix-blend-overlay">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-dark via-dark/80 to-dark"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <!-- Editorial Badge -->
            <div class="flex items-center justify-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="h-[1px] w-8 sm:w-12 md:w-24 bg-gradient-to-r from-transparent to-white/60"></div>
                <span class="text-white/90 text-xs sm:text-sm font-bold tracking-[0.3em] uppercase drop-shadow-sm">Trust & Transparency</span>
                <div class="h-[1px] w-8 sm:w-12 md:w-24 bg-gradient-to-l from-transparent to-white/60"></div>
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tight mb-6 drop-shadow-lg">
                Terms & Conditions
            </h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto font-medium">
                Last Updated: <?php echo date('F d, Y'); ?>
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <main class="flex-grow">
        <section class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Sidebar Navigation -->
                <div class="lg:w-1/4 hidden lg:block">
                    <div class="sticky top-32 bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Table of Contents</h3>
                        <nav class="space-y-4">
                            <a href="#agreement" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">1. Agreement to Terms</a>
                            <a href="#appointments" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">2. Appointments & Cancellations</a>
                            <a href="#payments" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">3. Payments & Pricing</a>
                            <a href="#health" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">4. Health & Safety</a>
                            <a href="#modifications" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">5. Modifications</a>
                            <a href="#contact" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">6. Contact Info</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 lg:p-16 border border-gray-100 prose prose-lg prose-purple max-w-none">
                        
                        <h2 id="agreement" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 mt-0 scroll-mt-32">1. Agreement to Terms</h2>
                        <p class="text-gray-600 mb-10 leading-relaxed">
                            These Terms of Service constitute a legally binding agreement made between you, whether personally or on behalf of an entity (“you”) and Rupini's (“we,” “us” or “our”), concerning your access to and use of our website as well as any other media form, media channel, mobile website, or mobile application related, linked, or otherwise connected thereto (collectively, the “Site”).
                        </p>

                        <h2 id="appointments" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">2. Appointments and Cancellations</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            By booking an appointment through our Site, you agree to the following terms regarding your booking:
                        </p>
                        <ul class="list-disc pl-6 text-gray-600 mb-10 space-y-3 marker:text-primary">
                            <li><strong>Arrival Time:</strong> Please arrive at least 10 minutes prior to your scheduled appointment to ensure a full session.</li>
                            <li><strong>Cancellations:</strong> We require at least 24 hours' notice for the cancellation or rescheduling of an appointment. Failure to do so may result in a cancellation fee.</li>
                            <li><strong>Late Arrivals:</strong> If you arrive late, your session may be shortened to accommodate the next appointment, and you will still be charged the full amount for the service booked.</li>
                        </ul>

                        <h2 id="payments" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">3. Payments and Pricing</h2>
                        <div class="bg-primary/5 rounded-2xl p-8 mb-10 border border-primary/10">
                            <p class="text-gray-700 m-0 leading-relaxed">
                                All prices are subject to change without prior notice. We reserve the right to correct any errors or mistakes in pricing, even if we have already requested or received payment. Payment is due at the time of service unless pre-paid online. We accept various forms of payment, which are securely processed through our third-party payment provider (e.g., HitPay).
                            </p>
                        </div>

                        <h2 id="health" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">4. Health and Safety</h2>
                        <p class="text-gray-600 mb-10 leading-relaxed">
                            For your safety and the safety of our staff, please inform us of any medical conditions, allergies, or physical ailments prior to your treatment. Some treatments may not be suitable for individuals with certain health conditions. We reserve the right to refuse service if we believe a treatment may be harmful to your health.
                        </p>

                        <h2 id="modifications" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">5. Modifications to the Service and Prices</h2>
                        <p class="text-gray-600 mb-10 leading-relaxed">
                            Prices for our products and services are subject to change without notice. We reserve the right at any time to modify or discontinue the Service (or any part or content thereof) without notice at any time.
                        </p>

                        <h2 id="contact" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">6. Contact Information</h2>
                        <p class="text-gray-600 leading-relaxed mb-0">
                            Questions about the Terms of Service should be sent to us at:
                        </p>
                        <div class="mt-8 p-8 border border-gray-100 rounded-2xl bg-gray-50 flex flex-col sm:flex-row gap-8 shadow-sm">
                            <div class="flex-1">
                                <strong class="text-dark block mb-2 text-lg">Rupini's Spa</strong>
                                <span class="text-gray-500 block leading-relaxed">123 Serangoon Road,<br>Singapore 218000</span>
                            </div>
                            <div class="flex-1">
                                <strong class="text-dark block mb-2 text-lg">Get in Touch</strong>
                                <span class="text-gray-500 block mb-1">Email: <a href="mailto:hello@rupinis.com" class="text-primary font-medium hover:underline">hello@rupinis.com</a></span>
                                <span class="text-gray-500 block">Phone: <a href="tel:+6561234567" class="text-primary font-medium hover:underline">+65 6123 4567</a></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
