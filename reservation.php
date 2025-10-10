<?php
// The header file now starts the session and defines $is_logged_in
include 'includes/header.php';

// Redirect if user is not logged in
if (!$is_logged_in) {
    header("location: login.php");
    exit;
}

// Include config file AFTER the login check
require_once "config.php";

// Define the restaurant's maximum capacity
define('MAX_CAPACITY', 50); 

$reservation_err = "";

// --- Processing form data when form is submitted ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $reservation_date = trim($_POST['reservation_date']);
    $reservation_time = trim($_POST['reservation_time']);
    $party_size = (int)trim($_POST['party_size']);

    // --- Check capacity before booking ---
    $sql_check = "SELECT SUM(party_size) AS total_guests FROM reservations WHERE reservation_date = ? AND reservation_time = ? AND status = 'confirmed'";
    
    if ($stmt_check = $mysqli->prepare($sql_check)) {
        $stmt_check->bind_param("ss", $reservation_date, $reservation_time);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $row = $result->fetch_assoc();
        $total_guests = ($row['total_guests']) ? (int)$row['total_guests'] : 0;
        
        if (($total_guests + $party_size) <= MAX_CAPACITY) {
            // --- Capacity is available, proceed with booking ---
            $sql_insert = "INSERT INTO reservations (user_id, reservation_date, reservation_time, party_size) VALUES (?, ?, ?, ?)";
            if ($stmt_insert = $mysqli->prepare($sql_insert)) {
                $stmt_insert->bind_param("issi", $user_id, $reservation_date, $reservation_time, $party_size);
                if ($stmt_insert->execute()) {
                    // Booking successful, set flash message and redirect to dashboard
                    $_SESSION['success_message'] = "Your reservation has been confirmed!";
                    header("location: dashboard.php");
                    exit();
                } else {
                    $reservation_err = "Oops! Something went wrong. Please try again.";
                }
                $stmt_insert->close();
            }
        } else {
            // --- Capacity is full ---
            $reservation_err = "We're sorry, but we are fully booked for that time slot. Please try another time.";
        }
        $stmt_check->close();
    }
    $mysqli->close();
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

            <?php 
            if(!empty($reservation_err)){
                echo '<div class="alert alert-error">' . $reservation_err . '</div>';
            }        
            ?>

            <form id="reservationForm" action="reservation.php" method="POST">
                <div class="form-group">
                    <label for="reservation_date">Date *</label>
                   <input type="date" id="reservation_date" name="reservation_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="reservation_time">Time *</label>
                    <select id="reservation_time" name="reservation_time" required>
                        <option value="">Select a time slot</option>
                        <optgroup label="Lunch">
                            <option value="12:00:00">12:00 PM</option>
                            <option value="13:00:00">1:00 PM</option>
                        </optgroup>
                        <optgroup label="Dinner">
                            <option value="18:00:00">6:00 PM</option>
                            <option value="19:00:00">7:00 PM</option>
                            <option value="20:00:00">8:00 PM</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label for="party_size">Number of Guests *</label>
                    <input type="number" id="party_size" name="party_size" min="1" max="8" placeholder="e.g., 2" required>
                    <small>For parties larger than 8, please contact us directly.</small>
                </div>
                <button type="submit" class="button submit-btn">Confirm Reservation</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>