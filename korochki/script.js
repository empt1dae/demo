const slides = document.querySelectorAll(".slide");
const prevButton = document.querySelector(".slider-arrow.prev");
const nextButton = document.querySelector(".slider-arrow.next");

if (slides.length) {
    let index = 0;

    const showSlide = (nextIndex) => {
        slides[index].classList.remove("active");
        index = (nextIndex + slides.length) % slides.length;
        slides[index].classList.add("active");
    };

    if (prevButton) {
        prevButton.addEventListener("click", () => showSlide(index - 1));
    }

    if (nextButton) {
        nextButton.addEventListener("click", () => showSlide(index + 1));
    }
}
