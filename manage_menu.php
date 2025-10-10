<?php
session_start();
require_once "config.php";

// Auth check
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Define variables and initialize with empty values
$name = $description = $price = $category = $image_path = "";
$name_err = $price_err = $category_err = $image_err = "";

// --- Logic to ADD a new menu item with IMAGE UPLOAD ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {

    // Validate name, price, category
    if (empty(trim($_POST["name"]))) $name_err = "Name is required.";
    else $name = trim($_POST["name"]);

    if (empty(trim($_POST["price"]))) $price_err = "Price is required.";
    else $price = trim($_POST["price"]);

    if (empty(trim($_POST["category"]))) $category_err = "Category is required.";
    else $category = trim($_POST["category"]);

    $description = trim($_POST['description']);

    // --- Image Upload Validation and Logic ---
    if (empty($_FILES["image"]["name"])) {
        $image_err = "An image is required.";
    } else {
        $target_dir = "uploads/menu/";
        $unique_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $unique_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a real image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $image_err = "File is not a valid image.";
        }

        // Check file size (e.g., 2MB limit)
        if ($_FILES["image"]["size"] > 2000000) {
            $image_err = "Sorry, your file is too large (2MB limit).";
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $image_err = "Sorry, only JPG, JPEG, & PNG files are allowed.";
        }
    }

    // Check for errors before processing
    if (empty($name_err) && empty($price_err) && empty($category_err) && empty($image_err)) {
        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;

            // Proceed with database insert
            $sql = "INSERT INTO menu_items (name, description, price, category, image_path) VALUES (?, ?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssdss", $name, $description, $price, $category, $image_path);
                if ($stmt->execute()) {
                    // Set a success message in the session
                    $_SESSION['success_message'] = "Menu item added successfully!";

                    // Redirect to refresh the page
                    header("location: manage_menu.php");
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

// --- Logic to FETCH all menu items ---
$menu_items = [];
$sql_fetch = "SELECT * FROM menu_items ORDER BY category, name";
if ($result = $mysqli->query($sql_fetch)) {
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
    $result->free();
}
// Note: We don't close the connection here if the POST fails, so the page can still fetch items
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Admin</title>
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
                <h1>Manage Menu</h1>
            </header>

            <main class="admin-main">
                <section class="content-card">
                    <h2>Add New Menu Item</h2>
                    <form action="manage_menu.php" method="POST" class="add-item-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Dish Name</label>
                            <input type="text" id="name" name="name" required>
                            <span class="error-text"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="price">Price (₹)</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                            <span class="error-text"><?php echo $price_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <option value="">-- Select a Category --</option>
                                <option value="Starters">Starters</option>
                                <option value="Soups">Soups</option>
                                <option value="North Indian">North Indian</option>
                                <option value="South Indian">South Indian</option>
                                <option value="Rice & Noodles">Rice & Noodles</option>
                                <option value="Breads">Breads</option>
                                <option value="Desserts">Desserts</option>
                                <option value="Beverages">Beverages</option>
                            </select>
                            <span class="error-text"><?php echo $category_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Dish Image</label>
                            <input type="file" id="image" name="image" accept="image/png, image/jpeg" required>
                            <span class="error-text"><?php echo $image_err; ?></span>
                        </div>
                        <div class="form-group full-width">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" name="add_item" class="submit-btn">Add Item</button>
                    </form>
                </section>

                <section class="content-card">
                    <h2>Current Menu</h2>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($menu_items)): ?>
                                    <tr>
                                        <td colspan="5">No menu items have been added yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($menu_items as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                                            <td>₹<?php echo htmlspecialchars($item['price']); ?></td>
                                            <td class="actions">
                                                <a href="edit_menu_item.php?id=<?php echo $item['id']; ?>" class="edit-btn">Edit</a>
                                                <a href="delete_menu_item.php?id=<?php echo $item['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
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
    <?php
    if (isset($_SESSION['success_message'])) {
        // Echo the JavaScript to show a standard alert box
        echo "<script>alert('" . addslashes($_SESSION['success_message']) . "');</script>";

        // Unset the message so it doesn't show again on refresh
        unset($_SESSION['success_message']);
    }
    ?>
</body>

</html>