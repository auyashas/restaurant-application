<?php
session_start();
require_once "config.php";

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Define variables
$name = $description = $price = $category = $image_path = "";
$id = 0;

// --- PROCESSING FORM DATA WHEN FORM IS SUBMITTED (UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    // --- Handle New Image Upload ---
    $new_image_path = $_POST['current_image']; // Keep old image by default
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        // If a new image is uploaded, process it
        $target_dir = "uploads/menu/";
        $unique_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $unique_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $new_image_path = $target_file;
            // If a new image is uploaded successfully, delete the old one
            if (!empty($_POST['current_image']) && file_exists($_POST['current_image'])) {
                unlink($_POST['current_image']);
            }
        }
    }

    // Prepare an update statement
    $sql = "UPDATE menu_items SET name=?, description=?, price=?, category=?, image_path=? WHERE id=?";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param(
            "ssdssi",
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['category'],
            $new_image_path,
            $id
        );

        if ($stmt->execute()) {
            header("location: manage_menu.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
} else {
    // --- FETCHING DATA TO PRE-FILL THE FORM (DISPLAY) ---
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);
        $sql = "SELECT * FROM menu_items WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    $name = $row["name"];
                    $description = $row["description"];
                    $price = $row["price"];
                    $category = $row["category"];
                    $image_path = $row["image_path"];
                } else {
                    echo "Error! No record found.";
                    exit();
                }
            } else {
                echo "Oops! Something went wrong.";
                exit();
            }
            $stmt->close();
        }
    } else {
        header("location: manage_menu.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Menu Item</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin-style.css">
</head>

<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header"><a href="index.php" class="sidebar-logo">The Malabar Table</a></div>
            <ul class="sidebar-nav">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_menu.php" class="active">Manage Menu</a></li>
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
                <h1>Edit Menu Item</h1>
            </header>
            <main class="admin-main">
                <section class="content-card">
                    <div class="card-header">
                        <h2>Update Item Details</h2>
                        <a href="manage_menu.php" class="cancel-link">Cancel</a>
                    </div>
                    <form action="edit_menu_item.php" method="POST" class="add-item-form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="current_image" value="<?php echo $image_path; ?>" />

                        <div class="form-group">
                            <label>Dish Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select id="category" name="category" required>
                                <option value="" disabled <?php if(empty($category)) echo 'selected'; ?>>-- Select a Category --</option>
                                <option value="Starters" <?php if($category == 'Starters') echo 'selected'; ?>>Starters</option>
                                <option value="Soups" <?php if($category == 'Soups') echo 'selected'; ?>>Soups</option>
                                <option value="North Indian" <?php if($category == 'North Indian') echo 'selected'; ?>>North Indian</option>
                                <option value="South Indian" <?php if($category == 'South Indian') echo 'selected'; ?>>South Indian</option>
                                <option value="Rice & Noodles" <?php if($category == 'Rice & Noodles') echo 'selected'; ?>>Rice & Noodles</option>
                                <option value="Breads" <?php if($category == 'Breads') echo 'selected'; ?>>Breads</option>
                                <option value="Desserts" <?php if($category == 'Desserts') echo 'selected'; ?>>Desserts</option>
                                <option value="Beverages" <?php if($category == 'Beverages') echo 'selected'; ?>>Beverages</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>New Dish Image (Optional)</label>
                            <input type="file" name="image" accept="image/png, image/jpeg">
                            <?php if (!empty($image_path)): ?>
                                <div class="image-preview">
                                    <p>Current Image:</p>
                                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Current Image" width="100">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group full-width">
                            <label>Description</label>
                            <textarea name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Update Item</button>
                    </form>
                </section>
            </main>
        </div>
    </div>
    <script src="js/admin.js"></script>
</body>

</html>