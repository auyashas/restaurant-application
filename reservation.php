<?php
// We'll add the form processing logic here later
// For now, it just includes the header and footer

include 'includes/header.php';

// Check if user is logged in, otherwise redirect
if (!$is_logged_in) {
    header("location: login.php");
    exit;
}
?>

<link rel="stylesheet" href="css/reservation-style.css">

<div class="reservation-page-wrapper">
    <div class="reservation-container" data-aos="fade-up">

        <div class="reservation-image">
            <img src="images/reservation-image.jpg" alt="A beautifully set table at The Malabar Table">
        </div>

        <div class="reservation-form">
            <div class="form-header">
                <h1>Book Your Table</h1>
                <p>Booking for: <strong><?php echo htmlspecialchars($_SESSION["user_name"]); ?></strong></p>
            </div>

            <form id="reservationForm" action="reservation.php" method="POST">
                <div class="form-group">
                    <label for="reservation_date">Date *</label>
                    <input type="date" id="reservation_date" name="reservation_date" required>
                </div>

                <div class="form-group">
                    <label for="reservation_time">Time *</label>
                    <select id="reservation_time" name="reservation_time" required>
                        <option value="">Select a time slot</option>
                        <optgroup label="Lunch">
                            <option value="12:00">12:00 PM</option>
                            <option value="12:30">12:30 PM</option>
                            <option value="13:00">1:00 PM</option>
                            <option value="13:30">1:30 PM</option>
                        </optgroup>
                        <optgroup label="Dinner">
                            <option value="18:00">6:00 PM</option>
                            <option value="18:30">6:30 PM</option>
                            <option value="19:00">7:00 PM</option>
                            <option value="19:30">7:30 PM</option>
                            <option value="20:00">8:00 PM</option>
                            <option value="20:30">8:30 PM</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <label for="party_size">Number of Guests *</label>
                    <input type="number" id="party_size" name="party_size" min="1" max="8" placeholder="e.g., 2" required>
                    <small>For parties larger than 8, please contact us directly.</small>
                </div>

                <div class="form-group">
                    <label for="special_requests">Special Requests (Optional)</label>
                    <textarea id="special_requests" name="special_requests" placeholder="e.g., dietary restrictions, high chair"></textarea>
                </div>

                <button type="submit" class="button submit-btn">Confirm Reservation</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>