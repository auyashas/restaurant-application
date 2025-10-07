document.addEventListener('DOMContentLoaded', function() {

    // ===============================================
    // DATA: For now, our gallery data is hardcoded.
    // TODO: In the next step, we will remove this and load data from the database using PHP.
    // ===============================================
    const galleryData = [
        { id: 1, category: 'food', title: 'Malabar Fish Curry', description: 'Our signature dish featuring fresh catch of the day.', image: 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800&h=600&fit=crop', alt: 'A plate of Malabar fish curry' },
        { id: 2, category: 'ambiance', title: 'Elegant Dining Space', description: 'Our beautifully designed interior combines tradition with modern comfort.', image: 'https://images.unsplash.com/photo-1552566626-52f8b828add9?w=800&h=600&fit=crop', alt: 'Interior view of the restaurant' },
        { id: 3, category: 'food', title: 'Biryani Platter', description: 'Aromatic Malabar biryani layered with fragrant basmati rice.', image: 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&h=600&fit=crop', alt: 'Traditional biryani served in a vessel' },
        { id: 4, category: 'ambiance', title: 'Private Dining Area', description: 'An intimate space perfect for special celebrations.', image: 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=800&h=600&fit=crop', alt: 'Private dining room with elegant table setting' },
        { id: 5, category: 'food', title: 'Seafood Selection', description: 'Fresh catches from the Malabar coast, grilled to perfection.', image: 'https://images.unsplash.com/photo-1559737558-2f5a35f4523c?w=800&h=600&fit=crop', alt: 'Assorted grilled seafood platter' },
        { id: 6, category: 'events', title: 'Wedding Reception', description: 'Celebrating love with authentic Malabar flavors and service.', image: 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=800&h=600&fit=crop', alt: 'Banquet hall set up for a wedding' },
        { id: 7, category: 'food', title: 'Traditional Appetizers', description: 'An assortment of Kerala-style appetizers including banana chips and more.', image: 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=800&h=600&fit=crop', alt: 'Platter of various Malabar appetizers' },
        { id: 8, category: 'ambiance', title: 'Outdoor Seating', description: 'Dine under the stars in our charming outdoor patio area.', image: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=600&fit=crop', alt: 'Outdoor restaurant patio with string lights' }
    ];

    // ===============================================
    // SCRIPT LOGIC
    // ===============================================
    const grid = document.querySelector('.gallery-grid');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const modal = document.getElementById('modal');
    const modalImg = document.getElementById('modal-img');
    const modalTitle = document.getElementById('modal-title');
    const modalDesc = document.getElementById('modal-description');
    const closeModalBtn = document.querySelector('.modal-close');
    const prevModalBtn = document.querySelector('.modal-nav.prev');
    const nextModalBtn = document.querySelector('.modal-nav.next');

    let currentFilter = 'all';
    let filteredData = [];
    let currentImageIndex = 0;

    function renderGallery(filter = 'all') {
        currentFilter = filter;
        filteredData = (filter === 'all') ? galleryData : galleryData.filter(item => item.category === filter);
        grid.innerHTML = '';

        filteredData.forEach((item, index) => {
            const galleryItem = document.createElement('article');
            galleryItem.className = 'gallery-item';
            galleryItem.setAttribute('data-index', index);
            galleryItem.innerHTML = `
                <img src="${item.image}" alt="${item.alt}" loading="lazy">
                <span class="gallery-category">${item.category}</span>
                <div class="gallery-overlay">
                    <h3>${item.title}</h3>
                </div>
            `;
            galleryItem.addEventListener('click', () => openModal(index));
            grid.appendChild(galleryItem);
        });
    }

    function openModal(index) {
        currentImageIndex = index;
        const item = filteredData[index];
        modalImg.src = item.image;
        modalImg.alt = item.alt;
        modalTitle.textContent = item.title;
        modalDesc.textContent = item.description;
        modal.hidden = false;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        modal.hidden = true;
        document.body.style.overflow = 'auto';
    }

    function navigateModal(direction) {
        currentImageIndex += direction;
        if (currentImageIndex < 0) currentImageIndex = filteredData.length - 1;
        if (currentImageIndex >= filteredData.length) currentImageIndex = 0;
        openModal(currentImageIndex);
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            renderGallery(button.dataset.filter);
        });
    });

    closeModalBtn.addEventListener('click', closeModal);
    prevModalBtn.addEventListener('click', () => navigateModal(-1));
    nextModalBtn.addEventListener('click', () => navigateModal(1));
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (modal.classList.contains('active')) {
            if (e.key === 'Escape') closeModal();
            if (e.key === 'ArrowLeft') navigateModal(-1);
            if (e.key === 'ArrowRight') navigateModal(1);
        }
    });

    // Initial render
    renderGallery();
});