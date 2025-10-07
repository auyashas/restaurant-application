<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="css/about-faq-style.css">

<main id="main-content" class="page-container">
    <section class="faq-header">
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about dining with us.</p>
    </section>

    <div class="faq-container">
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
</main>

<script src="js/faq.js"></script>

<?php include 'includes/footer.php'; ?>