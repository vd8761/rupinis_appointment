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

    <?php
    $fav_categories = [];
    $services = [];
    try {
        $stmt_cat = $pdo->prepare("SELECT cattitle FROM oc_product_cat WHERE favourite_status = 1 AND status = 1 ORDER BY IF(catorder > 0, 0, 1) ASC, catorder ASC, cattitle ASC");
        $stmt_cat->execute();
        $fav_categories = $stmt_cat->fetchAll();

        // Fetch services/products that belong to favorite categories
        $stmt_srv = $pdo->prepare("
            SELECT p.prdt_id, p.prdt_name, p.prdt_description, p.prdt_service_time, p.prdt_final_price, p.prd_image, c.cattitle 
            FROM oc_product p 
            INNER JOIN oc_product_cat c ON p.catid = c.catid 
            WHERE p.status = 1 AND p.deleted = 0 AND p.prdt_type = 2 AND p.prdt_name NOT LIKE '%Coffee%' AND c.favourite_status = 1 AND c.status = 1
            ORDER BY IF(c.catorder > 0, 0, 1) ASC, c.catorder ASC, c.cattitle ASC, IF(p.prdt_package_orderno > 0, 0, 1) ASC, p.prdt_package_orderno ASC
        ");
        $stmt_srv->execute();
        $services = $stmt_srv->fetchAll();
    } catch (\PDOException $e) {
        // Handle gracefully
    }
    ?>
    <section class="py-16 flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Categories / Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-16" id="filter-buttons">
                <button data-filter="all" class="filter-btn active px-6 py-2.5 rounded-full bg-primary text-white font-bold shadow-md transition-colors">All Services</button>
                <?php foreach ($fav_categories as $cat): ?>
                    <button data-filter="<?php echo htmlspecialchars($cat['cattitle']); ?>" class="filter-btn px-6 py-2.5 rounded-full bg-white text-gray-600 font-bold shadow-sm border border-light hover:bg-primary hover:text-white transition-colors">
                        <?php echo htmlspecialchars($cat['cattitle']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="services-grid">
                <?php foreach ($services as $service): 
                    $cat_name = $service['cattitle'] ? htmlspecialchars($service['cattitle']) : 'Uncategorized';
                    
                    // Determine image (use placeholder if not set)
                    $img_src = !empty($service['prd_image']) ? 'uploads/services/' . htmlspecialchars($service['prd_image']) : 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?q=80&w=2070&auto=format&fit=crop';
                    
                    // Format Price
                    $price = number_format((float)$service['prdt_final_price'], 2);
                    
                    // Format duration
                    $duration = !empty($service['prdt_service_time']) ? htmlspecialchars($service['prdt_service_time']) . ' min' : 'Varies';
                    
                    // Format description
                    $description = !empty($service['prdt_description']) ? htmlspecialchars($service['prdt_description']) : 'Experience our premium ' . htmlspecialchars($service['prdt_name']) . '.';
                ?>
                <div data-category="<?php echo $cat_name; ?>" class="service-card group rounded-3xl overflow-hidden bg-white border border-light/50 hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="h-60 overflow-hidden relative">
                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($service['prdt_name']); ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-primary"><?php echo $cat_name; ?></div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <h3 class="text-2xl font-bold text-dark mb-2"><?php echo htmlspecialchars($service['prdt_name']); ?></h3>
                        <p class="text-gray-500 mb-6 flex-grow line-clamp-3"><?php echo $description; ?></p>
                        <div class="flex items-center justify-between pt-4 border-t border-light/50">
                            <div>
                                <span class="block text-sm text-gray-400">Duration: <?php echo $duration; ?></span>
                                <span class="text-xl font-black text-primary">$<?php echo $price; ?></span>
                            </div>
                            <a href="book.php?service_id=<?php echo $service['prdt_id']; ?>" class="px-5 py-2 bg-primary/10 text-primary font-bold rounded-full hover:bg-primary hover:text-white transition-colors">Book Now</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const buttons = document.querySelectorAll('.filter-btn');
                const cards = document.querySelectorAll('.service-card');

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        // Remove active styling from all buttons
                        buttons.forEach(b => {
                            b.classList.remove('bg-primary', 'text-white');
                            b.classList.add('bg-white', 'text-gray-600');
                        });

                        // Add active styling to clicked button
                        btn.classList.remove('bg-white', 'text-gray-600');
                        btn.classList.add('bg-primary', 'text-white');

                        const filter = btn.getAttribute('data-filter');

                        // Show/Hide cards based on filter
                        cards.forEach(card => {
                            if (filter === 'all' || card.getAttribute('data-category') === filter) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                });
            });
        </script>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>