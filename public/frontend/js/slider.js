let currentSlide = 0;
const slides = document.querySelectorAll('.slide-item');
const dots = document.querySelectorAll('.slide-dot');
const totalSlides = slides.length;
let slideInterval;

function showSlide(index) {
    if (index >= totalSlides) index = 0;
    if (index < 0) index = totalSlides - 1;
    
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.classList.remove('opacity-0', 'scale-105', 'z-0');
            slide.classList.add('opacity-100', 'scale-100', 'z-10');
        } else {
            slide.classList.remove('opacity-100', 'scale-100', 'z-10');
            slide.classList.add('opacity-0', 'scale-105', 'z-0');
        }
    });

    dots.forEach((dot, i) => {
        if (i === index) {
            dot.classList.add('bg-taupe-oak', 'w-8', 'border-taupe-oak');
            dot.classList.remove('bg-white/50');
        } else {
            dot.classList.remove('bg-taupe-oak', 'w-8', 'border-taupe-oak');
            dot.classList.add('bg-white/50');
        }
    });

    currentSlide = index;
}

function nextSlide() {
    showSlide(currentSlide + 1);
    resetInterval();
}

function prevSlide() {
    showSlide(currentSlide - 1);
    resetInterval();
}

function goToSlide(index) {
    showSlide(index);
    resetInterval();
}

function startInterval() {
    slideInterval = setInterval(nextSlide, 5000);
}

function resetInterval() {
    clearInterval(slideInterval);
    startInterval();
}

// Initialize carousel autoplay
document.addEventListener('DOMContentLoaded', () => {
    if (totalSlides > 0) {
        startInterval();
    }
});
