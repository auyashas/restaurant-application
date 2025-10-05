<?php
// 1. Initialize the session
session_start();

// 2. Unset all of the session variables
session_unset();

// 3. Destroy the session
session_destroy();

// 4. Redirect to the homepage
header("location: index.php");
exit;
?>