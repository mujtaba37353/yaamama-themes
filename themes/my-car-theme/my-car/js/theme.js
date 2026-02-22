/**
 * My Car Theme JavaScript
 *
 * @package MyCarTheme
 */

(function($) {
    'use strict';

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('header-mobile');
        const mobileMenuIcon = document.getElementById('mobile-menu-icon');
        const mobileMenuLinks = document.querySelectorAll('.y-l-header-mobile-links a');

        if (!mobileMenuButton || !mobileMenu || !mobileMenuIcon) {
            return;
        }

        // Toggle dropdown on button click
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('open');
            const isOpen = mobileMenu.classList.contains('open');
            
            // Toggle icon between bars and times (close)
            if (isOpen) {
                mobileMenuIcon.classList.remove('fa-bars');
                mobileMenuIcon.classList.add('fa-times');
                mobileMenuButton.setAttribute('aria-expanded', 'true');
                mobileMenu.setAttribute('aria-hidden', 'false');
            } else {
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('open');
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            }
        });

        // Close dropdown when clicking on menu links
        mobileMenuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            });
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('open')) {
                mobileMenu.classList.remove('open');
                mobileMenuIcon.classList.remove('fa-times');
                mobileMenuIcon.classList.add('fa-bars');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
            }
        });
    }

    /**
     * Set Active Navigation Link
     */
    function setActiveNavLink() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.y-c-nav-link, .y-l-header-mobile-links a');

        navLinks.forEach(function(link) {
            const href = link.getAttribute('href');
            if (href === currentPath || (currentPath.includes(href) && href !== '/')) {
                link.classList.add('y-c-active-link');
            }
        });
    }

    /**
     * Initialize Search Expandable
     */
    function initSearchExpandable() {
        const searchInput = document.querySelector('.y-c-header-search-expandable__input');
        if (searchInput) {
            // Ensure input expands on focus
            searchInput.addEventListener('focus', function() {
                this.classList.add('active');
            });
        }
    }

    /**
     * Handle Horizontal Scrollable Containers
     */
    function setupScrollableContainers() {
        const scrollContainers = document.querySelectorAll('.y-l-shop-parts, .y-l-home-parts, .y-l-product-slider');

        scrollContainers.forEach(function(container) {
            container.addEventListener('scroll', function() {
                if (container.scrollLeft > 10) {
                    container.classList.add('scrolled');
                } else {
                    container.classList.remove('scrolled');
                }
            });
        });
    }

    /**
     * Initialize Hero Tabs
     */
    function initializeHeroTabs() {
        const tabContainer = document.querySelector('.y-c-hero-tabs');
        if (!tabContainer) {
            return;
        }

        const tabButtons = tabContainer.querySelectorAll('.y-c-hero-tab-btn');

        tabButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove 'active' from all sibling buttons
                tabButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                });

                // Add 'active' to the clicked button
                this.classList.add('active');
            });
        });
    }

    /**
     * Initialize Location Dropdowns
     */
    function initializeLocationDropdowns() {
        const arrows = document.querySelectorAll('.y-c-arrow');
        const dropdowns = document.querySelectorAll('.y-c-location-dropdown');

        arrows.forEach(function(arrow, index) {
            arrow.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = dropdowns[index];
                const isActive = dropdown.classList.contains('active');

                // Close all dropdowns first
                dropdowns.forEach(function(d) {
                    d.classList.remove('active');
                });
                arrows.forEach(function(a) {
                    a.style.transform = 'rotate(0deg)';
                });

                // Toggle the clicked dropdown
                if (!isActive) {
                    dropdown.classList.add('active');
                    arrow.style.transform = 'rotate(180deg)';
                }
            });
        });

        // Add click event to dropdown items
        dropdowns.forEach(function(dropdown) {
            const items = dropdown.querySelectorAll('li');
            const locationText = dropdown.parentElement.querySelector('.y-c-info-location');

            items.forEach(function(item) {
                item.addEventListener('click', function() {
                    if (locationText) {
                        locationText.textContent = item.textContent;
                    }
                    dropdown.classList.remove('active');
                    const parentArrow = dropdown.parentElement.querySelector('.y-c-arrow');
                    if (parentArrow) {
                        parentArrow.style.transform = 'rotate(0deg)';
                    }
                });
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.y-c-info-box-body')) {
                dropdowns.forEach(function(d) {
                    d.classList.remove('active');
                });
                arrows.forEach(function(a) {
                    a.style.transform = 'rotate(0deg)';
                });
            }
        });
    }

    /**
     * Custom Date Picker Class (Calendar Only)
     */
    class CustomDatePicker {
        constructor(container, options = {}) {
            this.container = container;
            this.options = {
                minDate: options.minDate || new Date(),
                defaultDate: options.defaultDate || null,
                onChange: options.onChange || function() {},
                storageKey: options.storageKey || null,
                ...options
            };
            
            this.selectedDate = this.options.defaultDate ? new Date(this.options.defaultDate) : null;
            this.currentMonth = this.selectedDate ? new Date(this.selectedDate) : new Date();
            this.isOpen = false;
            
            this.weekdays = ['سبت', 'جمعة', 'خميس', 'أربعاء', 'ثلاثاء', 'اثنين', 'أحد'];
            this.months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
            
            this.init();
        }
        
        init() {
            this.createPopup();
            this.bindEvents();
            this.loadFromStorage();
            this.updateDisplay();
        }
        
        createPopup() {
            const popup = document.createElement('div');
            popup.className = 'y-c-picker-popup y-c-date-popup';
            popup.innerHTML = `
                <div class="y-c-calendar-section">
                    <div class="y-c-calendar-header">
                        <div class="y-c-calendar-nav">
                            <button type="button" class="y-c-calendar-nav-btn y-c-nav-next">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button type="button" class="y-c-calendar-nav-btn y-c-nav-prev">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class="y-c-calendar-title"></div>
                    </div>
                    <div class="y-c-calendar-weekdays"></div>
                    <div class="y-c-calendar-days"></div>
                </div>
            `;
            
            this.container.appendChild(popup);
            this.popup = popup;
            
            this.trigger = this.container.querySelector('.y-c-picker-trigger');
            this.valueDisplay = this.container.querySelector('.y-c-picker-value');
            this.calendarTitle = popup.querySelector('.y-c-calendar-title');
            this.weekdaysContainer = popup.querySelector('.y-c-calendar-weekdays');
            this.daysContainer = popup.querySelector('.y-c-calendar-days');
            
            this.renderWeekdays();
            this.renderCalendar();
        }
        
        renderWeekdays() {
            this.weekdaysContainer.innerHTML = this.weekdays.map(day => 
                `<div class="y-c-calendar-weekday">${day}</div>`
            ).join('');
        }
        
        renderCalendar() {
            const year = this.currentMonth.getFullYear();
            const month = this.currentMonth.getMonth();
            
            this.calendarTitle.textContent = `${this.months[month]} ${year}`;
            
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const startDay = firstDay.getDay();
            const totalDays = lastDay.getDate();
            const prevMonthLastDay = new Date(year, month, 0).getDate();
            
            let html = '';
            
            // Previous month days (RTL adjusted)
            const daysFromPrevMonth = startDay;
            for (let i = daysFromPrevMonth - 1; i >= 0; i--) {
                const day = prevMonthLastDay - i;
                html += `<button type="button" class="y-c-calendar-day other-month disabled">${day}</button>`;
            }
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const minDate = new Date(this.options.minDate);
            minDate.setHours(0, 0, 0, 0);
            
            for (let day = 1; day <= totalDays; day++) {
                const date = new Date(year, month, day);
                date.setHours(0, 0, 0, 0);
                
                let classes = ['y-c-calendar-day'];
                
                if (date < minDate) {
                    classes.push('disabled');
                }
                
                if (date.getTime() === today.getTime()) {
                    classes.push('today');
                }
                
                if (this.selectedDate) {
                    const selectedDateOnly = new Date(this.selectedDate);
                    selectedDateOnly.setHours(0, 0, 0, 0);
                    if (date.getTime() === selectedDateOnly.getTime()) {
                        classes.push('selected');
                    }
                }
                
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                html += `<button type="button" class="${classes.join(' ')}" data-date="${dateStr}">${day}</button>`;
            }
            
            const remainingDays = 42 - (startDay + totalDays);
            for (let day = 1; day <= remainingDays; day++) {
                html += `<button type="button" class="y-c-calendar-day other-month disabled">${day}</button>`;
            }
            
            this.daysContainer.innerHTML = html;
        }
        
        updateDisplay() {
            if (this.selectedDate) {
                const day = this.selectedDate.getDate();
                const month = this.months[this.selectedDate.getMonth()];
                const year = this.selectedDate.getFullYear();
                
                this.valueDisplay.textContent = `${day} ${month} ${year}`;
                this.valueDisplay.classList.remove('placeholder');
            } else {
                this.valueDisplay.textContent = 'اختر التاريخ';
                this.valueDisplay.classList.add('placeholder');
            }
            
            const dateInput = this.container.querySelector('input[type="hidden"]');
            if (dateInput && this.selectedDate) {
                const dateStr = `${this.selectedDate.getFullYear()}-${String(this.selectedDate.getMonth() + 1).padStart(2, '0')}-${String(this.selectedDate.getDate()).padStart(2, '0')}`;
                dateInput.value = dateStr;
            }
        }
        
        bindEvents() {
            this.trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggle();
            });
            
            this.popup.querySelector('.y-c-nav-prev').addEventListener('click', (e) => {
                e.stopPropagation();
                this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);
                this.renderCalendar();
            });
            
            this.popup.querySelector('.y-c-nav-next').addEventListener('click', (e) => {
                e.stopPropagation();
                this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);
                this.renderCalendar();
            });
            
            this.daysContainer.addEventListener('click', (e) => {
                const dayBtn = e.target.closest('.y-c-calendar-day');
                if (dayBtn && !dayBtn.classList.contains('disabled') && !dayBtn.classList.contains('other-month')) {
                    const dateStr = dayBtn.dataset.date;
                    this.selectedDate = new Date(dateStr);
                    this.renderCalendar();
                    this.updateDisplay();
                    this.saveToStorage();
                    this.options.onChange(this.getValue());
                    this.close();
                }
            });
            
            document.addEventListener('click', (e) => {
                if (!this.container.contains(e.target)) {
                    this.close();
                }
            });
        }
        
        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }
        
        open() {
            document.querySelectorAll('.y-c-picker-popup.active').forEach(popup => {
                popup.classList.remove('active');
            });
            
            this.popup.classList.add('active');
            this.isOpen = true;
        }
        
        close() {
            this.popup.classList.remove('active');
            this.isOpen = false;
        }
        
        getValue() {
            return {
                date: this.selectedDate,
                dateString: this.selectedDate ? 
                    `${this.selectedDate.getFullYear()}-${String(this.selectedDate.getMonth() + 1).padStart(2, '0')}-${String(this.selectedDate.getDate()).padStart(2, '0')}` : null
            };
        }
        
        setMinDate(date) {
            this.options.minDate = date;
            if (this.selectedDate && this.selectedDate < date) {
                this.selectedDate = null;
                this.updateDisplay();
            }
            this.renderCalendar();
        }
        
        saveToStorage() {
            if (this.options.storageKey && this.selectedDate) {
                const dateStr = `${this.selectedDate.getFullYear()}-${String(this.selectedDate.getMonth() + 1).padStart(2, '0')}-${String(this.selectedDate.getDate()).padStart(2, '0')}`;
                localStorage.setItem(this.options.storageKey, dateStr);
            }
        }
        
        loadFromStorage() {
            if (this.options.storageKey) {
                const savedDate = localStorage.getItem(this.options.storageKey);
                if (savedDate) {
                    this.selectedDate = new Date(savedDate);
                    this.currentMonth = new Date(this.selectedDate);
                }
            }
        }
    }

    /**
     * Custom Time Picker Class (Time Only)
     */
    class CustomTimePicker {
        constructor(container, options = {}) {
            this.container = container;
            this.options = {
                defaultTime: options.defaultTime || { hours: 12, minutes: 0, ampm: 'AM' },
                onChange: options.onChange || function() {},
                storageKey: options.storageKey || null,
                ...options
            };
            
            this.selectedTime = { ...this.options.defaultTime };
            this.isOpen = false;
            
            this.init();
        }
        
        init() {
            this.createPopup();
            this.bindEvents();
            this.loadFromStorage();
            this.updateDisplay();
        }
        
        createPopup() {
            const popup = document.createElement('div');
            popup.className = 'y-c-picker-popup y-c-time-popup';
            popup.innerHTML = `
                <div class="y-c-time-picker-section">
                    <div class="y-c-time-label">الوقت</div>
                    <div class="y-c-time-picker-wrapper">
                        <div class="y-c-ampm-toggle">
                            <button type="button" class="y-c-ampm-btn" data-value="AM">AM</button>
                            <button type="button" class="y-c-ampm-btn" data-value="PM">PM</button>
                        </div>
                        <div class="y-c-time-input-group">
                            <input type="number" class="y-c-time-input y-c-time-minutes" min="0" max="59" value="00">
                            <span class="y-c-time-separator">:</span>
                            <input type="number" class="y-c-time-input y-c-time-hours" min="1" max="12" value="12">
                        </div>
                    </div>
                </div>
            `;
            
            this.container.appendChild(popup);
            this.popup = popup;
            
            this.trigger = this.container.querySelector('.y-c-picker-trigger');
            this.valueDisplay = this.container.querySelector('.y-c-picker-value');
            this.hoursInput = popup.querySelector('.y-c-time-hours');
            this.minutesInput = popup.querySelector('.y-c-time-minutes');
            this.ampmButtons = popup.querySelectorAll('.y-c-ampm-btn');
            
            this.updateTimeInputs();
        }
        
        updateTimeInputs() {
            this.hoursInput.value = String(this.selectedTime.hours).padStart(2, '0');
            this.minutesInput.value = String(this.selectedTime.minutes).padStart(2, '0');
            
            this.ampmButtons.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.value === this.selectedTime.ampm);
            });
        }
        
        updateDisplay() {
            const timeStr = `${String(this.selectedTime.hours).padStart(2, '0')}:${String(this.selectedTime.minutes).padStart(2, '0')} ${this.selectedTime.ampm}`;
            this.valueDisplay.textContent = timeStr;
            this.valueDisplay.classList.remove('placeholder');
            
            const timeInput = this.container.querySelector('input[type="hidden"]');
            if (timeInput) {
                let hours24 = this.selectedTime.hours;
                if (this.selectedTime.ampm === 'PM' && hours24 !== 12) {
                    hours24 += 12;
                } else if (this.selectedTime.ampm === 'AM' && hours24 === 12) {
                    hours24 = 0;
                }
                timeInput.value = `${String(hours24).padStart(2, '0')}:${String(this.selectedTime.minutes).padStart(2, '0')}`;
            }
        }
        
        bindEvents() {
            this.trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggle();
            });
            
            this.hoursInput.addEventListener('change', () => {
                let val = parseInt(this.hoursInput.value) || 12;
                val = Math.max(1, Math.min(12, val));
                this.selectedTime.hours = val;
                this.hoursInput.value = String(val).padStart(2, '0');
                this.updateDisplay();
                this.saveToStorage();
                this.options.onChange(this.getValue());
            });
            
            this.minutesInput.addEventListener('change', () => {
                let val = parseInt(this.minutesInput.value) || 0;
                val = Math.max(0, Math.min(59, val));
                this.selectedTime.minutes = val;
                this.minutesInput.value = String(val).padStart(2, '0');
                this.updateDisplay();
                this.saveToStorage();
                this.options.onChange(this.getValue());
            });
            
            this.ampmButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.selectedTime.ampm = btn.dataset.value;
                    this.updateTimeInputs();
                    this.updateDisplay();
                    this.saveToStorage();
                    this.options.onChange(this.getValue());
                });
            });
            
            document.addEventListener('click', (e) => {
                if (!this.container.contains(e.target)) {
                    this.close();
                }
            });
        }
        
        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        }
        
        open() {
            document.querySelectorAll('.y-c-picker-popup.active').forEach(popup => {
                popup.classList.remove('active');
            });
            
            this.popup.classList.add('active');
            this.isOpen = true;
        }
        
        close() {
            this.popup.classList.remove('active');
            this.isOpen = false;
        }
        
        getValue() {
            return {
                time: this.selectedTime,
                timeString: `${String(this.selectedTime.hours).padStart(2, '0')}:${String(this.selectedTime.minutes).padStart(2, '0')} ${this.selectedTime.ampm}`
            };
        }
        
        saveToStorage() {
            if (this.options.storageKey) {
                let hours24 = this.selectedTime.hours;
                if (this.selectedTime.ampm === 'PM' && hours24 !== 12) {
                    hours24 += 12;
                } else if (this.selectedTime.ampm === 'AM' && hours24 === 12) {
                    hours24 = 0;
                }
                localStorage.setItem(this.options.storageKey, `${String(hours24).padStart(2, '0')}:${String(this.selectedTime.minutes).padStart(2, '0')}`);
            }
        }
        
        loadFromStorage() {
            if (this.options.storageKey) {
                const savedTime = localStorage.getItem(this.options.storageKey);
                if (savedTime) {
                    const [hours, minutes] = savedTime.split(':').map(Number);
                    let hours12 = hours % 12 || 12;
                    let ampm = hours >= 12 ? 'PM' : 'AM';
                    this.selectedTime = { hours: hours12, minutes: minutes, ampm: ampm };
                }
            }
        }
    }

    /**
     * Initialize Separate Date and Time Pickers
     */
    function initializeSeparatePickers() {
        // Pickup Date Picker
        const pickupDateContainer = document.querySelector('.y-c-date-picker[data-picker="pickup-date"]');
        let pickupDatePicker = null;
        if (pickupDateContainer) {
            pickupDatePicker = new CustomDatePicker(pickupDateContainer, {
                minDate: new Date(),
                storageKey: 'pickup-date',
                onChange: function(value) {
                    // Update dropoff date picker min date
                    if (dropoffDatePicker && value.date) {
                        dropoffDatePicker.setMinDate(value.date);
                    }
                }
            });
        }
        
        // Pickup Time Picker
        const pickupTimeContainer = document.querySelector('.y-c-time-picker[data-picker="pickup-time"]');
        if (pickupTimeContainer) {
            new CustomTimePicker(pickupTimeContainer, {
                storageKey: 'pickup-time'
            });
        }
        
        // Dropoff Date Picker
        const dropoffDateContainer = document.querySelector('.y-c-date-picker[data-picker="dropoff-date"]');
        let dropoffDatePicker = null;
        if (dropoffDateContainer) {
            const savedPickupDate = localStorage.getItem('pickup-date');
            const minDate = savedPickupDate ? new Date(savedPickupDate) : new Date();
            
            dropoffDatePicker = new CustomDatePicker(dropoffDateContainer, {
                minDate: minDate,
                storageKey: 'dropoff-date'
            });
        }
        
        // Dropoff Time Picker
        const dropoffTimeContainer = document.querySelector('.y-c-time-picker[data-picker="dropoff-time"]');
        if (dropoffTimeContainer) {
            new CustomTimePicker(dropoffTimeContainer, {
                storageKey: 'dropoff-time'
            });
        }
        
        // Single Product Pickers
        const singlePickupDateContainer = document.querySelector('.y-c-date-picker[data-picker="single-pickup-date"]');
        let singlePickupDatePicker = null;
        if (singlePickupDateContainer) {
            singlePickupDatePicker = new CustomDatePicker(singlePickupDateContainer, {
                minDate: new Date(),
                storageKey: 'pickup-date',
                onChange: function(value) {
                    if (singleDropoffDatePicker && value.date) {
                        singleDropoffDatePicker.setMinDate(value.date);
                    }
                }
            });
        }
        
        const singlePickupTimeContainer = document.querySelector('.y-c-time-picker[data-picker="single-pickup-time"]');
        if (singlePickupTimeContainer) {
            new CustomTimePicker(singlePickupTimeContainer, {
                storageKey: 'pickup-time'
            });
        }
        
        const singleDropoffDateContainer = document.querySelector('.y-c-date-picker[data-picker="single-dropoff-date"]');
        let singleDropoffDatePicker = null;
        if (singleDropoffDateContainer) {
            const savedPickupDate = localStorage.getItem('pickup-date');
            const minDate = savedPickupDate ? new Date(savedPickupDate) : new Date();
            
            singleDropoffDatePicker = new CustomDatePicker(singleDropoffDateContainer, {
                minDate: minDate,
                storageKey: 'dropoff-date'
            });
        }
        
        const singleDropoffTimeContainer = document.querySelector('.y-c-time-picker[data-picker="single-dropoff-time"]');
        if (singleDropoffTimeContainer) {
            new CustomTimePicker(singleDropoffTimeContainer, {
                storageKey: 'dropoff-time'
            });
        }
    }

    /**
     * Initialize Date Pickers
     */
    function initializeDatepickers() {
        // Initialize separate date and time pickers
        initializeSeparatePickers();
        
        // Handle "احجز الان" button click on single product page
        const bookButton = document.querySelector('.y-c-single-product-book-btn');
        if (bookButton) {
            bookButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Check if date and time are selected
                const pickupDate = localStorage.getItem('pickup-date');
                const pickupTime = localStorage.getItem('pickup-time');
                const dropoffDate = localStorage.getItem('dropoff-date');
                const dropoffTime = localStorage.getItem('dropoff-time');
                
                // Validate all fields are filled
                if (!pickupDate || !pickupTime || !dropoffDate || !dropoffTime) {
                    // Show error message
                    let errorMessage = 'يرجى اختيار ';
                    let missingFields = [];
                    
                    if (!pickupDate) missingFields.push('تاريخ الخروج');
                    if (!pickupTime) missingFields.push('وقت الخروج');
                    if (!dropoffDate) missingFields.push('تاريخ التسليم');
                    if (!dropoffTime) missingFields.push('وقت التسليم');
                    
                    errorMessage += missingFields.join(' و ');
                    
                    // Create or update error notification
                    let errorNotification = document.querySelector('.y-c-booking-error');
                    if (!errorNotification) {
                        errorNotification = document.createElement('div');
                        errorNotification.className = 'y-c-booking-error';
                        bookButton.parentElement.insertBefore(errorNotification, bookButton);
                    }
                    errorNotification.textContent = errorMessage;
                    errorNotification.style.display = 'block';
                    
                    // Highlight missing fields
                    if (!pickupDate || !pickupTime) {
                        const pickupSection = document.querySelector('.y-c-date-picker[data-picker="single-pickup-date"]');
                        if (pickupSection) pickupSection.classList.add('y-c-picker-error');
                        const pickupTimeSection = document.querySelector('.y-c-time-picker[data-picker="single-pickup-time"]');
                        if (pickupTimeSection) pickupTimeSection.classList.add('y-c-picker-error');
                    }
                    if (!dropoffDate || !dropoffTime) {
                        const dropoffSection = document.querySelector('.y-c-date-picker[data-picker="single-dropoff-date"]');
                        if (dropoffSection) dropoffSection.classList.add('y-c-picker-error');
                        const dropoffTimeSection = document.querySelector('.y-c-time-picker[data-picker="single-dropoff-time"]');
                        if (dropoffTimeSection) dropoffTimeSection.classList.add('y-c-picker-error');
                    }
                    
                    // Remove error highlighting after 3 seconds
                    setTimeout(() => {
                        document.querySelectorAll('.y-c-picker-error').forEach(el => {
                            el.classList.remove('y-c-picker-error');
                        });
                    }, 3000);
                    
                    return;
                }
                
                // Remove any existing error message
                const existingError = document.querySelector('.y-c-booking-error');
                if (existingError) existingError.style.display = 'none';
                
                const productId = this.getAttribute('data-product-id');
                const checkoutUrl = this.getAttribute('href');
                
                if (productId) {
                    fetch(checkoutUrl, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(() => {
                        window.location.href = checkoutUrl;
                    })
                    .catch(() => {
                        window.location.href = checkoutUrl;
                    });
                } else {
                    window.location.href = checkoutUrl;
                }
            });
        }
    }

    /**
     * Fix menu links to point to correct pages
     */
    function fixMenuLinks() {
        // Fix "أسطولنا" link to go to shop page
        const menuLinks = document.querySelectorAll('.y-c-nav-link');
        menuLinks.forEach(function(link) {
            const linkText = link.textContent.trim();
            if ((linkText.includes('أسطولنا') || linkText.includes('اسطولنا')) && link.href.includes('/')) {
                // Only fix if link doesn't already point to shop
                const shopUrl = window.location.origin + '/shop/';
                if (!link.href.includes('/shop')) {
                    link.href = shopUrl;
                }
            }
        });
    }

    /**
     * Initialize all functionality when DOM is ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        setActiveNavLink();
        initSearchExpandable();
        setupScrollableContainers();
        initializeHeroTabs();
        initializeLocationDropdowns();
        
        // Fix menu links
        fixMenuLinks();
        
        // Initialize date pickers after a short delay to ensure flatpickr is loaded
        setTimeout(initializeDatepickers, 100);
    });

})(jQuery);
