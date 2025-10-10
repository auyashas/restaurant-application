<?php
session_start();
require_once "config.php";

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Fetch all reservations, joining with the users table to get the customer's name
$reservations = [];
$sql = "SELECT r.id, r.reservation_date, r.reservation_time, r.party_size, r.status, u.name as user_name 
        FROM reservations r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.reservation_date DESC, r.reservation_time DESC";

if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
    $result->free();
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Malabar Table</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="index.php" class="sidebar-logo">The Malabar Table</a>
            </div>
            <ul class="sidebar-nav">
                <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
                <li><a href="manage_menu.php">Manage Menu</a></li>
                <li><a href="manage_offers.php">Special Offers</a></li>
                <li><a href="manage_admins.php">Manage Admins</a></li>
            </ul>
            <div class="sidebar-footer">
                <p><?php echo htmlspecialchars($_SESSION["admin_username"]); ?></p>
                <a href="admin_logout.php">Logout</a>
            </div>
        </aside>

        <div class="admin-main-content">
            <header class="admin-header">
                <button class="mobile-nav-toggle">☰</button>
                <h1>Dashboard</h1>
            </header>

            <main class="admin-main">
                <section class="content-card">
                    <h2>All Reservations</h2>
                    <div class="table-container">
                        <table class="reservations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Guests</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reservations)): ?>
                                    <tr>
                                        <td colspan="6">No reservations found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reservations as $res): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($res['id']); ?></td>
                                            <td><?php echo htmlspecialchars($res['user_name']); ?></td>
                                            <td><?php echo date("d M Y", strtotime($res['reservation_date'])); ?></td>
                                            <td><?php echo date("g:i A", strtotime($res['reservation_time'])); ?></td>
                                            <td><?php echo htmlspecialchars($res['party_size']); ?></td>
                                            <td><span class="status-badge status-<?php echo strtolower(htmlspecialchars($res['status'])); ?>"><?php echo htmlspecialchars($res['status']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <script src="js/admin.js"></script>
</body>
</html>