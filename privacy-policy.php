<?php
$pageTitle = "Privacy Policy";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Privacy Policy for Rupini's Spa. Learn how we handle your data and ensure your privacy.">
    <title><?php echo $pageTitle; ?> - Rupini's Spa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- Open Graph SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo $pageTitle; ?> - Rupini's Spa">
    <meta property="og:description" content="Privacy Policy for Rupini's Spa. Learn how we handle your data and ensure your privacy.">
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
            <img src="assets/images/hero_bg.png" alt="Privacy Policy"
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
                Privacy Policy
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
                            <a href="#introduction" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">1. Introduction</a>
                            <a href="#information" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">2. Information We Collect</a>
                            <a href="#use" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">3. Use of Information</a>
                            <a href="#disclosure" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">4. Disclosure</a>
                            <a href="#contact" class="block text-sm font-medium text-gray-600 hover:text-primary transition-colors hover:translate-x-1 transform duration-200">5. Contact Us</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 lg:p-16 border border-gray-100 prose prose-lg prose-purple max-w-none">
                        
                        <h2 id="introduction" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 mt-0 scroll-mt-32">1. Introduction</h2>
                        <p class="text-gray-600 mb-10 leading-relaxed">
                            At <strong>Rupini's</strong>, we are committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our booking services. Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site.
                        </p>

                        <h2 id="information" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">2. Information We Collect</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            We may collect information about you in a variety of ways. The information we may collect on the Site includes:
                        </p>
                        <div class="bg-primary/5 rounded-2xl p-8 mb-10 border border-primary/10">
                            <ul class="list-disc pl-4 text-gray-700 space-y-4 m-0 marker:text-primary">
                                <li><strong>Personal Data:</strong> Personally identifiable information, such as your name, email address, and telephone number, that you voluntarily give to us when you register for an appointment or contact us.</li>
                                <li><strong>Derivative Data:</strong> Information our servers automatically collect when you access the Site, such as your IP address, your browser type, your operating system, your access times, and the pages you have viewed directly before and after accessing the Site.</li>
                            </ul>
                        </div>

                        <h2 id="use" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">3. Use of Your Information</h2>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Having accurate information about you permits us to provide you with a smooth, efficient, and customized experience. Specifically, we may use information collected about you via the Site to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-600 mb-10 space-y-3 marker:text-primary">
                            <li>Create and manage your appointments.</li>
                            <li>Email you regarding your account or appointments.</li>
                            <li>Fulfill and manage purchases, orders, payments, and other transactions related to the Site.</li>
                            <li>Increase the efficiency and operation of the Site.</li>
                            <li>Monitor and analyze usage and trends to improve your experience with the Site.</li>
                        </ul>

                        <h2 id="disclosure" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">4. Disclosure of Your Information</h2>
                        <p class="text-gray-600 mb-10 leading-relaxed">
                            We may share information we have collected about you in certain situations. Your information may be disclosed as follows: By Law or to Protect Rights, Third-Party Service Providers (such as HitPay for payment processing), and Business Transfers. We do not sell or rent your personal information to third parties for their marketing purposes without your explicit consent.
                        </p>

                        <h2 id="contact" class="text-3xl font-bold text-dark mb-6 pb-4 border-b border-gray-100 scroll-mt-32">5. Contact Us</h2>
                        <p class="text-gray-600 leading-relaxed mb-0">
                            If you have questions or comments about this Privacy Policy, please contact us at:
                        </p>
                        <div class="mt-8 p-8 border border-gray-100 rounded-2xl bg-gray-50 flex flex-col sm:flex-row gap-8 shadow-sm">
                            <div class="flex-1">
                                <strong class="text-dark block mb-2 text-lg">Rupini's Spa</strong>
                                <span class="text-gray-500 block leading-relaxed">123 Serangoon Road,<br>Singapore 218000</span>
                            </div>
                            <div class="flex-1">
                                <strong class="text-dark block mb-2 text-lg">Get in Touch</strong>
                                <span class="text-gray-500 block mb-1">Email: <a href="mailto:rupinisit@gmail.com" class="text-primary font-medium hover:underline">rupinisit@gmail.com</a></span>
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
