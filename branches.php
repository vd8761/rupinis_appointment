<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Find a Rupini's Spa branch near you in Singapore. Currently available in Little India exclusively.">
    <title>Our Branches - Rupini's Spa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- Open Graph SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Our Branches - Rupini's Spa">
    <meta property="og:description" content="Find a Rupini's Spa branch near you in Singapore. Currently available in Little India exclusively.">
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

    <?php include 'includes/header.php'; ?>

    <!-- Hero -->
    <section class="relative pt-32 pb-28 bg-dark overflow-hidden">
        <div class="absolute inset-0 w-full h-full">
            <img src="assets/images/branches_hero.png" alt="Spa Branches"
                class="w-full h-full object-cover object-center opacity-50">
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
                <span class="text-white/90 text-xs font-bold tracking-[0.2em] uppercase">Locations</span>
                <div class="h-[1px] w-8 sm:w-12 bg-gradient-to-l from-transparent to-white/60"></div>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tight mb-6 drop-shadow-lg">Visit
                Our Sanctuaries</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto font-light">Conveniently located in the heart of
                Singapore, our branches are designed to be your quick escape from the city hustle.</p>
        </div>
    </section>

    <!-- Branches List -->
    <section class="py-16 flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            <!-- Branch 1 -->
            <div
                class="bg-white rounded-3xl overflow-hidden shadow-lg border border-light/50 flex flex-col lg:flex-row mb-16">
                <div class="lg:w-2/5 h-64 lg:h-auto relative">
                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=2070&auto=format&fit=crop"
                        alt="Little India Branch" class="absolute inset-0 w-full h-full object-cover">
                </div>
                <div class="p-8 lg:p-12 lg:w-3/5 flex flex-col justify-center">
                    <div
                        class="inline-flex items-center text-sm font-bold text-primary bg-primary/10 px-4 py-1.5 rounded-full mb-4 w-fit">
                        Main Branch</div>
                    <h2 class="text-3xl font-bold text-dark mb-4">Little India</h2>
                    <p class="text-gray-600 mb-8 leading-relaxed">Experience our heritage at the original Rupini's
                        main branch. A spacious sanctuary offering our complete range of treatments in an authentic,
                        luxurious setting.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="font-bold text-dark mb-2 flex items-center"><svg
                                    class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg> Address</h4>
                            <p class="text-gray-600 text-sm">24/26 2nd Floor Buffalo Road,<br>Singapore 219791</p>
                            <p class="text-primary text-sm mt-1 font-semibold">3 mins from Farrer Park MRT</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-dark mb-2 flex items-center"><svg
                                    class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg> Hours</h4>
                            <p class="text-gray-600 text-sm">Mon-Sat - 9.30 AM to 8.30 PM<br>Sunday - 9.30 AM to 6.00 PM<br>Public Holiday Working Hours - 9.30 AM to 6.00 PM
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <a href="book.php"
                            class="px-8 py-3 bg-primary text-white font-bold rounded-full hover:bg-dark transition-colors text-center">Book
                            Here</a>
                        <a href="tel:+6562916789"
                            class="px-8 py-3 border border-light text-dark font-bold rounded-full hover:bg-gray-50 transition-colors text-center">Call
                            Branch</a>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>

</html>