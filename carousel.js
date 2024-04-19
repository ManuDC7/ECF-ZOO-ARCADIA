let slidePosition = 1;
slideShow(slidePosition);

function plusSlides(n) {
    slideShow(slidePosition += n);
}

function slideShow(n) {
    const slides = document.getElementsByClassName("service");
    if (n > slides.length) {
        slidePosition = 1;
    }
    if (n < 1) {
        slidePosition = slides.length;
    }
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slidePosition - 1].style.display = "block";
}