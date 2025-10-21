// --- RESERVATION PAGE: DYNAMIC TIME SLOT LOGIC ---

document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('reservation_date');
    const timeSelect = document.getElementById('reservation_time');

    // This function will run when the page loads and when the date changes
    function updateAvailableTimes() {
        const selectedDate = dateInput.value;

        // --- THE FIX: Get the current hour from the server via the data attribute ---
        const currentHour = parseInt(timeSelect.dataset.currentHour);

        // Get today's date from the user's browser for comparison
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const todayFormatted = `${year}-${month}-${day}`;

        // Loop through each option in the time dropdown
        for (let option of timeSelect.options) {
            if (option.value === "") {
                continue;
            }

            const optionHour = parseInt(option.value.split(':')[0]);

            // If the selected date is today, check if the time slot has passed
            if (selectedDate === todayFormatted) {
                if (optionHour <= currentHour) {
                    option.disabled = true; // Disable past time slots
                } else {
                    option.disabled = false; // Enable future time slots
                }
            } else {
                // If a future date is selected, all time slots are available
                option.disabled = false;
            }
        }

        // Reset the selected time if the current selection becomes disabled
        if (timeSelect.options[timeSelect.selectedIndex]?.disabled) {
            timeSelect.value = "";
        }
    }

    // Add an event listener to the date input
    dateInput.addEventListener('change', updateAvailableTimes);

    // Run the function once on page load to set the initial state
    updateAvailableTimes();
});

