const slides = document.querySelectorAll('.slide');
const prev = document.querySelector('.prev');
const next = document.querySelector('.next');
let currentSlide = 0; 1 

const showSlide = (n) => {
  slides[currentSlide].style.marginLeft = '-100%';
  currentSlide = (n + slides.length) % slides.length;
  slides[currentSlide].style.marginLeft = '0';
};

prev.addEventListener('click', () => {
  showSlide(currentSlide - 1);
});

next.addEventListener('click', () => {
  showSlide(currentSlide + 1);
});