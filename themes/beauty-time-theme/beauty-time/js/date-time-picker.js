(() => {
  const arabicMonths = [
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

  const arabicDays = ["ح", "ن", "ث", "ر", "خ", "ج", "س"];

  class DateTimePicker {
    constructor(container) {
      this.container = container;
      this.dateTimeWrapper = container.querySelector(".date-time-wrapper");
      this.currentDate = new Date();
      this.selectedDate = null;
      this.selectedTime = null;
      this.workingHours = {};
      this.intervalMinutes =
        (window.beautyBooking && window.beautyBooking.workingHoursInterval) || 60;
      this.init();
    }

    async init() {
      await this.fetchWorkingHoursForMonth(this.currentDate);
      this.renderCalendar();
      this.setupEventListeners();
      this.updateSelectedInfo();
      this.renderTimeSlotsForDate(null);
    }

    getMonthKey(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      return `${year}-${month}`;
    }

    async fetchWorkingHoursForMonth(date) {
      const monthKey = this.getMonthKey(date);
      if (!window.beautyBooking || !window.beautyBooking.workingHoursNonce) {
        this.workingHours = {};
        return;
      }
      try {
        const params = new URLSearchParams();
        params.set("action", "beauty_get_working_hours");
        params.set("nonce", window.beautyBooking.workingHoursNonce);
        params.set("month", monthKey);
        const response = await fetch(window.beautyBooking.ajaxUrl, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: params.toString(),
        });
        const data = await response.json();
        this.workingHours = data?.success ? data.data || {} : {};
      } catch (_) {
        this.workingHours = {};
      }
    }

    hasWorkingHours(dateKey) {
      return (
        this.workingHours &&
        Array.isArray(this.workingHours[dateKey]) &&
        this.workingHours[dateKey].length > 0
      );
    }

    renderCalendar() {
      const calendarDays = this.container.querySelector(".calendar-days");
      const monthName = this.container.querySelector(".month-name");
      const yearName = this.container.querySelector(".year-name");

      if (!calendarDays || !monthName || !yearName) return;

      monthName.textContent = arabicMonths[this.currentDate.getMonth()];
      yearName.textContent = this.currentDate.getFullYear();

      calendarDays.innerHTML = "";

      const year = this.currentDate.getFullYear();
      const month = this.currentDate.getMonth();
      const firstDay = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0);
      const daysInMonth = lastDay.getDate();

      let startingDay = firstDay.getDay();
      startingDay = (startingDay + 1) % 7;

      for (let i = 0; i < startingDay; i++) {
        const emptyDay = document.createElement("div");
        emptyDay.className = "day";
        calendarDays.appendChild(emptyDay);
      }

      const today = new Date();
      today.setHours(0, 0, 0, 0);

      for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement("div");
        dayElement.className = "day";
        dayElement.textContent = day;

        const currentDayDate = new Date(year, month, day);
        currentDayDate.setHours(0, 0, 0, 0);
        const dateKey = `${year}-${String(month + 1).padStart(2, "0")}-${String(
          day
        ).padStart(2, "0")}`;

        if (currentDayDate.getTime() === today.getTime()) {
          dayElement.classList.add("today");
        }

        if (currentDayDate < today) {
          dayElement.classList.add("disabled");
        } else {
          if (!this.hasWorkingHours(dateKey)) {
            dayElement.classList.add("disabled");
          }

          if (
            this.selectedDate &&
            currentDayDate.getTime() === this.selectedDate.getTime()
          ) {
            dayElement.classList.add("selected");
          }

          dayElement.addEventListener("click", () => {
            if (!dayElement.classList.contains("disabled")) {
              this.selectDate(new Date(year, month, day));
            }
          });
        }

        calendarDays.appendChild(dayElement);
      }
    }

    selectDate(date) {
      const previousSelected = this.container.querySelector(
        ".calendar-days .day.selected"
      );
      if (previousSelected) {
        previousSelected.classList.remove("selected");
      }

      const dayElements = this.container.querySelectorAll(
        ".calendar-days .day"
      );
      dayElements.forEach((dayEl) => {
        const dayNum = parseInt(dayEl.textContent);
        if (
          dayNum &&
          dayNum === date.getDate() &&
          !dayEl.classList.contains("disabled")
        ) {
          dayEl.classList.add("selected");
        }
      });

      this.selectedDate = new Date(date);
      this.selectedDate.setHours(0, 0, 0, 0);
      this.selectedTime = null;
      
      // Store date in YYYY-MM-DD format for form submission
      const year = this.selectedDate.getFullYear();
      const month = String(this.selectedDate.getMonth() + 1).padStart(2, '0');
      const day = String(this.selectedDate.getDate()).padStart(2, '0');
      const dateString = `${year}-${month}-${day}`;
      
      // Store in data attribute on date-time-wrapper
      if (this.dateTimeWrapper) {
        this.dateTimeWrapper.setAttribute('data-selected-date', dateString);
        this.dateTimeWrapper.removeAttribute('data-selected-time');
        this.dateTimeWrapper.removeAttribute('data-available-times');
      }
      
      this.updateSelectedInfo();
      this.renderTimeSlotsForDate(dateString);
    }

    selectTime(timeSlot) {
      const timeSlots = this.container.querySelectorAll(".time-slot");
      timeSlots.forEach((slot) => {
        slot.classList.remove("selected");
      });

      timeSlot.classList.add("selected");
      this.selectedTime = timeSlot.getAttribute("data-time");
      
      // Store time in data attribute on date-time-wrapper
      if (this.dateTimeWrapper) {
        this.dateTimeWrapper.setAttribute('data-selected-time', this.selectedTime);
      }
      
      this.updateSelectedInfo();
    }

    updateSelectedInfo() {
      const dateText = this.container.querySelector(".selected-date-text");
      const timeText = this.container.querySelector(".selected-time-text");

      if (dateText) {
        if (this.selectedDate) {
          const day = this.selectedDate.getDate();
          const month = arabicMonths[this.selectedDate.getMonth()];
          const year = this.selectedDate.getFullYear();
          dateText.textContent = `${day} ${month} ${year}`;
          dateText.classList.add("has-selection");
        } else {
          dateText.textContent = "لم يتم اختيار تاريخ";
          dateText.classList.remove("has-selection");
        }
      }

      if (timeText) {
        if (this.selectedTime) {
          const [hours, minutes] = this.selectedTime.split(":");
          const hour24 = parseInt(hours);
          const hour12 = hour24 > 12 ? hour24 - 12 : hour24 === 0 ? 12 : hour24;
          const period = hour24 >= 12 ? "م" : "ص";
          timeText.textContent = `${String(hour12).padStart(
            2,
            "0"
          )}:${minutes} ${period}`;
          timeText.classList.add("has-selection");
        } else {
          timeText.textContent = "لم يتم اختيار وقت";
          timeText.classList.remove("has-selection");
        }
      }
    }

    setupEventListeners() {
      const prevBtn = this.container.querySelector(".prev-month");
      const nextBtn = this.container.querySelector(".next-month");
      const timeInput = this.container.querySelector(".time-input");

      if (prevBtn) {
        prevBtn.addEventListener("click", async () => {
          this.currentDate.setMonth(this.currentDate.getMonth() - 1);
          await this.fetchWorkingHoursForMonth(this.currentDate);
          this.renderCalendar();
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener("click", async () => {
          this.currentDate.setMonth(this.currentDate.getMonth() + 1);
          await this.fetchWorkingHoursForMonth(this.currentDate);
          this.renderCalendar();
        });
      }

      if (timeInput) {
        timeInput.addEventListener("input", () => {
          const errorEl = this.container.querySelector("[data-time-error]");
          const startTime = timeInput.dataset.startTime;
          const endTime = timeInput.dataset.endTime;
          const value = timeInput.value;

          const toMinutes = (time) => {
            const [h, m] = time.split(":").map((val) => parseInt(val, 10));
            return h * 60 + m;
          };

          if (!value) {
            this.selectedTime = null;
            if (this.dateTimeWrapper) {
              this.dateTimeWrapper.removeAttribute("data-selected-time");
            }
            if (errorEl) errorEl.textContent = "";
            this.updateSelectedInfo();
            return;
          }

          if (!startTime || !endTime) {
            this.selectedTime = null;
            if (this.dateTimeWrapper) {
              this.dateTimeWrapper.removeAttribute("data-selected-time");
            }
            if (errorEl) {
              errorEl.textContent = "يرجى اختيار تاريخ متاح أولًا.";
            }
            this.updateSelectedInfo();
            return;
          }

          const valueMin = toMinutes(value);
          const startMin = toMinutes(startTime);
          const endMin = toMinutes(endTime);

          if (
            Number.isNaN(valueMin) ||
            Number.isNaN(startMin) ||
            Number.isNaN(endMin) ||
            valueMin < startMin ||
            valueMin >= endMin
          ) {
            this.selectedTime = null;
            if (this.dateTimeWrapper) {
              this.dateTimeWrapper.removeAttribute("data-selected-time");
            }
            if (errorEl) {
              errorEl.textContent = "الوقت يجب أن يكون بين بداية الدوام ونهاية الدوام.";
            }
            this.updateSelectedInfo();
            return;
          }

          this.selectedTime = value;
          if (this.dateTimeWrapper) {
            this.dateTimeWrapper.setAttribute("data-selected-time", value);
          }
          if (errorEl) errorEl.textContent = "";
          this.updateSelectedInfo();
        });
      }
    }

    renderTimeSlotsForDate(dateKey) {
      const timeInput = this.container.querySelector(".time-input");
      const rangeText = this.container.querySelector("[data-working-hours-range]");
      const errorEl = this.container.querySelector("[data-time-error]");
      if (!timeInput) return;

      const ranges = dateKey ? this.workingHours[dateKey] || [] : [];
      timeInput.value = "";
      timeInput.disabled = true;
      timeInput.removeAttribute("data-start-time");
      timeInput.removeAttribute("data-end-time");
      if (errorEl) errorEl.textContent = "";

      const formatArabicTime = (time24) => {
        const [hours, minutes] = time24.split(":");
        const hour24 = parseInt(hours, 10);
        const hour12 = hour24 > 12 ? hour24 - 12 : hour24 === 0 ? 12 : hour24;
        const period = hour24 >= 12 ? "م" : "ص";
        return `${String(hour12).padStart(2, "0")}:${minutes} ${period}`;
      };

      if (!ranges.length) {
        if (rangeText) {
          rangeText.textContent = "";
          rangeText.classList.add("is-hidden");
        }
        return;
      }

      const range = ranges[0] || {};
      if (range.start && range.end) {
        timeInput.disabled = false;
        timeInput.dataset.startTime = range.start;
        timeInput.dataset.endTime = range.end;
        if (rangeText) {
          const startFormatted = formatArabicTime(range.start);
          const endFormatted = formatArabicTime(range.end);
          rangeText.textContent = `الحجز متاح من ${startFormatted} حتى ${endFormatted}`;
          rangeText.classList.remove("is-hidden");
        }
      } else if (rangeText) {
        rangeText.textContent = "";
        rangeText.classList.add("is-hidden");
      }
    }
  }

  let dateTimePickerInstance = null;

  const initDateTimePicker = async () => {
    const dateTimeContainer = document.querySelector(".data-time");
    if (dateTimeContainer && !dateTimeContainer.dataset.initialized) {
      const dateTimeWrapper = dateTimeContainer.querySelector(".date-time-wrapper");
      if (dateTimeWrapper) {
        dateTimePickerInstance = new DateTimePicker(dateTimeContainer);
        dateTimeContainer.dataset.initialized = "true";
      }
    }
  };

  const handleTabChange = () => {
    const dateTab = document.querySelector('.tab-content[data-content="date"]');
    if (
      dateTab &&
      dateTab.classList.contains("active") &&
      dateTimePickerInstance
    ) {
      dateTimePickerInstance.renderCalendar();
    }
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      initDateTimePicker();
      const tabButtons = document.querySelectorAll(".tabs button[data-tab]");
      tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
          setTimeout(handleTabChange, 100);
        });
      });
    });
  } else {
    initDateTimePicker();
    const tabButtons = document.querySelectorAll(".tabs button[data-tab]");
    tabButtons.forEach((button) => {
      button.addEventListener("click", () => {
        setTimeout(handleTabChange, 100);
      });
    });
  }
})();
