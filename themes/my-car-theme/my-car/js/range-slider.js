class PriceRangeSlider {
    constructor() {
        this.track = document.querySelector('.y-c-slider-track');
        this.minPoint = document.getElementById('minPoint');
        this.maxPoint = document.getElementById('maxPoint');
        this.minValue = document.getElementById('min-value');
        this.maxValue = document.getElementById('max-value');

        if (!this.track || !this.minPoint || !this.maxPoint || !this.minValue || !this.maxValue) {
            console.warn("PriceRangeSlider elements not found. Slider will not initialize.");
            return;
        }

        this.MIN_PRICE = 100;
        this.MAX_PRICE = 1500;
        this.RANGE = this.MAX_PRICE - this.MIN_PRICE;
        this.isDragging = null;
        this.startX = 0;
        this.startLeft = 0;

        // Bind methods
        this.updateSlider = this.updateSlider.bind(this);
        this.startDragging = this.startDragging.bind(this);
        this.handleDragging = this.handleDragging.bind(this);
        this.stopDragging = this.stopDragging.bind(this);
        this.handleTrackClick = this.handleTrackClick.bind(this);
    }

    initialize() {
        if (!this.track) return; // Don't initialize if elements weren't found

        // Load values from URL parameters if available
        const urlParams = new URLSearchParams(window.location.search);
        const minPrice = urlParams.get('min_price');
        const maxPrice = urlParams.get('max_price');
        
        let minPos = 0;
        let maxPos = 100;
        
        if (minPrice && maxPrice) {
            const minPriceNum = parseInt(minPrice);
            const maxPriceNum = parseInt(maxPrice);
            
            // Calculate positions based on price range
            minPos = ((minPriceNum - this.MIN_PRICE) / this.RANGE) * 100;
            maxPos = ((maxPriceNum - this.MIN_PRICE) / this.RANGE) * 100;
            
            // Constrain values
            minPos = Math.max(0, Math.min(100, minPos));
            maxPos = Math.max(0, Math.min(100, maxPos));
            
            // Ensure min is less than max
            if (minPos >= maxPos) {
                minPos = Math.max(0, maxPos - 5);
            }
        }

        // Initialize slider positions
        this.track.style.setProperty('--min-pos', minPos + '%');
        this.track.style.setProperty('--max-pos', maxPos + '%');

        // Update slider line position
        const sliderLine = this.track.querySelector('.y-c-slider-line');
        if (sliderLine) {
            sliderLine.style.left = minPos + '%';
            sliderLine.style.right = (100 - maxPos) + '%';
        }

        // Update point positions
        if (this.minPoint) {
            this.minPoint.style.setProperty('--min-pos', minPos + '%');
        }
        if (this.maxPoint) {
            this.maxPoint.style.setProperty('--max-pos', maxPos + '%');
        }

        // Add event listeners for mouse and touch events
        this.minPoint.addEventListener('mousedown', (e) => this.startDragging(e, this.minPoint));
        this.maxPoint.addEventListener('mousedown', (e) => this.startDragging(e, this.maxPoint));
        this.minPoint.addEventListener('touchstart', (e) => this.startDragging(e, this.minPoint));
        this.maxPoint.addEventListener('touchstart', (e) => this.startDragging(e, this.maxPoint));

        // Handle clicks on the track
        this.track.addEventListener('click', this.handleTrackClick);

        // Initialize values (don't trigger events during initialization)
        this.updateSlider(this.minPoint, minPos, true);
        this.updateSlider(this.maxPoint, maxPos, true);
    }

    updateSlider(point, pos, isInitialization = false) {
        // Constrain position between 0 and 100
        pos = Math.max(0, Math.min(100, pos));

        // Prevent points from crossing
        if (point === this.minPoint) {
            const maxPos = parseFloat(getComputedStyle(this.track).getPropertyValue('--max-pos'));
            pos = Math.min(pos, maxPos - 5); // `pos` is now the constrained min position
            this.track.style.setProperty('--min-pos', `${pos}%`);
        } else { // point === maxPoint
            const minPos = parseFloat(getComputedStyle(this.track).getPropertyValue('--min-pos'));
            pos = Math.max(pos, minPos + 5); // `pos` is now the constrained max position
            this.track.style.setProperty('--max-pos', `${pos}%`);
        }

        // Update values
        const minPos = parseFloat(getComputedStyle(this.track).getPropertyValue('--min-pos'));
        const maxPos = parseFloat(getComputedStyle(this.track).getPropertyValue('--max-pos'));

        const minPrice = Math.round(this.MIN_PRICE + (this.RANGE * minPos / 100));
        const maxPrice = Math.round(this.MIN_PRICE + (this.RANGE * maxPos / 100));

        this.minValue.textContent = minPrice;
        this.maxValue.textContent = maxPrice;

        // Update slider line position between min and max points
        const sliderLine = this.track.querySelector('.y-c-slider-line');
        if (sliderLine) {
            sliderLine.style.left = minPos + '%';
            sliderLine.style.right = (100 - maxPos) + '%';
        }

        // Update point positions (in case they need to be synced)
        if (this.minPoint) {
            this.minPoint.style.setProperty('--min-pos', minPos + '%');
        }
        if (this.maxPoint) {
            this.maxPoint.style.setProperty('--max-pos', maxPos + '%');
        }

        // Don't dispatch event during initialization
        if (!isInitialization) {
            // Trigger a custom event for price change
            this.track.dispatchEvent(new CustomEvent('priceChange', {
                detail: { min: minPrice, max: maxPrice }
            }));
        }
    }

    startDragging(e, point) {
        e.preventDefault();
        this.isDragging = point;
        this.startX = e.clientX || e.touches?.[0].clientX;
        this.startLeft = point === this.minPoint ?
            parseFloat(getComputedStyle(this.track).getPropertyValue('--min-pos')) :
            parseFloat(getComputedStyle(this.track).getPropertyValue('--max-pos'));

        document.addEventListener('mousemove', this.handleDragging);
        document.addEventListener('touchmove', this.handleDragging);
        document.addEventListener('mouseup', this.stopDragging);
        document.addEventListener('touchend', this.stopDragging);
    }

    handleDragging(e) {
        if (!this.isDragging) return;
        e.preventDefault();

        const x = e.clientX || e.touches?.[0].clientX;
        const trackRect = this.track.getBoundingClientRect();
        const deltaX = x - this.startX;
        const deltaPercent = (deltaX / trackRect.width) * 100;
        const newPos = this.startLeft + deltaPercent;

        this.updateSlider(this.isDragging, newPos);
    }

    stopDragging() {
        this.isDragging = null;
        document.removeEventListener('mousemove', this.handleDragging);
        document.removeEventListener('touchmove', this.handleDragging);
        document.removeEventListener('mouseup', this.stopDragging);
        document.removeEventListener('touchend', this.stopDragging);
    }

    handleTrackClick(e) {
        if (this.isDragging || e.target === this.minPoint || e.target === this.maxPoint) return;

        const trackRect = this.track.getBoundingClientRect();
        const clickPos = ((e.clientX - trackRect.left) / trackRect.width) * 100;

        // Find the closest point to the click
        const minPos = parseFloat(getComputedStyle(this.track).getPropertyValue('--min-pos'));
        const maxPos = parseFloat(getComputedStyle(this.track).getPropertyValue('--max-pos'));
        const point = Math.abs(clickPos - minPos) < Math.abs(clickPos - maxPos) ? this.minPoint : this.maxPoint;

        this.updateSlider(point, clickPos);
    }
}