<?php

include 'includes/header.php'; 

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";


// --- Handle Reservation Cancellation ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_reservation'])) {
    $reservation_id_to_cancel = $_POST['reservation_id'];
    $user_id = $_SESSION["user_id"];

    // Prepare an update statement to prevent SQL injection
    $sql = "UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ii", $reservation_id_to_cancel, $user_id);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to the dashboard to see the change
            header("location: dashboard.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}

// --- Fetch User's Reservations ---
$upcoming_reservations = [];
$past_reservations = [];
$user_id = $_SESSION["user_id"];

// Prepare a select statement for upcoming reservations
$sql_upcoming = "SELECT id, reservation_date, reservation_time, party_size, status FROM reservations WHERE user_id = ? AND status = 'confirmed' AND reservation_date >= CURDATE() ORDER BY reservation_date, reservation_time";

if ($stmt_upcoming = $mysqli->prepare($sql_upcoming)) {
    $stmt_upcoming->bind_param("i", $user_id);
    if ($stmt_upcoming->execute()) {
        $result = $stmt_upcoming->get_result();
        while ($row = $result->fetch_assoc()) {
            $upcoming_reservations[] = $row;
        }
    }
    $stmt_upcoming->close();
}

// Prepare a select statement for past/cancelled reservations
$sql_past = "SELECT id, reservation_date, reservation_time, party_size, status FROM reservations WHERE user_id = ? AND (status != 'confirmed' OR reservation_date < CURDATE()) ORDER BY reservation_date DESC, reservation_time DESC";

if ($stmt_past = $mysqli->prepare($sql_past)) {
    $stmt_past->bind_param("i", $user_id);
    if ($stmt_past->execute()) {
        $result = $stmt_past->get_result();
        while ($row = $result->fetch_assoc()) {
            $past_reservations[] = $row;
        }
    }
    $stmt_past->close();
}

$mysqli->close();
?>


<link rel="stylesheet" href="css/dashboard-style.css">

<main class="dashboard-container">
    <div class="dashboard-header">
        <h1>My Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>! View and manage your reservations here.</p>
    </div>

    <div class="tabs">
        <button class="tab-link active" onclick="openTab(event, 'upcoming')">Upcoming</button>
        <button class="tab-link" onclick="openTab(event, 'history')">History</button>
    </div>

    <div id="upcoming" class="tab-content active">
        <?php if (empty($upcoming_reservations)): ?>
            <div class="empty-state">
                <p>You have no upcoming reservations.</p>
                <a href="reservation.php" class="button">Book a Table</a>
            </div>
        <?php else: ?>
            <div class="reservations-grid">
                <?php foreach ($upcoming_reservations as $res): ?>
                    <div class="reservation-card">
                        <div class="card-details">
                            <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($res['reservation_date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date("g:i A", strtotime($res['reservation_time'])); ?></p>
                            <p><strong>Guests:</strong> <?php echo htmlspecialchars($res['party_size']); ?></p>
                        </div>
                        <div class="card-status">
                            <span class="status-badge status-confirmed"><?php echo htmlspecialchars($res['status']); ?></span>
                        </div>
                        <div class="card-actions">
                            <form action="dashboard.php" method="post" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                <input type="hidden" name="reservation_id" value="<?php echo $res['id']; ?>">
                                <button type="submit" name="cancel_reservation" class="cancel-btn">Cancel</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="history" class="tab-content">
        <?php if (empty($past_reservations)): ?>
            <div class="empty-state">
                <p>You have no past reservations.</p>
            </div>
        <?php else: ?>
             <div class="reservations-grid">
                <?php foreach ($past_reservations as $res): ?>
                    <div class="reservation-card">
                        <div class="card-details">
                            <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($res['reservation_date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date("g:i A", strtotime($res['reservation_time'])); ?></p>
                            <p><strong>Guests:</strong> <?php echo htmlspecialchars($res['party_size']); ?></p>
                        </div>
                        <div class="card-status">
                             <span class="status-badge status-<?php echo strtolower(htmlspecialchars($res['status'])); ?>"><?php echo htmlspecialchars($res['status']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
// Simple script for tab functionality
function openTab(evt, tabName) {
    let i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}
</script>

<?php include 'includes/footer.php'; ?>