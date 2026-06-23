<?php
$current_page = basename($_SERVER['PHP_SELF']);
require_once __DIR__ . '/db.php';
?>
<!-- Tailwind CSS Header -->
<header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-light/50 shadow-sm">
    <!-- Litepicker CDN -->
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex-shrink-0 flex items-center">
                <a href="index.php" class="flex items-center gap-3">
                    <img src="assets/images/logo-dark.png"
                        alt="Rupini's Spa Logo" class="h-10 object-contain">
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="index.php"
                    class="<?php echo $current_page == 'index.php' ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary font-medium'; ?> transition-colors duration-200">Home</a>
                <a href="services.php"
                    class="<?php echo $current_page == 'services.php' ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary font-medium'; ?> transition-colors duration-200">Services</a>
                <a href="branches.php"
                    class="<?php echo $current_page == 'branches.php' ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary font-medium'; ?> transition-colors duration-200">Branches</a>
                <a href="contact.php"
                    class="<?php echo $current_page == 'contact.php' ? 'text-primary font-bold' : 'text-gray-600 hover:text-primary font-medium'; ?> transition-colors duration-200">Contact</a>
            </nav>

            <!-- Book Now Button (Desktop) -->
            <div class="hidden md:flex items-center">
                <a href="book.php"
                    class="bg-primary hover:bg-dark text-white px-6 py-2.5 rounded-full font-bold transition-all duration-300 shadow-lg shadow-primary/20 transform hover:-translate-y-0.5">
                    Book Now
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" id="mobile-menu-btn" class="text-dark hover:text-primary focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown (Hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-light/50 shadow-lg absolute w-full left-0 top-20">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <a href="index.php" class="block px-3 py-3 rounded-md text-base font-bold <?php echo $current_page == 'index.php' ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-primary/5 hover:text-primary'; ?> transition-colors">Home</a>
            <a href="services.php" class="block px-3 py-3 rounded-md text-base font-bold <?php echo $current_page == 'services.php' ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-primary/5 hover:text-primary'; ?> transition-colors">Services</a>
            <a href="branches.php" class="block px-3 py-3 rounded-md text-base font-bold <?php echo $current_page == 'branches.php' ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-primary/5 hover:text-primary'; ?> transition-colors">Branches</a>
            <a href="contact.php" class="block px-3 py-3 rounded-md text-base font-bold <?php echo $current_page == 'contact.php' ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-primary/5 hover:text-primary'; ?> transition-colors">Contact</a>
            <div class="pt-4 border-t border-gray-100">
                <a href="book.php" class="block w-full text-center bg-primary text-white px-6 py-3 rounded-full font-bold shadow-md hover:bg-dark transition-colors">Book Now</a>
            </div>
        </div>
    </div>
</header>

<script>
    // Mobile Menu Toggle Logic
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>