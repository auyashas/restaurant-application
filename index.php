<?php
// Include the database config file. Ensure the path is correct.
require_once "config.php";

// --- Fetch Chef's Recommendations (Menu Items) ---
$menu_items = [];
// Fetches 7 random items to showcase a variety of dishes
$sql_menu = "SELECT name, description, image_path FROM menu_items ORDER BY RAND() LIMIT 7";
if ($result_menu = $mysqli->query($sql_menu)) {
    while ($row = $result_menu->fetch_assoc()) {
        $menu_items[] = $row;
    }
    $result_menu->free();
}

// --- Fetch Active Special Offers ---
$special_offers = [];
// This query correctly selects offers that are permanent (no dates) or currently active.
$sql_offers = "SELECT title, description, image_path FROM special_offers WHERE (start_date IS NULL OR start_date <= CURDATE()) AND (end_date IS NULL OR end_date >= CURDATE()) ORDER BY id DESC LIMIT 5";
if ($result_offers = $mysqli->query($sql_offers)) {
    while ($row = $result_offers->fetch_assoc()) {
        $special_offers[] = $row;
    }
    $result_offers->free();
}

$mysqli->close();
?>
<?php include 'includes/header.php'; ?>

<!-- Add SwiperJS CSS for sliders -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="css/home-style.css">
<!-- You mentioned you will add faq.css to the header, which is the correct place -->

<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">The Malabar Table</h1>
        <p class="hero-slogan">Savour the story in every bite.</p>
        <a href="reservation.php" class="button">Book a Table</a>
    </div>
</section>

<!-- DYNAMIC SPECIAL OFFERS SECTION -->
<section class="special-offers content-section bg-white" data-aos="fade-up" data-aos-duration="800">
    <div class="offers-container">
        <h2>This Week's Special Offers</h2>
        <?php if (!empty($special_offers)): ?>
            <div class="swiper offersSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($special_offers as $offer): ?>
                    <div class="swiper-slide offer-card">
                        <img src="<?php echo htmlspecialchars($offer['image_path']); ?>" alt="<?php echo htmlspecialchars($offer['title']); ?>">
                        <div class="offer-card-content">
                            <h3><?php echo htmlspecialchars($offer['title']); ?></h3>
                            <p><?php echo htmlspecialchars($offer['description']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php else: ?>
            <p>There are currently no special offers. Please check back soon!</p>
        <?php endif; ?>
    </div>
</section>

<section class="home-about content-section" data-aos="fade-up" data-aos-duration="800">
    <div class="home-about-container">
        <div class="about-image">
            <img src="images/about-us-image.jpg" alt="Warm and inviting interior of The Malabar Table restaurant">
        </div>
        <div class="about-text">
            <h2>Our Story</h2>
            <p>Born from a passion for authentic Malabar cuisine, we are storytellers preserving centuries-old recipes passed down through generations while creating new memories with every dish we serve.</p>
            <a href="about.php" class="button">Learn More</a>
        </div>
    </div>
</section>

<!-- DYNAMIC FEATURED DISHES SECTION -->
<section class="featured-dishes content-section bg-white" data-aos="fade-up" data-aos-duration="800">
    <div class="featured-dishes-container">
        <h2>Chef's Recommendations</h2>
        <?php if (!empty($menu_items)): ?>
            <div class="swiper menuSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($menu_items as $item): ?>
                        <div class="swiper-slide dish-card">
                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                    <div class="swiper-slide dish-card dish-card-link">
                        <a href="menu.php" class="dish-card-link-content">
                            <h3>View Full Menu</h3>
                            <p>Explore all of our authentic Malabar dishes.</p>
                            <span class="view-menu-icon">→</span>
                        </a>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        <?php else: ?>
            <p>Our Chef is currently preparing recommendations. Please check back soon!</p>
        <?php endif; ?>
    </div>
</section>



<section class="why-choose-us content-section" data-aos="fade-up" data-aos-duration="800">
     <div class="why-choose-us-container">
        <h2>Why The Malabar Table?</h2>
        <div class="features-grid">
            <div class="feature-item" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">🌿</div>
                <h3>Authentic Recipes</h3>
                <p>Taste the legacy of Malabar with recipes passed down through generations.</p>
            </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">🐟</div>
                <h3>Fresh Ingredients</h3>
                <p>We source the freshest local produce and coastal catches daily for unmatched quality.</p>
            </div>
            <div class="feature-item" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">✨</div>
                <h3>Elegant Ambiance</h3>
                <p>Dine in a setting that blends traditional charm with modern comfort and elegance.</p>
            </div>
        </div>
    </div>
</section>

<section class="testimonials content-section" data-aos="fade-up" data-aos-duration="800">
    <div class="testimonials-container">
        <h2>What Our Guests Say</h2>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <blockquote>"An unforgettable dining experience. The Malabar Fish Curry was the best I've ever had. The ambiance is perfect for a quiet, elegant evening."</blockquote>
                <cite>— Priya S.</cite>
            </div>
            <div class="testimonial-card">
                <blockquote>"The attention to detail is remarkable. Every dish tells a story, just as their slogan says. A true gem in Udupi."</blockquote>
                <cite>— Rohan M.</cite>
            </div>
        </div>
    </div>
</section>

<!-- =================================== -->
<!-- UPDATED: FULL FAQ SECTION         -->
<!-- =================================== -->
<section class="home-faq content-section bg-white" data-aos="fade-up" data-aos-duration="800">
    <div class="home-faq-container">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-accordion">
            <div class="faq-item">
                <h3>
                    <button class="faq-question" aria-expanded="false" aria-controls="faq1-answer">
                        <span>How do I make a reservation?</span>
                        <span class="faq-icon" aria-hidden="true">+</span>
                    </button>
                </h3>
                <div class="faq-answer" id="faq1-answer">
                    <p>You can make a reservation through our online booking system. For groups of 8 or more, please call us directly to ensure we can accommodate your party.</p>
                </div>
            </div>
            <div class="faq-item">
                <h3>
                    <button class="faq-question" aria-expanded="false" aria-controls="faq2-answer">
                        <span>Do you accommodate dietary restrictions?</span>
                        <span class="faq-icon" aria-hidden="true">+</span>
                    </button>
                </h3>
                <div class="faq-answer" id="faq2-answer">
                    <p>Absolutely! We offer vegetarian, vegan, and gluten-free options. Please inform our staff about any allergies or restrictions when making your reservation or when ordering.</p>
                </div>
            </div>
            <div class="faq-item">
                <h3>
                    <button class="faq-question" aria-expanded="false" aria-controls="faq3-answer">
                        <span>What is your cancellation policy?</span>
                        <span class="faq-icon" aria-hidden="true">+</span>
                    </button>
                </h3>
                <div class="faq-answer" id="faq3-answer">
                    <p>You can cancel or modify your reservation up to 4 hours before your booking time. For group bookings, a 24-hour notice is required.</p>
                </div>
            </div>
            <div class="faq-item">
                <h3>
                    <button class="faq-question" aria-expanded="false" aria-controls="faq4-answer">
                        <span>Is there parking available?</span>
                        <span class="faq-icon" aria-hidden="true">+</span>
                    </button>
                </h3>
                <div class="faq-answer" id="faq4-answer">
                    <p>Yes, we have complimentary valet parking available for all our guests. There is also street parking nearby.</p>
                </div>
            </div>
        </div>
        <div class="contact-cta">
            <h3>Still Have Questions?</h3>
            <p>Our team is here to help. Don't hesitate to reach out!</p>
            <a href="contact.php" class="button">Contact Us</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<!-- Add SwiperJS script for sliders -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<!-- Add FAQ script -->
<script src="js/faq.js"></script>

<!-- Initialize Swipers -->
<script>
    // Initialize Chef's Recommendations Slider
    var menuSwiper = new Swiper(".menuSwiper", {
        slidesPerView: 1, spaceBetween: 20, grabCursor: true,
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        breakpoints: { 768: { slidesPerView: 2, spaceBetween: 30 }, 1024: { slidesPerView: 3, spaceBetween: 30 } }
    });

    // Initialize Special Offers Autoplay Slideshow
    const offerSlides = document.querySelectorAll('.offersSwiper .swiper-slide');
    if (offerSlides.length > 1) {
        var offersSwiper = new Swiper(".offersSwiper", {
            spaceBetween: 30, centeredSlides: true, loop: true, effect: 'fade',
            autoplay: { delay: 3000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true },
        });
    }
</script>

