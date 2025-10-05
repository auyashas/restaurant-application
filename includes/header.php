<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Malabar Table</title>
</head>

<body>
    <header>
        <h1>The Malabar Table</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Menu</a></li>
                <li><a href="#">Special Offers</a></li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <div class="dropdown">
                        <li>Reserve</li>
                        <div class="dropdown-content">
                            <a href="reservation.php">Reserve a Table</a>
                            <a href="dashboard.php">Check Reservation</a>
                        </div>
                    </div>
                <?php } ?>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])) { ?>
                    <a href="logout.php" class="logout-btn">Logout</a>
                <?php }else {?>
                    <a href="login.php" class="login-btn">Login</a>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>