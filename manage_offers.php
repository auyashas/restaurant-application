<?php
session_start();
require_once "config.php";

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// --- Logic to ADD a new special offer ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_offer'])) {
    
    // --- Image Upload Logic ---
    $image_path = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uploads/offers/"; // Create this folder!
        $unique_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $unique_name;
        
        // Basic validation
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        }
    }

    // --- Database Insert Logic ---
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    if (!empty($title)) {
        $sql = "INSERT INTO special_offers (title, description, image_path, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssss", $title, $description, $image_path, $start_date, $end_date);
            $stmt->execute();
            $stmt->close();
            header("location: manage_offers.php");
            exit();
        }
    }
}

// --- Logic to FETCH all special offers ---
$offers = [];
$sql = "SELECT * FROM special_offers ORDER BY start_date DESC";
if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $offers[] = $row;
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
    <title>Manage Special Offers - Admin</title>
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
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_menu.php">Manage Menu</a></li>
                <li><a href="manage_offers.php" class="active">Special Offers</a></li>
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
                <h1>Manage Special Offers</h1>
            </header>

            <main class="admin-main">
                <section class="content-card">
                    <h2>Add New Offer</h2>
                    <form action="manage_offers.php" method="POST" class="add-item-form" enctype="multipart/form-data">
                        <div class="form-group full-width">
                            <label for="title">Offer Title</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        <div class="form-group full-width">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date (Optional)</label>
                            <input type="date" id="start_date" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date (Optional)</label>
                            <input type="date" id="end_date" name="end_date">
                        </div>
                        <div class="form-group full-width">
                            <label for="image">Promotional Image (Optional)</label>
                            <input type="file" id="image" name="image" accept="image/png, image/jpeg">
                        </div>
                        <button type="submit" name="add_offer" class="submit-btn">Add Offer</button>
                    </form>
                </section>

                <section class="content-card">
                    <h2>Current & Past Offers</h2>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Starts</th>
                                    <th>Ends</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($offers as $offer): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($offer['title']); ?></td>
                                        <td><?php echo !empty($offer['start_date']) ? date("d M Y", strtotime($offer['start_date'])) : 'N/A'; ?></td>
                                        <td><?php echo !empty($offer['end_date']) ? date("d M Y", strtotime($offer['end_date'])) : 'N/A'; ?></td>
                                        <td class="actions">
                                            <a href="#" class="edit-btn">Edit</a>
                                            <a href="#" class="delete-btn">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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