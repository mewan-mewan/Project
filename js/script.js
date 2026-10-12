document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let currentSlide = 0;
    const slideCount = slides.length;

    function showSlide(index) {
        slides.forEach((slide, idx) => {
            slide.classList.toggle('active', idx === index);
        });
        dots.forEach((dot, idx) => {
            dot.classList.toggle('active', idx === index);
        });
        currentSlide = index;
    }

    function nextSlide() {
        let nextIndex = currentSlide + 1;
        if (nextIndex >= slideCount) nextIndex = 0;
        showSlide(nextIndex);
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', function () {
            showSlide(idx);
        });
    });

    showSlide(0);
    if (slideCount > 1) {
        setInterval(nextSlide, 5000);
    }
});
