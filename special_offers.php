<?php
require_once "config.php";

$active_offers = [];
// Fetch all offers that are current or have no end date
$sql = "SELECT id, title, description, image_path, start_date, end_date 
        FROM special_offers 
        WHERE end_date >= CURDATE() OR end_date IS NULL 
        ORDER BY created_at DESC";

if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $active_offers[] = $row;
    }
    $result->free();
}
$mysqli->close();

include 'includes/header.php';
?>

<link rel="stylesheet" href="css/special-offers-style.css">

<main id="main-content" role="main">
    <div class="hero">
        <h1>Special Offers & Deals</h1>
        <p>Discover our amazing promotions and save on your favorite meals!</p>
    </div>

    <div class="container">
        <h2 class="section-title">Current Promotions</h2>
        
        <div class="offers-grid">
            <?php if (empty($active_offers)): ?>
                <p class="no-offers">There are no special offers at the moment. Please check back soon!</p>
            <?php else: ?>
                <?php foreach ($active_offers as $offer): ?>
                    <article class="offer-card" data-aos="fade-up">
                        <div class="offer-image">
                            <img src="<?php echo htmlspecialchars($offer['image_path']); ?>" alt="<?php echo htmlspecialchars($offer['title']); ?>">
                        </div>
                        <div class="offer-content">
                            <h3 class="offer-title"><?php echo htmlspecialchars($offer['title']); ?></h3>
                            <p class="offer-description"><?php echo htmlspecialchars($offer['description']); ?></p>
                            
                            <?php if (!empty($offer['end_date'])): ?>
                                <div class="offer-validity">
                                    Valid until: <?php echo date("F j, Y", strtotime($offer['end_date'])); ?>
                                </div>
                            <?php endif; ?>

                            <a href="reservation.php" class="reserve-btn">Reserve Now</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>