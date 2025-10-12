<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Add SwiperJS CSS for sliders -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/home-style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/about-faq-style.css">
    <title>The Malabar Table</title>
</head>

<body class="<?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'home-page'; ?>">
    <header>
        <a href="index.php" class="logo">The Malabar Table</a>
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
                <?php if ($is_logged_in) { ?>
                    <li class="dropdown">
                        <a href="#">Reservation</a>
                        <ul class="dropdown-content">
                            <li><a href="reservation.php">Reserve a Table</a></li>
                            <li><a href="dashboard.php">My Dashboard</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li><a href="login.php">Reservation</a></li>
                <?php } ?>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <?php if ($is_logged_in) { ?>
                    <li><a href="logout.php" class="button logout-btn">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php" class="button">Login</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>

    <main class="main-content">