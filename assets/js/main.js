$(document).ready(function () {

    // --- State Variables ---
    let bookingData = {
        outlet: 'Little India',
        date: '',
        services: [],
        therapist: 'Any Available', // Default selection
        time: '',
        payment: 'Pay at Store' // Default selection
    };

    let totalDuration = 0;
    let totalPrice = 0;

    // --- Helper Functions ---
    function formatCurrency(amount) {
        return '$' + amount.toFixed(2);
    }

    function formatDateString(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
    }

    function updateSummary() {
        // Update basic info
        $('#summary-outlet').text(bookingData.outlet || '-');
        $('#summary-date').text(formatDateString(bookingData.date));
        
        if (bookingData.time && totalDuration > 0) {
            let startMins = parseTime(bookingData.time);
            let endMins = startMins + totalDuration;
            $('#summary-time').text(`${bookingData.time} - ${formatTime(endMins)}`);
        } else {
            $('#summary-time').text(bookingData.time || '-');
        }
        
        $('#summary-therapist').text(bookingData.therapist || '-');

        // Update totals
        $('#summary-total').text(formatCurrency(totalPrice));
        $('#summary-duration').text(totalDuration + ' mins');
        $('#total-duration-lbl').text(totalDuration + ' mins');
        
        // Update services list
        let servicesHtml = '';
        if(bookingData.services.length > 0) {
            $('#service-count').text(bookingData.services.length + ' Selected');
            $('#service-count').removeClass('bg-primary/10 text-primary').addClass('bg-primary text-white');
            
            bookingData.services.forEach(s => {
                servicesHtml += `
                <div class="mb-4 last:mb-0">
                    <div class="flex justify-between items-start">
                        <span class="font-bold text-dark text-sm sm:text-base pr-3 leading-tight">${s.name}</span>
                        <span class="font-bold text-primary">${formatCurrency(s.price)}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">${s.duration} mins</p>
                </div>`;
            });
            $('#summary-services-list').html(servicesHtml);
        } else {
            $('#service-count').text('0 Selected');
            $('#service-count').removeClass('bg-primary text-white').addClass('bg-primary/10 text-primary');
            $('#summary-services-list').html('<p class="italic text-gray-400 text-sm">No services selected.</p>');
        }
    }

    // --- UI POPULATION ---

    // 1. Therapists
    const therapists = ["Baanu", "Brinda", "Gayathri", "Kinder", "Riya", "Sarah"];
    function renderTherapists() {
        const container = $('#therapist-grid');
        container.empty();
        
        // "Any Available"
        container.append(`
            <button type="button" class="therapist-btn border-2 border-primary bg-primary/5 rounded-xl py-3 px-2 flex flex-col items-center justify-center transition-all" data-therapist="Any Available">
                <div class="w-12 h-12 rounded-full bg-white text-primary flex items-center justify-center mb-2 shadow-sm border border-primary/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <span class="text-xs font-bold text-primary">Any</span>
            </button>
        `);

        therapists.forEach(name => {
            let avatarUrl = `https://i.pravatar.cc/150?u=${encodeURIComponent(name)}`;
            container.append(`
                <button type="button" class="therapist-btn border-2 border-light bg-white rounded-xl py-3 px-2 flex flex-col items-center justify-center transition-all hover:border-primary/50" data-therapist="${name}">
                    <div class="w-12 h-12 rounded-full bg-gray-100 mb-2 overflow-hidden shadow-sm">
                        <img src="${avatarUrl}" class="w-full h-full object-cover" alt="${name}">
                    </div>
                    <span class="text-xs font-bold text-gray-500">${name}</span>
                </button>
            `);
        });
    }
    renderTherapists();
    updateSummary(); // Initialize summary with default outlet

    // 2. Dates Generation (Horizontal Scroll)
    function renderDates(startDate = null) {
        const container = $('#date-grid');
        container.empty();
        
        const days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
        
        let today = startDate ? new Date(startDate) : new Date();
        
        for (let i = 0; i < 7; i++) {
            let d = new Date(today);
            d.setDate(today.getDate() + i);
            
            let dayName = days[d.getDay()];
            let dateNum = d.getDate();
            let monthName = months[d.getMonth()];
            
            // Format to local YYYY-MM-DD instead of UTC ISO string
            let y = d.getFullYear();
            let m = String(d.getMonth() + 1).padStart(2, '0');
            let dd = String(d.getDate()).padStart(2, '0');
            let fullDateStr = `${y}-${m}-${dd}`;
            
            container.append(`
                <button type="button" class="date-btn w-full border border-light bg-white rounded-xl py-3 flex flex-col items-center justify-center transition-all hover:border-primary focus:outline-none" data-date="${fullDateStr}">
                    <span class="text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">${dayName}</span>
                    <span class="text-xl sm:text-2xl font-black text-dark leading-none">${dateNum}</span>
                    <span class="text-[10px] font-bold text-gray-500 mt-1 uppercase tracking-wider">${monthName}</span>
                </button>
            `);
        }

        // Add "More Dates" Button
        container.append(`
            <button type="button" id="btn-more-dates" class="w-full border border-dashed border-primary/40 bg-transparent rounded-xl py-3 flex flex-col items-center justify-center transition-all hover:border-primary hover:text-primary focus:outline-none">
                <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[10px] font-bold text-gray-500">More Dates</span>
            </button>
        `);
    }
    renderDates();

    // 2b. Custom Modern Calendar Modal Logic
    let calDate = new Date();
    let calMonth = calDate.getMonth();
    let calYear = calDate.getFullYear();
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const shortMonths = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
    const shortDays = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

    function renderCalendar(month, year) {
        const grid = $('#cal-grid');
        grid.empty();
        
        $('#cal-month').text(monthNames[month]);
        $('#cal-year').text(year);
        
        let firstDay = new Date(year, month, 1).getDay();
        let daysInMonth = new Date(year, month + 1, 0).getDate();
        
        let today = new Date();
        today.setHours(0,0,0,0);
        
        // Offset blanks
        for (let i = 0; i < firstDay; i++) {
            grid.append('<div></div>');
        }
        
        // Days
        for (let i = 1; i <= daysInMonth; i++) {
            let cellDate = new Date(year, month, i);
            let isPast = cellDate < today;
            
            // Format to local YYYY-MM-DD instead of UTC ISO string
            let y = cellDate.getFullYear();
            let m = String(cellDate.getMonth() + 1).padStart(2, '0');
            let dd = String(cellDate.getDate()).padStart(2, '0');
            let fullDateStr = `${y}-${m}-${dd}`;
            
            let isSelected = (bookingData.date === fullDateStr);
            
            if (isPast) {
                grid.append(`<div class="h-10 flex items-center justify-center text-gray-300 font-bold text-base">${i}</div>`);
            } else {
                let activeClasses = isSelected ? 'bg-primary text-white shadow-md' : 'text-dark hover:bg-primary/10';
                grid.append(`<button type="button" class="cal-day-btn h-10 w-full rounded-xl flex items-center justify-center font-bold text-base transition-colors focus:outline-none ${activeClasses}" data-date="${fullDateStr}">${i}</button>`);
            }
        }
    }

    // Modal Triggers
    function openCalendar() {
        $('#calendar-modal').removeClass('hidden').addClass('flex');
        // Small delay for transition
        setTimeout(() => {
            $('#calendar-backdrop').removeClass('opacity-0').addClass('opacity-100');
            $('#calendar-content').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
        renderCalendar(calMonth, calYear);
    }
    
    function closeCalendar() {
        $('#calendar-backdrop').removeClass('opacity-100').addClass('opacity-0');
        $('#calendar-content').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => {
            $('#calendar-modal').removeClass('flex').addClass('hidden');
        }, 200);
    }

    $(document).on('click', '#btn-more-dates', openCalendar);
    $('#cal-close, #calendar-backdrop').on('click', closeCalendar);
    
    $('#cal-prev').on('click', function() {
        calMonth--;
        if(calMonth < 0) { calMonth = 11; calYear--; }
        renderCalendar(calMonth, calYear);
    });
    
    $('#cal-next').on('click', function() {
        calMonth++;
        if(calMonth > 11) { calMonth = 0; calYear++; }
        renderCalendar(calMonth, calYear);
    });

    // Calendar Date Selection
    $(document).on('click', '.cal-day-btn', function() {
        let dateStr = $(this).data('date');
        bookingData.date = dateStr;
        $('#err-date').addClass('hidden');
        
        // Re-render sequence starting from the selected date
        renderDates(dateStr);
        
        // Select the first button which is now the selected date
        let firstBtn = $('.date-btn').first();
        firstBtn.removeClass('border border-light bg-white').addClass('border-2 border-primary bg-primary/10 shadow-md ring-2 ring-primary/30 ring-offset-1');
        firstBtn.find('span:nth-child(2)').removeClass('text-dark').addClass('text-primary');
        
        updateSummary();
        closeCalendar();
    });

    // 3. Times (With Blocked Slots)
    function renderTimes() {
        const container = $('#time-grid');
        container.empty();
        
        // Continuous 30-min intervals from 10am to 5:30pm
        const times = [];
        for (let h = 10; h <= 17; h++) {
            let ampm = h >= 12 ? 'PM' : 'AM';
            let hour = h > 12 ? h - 12 : h;
            times.push(`${hour}:00 ${ampm}`);
            times.push(`${hour}:30 ${ampm}`);
        }
        
        times.forEach((t, index) => {
            // Randomly block some slots for demonstration
            let isBlocked = (index % 6 === 5); 
            
            if (isBlocked) {
                container.append(`
                    <button type="button" disabled data-time="${t}" class="time-slot border-2 border-transparent bg-gray-100 rounded-xl py-3 text-sm font-bold text-gray-400 cursor-not-allowed opacity-60 flex items-center justify-center line-through decoration-gray-400">
                        ${t}
                    </button>
                `);
            } else {
                container.append(`
                    <button type="button" class="time-btn time-slot border-2 border-light bg-white rounded-xl py-3 text-sm font-bold text-dark transition-all hover:border-primary hover:text-primary focus:outline-none" data-time="${t}">
                        <span class="time-label relative z-10">${t}</span>
                    </button>
                `);
            }
        });
    }
    renderTimes();

    // Helper: Parse time string to minutes
    function parseTime(tStr) {
        if(!tStr) return 0;
        let parts = tStr.split(' ');
        let timeParts = parts[0].split(':');
        let hours = parseInt(timeParts[0], 10);
        let minutes = parseInt(timeParts[1], 10);
        let ampm = parts[1];
        
        if (hours === 12 && ampm === 'AM') hours = 0;
        if (hours < 12 && ampm === 'PM') hours += 12;
        
        return hours * 60 + minutes;
    }

    function formatTime(mins) {
        let h = Math.floor(mins / 60);
        let m = mins % 60;
        let ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12;
        if (h === 0) h = 12;
        m = m < 10 ? '0' + m : m;
        return `${h}:${m} ${ampm}`;
    }

    // Helper: Clear selected time
    function clearTimeSelection() {
        bookingData.time = '';
        $('.time-slot:not([disabled])').removeClass('border-primary bg-primary bg-primary/10 text-white !text-white text-primary').addClass('border-light bg-white text-dark');
        $('#err-time').addClass('hidden');
    }

    // Helper: Update visual selection dynamically
    function updateTimeSelectionState() {
        if (!bookingData.time) return;
        
        if (totalDuration === 0) {
            clearTimeSelection();
            return;
        }

        let startMins = parseTime(bookingData.time);
        let endMins = startMins + totalDuration;
        let isValid = true;

        $('.time-slot').each(function() {
            let slotMins = parseTime($(this).data('time'));
            let slotEndMins = slotMins + 30;
            if (slotMins < endMins && slotEndMins > startMins) {
                if ($(this).prop('disabled')) {
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            clearTimeSelection();
            $('#err-time').html('<svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Selected time is no longer available for the new duration.').removeClass('hidden');
            return;
        }

        // valid, update visuals without losing data
        $('.time-slot:not([disabled])').removeClass('border-primary bg-primary bg-primary/10 text-white !text-white text-primary').addClass('border-light bg-white text-dark');
        
        $('.time-slot').each(function() {
            let slotMins = parseTime($(this).data('time'));
            let slotEndMins = slotMins + 30;
            
            if (slotMins < endMins && slotEndMins > startMins) {
                 $(this).removeClass('border-light bg-white text-dark hover:text-primary bg-primary/10 text-primary').addClass('border-primary bg-primary text-white !text-white');
            }
        });
    }

    // --- EVENT LISTENERS ---

    // Accordion Toggle
    $('.category-toggle').on('click', function() {
        const content = $(this).next('.category-content');
        const chevron = $(this).find('.chevron');
        
        content.slideToggle(300);
        chevron.toggleClass('rotate-180');
    });

    // Category Pills Filter
    $('.cat-pill').on('click', function() {
        const target = $(this).data('target');
        
        // Update Pill UI
        $('.cat-pill').removeClass('bg-primary text-white border-transparent').addClass('bg-white text-gray-600 border-light');
        $(this).removeClass('bg-white text-gray-600 border-light').addClass('bg-primary text-white border-transparent');

        // Reset Search
        $('#service-search').val('');

        if(target === 'all') {
            $('.service-category').show();
            // Optional: reset accordion states
            $('.category-content').hide();
            $('.chevron').removeClass('rotate-180');
            $('.service-category:first .category-content').show();
            $('.service-category:first .chevron').addClass('rotate-180');
        } else {
            $('.service-category').hide();
            $('#' + target).show();
            // Auto open the target category
            $('#' + target).find('.category-content').slideDown();
            $('#' + target).find('.chevron').addClass('rotate-180');
        }
    });

    // Outlet Selection
    $('.outlet-btn').on('click', function() {
        $('.outlet-btn').removeClass('border-primary bg-primary/5 text-primary').addClass('border-light bg-white text-gray-500 hover:border-primary/50 hover:bg-gray-50');
        $(this).removeClass('border-light bg-white text-gray-500 hover:border-primary/50 hover:bg-gray-50').addClass('border-primary bg-primary/5 text-primary');
        bookingData.outlet = $(this).data('outlet');
        $('#err-outlet').addClass('hidden');
        updateSummary();
    });

    // Date Selection
    $(document).on('click', '.date-btn', function() {
        $('.date-btn').removeClass('border-2 border-primary bg-primary/10 shadow-md ring-2 ring-primary/30 ring-offset-1').addClass('border border-light bg-white');
        $('.date-btn').find('span:nth-child(2)').removeClass('text-primary').addClass('text-dark');
        
        $(this).removeClass('border border-light bg-white').addClass('border-2 border-primary bg-primary/10 shadow-md ring-2 ring-primary/30 ring-offset-1');
        $(this).find('span:nth-child(2)').removeClass('text-dark').addClass('text-primary');
        bookingData.date = $(this).data('date');
        $('#err-date').addClass('hidden');
        updateSummary();
    });

    // Time Selection (Dynamic Duration Highlight)
    $(document).on('click', '.time-btn', function() {
        if (bookingData.time === $(this).data('time')) {
             clearTimeSelection();
             updateSummary();
             return;
        }

        if (totalDuration === 0) {
            $('#err-time').html('<svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Please select your services first to calculate required time.').removeClass('hidden');
            return;
        }

        let startMins = parseTime($(this).data('time'));
        let endMins = startMins + totalDuration;
        let isValid = true;

        // 1. Check if range overlaps with any blocked slots
        $('.time-slot').each(function() {
            let slotMins = parseTime($(this).data('time'));
            let slotEndMins = slotMins + 30; // Assuming 30 min intervals for grid blocks
            
            // Overlap condition
            if (slotMins < endMins && slotEndMins > startMins) {
                if ($(this).prop('disabled')) {
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            $('#err-time').html('<svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Not enough consecutive time available for your selected services.').removeClass('hidden');
            return;
        }

        // 2. Select Range
        clearTimeSelection();
        
        $('.time-slot').each(function() {
            let slotMins = parseTime($(this).data('time'));
            let slotEndMins = slotMins + 30;
            
            if (slotMins < endMins && slotEndMins > startMins) {
                 $(this).removeClass('border-light bg-white text-dark hover:text-primary bg-primary/10 text-primary').addClass('border-primary bg-primary text-white !text-white');
            }
        });

        bookingData.time = $(this).data('time');
        updateSummary();
    });

    // Service Add/Remove
    $('.btn-add-service').on('click', function() {
        const btn = $(this);
        const id = btn.data('id');
        const name = btn.data('name');
        const duration = parseInt(btn.data('duration'));
        const price = parseFloat(btn.data('price'));

        if(btn.hasClass('added')) {
            // Remove action (Switch back to ADD visual state)
            btn.removeClass('added').addClass('bg-white text-primary border-primary hover:bg-primary hover:text-white');
            btn.text('+ Add');
            bookingData.services = bookingData.services.filter(s => s.id !== id);
            totalDuration -= duration;
            totalPrice -= price;
        } else {
            // Add action (Switch to REMOVE visual state)
            btn.addClass('added').removeClass('bg-white text-primary border-primary hover:bg-primary hover:text-white');
            btn.text('Remove');
            bookingData.services.push({id, name, duration, price});
            totalDuration += duration;
            totalPrice += price;
            $('#err-services').addClass('hidden');
        }
        
        // Update selected time visuals based on new duration
        if (bookingData.time) {
            updateTimeSelectionState();
        }
        updateSummary();
    });

    // Service Search Filter
    $('#service-search').on('keyup', function() {
        let val = $(this).val().toLowerCase();
        
        // Reset pills to "All" state visually when searching
        if(val.length > 0) {
            $('.cat-pill').removeClass('bg-primary text-white border-transparent').addClass('bg-white text-gray-600 border-light');
            $('.cat-pill[data-target="all"]').removeClass('bg-white text-gray-600 border-light').addClass('bg-primary text-white border-transparent');
            
            // Expand all accordions while searching
            $('.category-content').show();
            $('.chevron').addClass('rotate-180');
        } else {
            // Collapse all except first if search cleared
            $('.category-content').hide();
            $('.chevron').removeClass('rotate-180');
            $('.service-category:first .category-content').show();
            $('.service-category:first .chevron').addClass('rotate-180');
        }

        $('.service-item').each(function() {
            let name = $(this).find('.service-name').text().toLowerCase();
            if(name.indexOf(val) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Hide empty categories
        $('.service-category').each(function() {
            let visibleItems = $(this).find('.service-item:visible').length;
            if(visibleItems === 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    // Therapist Selection
    $(document).on('click', '.therapist-btn', function() {
        $('.therapist-btn').removeClass('border-primary bg-primary/5').addClass('border-light bg-white');
        $('.therapist-btn span').removeClass('text-primary').addClass('text-gray-500');
        
        $(this).removeClass('border-light bg-white').addClass('border-primary bg-primary/5');
        $(this).find('span').removeClass('text-gray-500').addClass('text-primary');
        bookingData.therapist = $(this).data('therapist');
        updateSummary();
    });

    // Payment Selection & Icon Styling
    $('.payment-label').on('click', function() {
        // Reset all
        $('.payment-label').removeClass('border-primary bg-primary/5').addClass('border-light bg-white');
        $('.payment-label').find('.icon-wrapper').removeClass('bg-white text-primary').addClass('bg-gray-100 text-gray-500');
        
        // Set Active
        $(this).removeClass('border-light bg-white').addClass('border-primary bg-primary/5');
        $(this).find('.icon-wrapper').removeClass('bg-gray-100 text-gray-500').addClass('bg-white text-primary');
        $(this).find('input[type="radio"]').prop('checked', true);
        bookingData.payment = $(this).find('input').val();
    });

    // --- FORM SUBMISSION & INLINE VALIDATION ---

    function scrollToElement(selector) {
        document.querySelector(selector).scrollIntoView({ behavior: 'smooth' });
    }

    $('#btn-submit').on('click', function() {
        
        // Clear previous generic errors
        $('.err-msg').addClass('hidden');

        // 1. Outlet Check
        if(!bookingData.outlet) {
            $('#err-outlet').removeClass('hidden');
            scrollToElement('#sec-outlet');
            return;
        }

        // 2. Date Check
        if(!bookingData.date) {
            $('#err-date').removeClass('hidden');
            scrollToElement('#sec-date');
            return;
        }

        // 3. Services Check
        if(bookingData.services.length === 0) {
            $('#err-services').removeClass('hidden');
            scrollToElement('#sec-services');
            return;
        }

        // 4. Therapist Check (Defaults to Any Available)

        // 5. Time Check
        if(!bookingData.time) {
            $('#err-time').removeClass('hidden');
            scrollToElement('#sec-time');
            return;
        }

        // 6. Details Check
        const name = $('#f-name').val().trim();
        const mobile = $('#f-mobile').val().trim();
        
        if(!name) {
            $('#f-name').addClass('border-error').focus();
            $('#err-name').removeClass('hidden');
            scrollToElement('#sec-details');
            return;
        } else {
            $('#f-name').removeClass('border-error');
        }
        
        if(!mobile) {
            $('#f-mobile').parent().addClass('border-error');
            $('#f-mobile').focus();
            $('#err-mobile').removeClass('hidden');
            scrollToElement('#sec-details');
            return;
        } else {
            $('#f-mobile').parent().removeClass('border-error');
        }

        // Process API Submit
        const btnSubmit = $('#btn-submit');
        const originalBtnHtml = btnSubmit.html();
        btnSubmit.html('<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...');
        btnSubmit.prop('disabled', true);

        $.ajax({
            url: 'api/process_booking.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(bookingData),
            success: function(response) {
                if (response && response.success) {
                    // SUCCESSFUL SUBMISSION
                    // Hide entire form section
                    $('#main-booking-section').addClass('hidden');
        
        // Populate Success Screen Details
        $('#success-outlet').text(bookingData.outlet);
        $('#success-date').text(formatDateString(bookingData.date));
        
        let startMins = parseTime(bookingData.time);
        let endMins = startMins + totalDuration;
        $('#success-time').text(`${bookingData.time} - ${formatTime(endMins)}`);
        
        $('#success-therapist').text(bookingData.therapist);
        
        let displayPaymentMode = bookingData.payment;
        if (bookingData.payment === 'HitPay') displayPaymentMode = 'HitPay (Online)';
        $('#success-payment-mode').text(displayPaymentMode);
        
        if (bookingData.payment === 'HitPay') {
            $('#success-payment-status').html('<span class="text-success font-bold">Paid</span>');
            $('#success-total-label').text('Total Paid');
        } else {
            $('#success-payment-status').html('<span class="text-orange-500 font-bold">Pending</span>');
            $('#success-total-label').text('Payable Amount');
        }
        
        let successServicesHtml = '';
        bookingData.services.forEach(s => {
            successServicesHtml += `
            <div class="flex justify-between items-start mb-3 last:mb-0">
                <div>
                    <span class="font-bold text-dark text-[15px] leading-tight">${s.name}</span>
                    <p class="text-xs text-gray-500 mt-1">${s.duration} mins</p>
                </div>
                <span class="font-bold text-primary">${formatCurrency(s.price)}</span>
            </div>`;
        });
        $('#success-services-list').html(successServicesHtml);
        
        $('#success-duration').text(totalDuration + ' mins');
        $('#success-total').text(formatCurrency(totalPrice));

        // Show success
        $('#step-success').removeClass('hidden').addClass('block');
        
        // Scroll to top of the widget
        $('html, body').animate({
            scrollTop: $('#step-success').offset().top - 100
        }, 500);

                } else {
                    alert('Booking failed: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                alert('A network error occurred: ' + error);
            },
            complete: function() {
                btnSubmit.html(originalBtnHtml);
                btnSubmit.prop('disabled', false);
            }
        }); // End AJAX

    });

    // Initialize UI
    updateSummary();

});
