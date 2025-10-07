// Create floating particles
function createParticles() {
    const particlesContainer = document.getElementById('particles');
    if (!particlesContainer) return;
    const particleCount = 20;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
        particlesContainer.appendChild(particle);
    }
}

createParticles();

// Form validation and submission
const form = document.getElementById('contactForm');
const submitBtn = document.getElementById('submitBtn');
const successModal = document.getElementById('successModal');
const closeModal = document.getElementById('closeModal');

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

function validateForm() {
    let isValid = true;
    const fields = ['name', 'email', 'subject', 'message'];

    fields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        const errorSpan = document.getElementById(fieldId + 'Error');
        let fieldIsValid = true;

        if (fieldId === 'email') {
            if (!validateEmail(input.value.trim())) {
                fieldIsValid = false;
            }
        } else {
            if (input.value.trim() === '') {
                fieldIsValid = false;
            }
        }
        
        if (!fieldIsValid) {
            input.classList.add('error');
            if (errorSpan) errorSpan.style.display = 'block';
            isValid = false;
        } else {
            input.classList.remove('error');
            if (errorSpan) errorSpan.style.display = 'none';
        }
    });
    
    return isValid;
}

if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (validateForm()) {
            // TODO: Replace this simulation with a real fetch() call to a PHP script
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.querySelector('span').textContent = 'Sending...';

            // Simulate API call
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.querySelector('span').textContent = 'Send Message';

                successModal.classList.add('show');
                form.reset();
            }, 2000);
        }
    });
}

if(closeModal) {
    closeModal.addEventListener('click', () => successModal.classList.remove('show'));
    successModal.addEventListener('click', (e) => {
        if (e.target === successModal) {
            successModal.classList.remove('show');
        }
    });
}