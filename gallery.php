<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="css/gallery-style.css">

<main id="main-content" role="main">
    <div class="container">
        <div class="gallery-header">
            <h1>Our Gallery</h1>
            <p>Explore the flavors, ambiance, and moments that make The Malabar Table a unique dining experience.</p>
        </div>

        <nav class="filter-nav" role="navigation" aria-label="Gallery filters">
            <button class="filter-btn active" data-filter="all" aria-pressed="true">All</button>
            <button class="filter-btn" data-filter="food" aria-pressed="false">Dishes</button>
            <button class="filter-btn" data-filter="ambiance" aria-pressed="false">Ambiance</button>
            <button class="filter-btn" data-filter="events" aria-pressed="false">Events</button>
        </nav>

        <div class="gallery-grid" role="region" aria-label="Photo gallery">
            </div>
    </div>
</main>

<div class="modal" id="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" hidden>
    <div class="modal-content">
        <button class="modal-close" aria-label="Close modal"></button>
        <button class="modal-nav prev" aria-label="Previous image"></button>
        <button class="modal-nav next" aria-label="Next image"></button>
        <img class="modal-image" src="" alt="" id="modal-img">
        <div class="modal-info">
            <h3 id="modal-title"></h3>
            <p id="modal-description"></p>
        </div>
    </div>
</div>

<script src="js/gallery.js"></script>

<?php include 'includes/footer.php'; ?>