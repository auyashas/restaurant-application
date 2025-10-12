<?php
session_start();
require_once "config.php";

// Auth check
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Define variables and initialize with empty values
$title = $description = $start_date = $end_date = "";
$title_err = $image_err = "";

// --- Logic to ADD a new special offer ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_offer'])) {
    
    // Validate title
    if(empty(trim($_POST["title"]))) {
        $title_err = "Offer title is required.";
    } else {
        $title = trim($_POST['title']);
    }
    
    $description = trim($_POST['description']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    // --- Image Upload Validation (Required) ---
    if (empty($_FILES["image"]["name"])) {
        $image_err = "A promotional image is required.";
    } else {
        $target_dir = "uploads/offers/";
        // Create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $unique_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $unique_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check === false) {
            $image_err = "File is not a valid image.";
        }
        
        if ($_FILES["image"]["size"] > 2000000) { // 2MB limit
            $image_err = "Sorry, your file is too large (Max 2MB).";
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $image_err = "Sorry, only JPG, JPEG, & PNG files are allowed.";
        }
    }
    
    // Check for errors before processing
    if (empty($title_err) && empty($image_err)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;

            // Proceed with database insert
            $sql = "INSERT INTO special_offers (title, description, image_path, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sssss", $title, $description, $image_path, $start_date, $end_date);
                if($stmt->execute()){
                    $_SESSION['success_message'] = "New offer added successfully!";
                    header("location: manage_offers.php");
                    exit();
                } else {
                    echo "Database insert failed.";
                }
                $stmt->close();
            }
        } else {
            $image_err = "Sorry, there was an error uploading your file.";
        }
    }
}

// --- Logic to FETCH all special offers ---
$offers = [];
$sql_fetch = "SELECT * FROM special_offers ORDER BY id DESC";
if ($result = $mysqli->query($sql_fetch)) {
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
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header"><a href="index.php" class="sidebar-logo">The Malabar Table</a></div>
            <ul class="sidebar-nav">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_menu.php">Manage Menu</a></li>
                <li><a href="manage_offers.php" class="active">Special Offers</a></li>
                <li><a href="manage_admins.php">Manage Admins</a></li>
            </ul>
            <div class="sidebar-footer">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION["admin_username"]); ?></p>
                <a href="admin_logout.php">Logout</a>
            </div>
        </aside>

        <div class="admin-main-content">
            <header class="admin-header">
                <button class="mobile-nav-toggle">☰</button>
                <h1>Manage Special Offers</h1>
            </header>
            
            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="success-message">
                    <?php 
                        echo $_SESSION['success_message']; 
                        unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <main class="admin-main">
                <section class="content-card">
                    <div class="card-header">
                        <h2>Add New Offer</h2>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="add-item-form" enctype="multipart/form-data">
                        <div class="form-group full-width">
                            <label for="title">Offer Title</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                            <span class="error-text"><?php echo $title_err; ?></span>
                        </div>
                        <div class="form-group full-width">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date (Optional)</label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date (Optional)</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                        </div>
                        <div class="form-group full-width">
                            <label for="image">Promotional Image</label>
                            <input type="file" id="image" name="image" accept="image/png, image/jpeg" required>
                            <span class="error-text"><?php echo $image_err; ?></span>
                        </div>
                        <button type="submit" name="add_offer" class="submit-btn full-width">Add Offer</button>
                    </form>
                </section>

                <section class="content-card">
                    <div class="card-header">
                        <h2>Current Offers</h2>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($offers)): ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center;">No special offers have been added yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($offers as $item): ?>
                                        <tr>
                                            <td><img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="100"></td>
                                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($item['description'], 0, 50)) . '...'; ?></td>
                                            <td><?php echo htmlspecialchars($item['start_date'] ? date('M j, Y', strtotime($item['start_date'])) : 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($item['end_date'] ? date('M j, Y', strtotime($item['end_date'])) : 'N/A'); ?></td>
                                            <td class="actions">
                                                <a href="edit_offer.php?id=<?php echo $item['id']; ?>" class="edit-btn">Edit</a>
                                                <a href="delete_offer.php?id=<?php echo $item['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this offer?');">Delete</a>
                                            </td>
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