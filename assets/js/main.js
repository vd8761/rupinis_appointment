$(document).ready(function () {

    // --- State Variables ---
    let bookingData = {
        outlet: $('.outlet-btn').first().data('outlet') || '-',
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

    function parseTime(timeStr) {
        if (!timeStr) return 0;
        let [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');
        hours = parseInt(hours, 10);
        minutes = parseInt(minutes, 10);
        if (hours === 12 && modifier.toUpperCase() === 'AM') {
            hours = 0;
        } else if (hours < 12 && modifier.toUpperCase() === 'PM') {
            hours += 12;
        }
        return hours * 60 + minutes;
    }

    function formatTime(totalMinutes) {
        let hours = Math.floor(totalMinutes / 60);
        let minutes = totalMinutes % 60;
        let modifier = hours >= 12 ? 'PM' : 'AM';
        
        hours = hours % 12;
        if (hours === 0) hours = 12;
        
        let hoursStr = hours < 10 ? '0' + hours : hours;
        let minutesStr = minutes < 10 ? '0' + minutes : minutes;
        
        return `${hoursStr}:${minutesStr} ${modifier}`;
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
        let finalPrice = totalPrice;
        if (window.appliedCouponDiscountVal) {
             // Basic flat calculation for UI, true calculation is handled by API
             finalPrice = totalPrice - window.appliedCouponDiscountVal;
             if (finalPrice < 0) finalPrice = 0;
             $('#summary-discount-row').removeClass('hidden flex').addClass('flex');
             $('#summary-discount-val').text('-' + formatCurrency(window.appliedCouponDiscountVal));
        } else {
             $('#summary-discount-row').addClass('hidden').removeClass('flex');
        }
        $('#summary-total').text(formatCurrency(finalPrice));
        $('#summary-duration').text(totalDuration > 0 ? totalDuration + ' mins' : '-');
        $('#total-duration-lbl').text(totalDuration > 0 ? totalDuration + ' mins' : '0 mins');

        // Disable HitPay if amount < 1 SGD
        if (finalPrice < 1) {
            $('input[name="payment"][value="HitPay"]').prop('disabled', true);
            $('input[name="payment"][value="HitPay"]').closest('.payment-label').addClass('opacity-50 cursor-not-allowed pointer-events-none').removeClass('cursor-pointer hover:bg-gray-50 bg-white');
            
            // Switch to Pay at Store if HitPay was selected
            if ($('input[name="payment"]:checked').val() === 'HitPay') {
                $('input[name="payment"][value="Pay at Store"]').closest('.payment-label').trigger('click');
            }
        } else {
            $('input[name="payment"][value="HitPay"]').prop('disabled', false);
            // Restore default classes carefully so we don't overwrite selected state if it is currently selected
            $('input[name="payment"][value="HitPay"]').closest('.payment-label').removeClass('opacity-50 cursor-not-allowed pointer-events-none');
            if (!$('input[name="payment"][value="HitPay"]').prop('checked')) {
                $('input[name="payment"][value="HitPay"]').closest('.payment-label').addClass('cursor-pointer hover:bg-gray-50 bg-white');
            } else {
                $('input[name="payment"][value="HitPay"]').closest('.payment-label').addClass('cursor-pointer');
            }
        }
        
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

    function clearTimeSelection() {
        $('.time-slot').removeClass('border-primary bg-primary text-white !text-white').addClass('border-light bg-white text-dark');
        bookingData.time = '';
    }

    function updateTimeSelectionState() {
        if (!bookingData.time) return;
        
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
            $('#err-time').html('<svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Not enough consecutive time available for updated services. Please select a new time.').removeClass('hidden');
        } else {
            let cachedTime = bookingData.time;
            clearTimeSelection();
            bookingData.time = cachedTime;
            
            $('.time-slot').each(function() {
                let slotMins = parseTime($(this).data('time'));
                let slotEndMins = slotMins + 30;
                
                if (slotMins < endMins && slotEndMins > startMins) {
                     $(this).removeClass('border-light bg-white text-dark').addClass('border-primary bg-primary text-white !text-white');
                }
            });
        }
    }

    // --- UI POPULATION ---

    // 1. Therapists
    function fetchAndRenderTherapists(branchId) {
        const container = $('#therapist-grid');
        
        if (!bookingData.date) {
            container.html(`
                <div class="col-span-full py-4 text-center">
                    <p class="text-sm font-bold text-gray-400">Please select a Date to view available therapists.</p>
                </div>
            `);
            return;
        }

        container.html(`
            <button type="button" class="therapist-btn border-2 border-primary bg-primary/5 rounded-xl py-3 px-2 flex flex-col items-center justify-center transition-all" data-therapist="Any Available">
                <div class="w-12 h-12 rounded-full bg-white text-primary flex items-center justify-center mb-2 shadow-sm border border-primary/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <span class="text-xs font-bold text-primary">Any</span>
            </button>
        `);

        // We can optionally pass date here if the backend needs it later
        fetch('api/get_beauticians.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ branch_id: branchId, date: bookingData.date })
        })
        .then(response => response.json())
        .then(res => {
            if (res.status && res.data) {
                res.data.forEach(t => {
                    const avatar = t.beautician_avatar ? t.beautician_avatar : 'assets/staff/common_female_avatar.svg';
                    container.append(`
                        <button type="button" class="therapist-btn border-2 border-light bg-white rounded-xl py-3 px-2 flex flex-col items-center justify-center transition-all hover:border-primary/50" data-therapist="${t.beautician_name}" data-id="${t.beautician_id}">
                            <img src="${avatar}" alt="${t.beautician_name}" class="w-12 h-12 rounded-full object-cover mb-2 border border-light">
                            <span class="text-xs font-bold text-gray-500">${t.beautician_name}</span>
                        </button>
                    `);
                });
                renderTimes();
            } else {
                renderTimes();
            }
        })
        .catch(err => {
            console.error("Error fetching therapists:", err);
            renderTimes();
        });
    }
    
    // Default message setup
    let initialBranchId = $('.outlet-btn.border-primary').data('branchid');
    fetchAndRenderTherapists(initialBranchId);
    
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
        
        let branchId = $('.outlet-btn.border-primary').data('branchid');
        fetchAndRenderTherapists(branchId);
    });

    // 3. Times (With Blocked Slots)
    // 3. Times (With Blocked Slots)
    async function renderTimes() {
        const container = $('#time-grid');
        container.html('<div class="col-span-full py-4 text-center text-gray-400 font-bold">Loading slots...</div>');
        
        let branchId = $('.outlet-btn.border-primary').data('branchid');
        let selectedTherapistId = $('.therapist-btn.border-primary').data('id') || '';
        
        if (!bookingData.date) {
            container.html('<div class="col-span-full py-4 text-center text-gray-400 font-bold">Please select a Date to view available time slots.</div>');
            return;
        }

        try {
            const response = await fetch('api/get_slots.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    branch_id: branchId,
                    date: bookingData.date,
                    beautician_id: selectedTherapistId,
                    duration: totalDuration > 0 ? totalDuration : 30
                })
            });
            const result = await response.json();
            container.empty();

            if (result.status && result.data && result.data.length > 0) {
                if (result.data[0].label === "Slot not available") {
                     container.append(`<div class="col-span-3 sm:col-span-4 text-center text-red-500 py-6 font-bold">No slots available or shop is closed on this date.</div>`);
                     return;
                }

                result.data.forEach((slot) => {
                    let startTimeOnly = slot.label.split(' - ')[0];
                    let displayTime = slot.label; // the full range from get_slots.php
                    if (slot.isBooked || slot.isFrozen) {
                        container.append(`
                            <button type="button" disabled data-time="${startTimeOnly}" class="time-slot border-2 border-transparent bg-gray-100 rounded-xl py-3 text-xs sm:text-sm font-bold text-gray-400 cursor-not-allowed opacity-60 flex items-center justify-center line-through decoration-gray-400 px-1">
                                ${displayTime}
                            </button>
                        `);
                    } else {
                        let availBeauticiansJson = JSON.stringify(slot.available_beauticians || []);
                        container.append(`
                            <button type="button" class="time-btn time-slot border-2 border-light bg-white rounded-xl py-3 text-xs sm:text-sm font-bold text-dark transition-all hover:border-primary hover:text-primary focus:outline-none relative group px-1" data-time="${startTimeOnly}" data-label="${slot.label}" data-backend-time="${slot.start_time}" data-avail-beauticians='${availBeauticiansJson}'>
                                <span class="time-label relative z-10">${displayTime}</span>
                            </button>
                        `);
                    }
                });
            } else {
                container.append(`<div class="col-span-3 sm:col-span-4 text-center text-gray-500 py-6 font-bold">No slots available for the selected criteria.</div>`);
            }
        } catch (error) {
            console.error("Error fetching slots:", error);
            container.append(`<div class="col-span-3 sm:col-span-4 text-center text-red-500 py-6 font-bold">Error loading slots. Please try again.</div>`);
        }
    }

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

    // Category Accordion Toggle
    $(document).on('click', '.category-toggle', function() {
        const content = $(this).next('.category-content');
        const chevron = $(this).find('.chevron');
        
        if (content.is(':hidden')) {
            content.slideDown();
            chevron.addClass('rotate-180');
        } else {
            content.slideUp();
            chevron.removeClass('rotate-180');
        }
    });

    // Category Selection
    $('.cat-pill').on('click', function() {
        $('.cat-pill').removeClass('bg-primary text-white border-transparent').addClass('bg-white text-gray-600 border-light');
        $(this).removeClass('bg-white text-gray-600 border-light').addClass('bg-primary text-white border-transparent');
        
        let target = $(this).data('target');
        
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
        
        // Restore Show More button visibility logic after category change
        $('.show-more-container').each(function() {
            let remaining = $(this).closest('.category-content').find('.extra-service.hidden').length;
            if(remaining > 0) {
                $(this).show();
                $(this).find('.remaining-count').text(remaining);
            } else {
                $(this).hide();
            }
        });
    });

    // Outlet Selection
    $('.outlet-btn').on('click', function() {
        $('.outlet-btn').removeClass('border-primary bg-primary/5 text-primary').addClass('border-light bg-white text-gray-500 hover:border-primary/50 hover:bg-gray-50');
        $(this).removeClass('border-light bg-white text-gray-500 hover:border-primary/50 hover:bg-gray-50').addClass('border-primary bg-primary/5 text-primary');
        bookingData.outlet = $(this).data('outlet');
        
        let branchId = $(this).data('branchid');
        fetchAndRenderTherapists(branchId);
        
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
        
        let branchId = $('.outlet-btn.border-primary').data('branchid');
        fetchAndRenderTherapists(branchId);
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
            
            // If this slot falls inside our selected booking duration, it must not be disabled!
            if (slotMins >= startMins && slotMins < endMins) {
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
            
            if (slotMins >= startMins && slotMins < endMins) {
                 $(this).removeClass('border-light bg-white text-dark hover:text-primary bg-primary/10 text-primary').addClass('border-primary bg-primary text-white !text-white');
            }
        });

        bookingData.time = $(this).data('time');
        
        var availBeauticians = $(this).data('avail-beauticians');
        if (availBeauticians && availBeauticians.length > 0) {
            bookingData.any_assigned_beautician_id = availBeauticians[0];
        } else {
            bookingData.any_assigned_beautician_id = 0;
        }
        
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

    // Show More Services Logic
    $(document).on('click', '.btn-show-more', function() {
        const container = $(this).closest('.category-content');
        let val = $('#service-search').val().toLowerCase().trim();
        
        let hiddenExtras;
        if (val.length > 0) {
            // In search mode, find all hidden items that match the search
            hiddenExtras = container.find('.service-item.hidden').filter(function() {
                let name = $(this).find('.service-name').text().toLowerCase();
                return name.indexOf(val) > -1;
            });
        } else {
            // Normal mode
            hiddenExtras = container.find('.extra-service.hidden');
        }
        
        // Reveal next 5
        hiddenExtras.slice(0, 5).removeClass('hidden').show().addClass('revealed');
        
        const remaining = hiddenExtras.length - 5;
        if (remaining > 0) {
            $(this).find('.remaining-count').text(remaining);
            $(this).closest('.show-more-container').show();
        } else {
            $(this).closest('.show-more-container').hide();
        }
    });

    // Auto-select text on click/focus
    $('#service-search').on('focus click', function() {
        $(this).select();
    });

    // Service Search Filter
    $('#service-search').on('keyup', function() {
        let val = $(this).val().toLowerCase().trim();
        
        if(val.length > 0) {
            $('.cat-pill').removeClass('bg-primary text-white border-transparent').addClass('bg-white text-gray-600 border-light');
            $('.cat-pill[data-target="all"]').removeClass('bg-white text-gray-600 border-light').addClass('bg-primary text-white border-transparent');
            
            $('.category-content').removeClass('hidden').show();
            $('.chevron').addClass('rotate-180');
            
            // Loop through categories to apply search with pagination
            $('.service-category').each(function() {
                let matchedItems = $(this).find('.service-item').filter(function() {
                    let name = $(this).find('.service-name').text().toLowerCase();
                    return name.indexOf(val) > -1;
                });
                
                // Hide all items initially in this category
                $(this).find('.service-item').addClass('hidden').hide().removeClass('revealed');
                
                // Show first 5 matched items
                matchedItems.slice(0, 5).removeClass('hidden').show().addClass('revealed');
                
                let remaining = matchedItems.length - 5;
                let showMoreBtn = $(this).find('.show-more-container');
                
                if (remaining > 0) {
                    showMoreBtn.show();
                    showMoreBtn.find('.remaining-count').text(remaining);
                } else {
                    showMoreBtn.hide();
                }
            });
            
        } else {
            // Restore service items to their default hidden/shown state
            $('.service-category').each(function() {
                $(this).removeClass('hidden').show(); // Make sure category is visible
                $(this).find('.service-item').each(function() {
                    $(this).removeClass('revealed');
                    if ($(this).hasClass('extra-service')) {
                        $(this).addClass('hidden').hide();
                    } else {
                        $(this).removeClass('hidden').show();
                    }
                });
            });
            
            // Ensure category pills are visible
            $('.cat-pill').show();
            
            // Trigger click on the first pill to reset the UI correctly
            $('.cat-pill:first').trigger('click');
        }
        
        // Hide empty categories and filter category pills
        $('.service-category').each(function() {
            let visibleCount = $(this).find('.service-item:not(.hidden)').length;
            let targetCatId = $(this).attr('id');
            if(visibleCount === 0 && val.length > 0) {
                $(this).addClass('hidden').hide();
                $('.cat-pill[data-target="'+targetCatId+'"]').hide();
            } else if (val.length > 0) {
                $(this).removeClass('hidden').show();
                $('.cat-pill[data-target="'+targetCatId+'"]').show();
            }
        });

        // Show empty state if no categories are visible and we are searching
        if ($('.service-category:not(.hidden)').length === 0 && val.length > 0) {
            $('#no-services-found').removeClass('hidden');
        } else {
            $('#no-services-found').addClass('hidden');
        }
    });
    
        // Therapist Selection
        $(document).on('click', '.therapist-btn', function() {
        $('.therapist-btn').removeClass('border-primary bg-primary/5').addClass('border-light bg-white');
        $('.therapist-btn span').removeClass('text-primary').addClass('text-gray-500');
        
        $(this).removeClass('border-light bg-white').addClass('border-primary bg-primary/5');
        $(this).find('span').removeClass('text-gray-500').addClass('text-primary');
        bookingData.therapist = $(this).data('therapist');
        bookingData.therapist_id = $(this).data('id') || 0;
        updateSummary();
        renderTimes();
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

    // --- COUPON LOGIC ---
    window.appliedCouponCode = '';
    window.appliedCouponDiscountId = 0;
    window.appliedCouponDiscountVal = 0;

    $('#applyCouponBtn').on('click', function() {
        const code = $('#couponCodeInput').val().trim().toUpperCase();
        if (!code) return;

        if (totalPrice <= 0) {
            $('#couponMessage').html('<span class="text-error font-bold">Please select services first.</span>');
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).text('...');
        $('#couponMessage').html('');

        $.ajax({
            url: 'api/apply_coupon.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                coupon_code: code,
                getprice: totalPrice,
                mobile: $('#f-mobile').val().trim()
            }),
            success: function(res) {
                btn.prop('disabled', false).text('Apply');
                if (res.status && res.data) {
                    window.appliedCouponCode = code;
                    window.appliedCouponDiscountId = res.data.discount_id;
                    window.appliedCouponDiscountVal = parseFloat(res.data.discount_value || 0);

                    $('#promoInputGroup').addClass('hidden');
                    $('#appliedCouponLabel').html(`${code}`);
                    $('#appliedCouponState').removeClass('hidden').addClass('flex');
                    $('#couponMessage').html('<span class="text-success font-bold">Coupon applied successfully!</span>');
                    
                    updateSummary();
                } else {
                    $('#couponMessage').html(`<span class="text-error font-bold">${res.message || 'Invalid code'}</span>`);
                    $('#couponCodeInput').val('');
                }
            },
            error: function() {
                btn.prop('disabled', false).text('Apply');
                $('#couponMessage').html('<span class="text-error font-bold">Error validating code.</span>');
                $('#couponCodeInput').val('');
            }
        });
    });

    $('#removeCouponBtn').on('click', function() {
        window.appliedCouponCode = '';
        window.appliedCouponDiscountId = 0;
        window.appliedCouponDiscountVal = 0;

        $('#couponCodeInput').val('');
        $('#promoInputGroup').removeClass('hidden');
        $('#appliedCouponState').addClass('hidden').removeClass('flex');
        $('#couponMessage').html('');
        updateSummary();
    });

    // --- FORM SUBMISSION & INLINE VALIDATION ---

    function scrollToElement(selector) {
        document.querySelector(selector).scrollIntoView({ behavior: 'smooth' });
    }

    $('#btn-submit').on('click', function() {
        
        // Clear previous generic errors
        $('.err-msg').addClass('hidden');

        // Check if coupon is typed but not applied
        if ($('#couponCodeInput').val().trim() !== '' && window.appliedCouponCode === '') {
            $('#couponMessage').html('<span class="text-error font-bold">Promo code is entered but not applied. Please click "Apply" or clear the field.</span>');
            scrollToElement('#couponCodeInput');
            return;
        }

        // 1. Outlet Check
        if(!bookingData.outlet) {
            $('#err-outlet').removeClass('hidden');
            scrollToElement('#sec-outlet');
            return;
        }

        // 2. Date Check
        if (bookingData.services.length === 0) {
            $('#err-services').removeClass('hidden');
            scrollToElement('#sec-services');
            return;
        }

        if (!bookingData.date) {
            $('#err-date').removeClass('hidden');
            scrollToElement('#sec-date');
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

        bookingData.customer = {
            name: name,
            mobile: mobile,
            country_code: $('#f-cc').val(),
            email: $('#f-email').val().trim()
        };

        // Add Coupon Details if applied
        bookingData.coupon_code = window.appliedCouponCode || '';
        bookingData.discount_id = window.appliedCouponDiscountId || 0;
        bookingData.discount_amount = window.appliedCouponDiscountVal || 0;

        // Force capture the correct payment method in case it was programmatically switched
        bookingData.payment = $('input[name="payment"]:checked').val();

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
                    // Check if HitPay redirect URL is present
                    if (response.payment_url) {
                        window.location.href = response.payment_url;
                        return; // Stop execution to prevent showing success screen yet
                    }

                    // SUCCESSFUL SUBMISSION
                    // Hide entire form section
                    $('#main-booking-section').addClass('hidden');
        
        // Populate Success Screen Details
        $('#success-booking-id').text(response.ref_no || '#RPS-' + response.booking_id);
        $('#success-outlet').text(bookingData.outlet);
        $('#success-date').text(formatDateString(bookingData.date));
        
        let startMins = parseTime(bookingData.time);
        let endMins = startMins + totalDuration;
        $('#success-time').text(`${bookingData.time} - ${formatTime(endMins)}`);
        
        $('#success-therapist').text(response.beautician_name || bookingData.therapist);
        
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
        
        // Calculate total considering discount
        let finalSuccessPrice = totalPrice - (window.appliedCouponDiscountVal || 0);
        if (finalSuccessPrice < 0) finalSuccessPrice = 0;
        $('#success-total').text(formatCurrency(finalSuccessPrice));

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
        });
    });

    // Initialize UI
    updateSummary();

    // --- COUNTRY CODE LOGIC ---
    const countryCodes = [
        { code: "+65", name: "Singapore", format: 8, iso: "sg" },
        { code: "+60", name: "Malaysia", format: 9, iso: "my" },
        { code: "+1", name: "USA / Canada", format: 10, iso: "us" },
        { code: "+44", name: "UK", format: 10, iso: "gb" },
        { code: "+61", name: "Australia", format: 9, iso: "au" },
        { code: "+91", name: "India", format: 10, iso: "in" },
        { code: "+62", name: "Indonesia", format: 11, iso: "id" },
        { code: "+63", name: "Philippines", format: 10, iso: "ph" },
        { code: "+86", name: "China", format: 11, iso: "cn" },
        { code: "+81", name: "Japan", format: 10, iso: "jp" },
        { code: "+82", name: "South Korea", format: 10, iso: "kr" },
        { code: "+971", name: "UAE", format: 9, iso: "ae" },
        { code: "+49", name: "Germany", format: 11, iso: "de" },
        { code: "+33", name: "France", format: 9, iso: "fr" },
        { code: "+94", name: "Sri Lanka", format: 9, iso: "lk" },
        { code: "+880", name: "Bangladesh", format: 10, iso: "bd" },
        { code: "+977", name: "Nepal", format: 10, iso: "np" },
        { code: "+92", name: "Pakistan", format: 10, iso: "pk" },
        { code: "+95", name: "Myanmar", format: 9, iso: "mm" },
        { code: "+66", name: "Thailand", format: 9, iso: "th" },
        { code: "+84", name: "Vietnam", format: 9, iso: "vn" },
        { code: "+855", name: "Cambodia", format: 9, iso: "kh" },
        { code: "+856", name: "Laos", format: 8, iso: "la" },
        { code: "+673", name: "Brunei", format: 7, iso: "bn" },
        { code: "+852", name: "Hong Kong", format: 8, iso: "hk" },
        { code: "+886", name: "Taiwan", format: 9, iso: "tw" },
        { code: "+853", name: "Macau", format: 8, iso: "mo" },
        { code: "+39", name: "Italy", format: 10, iso: "it" },
        { code: "+34", name: "Spain", format: 9, iso: "es" },
        { code: "+7", name: "Russia", format: 10, iso: "ru" },
        { code: "+55", name: "Brazil", format: 11, iso: "br" },
        { code: "+52", name: "Mexico", format: 10, iso: "mx" },
        { code: "+27", name: "South Africa", format: 9, iso: "za" },
        { code: "+234", name: "Nigeria", format: 10, iso: "ng" }
    ];

    const ccSelect = $('#f-cc');
    ccSelect.empty();
    
    // Group Top Countries (SG, MY)
    let topOptions = '';
    let otherOptions = '';
    
    countryCodes.forEach(c => {
        const opt = `<option value="${c.code}" data-format="${c.format}" data-iso="${c.iso}">${c.code} (${c.name})</option>`;
        if (c.code === '+65' || c.code === '+60') {
            topOptions += opt;
        } else {
            otherOptions += opt;
        }
    });
    
    ccSelect.html(topOptions + '<option disabled>──────────</option>' + otherOptions);
    ccSelect.val('+65');

    // Remove the absolute positioned flag since Select2 will handle it
    $('#f-cc').siblings('div.absolute.left-0').remove();
    // Also remove left padding from select and input to fix alignment
    $('#f-cc').removeClass('pl-12');

    // Initialize Select2 with flag template
    function formatCountry(state) {
        if (!state.id) { return state.text; } // for the disabled dashed line
        const iso = $(state.element).data('iso');
        if(!iso) return state.text;
        return $(`<span class="flex items-center"><img src="https://flagcdn.com/w20/${iso}.png" class="w-5 h-auto rounded-sm shadow-sm mr-2" /> <span class="font-bold text-dark">${state.text}</span></span>`);
    }

    function formatCountrySelection(state) {
        if (!state.id) { return state.text; }
        const iso = $(state.element).data('iso');
        if(!iso) return state.text;
        return $(`<span class="flex items-center"><img src="https://flagcdn.com/w20/${iso}.png" class="w-5 h-auto rounded-sm shadow-sm mr-2" /> <span class="font-bold text-dark">${state.id}</span></span>`);
    }

    ccSelect.select2({
        width: '110px',
        templateResult: formatCountry,
        templateSelection: formatCountrySelection,
        dropdownAutoWidth: true
    });

    // Custom background styling for the wrapper
    ccSelect.next('.select2-container').addClass('rounded-l-xl border border-r-0 border-light bg-gray-50 py-2 pl-3');

    // Handle Mobile Length restriction and flag update UX
    ccSelect.on('change', function() {
        const formatLen = $(this).find(':selected').data('format') || 15;
        $('#f-mobile').attr('maxlength', formatLen);
        
        // Update placeholder based on length
        let placeholder = '1234 5678';
        if (formatLen == 8) placeholder = '9123 4567';
        else if (formatLen == 9) placeholder = '123 456 789';
        else if (formatLen == 10) placeholder = '98765 43210';
        else if (formatLen == 11) placeholder = '1234 567 8901';
        else if (formatLen == 7) placeholder = '123 4567';
        
        $('#f-mobile').attr('placeholder', placeholder);
        $('#f-mobile').val(''); // Clear on change
        updateMobileUX();
    });

    $('#f-mobile').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, ''); // Numbers only
        updateMobileUX();
    });

    function updateMobileUX() {
        const val = $('#f-mobile').val();
        const max = $('#f-mobile').attr('maxlength') || 8;
        
        // Always show the counter
        $('#mobile-counter').removeClass('hidden').html(`${val.length}/${max}`);
        
        if (val.length > 0) {
            $('#f-mobile').parent().removeClass('border-error');
            
            // Check if max length reached
            if (val.length == max) {
                const countryCode = $('#f-cc').val();
                fetch('api/get_customer_by_phone.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ country_code: countryCode, mobile: val })
                })
                .then(res => res.json())
                .then(data => {
                    $('#customer-details').removeClass('hidden'); // Reveal details section
                    
                    if (data.status && data.data) {
                        // Customer Found
                        $('#f-name').val(data.data.contact_fname).prop('readonly', true).addClass('bg-gray-100 cursor-not-allowed text-gray-500').removeClass('bg-white');
                        
                        if (data.data.contact_email) {
                            $('#f-email').val(data.data.contact_email).prop('readonly', true).addClass('bg-gray-100 cursor-not-allowed text-gray-500').removeClass('bg-white');
                        } else {
                            $('#f-email').val('').prop('readonly', false).addClass('bg-white').removeClass('bg-gray-100 cursor-not-allowed text-gray-500');
                        }
                        
                        // User requested not to show "Customer found: name (email)"
                        $('#err-mobile').addClass('hidden').html('');
                    } else {
                        // Customer Not Found (New)
                        $('#f-name').val('').prop('readonly', false).addClass('bg-white').removeClass('bg-gray-100 cursor-not-allowed text-gray-500');
                        $('#f-email').val('').prop('readonly', false).addClass('bg-white').removeClass('bg-gray-100 cursor-not-allowed text-gray-500');
                        $('#err-mobile').removeClass('hidden text-error text-gray-500').addClass('text-primary font-bold').html('New customer? Please provide your details below.');
                    }
                })
                .catch(err => console.error(err));
            } else {
                $('#customer-details').addClass('hidden'); // Hide details if mobile is incomplete
                $('#err-mobile').addClass('hidden');
            }

        } else {
            $('#err-mobile').addClass('hidden');
        }
    }

    // --- HitPay Redirect Success Check ---
    const urlParams = new URLSearchParams(window.location.search);
    const bookingRef = urlParams.get('booking');
    const hitpayStatus = urlParams.get('status') || '';
    const hitpayRef = urlParams.get('reference') || '';
    if (bookingRef) {
        // Hide form and show success section with loading state
        $('#main-booking-section').addClass('hidden');
        $('#step-success').removeClass('hidden').addClass('block');
        
        // Show loading card, hide details card
        $('#success-details-card').addClass('hidden');
        $('#success-loading-card').removeClass('hidden');
        
        $.get('api/get_booking_details.php?ref_no=' + encodeURIComponent(bookingRef) + '&status=' + encodeURIComponent(hitpayStatus) + '&hitpay_ref=' + encodeURIComponent(hitpayRef), function(response) {
            // Hide loading card, show details card
            $('#success-loading-card').addClass('hidden');
            $('#success-details-card').removeClass('hidden');
            
            if (response && response.success) {
                const data = response.data;
                
                $('#success-booking-id').text(data.ref_no);
                $('#success-outlet').text(data.outlet);
                $('#success-date').text(formatDateString(data.date));
                
                let startMins = parseTime(data.time);
                let endMins = startMins + parseInt(data.duration);
                $('#success-time').text(`${data.time} - ${formatTime(endMins)}`);
                
                $('#success-therapist').text(data.therapist);
                $('#success-payment-mode').text(data.payment);
                
                if (data.payment === 'HitPay') {
                    $('#success-payment-status').html('<span class="text-success font-bold">Paid</span>');
                    $('#success-total-label').text('Total Paid');
                } else {
                    $('#success-payment-status').html('<span class="text-orange-500 font-bold">Pending</span>');
                    $('#success-total-label').text('Payable Amount');
                }
                
                let successServicesHtml = '';
                if (data.services && data.services.length > 0) {
                    data.services.forEach(s => {
                        successServicesHtml += `
                        <div class="flex justify-between items-start mb-3 last:mb-0">
                            <div>
                                <span class="font-bold text-dark text-[15px] leading-tight">${s.name}</span>
                                <p class="text-xs text-gray-500 mt-1">${s.duration} mins</p>
                            </div>
                            <span class="font-bold text-primary">${formatCurrency(parseFloat(s.price))}</span>
                        </div>`;
                    });
                }
                $('#success-services-list').html(successServicesHtml);
                
                $('#success-duration').text(data.duration + ' mins');
                $('#success-total').text(formatCurrency(parseFloat(data.final_price)));
                
                // Scroll to top of the widget
                $('html, body').animate({
                    scrollTop: $('#step-success').offset().top - 100
                }, 500);
            } else {
                $('#success-services-list').html('<div class="text-center py-4 text-error font-bold">Failed to load booking details. Please check your email for confirmation.</div>');
            }
        }).fail(function() {
            $('#success-services-list').html('<div class="text-center py-4 text-error font-bold">Network error loading details. Please check your email.</div>');
        });
    }

});
