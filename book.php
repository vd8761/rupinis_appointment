<?php
require_once 'includes/db.php';

$active_branches = [];
$service_categories = [];
try {
    $stmt = $pdo->prepare("SELECT branch_id, branch_name FROM oc_branch WHERE status = 1 AND deleted = 0 ORDER BY branch_id ASC");
    $stmt->execute();
    $active_branches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch categories that have services
    $stmt_cats = $pdo->prepare("SELECT catid, cattitle FROM oc_product_cat WHERE status = 1 ORDER BY IF(catorder > 0, 0, 1) ASC, catorder ASC, cattitle ASC");
    $stmt_cats->execute();
    $cats = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all active services
    $stmt_srv = $pdo->prepare("SELECT prdt_id, catid, prdt_name, prdt_final_price, prdt_service_time FROM oc_product WHERE prdt_type = 2 AND prdt_pos = 1 AND status = 1 AND deleted = 0 AND prdt_name NOT LIKE '%Coffee%' ORDER BY prdt_name ASC");
    $stmt_srv->execute();
    $services = $stmt_srv->fetchAll(PDO::FETCH_ASSOC);

    // Group services by category
    foreach ($cats as $cat) {
        $catServices = [];
        foreach ($services as $s) {
            if ($s['catid'] == $cat['catid']) {
                $catServices[] = $s;
            }
        }
        if (count($catServices) > 0) {
            $cat['services'] = $catServices;
            $service_categories[] = $cat;
        }
    }
} catch (\PDOException $e) {
    // Ignore DB errors
}

$promo_code = isset($_GET['promo']) ? htmlspecialchars(trim($_GET['promo']), ENT_QUOTES, 'UTF-8') : '';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Book your premium spa appointment at Rupini's Spa. Select your services, choose a beautician, and reserve your time online.">
    <title>Book Appointment - Rupini's Spa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- Open Graph SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Book Appointment - Rupini's Spa">
    <meta property="og:description" content="Book your premium spa appointment at Rupini's Spa. Select your services, choose a beautician, and reserve your time online.">
    <meta property="og:image" content="assets/images/logo-dark.png">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">



    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#6F2C91', dark: '#4B145F', lavender: '#F7F0FA', light: '#E6D8EF', success: '#10b981', error: '#ef4444' },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Select2 Tailwind Overrides */
        .select2-container--default .select2-selection--single {
            height: 100%;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151; /* gray-700 */
            font-weight: 700;
            padding-left: 0;
            line-height: normal;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 5px;
        }
        .select2-dropdown {
            border: 1px solid #E6D8EF;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            z-index: 50;
            background: #ffffff;
        }
        .select2-search--dropdown {
            padding: 10px;
            background: #faf7fc;
            border-bottom: 1px solid #E6D8EF;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #E6D8EF;
            border-radius: 0.5rem;
            padding: 8px 12px;
            outline: none;
            width: 100%;
            font-family: inherit;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #6F2C91;
            box-shadow: 0 0 0 2px rgba(111, 44, 145, 0.2);
        }
        .select2-results__option {
            padding: 10px 16px;
            font-weight: 600;
            color: #4B145F;
            font-size: 15px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6F2C91;
            color: white;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #F7F0FA;
            color: #6F2C91;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E6D8EF;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6F2C91;
        }

        border-radius: 0.5rem !important;
        font-weight: 600;
        color: #4B145F;
        border: 2px solid transparent !important;
        margin: 2px !important;
        height: 45px !important;
        line-height: 41px !important;
        }

        .flatpickr-day:hover {
            background: #F7F0FA !important;
            border-color: #E6D8EF !important;
        }

        .flatpickr-day.selected {
            background: #6F2C91 !important;
            color: white !important;
            border-color: #6F2C91 !important;
        }

        .flatpickr-day.flatpickr-disabled {
            color: #cbd5e1 !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 800 !important;
            color: #4B145F !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #6F2C91 !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #6F2C91 !important;
        }

        .flatpickr-prev-month svg,
        .flatpickr-next-month svg {
            fill: #6F2C91 !important;
        }
    </style>
</head>

<body class="bg-[#faf7fc] text-gray-800 antialiased font-sans flex flex-col min-h-screen relative">

    <!-- Custom Calendar Modal -->
    <div id="calendar-modal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-dark/40 backdrop-blur-sm transition-opacity" id="calendar-backdrop"></div>

        <!-- Modal Content -->
        <div class="relative bg-white rounded-[2rem] shadow-2xl p-6 sm:p-8 w-full max-w-[360px] transform transition-all scale-95 opacity-0"
            id="calendar-content">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <button type="button" id="cal-prev"
                    class="text-gray-400 hover:text-primary transition-colors p-2 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div class="text-xl font-black text-dark flex items-center gap-2">
                    <span id="cal-month" class="cursor-pointer hover:text-primary">May</span>
                    <span id="cal-year" class="font-normal text-gray-400">2026</span>
                </div>
                <button type="button" id="cal-next"
                    class="text-gray-400 hover:text-primary transition-colors p-2 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Days Header -->
            <div class="grid grid-cols-7 gap-1 text-center mb-4">
                <div class="text-sm font-bold text-gray-500">Sun</div>
                <div class="text-sm font-bold text-gray-500">Mon</div>
                <div class="text-sm font-bold text-gray-500">Tue</div>
                <div class="text-sm font-bold text-gray-500">Wed</div>
                <div class="text-sm font-bold text-gray-500">Thu</div>
                <div class="text-sm font-bold text-gray-500">Fri</div>
                <div class="text-sm font-bold text-gray-500">Sat</div>
            </div>

            <!-- Dates Grid -->
            <div id="cal-grid" class="grid grid-cols-7 gap-y-2 gap-x-1 text-center">
                <!-- Populated via JS -->
            </div>

            <button type="button" id="cal-close"
                class="mt-6 w-full text-center text-sm font-bold text-gray-400 hover:text-primary transition-colors">Cancel</button>
        </div>
    </div>

    <?php include 'includes/header.php'; ?>

    <!-- Immersive Hero Section (Matches services.php) -->
    <section class="relative pt-32 pb-24 bg-dark overflow-hidden">
        <div class="absolute inset-0 w-full h-full">
            <img src="assets/images/booking_bg.png" alt="Spa Booking"
                class="w-full h-full object-cover object-[center_15%] md:object-center opacity-50 mix-blend-overlay">
        </div>
        <div class="absolute inset-0"></div> <!-- bg-gradient-to-t from-[#faf7fc] via-dark/80 to-transparent -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <!-- Editorial Badge -->
            <div class="flex items-center justify-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="h-[1px] w-8 sm:w-12 md:w-24 bg-gradient-to-r from-transparent to-white/60"></div>
                <span
                    class="text-white/90 text-xs sm:text-sm font-bold tracking-[0.2em] sm:tracking-[0.3em] uppercase drop-shadow-sm">Premium
                    Spa Experience</span>
                <div class="h-[1px] w-8 sm:w-12 md:w-24 bg-gradient-to-l from-transparent to-white/60"></div>
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white tracking-tight mb-6 drop-shadow-lg">
                Reserve Your Time</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto font-light">Select your preferred treatments and find a
                time that works for you. Fast, secure, and instantly confirmed.</p>
        </div>
    </section>

    <!-- Unified Split Pane Form -->
    <section id="main-booking-section" class="relative z-20 -mt-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left Pane: The Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2rem] shadow-xl border border-light overflow-hidden">
                    <!-- THE BOOKING FORM -->
                    <div id="booking-form" class="p-6 md:p-10 space-y-12">

                        <!-- Section 1: Outlet -->
                        <!-- Section 1: Mobile Number -->
                        <div id="sec-mobile" class="scroll-mt-32">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-sm shadow-md">
                                    1</div>
                                <h2 class="text-2xl font-bold text-dark">Your Mobile Number</h2>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-dark mb-2">Mobile Number <span
                                        class="text-error">*</span></label>
                                <div class="flex shadow-sm rounded-xl relative border border-light focus-within:ring-2 focus-within:ring-primary focus-within:border-primary bg-gray-50 transition-all overflow-hidden">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                        <img src="https://flagcdn.com/w20/sg.png" alt="SG" class="w-5 h-auto rounded-sm object-cover shadow-sm">
                                    </div>
                                    <select id="f-cc"
                                        class="pl-12 pr-4 py-4 bg-transparent border-0 focus:ring-0 outline-none text-gray-700 font-bold text-lg appearance-none relative z-0 border-r border-light rounded-none">
                                        <option value="+65">+65</option>
                                    </select>
                                    <input type="tel" id="f-mobile"
                                        class="w-full pl-5 pr-20 py-4 bg-transparent border-0 focus:ring-0 outline-none transition-all text-lg"
                                        placeholder="9123 4567" maxlength="8">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span id="mobile-counter" class="text-sm font-medium text-gray-400 hidden">0/8</span>
                                    </div>
                                </div>
                                <p class="hidden text-error text-sm font-bold mt-2 err-msg" id="err-mobile">
                                    <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Mobile number is required.
                                </p>
                            </div>
                            
                            <!-- Customer Details (Hidden until mobile is entered) -->
                            <div id="customer-details" class="hidden space-y-4 mt-6 p-5 bg-gray-50 border border-light rounded-xl">
                                <div>
                                    <label class="block text-sm font-bold text-dark mb-2">Full Name <span
                                            class="text-error">*</span></label>
                                    <input type="text" id="f-name"
                                        class="w-full px-5 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white transition-all text-lg"
                                        placeholder="e.g. Amanda Tan">
                                    <p class="hidden text-error text-sm font-bold mt-2 err-msg" id="err-name">
                                        <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Full name is required.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-dark mb-2">Email Address <span
                                            class="text-gray-400 font-normal">(Optional)</span></label>
                                    <input type="email" id="f-email"
                                        class="w-full px-5 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-white transition-all text-lg"
                                        placeholder="For receipt copy">
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Outlet -->
                        <div class="border-t border-light pt-12 scroll-mt-32" id="sec-outlet">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-sm shadow-md">
                                    2</div>
                                <h2 class="text-2xl font-bold text-dark">Choose Outlet</h2>
                            </div>
                            <?php 
                            $bCount = count($active_branches);
                            if ($bCount === 1) {
                                $gridCols = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3';
                            } elseif ($bCount === 2) {
                                $gridCols = 'grid-cols-1 sm:grid-cols-2';
                            } elseif ($bCount === 3) {
                                $gridCols = 'grid-cols-1 sm:grid-cols-3';
                            } else {
                                $gridCols = 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4';
                            }
                            ?>
                            <div class="grid <?php echo $gridCols; ?> gap-4">
                                <?php foreach ($active_branches as $index => $branch): 
                                    $branchName = htmlspecialchars($branch['branch_name']);
                                    $isActive = ($index === 0) ? 'border-primary bg-primary/5 text-primary' : 'border-light text-gray-500 hover:border-primary/50 hover:bg-gray-50';
                                ?>
                                <button type="button"
                                    class="outlet-btn border-2 font-bold py-4 px-4 rounded-xl transition-all shadow-sm flex items-center justify-center text-center <?php echo $isActive; ?>"
                                    data-outlet="<?php echo $branchName; ?>" data-branchid="<?php echo $branch['branch_id']; ?>"><?php echo $branchName; ?></button>
                                <?php endforeach; ?>
                            </div>
                            <!-- Inline Error -->
                            <p class="hidden text-error text-sm font-bold mt-3 err-msg" id="err-outlet">
                                <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Please choose an outlet.
                            </p>
                        </div>

                        <!-- Section 3: Services -->
                        <div class="border-t border-light pt-12 scroll-mt-32" id="sec-services">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-sm shadow-md">
                                        3</div>
                                    <h2 class="text-2xl font-bold text-dark">Choose Services</h2>
                                </div>
                                <span class="text-sm font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-lg"
                                    id="service-count">0 Selected</span>
                            </div>

                            <!-- Search Bar -->
                            <div class="relative mb-4">
                                <input type="text" id="service-search" placeholder="Search 200+ services..."
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-light rounded-xl text-base focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all text-dark font-medium shadow-sm">
                                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>

                            <!-- Category Pills (Horizontal Scroll) -->
                            <div class="flex overflow-x-auto no-scrollbar gap-2 mb-6 pb-2" id="category-pills">
                                <?php foreach ($service_categories as $cIndex => $cat): ?>
                                <button type="button"
                                    class="cat-pill whitespace-nowrap px-4 py-2 rounded-full text-sm font-bold <?php echo $cIndex === 0 ? 'bg-primary text-white border-transparent' : 'bg-white text-gray-600 border-light hover:border-primary/50'; ?> transition-colors"
                                    data-target="cat-<?php echo htmlspecialchars($cat['catid']); ?>"><?php echo htmlspecialchars($cat['cattitle']); ?></button>
                                <?php endforeach; ?>
                            </div>


                            <div class="space-y-4" id="services-container">
                                <?php foreach ($service_categories as $cIndex => $cat): 
                                    $isFirst = ($cIndex === 0);
                                ?>
                                <div class="service-category border border-light rounded-xl overflow-hidden shadow-sm bg-white <?php echo $isFirst ? '' : 'hidden'; ?>"
                                    id="cat-<?php echo htmlspecialchars($cat['catid']); ?>">
                                    <button type="button"
                                        class="category-toggle w-full flex justify-between items-center px-5 py-4 font-bold text-dark hover:bg-gray-50 transition-colors focus:outline-none">
                                        <span class="text-lg"><?php echo htmlspecialchars($cat['cattitle']); ?></span>
                                        <svg class="w-5 h-5 transform transition-transform duration-200 chevron <?php echo $isFirst ? 'rotate-180' : ''; ?>"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div class="divide-y divide-light category-content border-t border-light <?php echo $isFirst ? '' : 'hidden'; ?>">
                                        <?php 
                                        $sCount = 0;
                                        foreach ($cat['services'] as $service): 
                                            $sCount++;
                                            $sName = htmlspecialchars($service['prdt_name']);
                                            $sPrice = number_format((float)$service['prdt_final_price'], 2);
                                            $sDuration = (int)preg_replace('/\D/', '', $service['prdt_service_time'] ?: '0');
                                            if ($sDuration === 0) $sDuration = 30; // fallback duration
                                            $hiddenClass = ($sCount > 5) ? 'hidden extra-service' : '';
                                        ?>
                                        <div
                                            class="service-item p-5 flex justify-between items-center bg-white hover:bg-gray-50 transition-colors <?php echo $hiddenClass; ?>">
                                            <div>
                                                <h4 class="font-bold text-dark text-base service-name"><?php echo $sName; ?></h4>
                                                <p class="text-sm text-gray-500 mt-1"><?php echo $sDuration; ?> mins • $<?php echo $sPrice; ?></p>
                                            </div>
                                            <button type="button"
                                                class="btn-add-service px-5 py-2 text-sm font-bold text-primary bg-white border border-primary rounded-full hover:bg-primary hover:text-white transition-colors w-[100px] text-center flex justify-center items-center"
                                                data-id="s<?php echo $service['prdt_id']; ?>" data-name="<?php echo $sName; ?>" data-price="<?php echo $service['prdt_final_price']; ?>"
                                                data-duration="<?php echo $sDuration; ?>">+ Add</button>
                                        </div>
                                        <?php endforeach; ?>
                                        
                                        <?php if ($sCount > 5): ?>
                                        <div class="p-4 flex justify-center border-t border-light show-more-container bg-white">
                                            <button type="button" class="btn-show-more px-6 py-2 rounded-full border border-primary text-primary font-bold text-sm hover:bg-primary hover:text-white transition-colors">
                                                Show More (<span class="remaining-count"><?php echo $sCount - 5; ?></span>)
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- No Services Found Message -->
                            <div id="no-services-found" class="hidden text-center py-10 bg-gray-50 rounded-xl border border-dashed border-light">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-500 font-medium">No services found matching your search.</p>
                                <button type="button" class="mt-3 text-primary font-bold text-sm hover:underline" onclick="$('#service-search').val('').trigger('keyup')">Clear Search</button>
                            </div>

                            <!-- Inline Error -->
                            <p class="hidden text-error text-sm font-bold mt-3 err-msg" id="err-services">
                                <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Please select at least one service to continue.
                            </p>
                        </div>

                        <!-- Section 4: Date -->
                        <div class="border-t border-light pt-12 scroll-mt-32" id="sec-date">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-sm shadow-md">
                                    4</div>
                                <h2 class="text-2xl font-bold text-dark">Select Date</h2>
                            </div>

                            <div class="grid grid-cols-4 md:grid-cols-8 gap-2 sm:gap-3" id="date-grid">
                                <!-- Populated via JS -->
                            </div>

                            <p class="hidden text-error text-sm font-bold mt-3 err-msg" id="err-date">
                                <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Please select a valid date from the calendar.
                            </p>
                        </div>

                        <!-- Section 5: Therapist -->
                        <div class="border-t border-light pt-12 scroll-mt-32" id="sec-therapist">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-sm shadow-md">
                                    5</div>
                                <h2 class="text-2xl font-bold text-dark">Choose Therapist</h2>
                            </div>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-4" id="therapist-grid">
                                <!-- Populated via JS -->
                            </div>
                        </div>

                        <!-- Section 6: Time Slots -->
                        <div class="border-t border-light pt-12 scroll-mt-32" id="sec-time">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-sm shadow-md">
                                    6</div>
                                <h2 class="text-2xl font-bold text-dark">Select Time Slot</h2>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3" id="time-grid">
                                <!-- Populated via JS -->
                            </div>

                            <!-- Inline Error -->
                            <p class="hidden text-error text-sm font-bold mt-3 err-msg" id="err-time">
                                <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Please select an available time slot.
                            </p>
                        </div>

                        <!-- Section 7: Details & Payment -->
                        <div class="border-t border-light pt-12 scroll-mt-32" id="sec-details">
                            <div class="flex items-center gap-3 mb-8">
                                <div
                                    class="w-8 h-8 rounded-full bg-dark text-white flex items-center justify-center font-bold text-sm shadow-md">
                                    7</div>
                                <h2 class="text-2xl font-bold text-dark">Your Details</h2>
                            </div>

                            <!-- Name and Email moved to Section 1 -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Payment
                                    Method <span class="text-error">*</span></label>
                                <div class="space-y-4">
                                    <!-- Pay at Store -->
                                    <label
                                        class="flex items-center p-5 border rounded-xl cursor-pointer transition-colors payment-label border-primary bg-primary/5 shadow-sm">
                                        <input type="radio" name="payment" value="Pay at Store"
                                            class="w-6 h-6 text-primary focus:ring-primary border-gray-300"
                                            style="accent-color: #6F2C91;" checked>
                                        <div class="ml-4 flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm text-primary icon-wrapper transition-colors">
                                                <!-- Cash Icon -->
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-dark text-lg">Pay at Store</span>
                                        </div>
                                    </label>
                                    <!-- Membership -->
                                    <label
                                        class="flex items-center p-5 border border-light rounded-xl cursor-pointer hover:bg-gray-50 transition-colors payment-label bg-white shadow-sm">
                                        <input type="radio" name="payment" value="Membership"
                                            class="w-6 h-6 text-primary focus:ring-primary border-gray-300"
                                            style="accent-color: #6F2C91;">
                                        <div class="ml-4 flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3 text-gray-500 icon-wrapper transition-colors">
                                                <!-- Membership Card Icon -->
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-dark text-lg">Membership</span>
                                        </div>
                                    </label>
                                    <!-- HitPay -->
                                    <label
                                        class="flex items-center p-5 border border-light rounded-xl cursor-pointer hover:bg-gray-50 transition-colors payment-label bg-white shadow-sm">
                                        <input type="radio" name="payment" value="HitPay"
                                            class="w-6 h-6 text-primary focus:ring-primary border-gray-300"
                                            style="accent-color: #6F2C91;">
                                        <div class="ml-4 flex items-center">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3 text-gray-500 icon-wrapper transition-colors">
                                                <!-- Hitpay Icon (Digital Wallet/PayNow) -->
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <span class="font-bold text-dark text-lg">HitPay (PayNow / Credit
                                                Card)</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Right Pane: Booking Summary -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 mt-8 lg:mt-0" id="right-pane-summary">
                <div class="bg-white rounded-[2rem] shadow-xl border border-light p-6 sm:p-8">
                    <h3 class="text-2xl font-black text-dark mb-6 border-b border-light pb-4">Booking Summary</h3>

                    <div class="space-y-5 mb-8">
                        <div class="flex justify-between text-base">
                            <span class="text-gray-500 font-medium">Outlet</span>
                            <span class="font-bold text-dark text-right" id="summary-outlet">-</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-gray-500 font-medium">Date</span>
                            <span class="font-bold text-dark text-right" id="summary-date">-</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-gray-500 font-medium">Time</span>
                            <span class="font-bold text-dark text-right" id="summary-time">-</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-gray-500 font-medium">Therapist</span>
                            <span class="font-bold text-dark text-right" id="summary-therapist">-</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 pt-6 mb-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Services Selected
                        </h4>
                        <div id="summary-services-list" class="text-dark">
                            <p class="italic text-gray-400 text-sm">No services selected.</p>
                        </div>
                    </div>

                    <!-- Coupon Code Section -->
                    <div class="border-t border-dashed border-gray-300 pt-6 mb-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Promo / Coupon Code</h4>
                        
                        <!-- Input Group -->
                        <div id="promoInputGroup" class="flex gap-2">
                            <input type="text" id="couponCodeInput" value="<?php echo $promo_code; ?>" placeholder="Enter code" class="w-full px-4 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none bg-gray-50 transition-all font-bold uppercase placeholder:normal-case placeholder:font-normal">
                            <button type="button" id="applyCouponBtn" class="bg-dark text-white px-5 py-3 rounded-xl font-bold hover:bg-primary transition-colors disabled:opacity-50">Apply</button>
                        </div>
                        
                        <!-- Applied State -->
                        <div id="appliedCouponState" class="hidden items-center justify-between bg-green-50 border border-green-200 rounded-xl p-3">
                            <div class="flex items-center gap-2 text-success">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span id="appliedCouponLabel" class="font-bold text-sm"></span>
                            </div>
                            <button type="button" id="removeCouponBtn" class="text-error hover:text-red-700 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div id="couponMessage" class="mt-2 text-sm"></div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 pt-6 mb-8">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-gray-500">Total Duration</span>
                            <span class="font-bold text-dark text-base" id="summary-duration">0 mins</span>
                        </div>
                        
                        <!-- Discount Row (Hidden by default) -->
                        <div id="summary-discount-row" class="hidden flex justify-between items-center mb-3 text-success">
                            <span class="text-sm font-medium">Discount Applied</span>
                            <span class="font-bold text-base" id="summary-discount-val">-$0.00</span>
                        </div>
                        
                        <div class="flex justify-between items-end mt-4">
                            <span class="text-sm font-bold text-dark uppercase tracking-wider mb-1">Total Amount</span>
                            <span class="text-4xl font-black text-primary tracking-tighter"
                                id="summary-total">$0.00</span>
                        </div>
                    </div>

                    <button type="button" id="btn-submit"
                        class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-dark transition-all shadow-md flex justify-center items-center text-lg focus:outline-none">
                        Confirm Booking
                    </button>

                    <p class="text-center text-xs text-gray-400 mt-4 font-medium">By confirming, you agree to our terms
                        and conditions.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- SUCCESS VIEW (Hidden by default) -->
    <section id="step-success" class="relative z-20 -mt-10 px-4 sm:px-6 lg:px-8 max-w-2xl mx-auto hidden pb-20">
        <div class="bg-white rounded-[2rem] shadow-xl border border-light p-8 md:p-12 text-center">
            <div
                class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center text-success mx-auto mb-8 shadow-md border border-green-200">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-dark mb-4">Booking Confirmed!</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">We've sent a confirmation SMS with your booking
                details. We look forward to pampering you.</p>

            <div id="success-loading-card" class="bg-[#faf7fc] px-6 py-12 rounded-2xl border border-light mb-8 w-full shadow-inner text-center hidden">
                <div class="inline-block animate-spin w-8 h-8 border-4 border-primary border-t-transparent rounded-full mb-4"></div>
                <p class="text-gray-500 font-bold">Verifying payment status...</p>
            </div>

            <div id="success-details-card" class="bg-[#faf7fc] px-6 py-8 rounded-2xl border border-light mb-8 w-full shadow-inner text-left">
                <div class="text-center mb-6">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Booking Reference</p>
                    <p class="text-3xl font-black text-primary" id="success-booking-id">#RPS-88291</p>
                </div>

                <div class="border-t border-dashed border-gray-300 pt-6 mt-6">
                    <h3 class="text-lg font-black text-dark mb-4 text-center">Booking Details</h3>
                    <div class="space-y-4 text-base">
                        <div class="flex justify-between items-center"><span
                                class="text-gray-500 font-medium">Outlet</span><span
                                class="font-bold text-dark text-right" id="success-outlet">-</span></div>
                        <div class="flex justify-between items-center"><span
                                class="text-gray-500 font-medium">Date</span><span
                                class="font-bold text-dark text-right" id="success-date">-</span></div>
                        <div class="flex justify-between items-center"><span
                                class="text-gray-500 font-medium">Time</span><span
                                class="font-bold text-dark text-right" id="success-time">-</span></div>
                        <div class="flex justify-between items-center"><span
                                class="text-gray-500 font-medium">Therapist</span><span
                                class="font-bold text-dark text-right" id="success-therapist">-</span></div>
                        <div class="flex justify-between items-center"><span
                                class="text-gray-500 font-medium">Payment Mode</span><span
                                class="font-bold text-dark text-right" id="success-payment-mode">-</span></div>
                        <div class="flex justify-between items-center"><span
                                class="text-gray-500 font-medium">Payment Status</span><span
                                class="font-bold text-right" id="success-payment-status">-</span></div>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-300 pt-6 mt-6">
                    <h3 class="text-lg font-black text-dark mb-4 text-center">Services Selected</h3>
                    <div id="success-services-list" class="space-y-4 text-dark">
                        <!-- Populated via JS -->
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-300 pt-6 mt-6">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-medium text-gray-500">Total Duration</span>
                        <span class="font-bold text-dark text-base" id="success-duration">0 mins</span>
                    </div>
                    <div class="flex justify-between items-center mb-3 hidden" id="success-discount-row">
                        <span class="text-sm font-medium text-gray-500">Discount Applied</span>
                        <span class="font-bold text-success text-base" id="success-discount-val">-$0.00</span>
                    </div>
                    <div class="flex justify-between items-end mt-4">
                        <span class="text-sm font-bold text-dark uppercase tracking-wider mb-1" id="success-total-label">Total Paid</span>
                        <span class="text-4xl font-black text-primary tracking-tighter" id="success-total">$0.00</span>
                    </div>
                </div>
            </div>

            <button onclick="window.location.href='book.php'"
                class="font-bold text-primary border-b-2 border-primary pb-1 hover:text-dark hover:border-dark transition-colors text-lg">Book
                Another Appointment</button>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>

</html>