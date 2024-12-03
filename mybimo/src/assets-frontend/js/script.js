// Get the carousel container and inner elements
const carouselContainer = document.querySelector('.carousel-container');
const carouselInner = document.querySelector('.carousel-inner');
const carouselItems = document.querySelectorAll('.carousel-item');

// Function to set the height of the carousel container
function setCarouselHeight() {
    // Get the height of the active carousel item
    const activeItem = document.querySelector('.carousel-item.active');
    const activeItemHeight = activeItem.offsetHeight;

    // Set the height of the carousel container
    carouselContainer.style.height = `${activeItemHeight}px`;
}

// Call the function when the page loads
setCarouselHeight();

// Call the function when the carousel slides
document.addEventListener('DOMContentLoaded', function() {
    carouselInner.addEventListener('slide.bs.carousel', function() {
        setCarouselHeight();
    });
});