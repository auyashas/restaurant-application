<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="css/home-style.css">

<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">The Malabar Table</h1>
        <p class="hero-slogan">Savour the story in every bite.</p>
        <a href="reservation.php" class="button">Book a Table</a>
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

<section class="featured-dishes content-section bg-white" data-aos="fade-up" data-aos-duration="800">
    <div class="featured-dishes-container">
        <h2>Chef's Recommendations</h2>
        <div class="dishes-grid">
            <div class="dish-card">
                <img src="images/dish-1.jpg" alt="A plate of Malabar Fish Curry">
                <h3>Malabar Fish Curry</h3>
                <p>Our signature dish featuring fresh catch simmered in a rich, coconut-based curry.</p>
            </div>
            <div class="dish-card">
                <img src="images/dish-2.jpg" alt="A platter of Biryani">
                <h3>Biryani Platter</h3>
                <p>Aromatic Malabar biryani layered with fragrant basmati rice and exotic spices.</p>
            </div>
            <div class="dish-card">
                <img src="images/dish-3.jpg" alt="An assortment of appetizers">
                <h3>Traditional Appetizers</h3>
                <p>An assortment of Kerala-style appetizers including banana chips and fresh fish fry.</p>
            </div>
        </div>
        <a href="menu.php" class="button">View Full Menu</a>
    </div>
</section>

<section class="why-choose-us content-section bg-white" data-aos="fade-up" data-aos-duration="800">
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
                <blockquote>
                    "An unforgettable dining experience. The Malabar Fish Curry was the best I've ever had. The ambiance is perfect for a quiet, elegant evening."
                </blockquote>
                <cite>— Priya S.</cite>
            </div>
            <div class="testimonial-card">
                <blockquote>
                    "The attention to detail is remarkable. Every dish tells a story, just as their slogan says. A true gem in Udupi."
                </blockquote>
                <cite>— Rohan M.</cite>
            </div>
        </div>
    </div>
</section>

<section class="special-offers content-section" data-aos="fade-up" data-aos-duration="800">
    <div class="offers-container">
        <h2>This Week's Special Offers</h2>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">

                <div class="swiper-slide offer-card">
                    <img src="images/offer-1.jpg" alt="Lunch Combo">
                    <div class="offer-card-content">
                        <h3>Weekday Lunch Combo</h3>
                        <p>Main course, and drink. Mon-Fri, 12-3 PM.</p>
                    </div>
                </div>

                <div class="swiper-slide offer-card">
                    <img src="images/offer-2.jpg" alt="Dinner Special">
                    <div class="offer-card-content">
                        <h3>Festive Dinner Special</h3>
                        <p>Celebrate with our exclusive seasonal menu.</p>
                    </div>
                </div>

                <div class="swiper-slide offer-card">
                    <img src="images/offer-3.jpg" alt="Dessert Offer">
                    <div class="offer-card-content">
                        <h3>Dessert of the Day</h3>
                        <p>Ask our staff about today's sweet creation.</p>
                    </div>
                </div>

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>