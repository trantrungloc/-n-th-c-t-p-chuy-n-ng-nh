class ZaraSlider {
    constructor() {
        this.slider = null;
        this.track = null;
        this.isInitialized = false;
        this.autoInterval = null;
        this.currentIndex = 0;
        this.slideCount = 0;
        this.slideWidth = 100;
        this.isMoving = false;
        
        this.init();
    }

    init() {
        console.log("ZaraSlider: Starting initialization...");
        
        // Khởi tạo slider ngay lập tức
        this.initializeSlider();
        
        // Theo dõi click trên menu
        document.addEventListener('click', (e) => {
            if (e.target.closest('.menu a') || e.target.closest('.sub-menu a')) {
                console.log("Menu clicked, reinitializing slider...");
                setTimeout(() => this.reinitialize(), 100);
            }
        });

        // Theo dõi thay đổi DOM
        this.setupDOMObserver();
    }

    setupDOMObserver() {
        this.observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    const currentSlider = document.querySelector("#Slider");
                    const currentTrack = document.querySelector(".slider-track");
                    
                    if (currentSlider && currentTrack && !this.isInitialized) {
                        console.log("Slider found in DOM, initializing...");
                        this.initializeSlider();
                    }
                }
            });
        });

        this.observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    initializeSlider() {
        this.slider = document.querySelector("#Slider");
        this.track = document.querySelector(".slider-track");
        
        if (!this.track || !this.slider) {
            console.log("Slider elements not found");
            return;
        }

        // Nếu đã được khởi tạo, không làm gì cả
        if (this.track.classList.contains('initialized')) {
            console.log("Slider already initialized");
            return;
        }

        console.log("Initializing slider...");

        const slides = Array.from(this.track.children);
        const dots = document.querySelectorAll(".dot");
        const prevBtn = document.querySelector(".arrow.prev");
        const nextBtn = document.querySelector(".arrow.next");

        if (slides.length === 0) {
            console.log("No slides found");
            return;
        }

        this.slideCount = slides.length;
        this.currentIndex = 0;
        this.isMoving = false;

        // Clone slides để tạo hiệu ứng vô hạn
        this.setupInfiniteSlides(slides);

        // Gắn sự kiện cho nút điều hướng - SỬA LỖI Ở ĐÂY
        this.setupNavigation(prevBtn, nextBtn);

        // Gắn sự kiện cho dots
        this.setupDots(dots);

        // Tự động chuyển slide
        this.startAutoSlide();

        // Đánh dấu đã khởi tạo
        this.track.classList.add('initialized');
        this.isInitialized = true;

        console.log("Slider initialized successfully");
        console.log("Prev button:", prevBtn);
        console.log("Next button:", nextBtn);
    }

    setupInfiniteSlides(slides) {
        // Xóa các clone cũ nếu có
        const existingClones = this.track.querySelectorAll('[data-clone="true"]');
        existingClones.forEach(clone => clone.remove());

        // Tạo clones mới
        const firstClone = slides[0].cloneNode(true);
        const lastClone = slides[slides.length - 1].cloneNode(true);
        
        firstClone.setAttribute('data-clone', 'true');
        lastClone.setAttribute('data-clone', 'true');
        
        this.track.appendChild(firstClone);
        this.track.insertBefore(lastClone, slides[0]);

        // Thiết lập vị trí ban đầu
        this.track.style.transform = `translateX(-${this.slideWidth}%)`;

        // Gắn sự kiện transitionend
        this.setupTransitionEnd();
    }

    setupTransitionEnd() {
        const handleTransitionEnd = () => {
            if (this.currentIndex === -1) {
                this.track.style.transition = "none";
                this.currentIndex = this.slideCount - 1;
                this.track.style.transform = `translateX(-${(this.currentIndex + 1) * this.slideWidth}%)`;
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.track.style.transition = "transform 0.6s ease-in-out";
                    }, 20);
                });
            }
            if (this.currentIndex === this.slideCount) {
                this.track.style.transition = "none";
                this.currentIndex = 0;
                this.track.style.transform = `translateX(-${this.slideWidth}%)`;
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.track.style.transition = "transform 0.6s ease-in-out";
                    }, 20);
                });
            }
            this.isMoving = false;
        };

        // Xóa sự kiện cũ trước khi gắn mới
        this.track.removeEventListener('transitionend', handleTransitionEnd);
        this.track.addEventListener('transitionend', handleTransitionEnd);
    }

    setupNavigation(prevBtn, nextBtn) {
        console.log("Setting up navigation...");
        
        // Xóa sự kiện cũ trước khi gắn mới
        if (prevBtn) {
            prevBtn.replaceWith(prevBtn.cloneNode(true));
        }
        if (nextBtn) {
            nextBtn.replaceWith(nextBtn.cloneNode(true));
        }

        // Lấy lại reference sau khi clone
        const newPrevBtn = document.querySelector(".arrow.prev");
        const newNextBtn = document.querySelector(".arrow.next");

        // Gắn sự kiện mới - SỬA LỖI QUAN TRỌNG
        if (newPrevBtn) {
            newPrevBtn.onclick = () => {
                console.log("Prev button clicked");
                this.goToSlide(this.currentIndex - 1);
            };
            console.log("Prev button event attached");
        } else {
            console.log("Prev button not found");
        }

        if (newNextBtn) {
            newNextBtn.onclick = () => {
                console.log("Next button clicked");
                this.goToSlide(this.currentIndex + 1);
            };
            console.log("Next button event attached");
        } else {
            console.log("Next button not found");
        }
    }

    setupDots(dots) {
        dots.forEach((dot, i) => {
            // Xóa sự kiện cũ
            dot.replaceWith(dot.cloneNode(true));
        });

        // Lấy lại reference sau khi clone
        const newDots = document.querySelectorAll(".dot");
        
        newDots.forEach((dot, i) => {
            dot.onclick = () => {
                console.log("Dot clicked:", i);
                this.goToSlide(i);
            };
        });
    }

    goToSlide(i) {
        if (this.isMoving) {
            console.log("Slider is moving, ignoring click");
            return;
        }
        
        console.log("Going to slide:", i);
        this.isMoving = true;
        this.currentIndex = i;

        this.track.style.transition = "transform 0.6s ease-in-out";
        this.track.style.transform = `translateX(-${(this.currentIndex + 1) * this.slideWidth}%)`;

        this.updateDots();
        
        // Reset auto slide timer
        this.resetAutoSlide();
    }

    updateDots() {
        const dots = document.querySelectorAll(".dot");
        dots.forEach(dot => dot.classList.remove("active"));
        const normalized = (this.currentIndex + this.slideCount) % this.slideCount;
        if (dots[normalized]) {
            dots[normalized].classList.add("active");
        }
    }

    startAutoSlide() {
        this.resetAutoSlide();
    }

    resetAutoSlide() {
        // Dừng interval cũ nếu có
        if (this.autoInterval) {
            clearInterval(this.autoInterval);
        }

        // Bắt đầu interval mới
        this.autoInterval = setInterval(() => {
            if (!this.isMoving) {
                this.goToSlide(this.currentIndex + 1);
            }
        }, 4000);

        // Dừng khi hover
        if (this.slider) {
            this.slider.addEventListener("mouseenter", () => {
                if (this.autoInterval) {
                    clearInterval(this.autoInterval);
                }
            });

            this.slider.addEventListener("mouseleave", () => {
                this.resetAutoSlide();
            });
        }
    }

    reinitialize() {
        console.log("Reinitializing slider...");
        this.isInitialized = false;
        
        // Dừng auto slide cũ
        if (this.autoInterval) {
            clearInterval(this.autoInterval);
            this.autoInterval = null;
        }

        // Xóa class initialized
        if (this.track) {
            this.track.classList.remove('initialized');
        }

        // Khởi tạo lại sau 100ms để đảm bảo DOM đã update
        setTimeout(() => {
            this.initializeSlider();
        }, 100);
    }

    destroy() {
        console.log("Destroying slider...");
        
        if (this.autoInterval) {
            clearInterval(this.autoInterval);
        }
        
        if (this.observer) {
            this.observer.disconnect();
        }
        
        this.isInitialized = false;
        
        if (this.track) {
            this.track.classList.remove('initialized');
        }
    }
}

// Khởi tạo slider khi trang load
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM loaded, initializing ZaraSlider...");
    window.zaraSlider = new ZaraSlider();
});

// Đảm bảo slider hoạt động khi trang được hiển thị lại
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && window.zaraSlider) {
        setTimeout(() => {
            window.zaraSlider.reinitialize();
        }, 100);
    }
});

// Xử lý các tab arrival (nếu có)
document.querySelectorAll('.arrival-tabs .tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.arrival-items').forEach(items => items.classList.remove('active'));
        
        tab.classList.add('active');
        
        const target = tab.textContent.trim();
        const targetElement = document.querySelector(`.arrival-items[data-tab="${target}"]`);
        if (targetElement) {
            targetElement.classList.add('active');
        }
        
        // Reinitialize slider khi chuyển tab arrival
        if (window.zaraSlider) {
            setTimeout(() => {
                window.zaraSlider.reinitialize();
            }, 200);
        }
    });
});

// Debug: Kiểm tra xem các nút có tồn tại không
setTimeout(() => {
    const prevBtn = document.querySelector(".arrow.prev");
    const nextBtn = document.querySelector(".arrow.next");
    console.log("Debug - Prev button exists:", !!prevBtn);
    console.log("Debug - Next button exists:", !!nextBtn);
    console.log("Debug - Slider track exists:", !!document.querySelector(".slider-track"));
}, 1000);