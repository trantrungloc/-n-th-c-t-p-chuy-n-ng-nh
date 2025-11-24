document.addEventListener("DOMContentLoaded", function () {
  const track = document.querySelector(".slider-track");
  const slides = Array.from(track.children);
  const dots = document.querySelectorAll(".dot");
  const prev = document.querySelector(".prev");
  const next = document.querySelector(".next");
  const slider = document.querySelector("#Slider");

  let index = 0;
  const slideCount = slides.length;
  const slideWidth = 100;

  // Clone đầu & cuối
  const firstClone = slides[0].cloneNode(true);
  const lastClone = slides[slideCount - 1].cloneNode(true);
  track.appendChild(firstClone);
  track.insertBefore(lastClone, slides[0]);

  let totalSlides = track.querySelectorAll("img").length;

  // Thiết lập vị trí ban đầu
  track.style.transform = `translateX(-${slideWidth}%)`;

  let isMoving = false; // ✅ Chặn spam click

  function goToSlide(i) {
    if (isMoving) return; // bỏ qua khi đang chuyển
    isMoving = true;

    index = i;
    track.style.transition = "transform 0.6s ease-in-out";
    track.style.transform = `translateX(-${(index + 1) * slideWidth}%)`;

    updateDots();

    // Cho phép click sau khi chuyển xong
    setTimeout(() => (isMoving = false), 650);
  }

  function updateDots() {
    dots.forEach(dot => dot.classList.remove("active"));
    const normalized = (index + slideCount) % slideCount;
    dots[normalized].classList.add("active");
  }

  // Khi chuyển xong → xử lý clone
  track.addEventListener("transitionend", () => {
    if (index === -1) {
      track.style.transition = "none";
      index = slideCount - 1;
      track.style.transform = `translateX(-${(index + 1) * slideWidth}%)`;
      requestAnimationFrame(() => {
        setTimeout(() => (track.style.transition = "transform 0.6s ease-in-out"), 20);
      });
    }
    if (index === slideCount) {
      track.style.transition = "none";
      index = 0;
      track.style.transform = `translateX(-${slideWidth}%)`;
      requestAnimationFrame(() => {
        setTimeout(() => (track.style.transition = "transform 0.6s ease-in-out"), 20);
      });
    }
  });

  // Nút điều hướng
  prev.addEventListener("click", () => goToSlide(index - 1));
  next.addEventListener("click", () => goToSlide(index + 1));

  // Dots
  dots.forEach((dot, i) => dot.addEventListener("click", () => goToSlide(i)));

  // Tự động chuyển ảnh
  let auto = setInterval(() => goToSlide(index + 1), 4000);

  // Dừng khi hover
  slider.addEventListener("mouseenter", () => clearInterval(auto));
  slider.addEventListener("mouseleave", () => {
    auto = setInterval(() => goToSlide(index + 1), 4000);
  });

  updateDots();
});







