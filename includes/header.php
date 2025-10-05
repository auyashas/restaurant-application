<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>The Malabar Table</title>
    <script>
        function logout() {
            confirm("Are you sure you want to logout?");
            window.location.href = "logout.php";
        }
    </script>
</head>

<body>
    <header>
        <a href="index.php" class="logo"><h1>The Malabar Table</h1></a>

        <button class="hamburger-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <nav class="nav-links">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="special_offers.php">Special Offers</a></li>
                <?php if (isset($_SESSION['email'])) { ?>
                    <li><a href="reservation.php">Reserve a Table</a></li>
                    <li><a href="dashboard.php">My Dashboard</a></li>
                <?php } else { ?>
                    <li><a href="login.html">Reserve a Table</a></li>
                <?php } ?>
                
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <?php if (isset($_SESSION['email'])) { ?>
                    <li><a href="logout.php" class="button" onclick=logout()>Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="button">Login</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>