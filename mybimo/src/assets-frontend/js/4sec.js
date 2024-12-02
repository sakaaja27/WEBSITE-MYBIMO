const carousel = document.querySelector('.carousel');
const items = document.querySelectorAll('.carousel-item');
const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');

let currentIndex = 0;
const totalSlides = items.length - 1; // Number of total slide shifts possible
const itemsPerSlide = 2; // Number of items visible per slide

function updateCarousel() {
    const offset = -currentIndex * (100 / itemsPerSlide);
    carousel.style.transform = `translateX(${offset}%)`;
}

prevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex === 0) ? totalSlides : currentIndex - 1;
    updateCarousel();
});

nextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex === totalSlides) ? 0 : currentIndex + 1;
    updateCarousel();
});
