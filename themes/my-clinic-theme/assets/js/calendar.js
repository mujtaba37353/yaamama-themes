function initializeCalendar(buttonId, popoverId, reservationLinkId) {
  const openButton = document.getElementById(buttonId);
  const calendarPopover = document.getElementById(popoverId);
  const reservationLink = document.getElementById(reservationLinkId);

  if (!openButton || !calendarPopover) {
    return;
  }

  const monthYearElement = calendarPopover.querySelector(".month-year");
  const daysGrid = calendarPopover.querySelector(".days-grid");
  const prevMonthButton = calendarPopover.querySelector(".prev-month");
  const nextMonthButton = calendarPopover.querySelector(".next-month");

  // Get work schedule from data attribute
  const workScheduleData = calendarPopover.getAttribute("data-work-schedule");
  const workSchedule = workScheduleData ? JSON.parse(workScheduleData) : {};
  const bookingTime = calendarPopover.getAttribute("data-booking-time") || "04:00 مساءً - 07:00 مساءً";
  const doctorId = calendarPopover.getAttribute("data-doctor-id") || "";
  const clinicId = calendarPopover.getAttribute("data-clinic-id") || "";

  let currentDate = new Date();
  let selectedDate = null; // No date selected initially
  let dateSelectedByUser = false; // Track if user has selected a date

  // Map day names to schedule keys
  const dayNameToKey = {
    0: 'sunday',    // الأحد
    1: 'monday',    // الاثنين
    2: 'tuesday',   // الثلاثاء
    3: 'wednesday', // الأربعاء
    4: 'thursday',  // الخميس
    5: 'friday',    // الجمعة
    6: 'saturday'   // السبت
  };

  // Function to check if a date is in work schedule
  function isDateInWorkSchedule(date) {
    // Disable past dates
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const checkDate = new Date(date);
    checkDate.setHours(0, 0, 0, 0);
    
    if (checkDate < today) {
      return false; // Past dates are disabled
    }
    
    if (!workSchedule || Object.keys(workSchedule).length === 0) {
      return true; // If no schedule, allow all future dates
    }
    const dayOfWeek = date.getDay();
    const dayKey = dayNameToKey[dayOfWeek];
    // Check if the day exists in schedule and has valid from/to times
    return workSchedule.hasOwnProperty(dayKey) && 
           workSchedule[dayKey] && 
           workSchedule[dayKey].from && 
           workSchedule[dayKey].to;
  }

  function renderCalendar() {
    daysGrid.innerHTML = "";
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);

    const monthNames = [
      "يناير",
      "فبراير",
      "مارس",
      "أبريل",
      "مايو",
      "يونيو",
      "يوليو",
      "أغسطس",
      "سبتمبر",
      "أكتوبر",
      "نوفمبر",
      "ديسمبر",
    ];
    monthYearElement.textContent = `${monthNames[month]} ${year}`;

    const startDay = firstDayOfMonth.getDay();

    for (let i = 0; i < startDay; i++) {
      const emptyDay = document.createElement("div");
      emptyDay.classList.add("day", "empty");
      daysGrid.appendChild(emptyDay);
    }

    for (let i = 1; i <= lastDayOfMonth.getDate(); i++) {
      const dayElement = document.createElement("div");
      dayElement.classList.add("day");
      dayElement.textContent = i;

      const checkDate = new Date(year, month, i);
      const isInSchedule = isDateInWorkSchedule(checkDate);

      if (!isInSchedule) {
        dayElement.classList.add("disabled");
      } else {
        dayElement.addEventListener("click", () => {
          selectedDate = new Date(year, month, i);
          dateSelectedByUser = true; // Mark that user has selected a date
          document
            .querySelectorAll(`#${popoverId} .day`)
            .forEach((d) => d.classList.remove("selected"));
          dayElement.classList.add("selected");
          updateReservationLink();
        });
      }

      if (
        selectedDate &&
        i === selectedDate.getDate() &&
        month === selectedDate.getMonth() &&
        year === selectedDate.getFullYear()
      ) {
        dayElement.classList.add("selected");
      }

      daysGrid.appendChild(dayElement);
    }
  }

  function updateReservationLink() {
    if (reservationLink) {
      if (!dateSelectedByUser || !selectedDate) {
        // No date selected - show "احجز في" only and disable button
        reservationLink.textContent = 'احجز في';
        reservationLink.style.opacity = '0.6';
        reservationLink.style.cursor = 'not-allowed';
        reservationLink.style.pointerEvents = 'none';
        reservationLink.classList.add('disabled');
      } else {
        // Date selected - show date and enable button
        const formattedDate = `${selectedDate.getDate()}/${
          selectedDate.getMonth() + 1
        }/${selectedDate.getFullYear()}`;
        reservationLink.textContent = `احجز في ${formattedDate}`;
        const baseUrl = reservationLink.href.split("?")[0];
        let bookingUrl = `${baseUrl}?date=${formattedDate}`;
        
        // Add doctor_id or clinic_id to URL
        if (doctorId) {
          bookingUrl += `&doctor_id=${doctorId}`;
        } else if (clinicId) {
          bookingUrl += `&clinic_id=${clinicId}`;
        }
        
        reservationLink.href = bookingUrl;
        reservationLink.style.opacity = '1';
        reservationLink.style.cursor = 'pointer';
        reservationLink.style.pointerEvents = 'auto';
        reservationLink.classList.remove('disabled');
      }
    }
  }

  // Store button reference in popover for easy lookup
  calendarPopover.setAttribute('data-button-id', buttonId);
  
  openButton.addEventListener("click", (event) => {
    event.stopPropagation(); // Prevent event from bubbling to document
    event.preventDefault(); // Prevent any default behavior
    // Close all other calendars first
    document.querySelectorAll('.calendar-popover.show').forEach(pop => {
      if (pop !== calendarPopover) {
        pop.classList.remove('show');
      }
    });
    calendarPopover.classList.toggle("show");
  });

  prevMonthButton.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
  });

  nextMonthButton.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
  });

  renderCalendar();
  updateReservationLink(); // Initialize button as disabled with "احجز في" only
}

// Single document click handler for all calendars
document.addEventListener("click", (event) => {
  // Check all calendar popovers
  document.querySelectorAll('.calendar-popover.show').forEach(popover => {
    // Get button ID from data attribute
    const buttonId = popover.getAttribute('data-button-id');
    const button = buttonId ? document.getElementById(buttonId) : null;
    
    // If click is outside both the popover and its button, close it
    if (button && popover) {
      if (
        !popover.contains(event.target) &&
        !button.contains(event.target)
      ) {
        popover.classList.remove("show");
      }
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  initializeCalendar(
    "open-calendar-btn",
    "calendar-popover",
    "reservation-link"
  );
  initializeCalendar(
    "open-calendar-btn-clinic",
    "calendar-popover-clinic",
    "reservation-link-clinic"
  );

  for (let i = 1; i <= 6; i++) {
    initializeCalendar(
      `open-calendar-btn-clinic-${i}`,
      `calendar-popover-clinic-${i}`,
      `reservation-link-clinic-${i}`
    );
  }

  for (let i = 1; i <= 6; i++) {
    initializeCalendar(
      `open-calendar-btn-doctor-${i}`,
      `calendar-popover-doctor-${i}`,
      `reservation-link-doctor-${i}`
    );
  }

  // Initialize calendars for doctors on clinic page
  for (let i = 1; i <= 20; i++) {
    initializeCalendar(
      `open-calendar-btn-doctor-clinic-${i}`,
      `calendar-popover-doctor-clinic-${i}`,
      `reservation-link-doctor-clinic-${i}`
    );
  }
});
