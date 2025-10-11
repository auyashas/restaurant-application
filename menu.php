<?php
require_once "config.php";

$menu_by_category = [];
// Fetch all menu items from the database, ordered by category
$sql = "SELECT id, name, description, price, category, image_path FROM menu_items ORDER BY category, name";

if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        // Group items by their category in a nested array
        $menu_by_category[$row['category']][] = $row;
    }
    $result->free();
}
$mysqli->close();

include 'includes/header.php';
?>

<link rel="stylesheet" href="css/menu-style.css">

<main id="main-content" role="main">
    <div class="menu-page-header">
        <h1>Our Menu</h1>
        <p>Savour the story in every bite</p>
    </div>

    <div class="container">
        <nav class="category-nav" role="navigation" aria-label="Menu categories">
            <div class="category-buttons">
                <?php foreach ($menu_by_category as $category => $items): ?>
                    <a href="#<?php echo strtolower(str_replace(' ', '-', $category)); ?>" class="category-btn"><?php echo htmlspecialchars($category); ?></a>
                <?php endforeach; ?>
            </div>
        </nav>

        <?php foreach ($menu_by_category as $category => $items): ?>
            <section id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>" class="menu-section" aria-labelledby="<?php echo strtolower(str_replace(' ', '-', $category)); ?>-heading">
                <div class="section-header">
                    <h2 id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>-heading"><?php echo htmlspecialchars($category); ?></h2>
                </div>
                <div class="menu-grid">
                    <?php foreach ($items as $item): ?>
                        <article class="menu-item" data-aos="fade-up" data-aos-duration="600">
                            <?php if (!empty($item['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                            <?php endif; ?>
                            <div class="item-content">
                                <div class="item-header">
                                    <h3 class="item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <span class="item-price" aria-label="Price">₹<?php echo htmlspecialchars($item['price']); ?></span>
                                </div>
                                <p class="item-description"><?php echo htmlspecialchars($item['description']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
</main>

<script src="js/menu.js"></script>

<?php include 'includes/footer.php'; ?>