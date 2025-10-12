<?php
session_start();
require_once "config.php";

// Auth check
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Check if ID is provided in URL
if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    die("Invalid request: No offer ID provided.");
}

$offer_id = trim($_GET["id"]);
$title = $description = $start_date = $end_date = $current_image_path = "";
$title_err = $image_err = "";

// --- Logic to FETCH the existing offer data ---
$sql_fetch = "SELECT * FROM special_offers WHERE id = ?";
if ($stmt_fetch = $mysqli->prepare($sql_fetch)) {
    $stmt_fetch->bind_param("i", $offer_id);
    if ($stmt_fetch->execute()) {
        $result = $stmt_fetch->get_result();
        if ($result->num_rows == 1) {
            $offer = $result->fetch_assoc();
            $title = $offer['title'];
            $description = $offer['description'];
            $start_date = $offer['start_date'];
            $end_date = $offer['end_date'];
            $current_image_path = $offer['image_path'];
        } else {
            die("No offer found with ID: " . $offer_id);
        }
    } else {
        die("Oops! Something went wrong while fetching data.");
    }
    $stmt_fetch->close();
}


// --- Logic to UPDATE the offer on POST request ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_offer'])) {
    
    // Validate title
    if(empty(trim($_POST["title"]))) {
        $title_err = "Offer title is required.";
    } else {
        $title = trim($_POST['title']);
    }
    
    $description = trim($_POST['description']);
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    
    // --- Image Upload Validation (Now required) ---
    if (empty($_FILES["image"]["name"])) {
        $image_err = "A new promotional image is required to update.";
    } else {
        $target_dir = "uploads/offers/";
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
    
    // Check for errors before updating database
    if (empty($title_err) && empty($image_err)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Delete old image file
            if (!empty($current_image_path) && file_exists($current_image_path)) {
                unlink($current_image_path);
            }
            $new_image_path = $target_file; 

            $sql_update = "UPDATE special_offers SET title = ?, description = ?, image_path = ?, start_date = ?, end_date = ? WHERE id = ?";
            if ($stmt_update = $mysqli->prepare($sql_update)) {
                $stmt_update->bind_param("sssssi", $title, $description, $new_image_path, $start_date, $end_date, $offer_id);
                if($stmt_update->execute()){
                    header("location: manage_offers.php");
                    exit();
                } else {
                    echo "Database update failed.";
                }
                $stmt_update->close();
            }
        } else {
            $image_err = "Sorry, there was an error uploading your new file.";
        }
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Special Offer - Admin</title>
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
                <h1>Edit Special Offer</h1>
            </header>

            <main class="admin-main">
                <section class="content-card">
                    <div class="card-header">
                        <h2>Update Offer Details</h2>
                        <a href="manage_offers.php" class="cancel-link">Cancel</a>
                    </div>
                    
                    <form action="edit_offer.php?id=<?php echo $offer_id; ?>" method="POST" class="add-item-form" enctype="multipart/form-data">
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
                            <label for="image">New Promotional Image</label>
                            <p style="margin: 5px 0;">Current Image:</p>
                            <img src="<?php echo htmlspecialchars($current_image_path); ?>" alt="Current Image" width="150" class="image-preview">
                            <br><br>
                            <input type="file" id="image" name="image" accept="image/png, image/jpeg" required>
                            <span class="error-text"><?php echo $image_err; ?></span>
                        </div>
                        <button type="submit" name="update_offer" class="submit-btn full-width">Update Offer</button>
                    </form>
                </section>
            </main>
        </div>
    </div>
    <script src="js/admin.js"></script>
</body>
</html>