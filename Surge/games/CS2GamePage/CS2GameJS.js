document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('mousemove', (event) => {
        const cursor = document.querySelector('.custom-cursor');
        cursor.style.left = `${event.pageX}px`;
        cursor.style.top = `${event.pageY}px`;
    });
});

let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("main-slide");
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex - 1].style.display = "block";
}
