<?php
session_start();
require_once "config.php";

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true) {
    header("location: admin_login.php");
    exit;
}

// Check if ID parameter exists
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // --- First, get the image path so we can delete the file ---
    $sql_select = "SELECT image_path FROM menu_items WHERE id = ?";
    if ($stmt_select = $mysqli->prepare($sql_select)) {
        $stmt_select->bind_param("i", $id);
        $stmt_select->execute();
        $stmt_select->bind_result($image_path);
        if ($stmt_select->fetch()) {
            // If an image path exists, delete the file from the server
            if (!empty($image_path) && file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $stmt_select->close();
    }
    
    // --- Now, delete the record from the database ---
    $sql_delete = "DELETE FROM menu_items WHERE id = ?";
    if ($stmt_delete = $mysqli->prepare($sql_delete)) {
        $stmt_delete->bind_param("i", $id);
        
        if ($stmt_delete->execute()) {
            // Record deleted successfully. Redirect to manage menu page.
            header("location: manage_menu.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt_delete->close();
    }
} else {
    // If no ID was provided, redirect
    header("location: manage_menu.php");
    exit();
}

$mysqli->close();
?>