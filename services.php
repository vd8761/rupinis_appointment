<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Explore our premium range of beauty services including facials, massages, and threading.">
    <title>Our Services - Rupini's Spa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- Open Graph SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Our Services - Rupini's Spa">
    <meta property="og:description" content="Explore our premium range of beauty services including facials, massages, and threading.">
    <meta property="og:image" content="assets/images/logo-dark.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#6F2C91', dark: '#4B145F', lavender: '#F7F0FA', light: '#E6D8EF' },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
</head>

<body class="bg-[#faf7fc] text-gray-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-28 bg-dark overflow-hidden">
        <div class="absolute inset-0 w-full h-full">
            <img src="https://images.unsplash.com/photo-1552693673-1bf958298935?q=80&w=2070&auto=format&fit=crop"
                alt="Spa Services" class="w-full h-full object-cover opacity-50">
        </div>
        
        <!-- Ambient Premium Glow -->
        <div class="absolute inset-0 opacity-60 mix-blend-screen pointer-events-none">
            <div class="absolute -top-40 -right-40 w-[30rem] h-[30rem] rounded-full bg-primary blur-[120px]"></div>
            <div class="absolute bottom-10 -left-20 w-[20rem] h-[20rem] rounded-full bg-[#8c3ab8] blur-[100px]"></div>
        </div>

        <div class="absolute inset-0 bg-gradient-to-t from-dark/95 via-dark/50 to-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <div class="flex items-center justify-center gap-3 sm:gap-4 mb-4">
                <div class="h-[1px] w-8 sm:w-12 bg-gradient-to-r from-transparent to-white/60"></div>
                <span class="text-white/90 text-xs font-bold tracking-[0.2em] uppercase">Treatments</span>
                <div class="h-[1px] w-8 sm:w-12 bg-gradient-to-l from-transparent to-white/60"></div>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tight mb-6 drop-shadow-lg">Our
                Curated Services</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto font-light">Explore our comprehensive menu of premium
                beauty therapies designed to rejuvenate your mind, body, and soul.</p>
        </div>
    </section>

    <!-- Services Grid Section -->
    <section class="py-16 flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Categories / Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                <button
                    class="px-6 py-2.5 rounded-full bg-primary text-white font-bold shadow-md hover:bg-dark transition-colors">All
                    Services</button>
                <button
                    class="px-6 py-2.5 rounded-full bg-white text-gray-600 font-bold shadow-sm border border-light hover:bg-primary hover:text-white transition-colors">Facials</button>
                <button
                    class="px-6 py-2.5 rounded-full bg-white text-gray-600 font-bold shadow-sm border border-light hover:bg-primary hover:text-white transition-colors">Body
                    Massage</button>
                <button
                    class="px-6 py-2.5 rounded-full bg-white text-gray-600 font-bold shadow-sm border border-light hover:bg-primary hover:text-white transition-colors">Threading</button>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Card 1 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-white border border-light/50 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?q=80&w=2070&auto=format&fit=crop"
                            alt="Signature Facial"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary">
                            Facials</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold text-dark mb-2">Signature Glowing Facial</h3>
                        <p class="text-gray-500 mb-6 flex-grow">A deeply hydrating and cleansing facial tailored to your
                            specific skin type. Restores natural radiance using premium organic products.</p>
                        <div class="flex items-center justify-between pt-4 border-t border-light/50">
                            <div>
                                <span class="block text-sm text-gray-400">Duration: 60 min</span>
                                <span class="text-xl font-black text-primary">$85.00</span>
                            </div>
                            <a href="book.php"
                                class="px-5 py-2 bg-primary/10 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors">Book
                                Now</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-white border border-light/50 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=2070&auto=format&fit=crop"
                            alt="Aromatherapy Massage"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary">
                            Massage</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold text-dark mb-2">Aromatherapy Massage</h3>
                        <p class="text-gray-500 mb-6 flex-grow">Melt away stress with a full-body massage utilizing
                            custom-blended essential oils for deep relaxation and muscle tension relief.</p>
                        <div class="flex items-center justify-between pt-4 border-t border-light/50">
                            <div>
                                <span class="block text-sm text-gray-400">Duration: 90 min</span>
                                <span class="text-xl font-black text-primary">$120.00</span>
                            </div>
                            <a href="book.php"
                                class="px-5 py-2 bg-primary/10 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors">Book
                                Now</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-white border border-light/50 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?q=80&w=2070&auto=format&fit=crop"
                            alt="Precision Threading"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary">
                            Threading</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold text-dark mb-2">Precision Eyebrow Threading</h3>
                        <p class="text-gray-500 mb-6 flex-grow">Expert eyebrow shaping using traditional threading
                            techniques for perfectly defined, lasting results with minimal irritation.</p>
                        <div class="flex items-center justify-between pt-4 border-t border-light/50">
                            <div>
                                <span class="block text-sm text-gray-400">Duration: 15 min</span>
                                <span class="text-xl font-black text-primary">$15.00</span>
                            </div>
                            <a href="book.php"
                                class="px-5 py-2 bg-primary/10 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors">Book
                                Now</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-white border border-light/50 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=2070&auto=format&fit=crop"
                            alt="Anti-Aging Facial"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary">
                            Facials</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold text-dark mb-2">Anti-Aging Gold Facial</h3>
                        <p class="text-gray-500 mb-6 flex-grow">A luxurious treatment using 24k gold infused serums to
                            improve skin elasticity, reduce fine lines, and impart a luminous glow.</p>
                        <div class="flex items-center justify-between pt-4 border-t border-light/50">
                            <div>
                                <span class="block text-sm text-gray-400">Duration: 75 min</span>
                                <span class="text-xl font-black text-primary">$150.00</span>
                            </div>
                            <a href="book.php"
                                class="px-5 py-2 bg-primary/10 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors">Book
                                Now</a>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-white border border-light/50 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?q=80&w=2070&auto=format&fit=crop"
                            alt="Deep Tissue Massage"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary">
                            Massage</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold text-dark mb-2">Deep Tissue Massage</h3>
                        <p class="text-gray-500 mb-6 flex-grow">Targeted firm pressure massage to relieve chronic muscle
                            tension, perfect for active lifestyles and deep stress relief.</p>
                        <div class="flex items-center justify-between pt-4 border-t border-light/50">
                            <div>
                                <span class="block text-sm text-gray-400">Duration: 60 min</span>
                                <span class="text-xl font-black text-primary">$110.00</span>
                            </div>
                            <a href="book.php"
                                class="px-5 py-2 bg-primary/10 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors">Book
                                Now</a>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div
                    class="group rounded-3xl overflow-hidden bg-white border border-light/50 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=2070&auto=format&fit=crop"
                            alt="Bridal Package"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div
                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary">
                            Packages</div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold text-dark mb-2">Bridal Radiance Package</h3>
                        <p class="text-gray-500 mb-6 flex-grow">The ultimate pre-wedding pampering session. Includes our
                            Signature Facial, full body polish, and express threading.</p>
                        <div class="flex items-center justify-between pt-4 border-t border-light/50">
                            <div>
                                <span class="block text-sm text-gray-400">Duration: 180 min</span>
                                <span class="text-xl font-black text-primary">$280.00</span>
                            </div>
                            <a href="book.php"
                                class="px-5 py-2 bg-primary/10 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors">Book
                                Now</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>