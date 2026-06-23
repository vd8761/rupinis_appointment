<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Rupini's Spa for any inquiries or to book an appointment. We're here to help you with your premium beauty needs.">
    <title>Contact Us - Rupini's Spa</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- Open Graph SEO -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Contact Us - Rupini's Spa">
    <meta property="og:description" content="Contact Rupini's Spa for any inquiries or to book an appointment. We're here to help you with your premium beauty needs.">
    <meta property="og:image" content="assets/images/logo-dark.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
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

    <section class="py-32 flex-grow relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="h-[1px] w-8 bg-gradient-to-r from-transparent to-primary/60"></div>
                    <span class="text-primary text-xs font-bold tracking-[0.2em] uppercase">Get In Touch</span>
                    <div class="h-[1px] w-8 bg-gradient-to-l from-transparent to-primary/60"></div>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-dark mb-4">We'd Love to Hear From You</h1>
                <p class="text-gray-600 text-lg">Whether you have a question about our treatments, pricing, or want to book a specialized bridal package, our team is ready to assist you.</p>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl border border-light overflow-hidden flex flex-col lg:flex-row">
                
                <!-- Left Info Column -->
                <div class="lg:w-2/5 bg-dark p-10 lg:p-14 text-white relative overflow-hidden">
                    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-primary rounded-full blur-3xl opacity-50"></div>
                    
                    <h3 class="text-2xl font-bold mb-8 relative z-10">Contact Information</h3>
                    
                    <div class="space-y-8 relative z-10">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-1">Phone</h4>
                                <p class="text-lg">+65 6291 6789</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-1">Email</h4>
                                <p class="text-lg">rupinisit@gmail.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-1">HQ Address</h4>
                                <p class="text-lg">24/26 2nd Floor Buffalo Road,<br>Singapore 219791</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Form Column -->
                <div class="lg:w-3/5 p-10 lg:p-14 bg-white" id="contact-form-container">
                    <form id="contact-form" action="#" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-dark mb-2">Full Name</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all bg-gray-50" placeholder="Jane Doe">
                                <p class="text-red-500 text-xs font-bold mt-1 hidden err-msg" id="err-name">Name is required.</p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-bold text-dark mb-2">Email Address</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all bg-gray-50" placeholder="jane@example.com">
                                <p class="text-red-500 text-xs font-bold mt-1 hidden err-msg" id="err-email">Valid email is required.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-bold text-dark mb-2">Phone Number</label>
                                <div class="w-full">
                                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all bg-gray-50" placeholder="9123 4567">
                                </div>
                                <p class="text-red-500 text-xs font-bold mt-1 hidden err-msg" id="err-phone">Phone number is required.</p>
                            </div>
                            
                            <!-- Add intl-tel-input CSS & JS for real flags -->
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
                            <style>
                                .iti { width: 100%; }
                                .iti__flag { background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/img/flags.png"); }
                                @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
                                  .iti__flag { background-image: url("https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/img/flags@2x.png"); }
                                }
                                /* Fix padding for our custom input */
                                .iti__flag-container { border-radius: 0.75rem 0 0 0.75rem; }
                            </style>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    var input = document.querySelector("#phone");
                                    window.intlTelInput(input, {
                                        initialCountry: "sg",
                                        preferredCountries: ["sg", "my", "id", "in"],
                                        separateDialCode: true,
                                        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                                    });
                                });
                            </script>
                            
                            <div>
                                <label for="service" class="block text-sm font-bold text-dark mb-2">Interested Service</label>
                                <select id="service" name="service" class="w-full px-4 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all bg-gray-50 appearance-none">
                                    <option value="" disabled selected>Select a service</option>
                                    <option value="facial">Facial Treatments</option>
                                    <option value="massage">Body Massage</option>
                                    <option value="threading">Threading</option>
                                    <option value="bridal">Bridal Package</option>
                                    <option value="other">Other Inquiry</option>
                                </select>
                                <p class="text-red-500 text-xs font-bold mt-1 hidden err-msg" id="err-service">Please select a service.</p>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-bold text-dark mb-2">Your Message</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-3 rounded-xl border border-light focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all bg-gray-50 resize-none" placeholder="How can we help you today?"></textarea>
                            <p class="text-red-500 text-xs font-bold mt-1 hidden err-msg" id="err-message">Message is required.</p>
                        </div>

                        <button type="submit" id="btn-contact-submit" class="w-full py-4 bg-primary text-white font-bold rounded-xl hover:bg-dark transition-colors shadow-lg shadow-primary/20 hover:shadow-primary/40 transform hover:-translate-y-0.5">
                            Send Message
                        </button>
                    </form>

                    <!-- Success Message -->
                    <div id="contact-success" class="hidden h-full flex-col items-center justify-center text-center py-10">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center text-green-500 mb-6 shadow-sm border border-green-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-dark mb-3">Message Sent!</h3>
                        <p class="text-gray-600 mb-8 max-w-sm mx-auto">Thank you for reaching out. Our team will get back to you shortly.</p>
                        <button onclick="location.reload()" class="font-bold text-primary border-b-2 border-primary pb-0.5 hover:text-dark hover:border-dark transition-colors">Send Another Message</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contact-form').on('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                
                // Clear errors
                $('.err-msg').addClass('hidden');
                $('.border-red-500').removeClass('!border-red-500 border-red-500');
                
                // Validate Name
                if (!$('#name').val().trim()) {
                    $('#err-name').removeClass('hidden');
                    $('#name').addClass('!border-red-500');
                    isValid = false;
                }
                
                // Validate Email
                let email = $('#email').val().trim();
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email || !emailRegex.test(email)) {
                    $('#err-email').removeClass('hidden');
                    $('#email').addClass('!border-red-500');
                    isValid = false;
                }
                
                // Validate Phone
                if (!$('#phone').val().trim()) {
                    $('#err-phone').removeClass('hidden');
                    $('#phone').addClass('!border-red-500');
                    $('#country_code').addClass('!border-red-500');
                    isValid = false;
                }
                
                // Validate Service
                if (!$('#service').val()) {
                    $('#err-service').removeClass('hidden');
                    $('#service').addClass('!border-red-500');
                    isValid = false;
                }
                
                // Validate Message
                if (!$('#message').val().trim()) {
                    $('#err-message').removeClass('hidden');
                    $('#message').addClass('!border-red-500');
                    isValid = false;
                }
                
                if (isValid) {
                    let btn = $('#btn-contact-submit');
                    let originalText = btn.text();
                    btn.prop('disabled', true).html('<svg class="w-5 h-5 animate-spin mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');
                    
                    // AJAX call to send email via SMTP
                    $.ajax({
                        url: 'api/contact_submit.php',
                        type: 'POST',
                        data: $('#contact-form').serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#contact-form').fadeOut(300, function() {
                                    $('#contact-success').removeClass('hidden').addClass('flex').hide().fadeIn(300);
                                });
                            } else {
                                alert(response.message || 'An error occurred while sending your message. Please try again.');
                                btn.prop('disabled', false).html(originalText);
                            }
                        },
                        error: function() {
                            alert('A network error occurred. Please try again.');
                            btn.prop('disabled', false).html(originalText);
                        }
                    });
                }
            });
            
            // Clear errors on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('!border-red-500');
                if ($(this).attr('id') === 'phone' || $(this).attr('id') === 'country_code') {
                    $('#phone, #country_code').removeClass('!border-red-500');
                    $('#err-phone').addClass('hidden');
                } else {
                    $(this).next('.err-msg').addClass('hidden');
                }
            });
        });
    </script>
</body>
</html>
