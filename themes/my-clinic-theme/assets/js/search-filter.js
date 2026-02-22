// Search and filter functionality for doctors and clinics pages
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.getElementById('search-btn');
    const searchInput = document.getElementById('doctor-search') || document.getElementById('clinic-search');
    const specialtyButtons = document.querySelectorAll('.specialty-filter');
    const doctorBoxes = document.querySelectorAll('.doctors-box-clinic .box, .doctors-box-clinic .main-box');
    
    if (doctorBoxes.length === 0) {
        return; // Exit if no boxes found (might be on wrong page)
    }
    
    let selectedSpecialty = '';
    
    // Handle specialty filter
    specialtyButtons.forEach(button => {
        button.addEventListener('click', function() {
            selectedSpecialty = this.getAttribute('data-specialty') || '';
            
            // Update active state
            specialtyButtons.forEach(btn => btn.classList.remove('active'));
            if (selectedSpecialty) {
                this.classList.add('active');
            }
            
            // Close dropdown
            const checkbox = document.getElementById('list1');
            if (checkbox) {
                checkbox.checked = false;
            }
            
            filterResults();
        });
    });
    
    // Handle search button click
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterResults();
        });
    }
    
    // Handle Enter key in search input
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterResults();
            }
        });
        
        // Real-time search as user types (optional - can be removed if too aggressive)
        searchInput.addEventListener('input', function() {
            // Debounce search
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                filterResults();
            }, 300);
        });
    }
    
    // Filter results function
    function filterResults() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        
        doctorBoxes.forEach(box => {
            let showBox = true;
            
            // Filter by specialty
            if (selectedSpecialty) {
                const specialtyText = box.querySelector('.content p')?.textContent || '';
                const fullText = specialtyText.toLowerCase();
                
                if (!fullText.includes(selectedSpecialty.toLowerCase())) {
                    showBox = false;
                }
            }
            
            // Filter by search term
            if (searchTerm && showBox) {
                const name = box.querySelector('.content h3')?.textContent || '';
                const address = box.querySelector('.address')?.textContent || '';
                const fullText = (name + ' ' + address).toLowerCase();
                
                if (!fullText.includes(searchTerm)) {
                    showBox = false;
                }
            }
            
            // Show or hide box
            if (showBox) {
                box.style.display = '';
            } else {
                box.style.display = 'none';
            }
        });
        
        // Show message if no results
        const visibleBoxes = Array.from(doctorBoxes).filter(box => {
            const styles = window.getComputedStyle(box);
            return styles.display !== 'none';
        });
        const noResultsMsg = document.getElementById('no-results-message');
        
        if (visibleBoxes.length === 0) {
            if (!noResultsMsg) {
                const msg = document.createElement('p');
                msg.id = 'no-results-message';
                msg.style.cssText = 'text-align: center; padding: 2rem; grid-column: 1 / -1; width: 100%;';
                msg.textContent = 'لا توجد نتائج مطابقة لبحثك.';
                const container = document.querySelector('.doctors-box-clinic');
                if (container) {
                    container.appendChild(msg);
                }
            }
        } else {
            if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }
    }
});
