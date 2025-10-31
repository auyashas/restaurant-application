<?php
// This PHP block will handle the form submission.
// For now, it just sanitizes the data.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST["subject"]), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_STRING);

    // Basic validation
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($subject) && !empty($message)) {
        // In a real application, you would send an email here.
        // For example: mail("your-email@example.com", "Message from $name: $subject", $message);
        
        // The JavaScript will handle showing the success message.
    }
}

// Include the header AFTER the processing block
include 'includes/header.php';
?>

<link rel="stylesheet" href="css/contact-style.css">

<div class="contact-page-wrapper">
    <div class="particles" id="particles"></div>

    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info">
                <div class="info-content">
                    <h2>Let's Talk</h2>
                    <p>Have a question or want to book a private event? We'd love to hear from you.</p>

                    <div class="info-item">
                        <div class="info-icon">📍</div>
                        <div class="info-details">
                            <h4>Visit Us</h4>
                            <p>123 Gourmet Street<br>Udupi, Karnataka 576101</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">📞</div>
                        <div class="info-details">
                            <h4>Call Us</h4>
                            <p>+91 98765 43210</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">✉️</div>
                        <div class="info-details">
                            <h4>Email Us</h4>
                            <p>info@themalabartable.com</p>
                        </div>
                    </div>
                </div>

                <div class="social-links">
                    <div class="social-icon" title="Facebook">f</div>
                    <div class="social-icon" title="Instagram">i</div>
                    <div class="social-icon" title="Twitter">t</div>
                </div>
            </div>

            <div class="contact-form">
                <div class="form-header">
                    <h3>Send us a message</h3>
                    <p>Fill out the form and we'll get back to you soon.</p>
                </div>

                <form id="contactForm" action="contact.php" method="POST">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" placeholder="Your Name">
                        <span class="error-message" id="nameError">Please enter your name</span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="abc@example.com">
                        <span class="error-message" id="emailError">Please enter a valid email</span>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject">
                            <option value="">Choose a subject</option>
                            <option value="General Inquiry">General Inquiry</option>
                            <option value="Reservation Question">Reservation Question</option>
                            <option value="Feedback">Feedback</option>
                        </select>
                        <span class="error-message" id="subjectError">Please select a subject</span>
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" placeholder="Tell us what's on your mind..."></textarea>
                         <span class="error-message" id="messageError">Please enter your message</span>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span>Send Message</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="success-modal" id="successModal">
    <div class="success-content">
        <div class="success-icon">✓</div>
        <h3>Message Sent!</h3>
        <p>Thank you for contacting us. We'll get back to you shortly.</p>
        <button class="close-btn" id="closeModal">Close</button>
    </div>
</div>

<script src="js/contact-form.js"></script>

<?php include 'includes/footer.php'; ?>