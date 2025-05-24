// Слайдер для главной страницы
let slideIndex = 1;
let slideInterval;

// Инициализация слайдера
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.slider')) {
        showSlides(slideIndex);
        // Автопрокрутка каждые 5 секунд
        slideInterval = setInterval(nextSlide, 5000);
    }
});

// Увеличиваем индекс на 1 — показываем следующий слайд
function nextSlide() {
    showSlides(slideIndex += 1);
}

// Уменьшаем индекс на 1 — показываем предыдущий слайд
function previousSlide() {
    showSlides(slideIndex -= 1);
}

// Устанавливаем текущий слайд
function currentSlide(n) {
    clearInterval(slideInterval); // Останавливаем автопрокрутку при ручном переключении
    showSlides(slideIndex = n);
    // Возобновляем автопрокрутку через 5 секунд
    slideInterval = setInterval(nextSlide, 5000);
}

// Функция перелистывания
function showSlides(n) {
    let slides = document.getElementsByClassName("item");
    let dots = document.getElementsByClassName("dot");
    
    if (slides.length === 0) return; // Если слайдов нет, выходим
    
    // Проверяем количество слайдов
    if (n > slides.length) {
        slideIndex = 1;
    }
    if (n < 1) {
        slideIndex = slides.length;
    }
    
    // Проходим по каждому слайду в цикле for
    for (let slide of slides) {
        slide.style.display = "none";
    }
    
    // Убираем активный класс со всех точек
    for (let dot of dots) {
        dot.classList.remove("active");
    }
    
    // Показываем текущий слайд
    if (slides[slideIndex - 1]) {
        slides[slideIndex - 1].style.display = "block";
    }
    
    // Активируем соответствующую точку
    if (dots[slideIndex - 1]) {
        dots[slideIndex - 1].classList.add("active");
    }
}

// Слайдер для галереи товара
let productSlideIndex = 1;

function initProductGallery() {
    const productSlides = document.getElementsByClassName("product-slide");
    if (productSlides.length > 0) {
        showProductSlides(productSlideIndex);
    }
}

function nextProductSlide() {
    showProductSlides(productSlideIndex += 1);
}

function previousProductSlide() {
    showProductSlides(productSlideIndex -= 1);
}

function currentProductSlide(n) {
    showProductSlides(productSlideIndex = n);
}

function showProductSlides(n) {
    let slides = document.getElementsByClassName("product-slide");
    let thumbnails = document.getElementsByClassName("thumbnail");
    
    if (slides.length === 0) return;
    
    if (n > slides.length) {
        productSlideIndex = 1;
    }
    if (n < 1) {
        productSlideIndex = slides.length;
    }
    
    for (let slide of slides) {
        slide.style.display = "none";
    }
    
    for (let thumbnail of thumbnails) {
        thumbnail.classList.remove("active");
    }
    
    if (slides[productSlideIndex - 1]) {
        slides[productSlideIndex - 1].style.display = "block";
    }
    
    if (thumbnails[productSlideIndex - 1]) {
        thumbnails[productSlideIndex - 1].classList.add("active");
    }
}

// Функция для открытия изображения в полном размере
function openFullImage(imageSrc) {
    window.open(imageSrc, '_blank', 'width=800,height=600');
}