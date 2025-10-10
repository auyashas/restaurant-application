<?php
session_start();
require_once "config.php";

// --- SECURITY CHECK: SUPER ADMIN ONLY ---
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}
if ($_SESSION["admin_role"] !== 'super_admin') {
    // If not a super_admin, show an access denied message and exit
    echo "Access Denied. You do not have permission to view this page.";
    exit;
}

// --- Logic to DELETE an admin ---
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $id_to_delete = trim($_GET['delete_id']);

    // --- SECURITY CHECK: Prevent super_admin from deleting their own account ---
    if ($id_to_delete == $_SESSION['admin_id']) {
        // Optionally, set an error message to display
    } else {
        $sql = "DELETE FROM admins WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $id_to_delete);
            $stmt->execute();
            $stmt->close();
            header("location: manage_admins.php");
            exit();
        }
    }
}

// --- Logic to ADD a new admin ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (!empty($username) && !empty($password) && !empty($role)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sss", $username, $hashed_password, $role);
            $stmt->execute();
            $stmt->close();
            header("location: manage_admins.php");
            exit();
        }
    }
}

// --- Logic to FETCH all admins ---
$admins = [];
$sql = "SELECT id, username, role FROM admins";
if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
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
    <title>Manage Admins - Admin Panel</title>
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header"><a href="index.php" class="sidebar-logo">The Malabar Table</a></div>
            <ul class="sidebar-nav">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_menu.php">Manage Menu</a></li>
                <li><a href="manage_offers.php">Special Offers</a></li>
                <li><a href="manage_admins.php" class="active">Manage Admins</a></li>
            </ul>
            <div class="sidebar-footer">
                <p><?php echo htmlspecialchars($_SESSION["admin_username"]); ?></p>
                <a href="admin_logout.php">Logout</a>
            </div>
        </aside>

        <div class="admin-main-content">
            <header class="admin-header">
                <button class="mobile-nav-toggle">☰</button>
                <h1>Manage Admins</h1>
            </header>
            <main class="admin-main">
                <section class="content-card">
                    <h2>Add New Admin</h2>
                    <form action="manage_admins.php" method="POST" class="add-item-form">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select id="role" name="role" required>
                                <option value="staff">Staff</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <button type="submit" name="add_admin" class="submit-btn full-width">Add Admin</button>
                    </form>
                </section>

                <section class="content-card">
                    <h2>Current Admins</h2>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admins as $admin): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($admin['id']); ?></td>
                                        <td><?php echo htmlspecialchars($admin['username']); ?></td>
                                        <td><?php echo htmlspecialchars($admin['role']); ?></td>
                                        <td class="actions">
                                            <?php if ($admin['id'] == $_SESSION['admin_id']): ?>
                                                <span class="delete-btn disabled">Delete</span>
                                            <?php else: ?>
                                                <a href="manage_admins.php?delete_id=<?php echo $admin['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                                            <?php endif; ?>
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