<?php
session_start();
require_once "config.php";

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// --- Logic to ADD a new menu item with IMAGE UPLOAD ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    
    // --- Image Upload Logic ---
    $image_path = ""; // Default to empty path if no image is uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uploads/menu/"; // IMPORTANT: Create this folder!
        
        // Create a unique filename to prevent files from being overwritten
        $unique_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $unique_name;
        
        // Basic validation
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Attempt to move the uploaded file to your folder
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file; // This is the path we'll save in the database
            } else {
                // Handle upload error if needed
            }
        } else {
            // Handle error if file is not an image
        }
    }

    // --- Database Insert Logic ---
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);

    if (!empty($name) && !empty($price) && !empty($category)) {
        $sql = "INSERT INTO menu_items (name, description, price, category, image_path) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssdss", $name, $description, $price, $category, $image_path);
            $stmt->execute();
            $stmt->close();
            // Redirect to refresh the page and show the new item
            header("location: manage_menu.php");
            exit();
        }
    }
}

// --- Logic to FETCH all menu items ---
$menu_items = [];
$sql = "SELECT * FROM menu_items ORDER BY category, name";
if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
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
    <title>Manage Menu - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
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
                <li><a href="#">Manage Admins</a></li>
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
                        </div>
                        <div class="form-group">
                            <label for="price">Price ₹</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" required>
                                <option value="Appetizer">Appetizer</option>
                                <option value="Main Course">Main Course</option>
                                <option value="Dessert">Dessert</option>
                                <option value="Beverage">Beverage</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Dish Image</label>
                            <input type="file" id="image" name="image" accept="image/png, image/jpeg">
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
                                <?php foreach ($menu_items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td>₹<?php echo htmlspecialchars($item['price']); ?></td>
                                        <td class="actions">
                                            <a href="edit_menu_item.php?id=<?php echo $item['id']; ?>" class="edit-btn">Edit</a>
                                            <a href="delete_menu_item.php?id=<?php echo $item['id']; ?>" class="delete-btn">Delete</a>
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