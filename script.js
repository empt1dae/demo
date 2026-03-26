const slides = document.querySelectorAll(".slide");
const prevButton = document.querySelector(".slider-arrow.prev");
const nextButton = document.querySelector(".slider-arrow.next");
const dots = document.querySelectorAll(".dot");

if (slides.length) {
    let index = 0;

    const showSlide = (nextIndex) => {
        slides[index].classList.remove("active");
        dots.forEach(dot => dot.classList.remove("active"));
        index = (nextIndex + slides.length) % slides.length;
        slides[index].classList.add("active");
        dots[index].classList.add("active");
    };

    if (prevButton) {
        prevButton.addEventListener("click", () => showSlide(index - 1));
    }

    if (nextButton) {
        nextButton.addEventListener("click", () => showSlide(index + 1));
    }

    dots.forEach((dot, i) => {
        dot.addEventListener("click", () => showSlide(i));
    });

    setInterval(() => showSlide(index + 1), 5000);
}